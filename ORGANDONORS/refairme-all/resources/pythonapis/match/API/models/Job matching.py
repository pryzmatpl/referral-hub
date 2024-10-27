# Importing the dataset
import pandas as pd
from sklearn.preprocessing import LabelEncoder

dataset = pd.read_csv('/home/piotro/Job matching/nofluff.csv',
                      sep = ';',
                      encoding = 'ISO-8859-1'
                      )

dataset['category'] = dataset['category'].replace('Full stack', 'Full Stack')
encoder = LabelEncoder()
dataset['category_code'] = encoder.fit_transform(dataset['category'])

# Cleaning the texts
import re
import nltk
nltk.download('stopwords')
from nltk.corpus import stopwords
from nltk.stem.porter import PorterStemmer
corpus = []
for description in dataset['description']:
    description = re.sub('[^a-zA-Z]', ' ', description)
    description = description.lower()
    description = description.split()
    ps = PorterStemmer()
    description = [ps.stem(word) for word in description if not word in set(stopwords.words('english'))]
    description = ' '.join(description)
    corpus.append(description)

# Creating the Bag of Words model
from sklearn.feature_extraction.text import CountVectorizer
cv = CountVectorizer(max_features = 1500)
X = cv.fit_transform(corpus).toarray()
y = dataset.iloc[:, 3].values

# Splitting the dataset into the Training set and Test set
from sklearn.cross_validation import train_test_split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size = 0.20, random_state = 0)

# Fitting Neural Network to the Training set
from sklearn.neural_network import MLPClassifier
classifier = MLPClassifier(solver = 'lbfgs',
                           alpha = 1e-5,
                           hidden_layer_sizes = (300),
                           random_state = 0
                           )
classifier.fit(X_train, y_train)

def string_preprocessing(input_string):
    import re
    from nltk.stem.porter import PorterStemmer
    from nltk.corpus import stopwords
    input_string = re.sub('[^a-zA-Z]', ' ', input_string)
    input_string = input_string.lower()
    input_string = input_string.split()
    ps = PorterStemmer()
    input_string = [ps.stem(word) for word in input_string if not word in set(stopwords.words('english'))]
    input_string = ' '.join(input_string)
    return cv.transform([input_string]).toarray()

#Saving classifier
import dill as pickle

with open("classifier.pk", "wb") as f:
    pickle.dump((classifier, cv, string_preprocessing), f, protocol = 2)
