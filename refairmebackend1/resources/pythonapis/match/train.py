import pandas as pd
import numpy as np
from sklearn.preprocessing import LabelEncoder
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.model_selection import train_test_split
from sklearn.neural_network import MLPClassifier
from nltk.corpus import stopwords
from nltk.stem import PorterStemmer
import re
import nltk
import dill as pickle
from typing import List, Tuple, Any
from functools import lru_cache

class TextClassificationPipeline:
    def __init__(self, max_features: int = 1500, test_size: float = 0.20):
        self.max_features = max_features
        self.test_size = test_size
        self.ps = PorterStemmer()
        self.cv = CountVectorizer(max_features=max_features)
        self.encoder = LabelEncoder()
        self.classifier = MLPClassifier(
            solver='lbfgs',
            alpha=1e-5,
            hidden_layer_sizes=(300,),
            random_state=0,
            early_stopping=True,  # Added early stopping
            validation_fraction=0.1,
            n_iter_no_change=10
        )
        
        # Download NLTK resources only if needed
        try:
            stopwords.words('english')
        except LookupError:
            nltk.download('stopwords', quiet=True)
            nltk.download('wordnet', quiet=True)
        
        self.stop_words = set(stopwords.words('english'))
        
    @staticmethod
    def load_data(filepath: str) -> pd.DataFrame:
        """Load and preprocess the dataset."""
        dataset = pd.read_csv(
            filepath,
            sep=';',
            encoding='ISO-8859-1'
        )
        dataset['category'] = dataset['category'].replace('Full stack', 'Full Stack')
        return dataset
    
    def preprocess_text(self, text: str) -> str:
        """Clean and preprocess text data."""
        # Remove non-alphabetic characters and convert to lowercase
        text = re.sub('[^a-zA-Z]', ' ', text.lower())
        
        # Tokenize and remove stopwords
        words = [
            self.ps.stem(word) 
            for word in text.split() 
            if word not in self.stop_words
        ]
        
        return ' '.join(words)
    
    @lru_cache(maxsize=1000)  # Cache preprocessed texts
    def preprocess_text_cached(self, text: str) -> str:
        """Cached version of preprocess_text for repeated texts."""
        return self.preprocess_text(text)
    
    def prepare_data(self, dataset: pd.DataFrame) -> Tuple[np.ndarray, np.ndarray]:
        """Prepare features and target variables."""
        # Encode categories
        dataset['category_code'] = self.encoder.fit_transform(dataset['category'])
        
        # Preprocess descriptions in parallel using pandas
        corpus = dataset['description'].apply(self.preprocess_text_cached)
        
        # Create bag of words
        X = self.cv.fit_transform(corpus).toarray()
        y = dataset['category_code'].values
        
        return X, y
    
    def train(self, X: np.ndarray, y: np.ndarray) -> None:
        """Train the model."""
        # Split dataset
        X_train, X_test, y_train, y_test = train_test_split(
            X, y,
            test_size=self.test_size,
            random_state=0,
            stratify=y  # Ensure balanced split
        )
        
        # Train classifier
        self.classifier.fit(X_train, y_train)
        
        # Print model performance
        train_score = self.classifier.score(X_train, y_train)
        test_score = self.classifier.score(X_test, y_test)
        print(f'Training accuracy: {train_score:.4f}')
        print(f'Testing accuracy: {test_score:.4f}')
    
    def predict(self, text: str) -> int:
        """Predict category for new text."""
        processed_text = self.preprocess_text(text)
        features = self.cv.transform([processed_text]).toarray()
        return self.classifier.predict(features)[0]
    
    def save_model(self, filepath: str) -> None:
        """Save the trained model and preprocessor."""
        with open(filepath, "wb") as f:
            pickle.dump(self, f, protocol=2)
    
    @staticmethod
    def load_model(filepath: str) -> 'TextClassificationPipeline':
        """Load a trained model."""
        with open(filepath, "rb") as f:
            return pickle.load(f)

def main():
    # Initialize pipeline
    pipeline = TextClassificationPipeline()
    
    # Load and prepare data
    dataset = pipeline.load_data('nofluff.csv')
    X, y = pipeline.prepare_data(dataset)
    
    # Train model
    pipeline.train(X, y)
    
    # Save model
    pipeline.save_model("classifier.pk")

if __name__ == "__main__":
    main()