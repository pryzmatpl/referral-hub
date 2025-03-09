#!/usr/bin/env python
from typing import Dict, List, Union, Any
import logging
import json
import sys
from pathlib import Path
import dill as pickle
from flask import Flask, jsonify, request, Response
import numpy as np
from sklearn.neural_network import MLPClassifier
from typing import Optional
from sklearn.feature_extraction.text import CountVectorizer
from nltk.stem.porter import PorterStemmer
from nltk.corpus import stopwords
import re

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

class TextClassificationAPI:
    def __init__(self, model_path: Optional[str] = None):
        """Initialize the Text Classification API.

        Args:
            model_path: Path to the pickled model file
        """
        if model_path is None:
            model_path = Path(__file__).resolve().parent / 'classifier.pk'
        else:
            model_path = Path(model_path)
        self.model_path=model_path
        self.pipeline = None
        self.load_model()

    def load_model(self) -> None:
        """Load the classification pipeline from pickle file."""
        try:
            with open(self.model_path, 'rb') as f:
                self.pipeline = pickle.load(f)
            logger.info(f"Model loaded successfully from {self.model_path}")
        except Exception as e:
            logger.error(f"Error loading model: {str(e)}")
            raise RuntimeError(f"Failed to load model from {self.model_path}")

    def predict(self, description: str) -> Dict[str, Any]:
        """Make predictions for the input text.

        Args:
            description: Input text to classify

        Returns:
            Dictionary containing prediction probabilities
        """
        try:
            # Process the input using the pipeline
            processed_text = self.pipeline.preprocess_text(description)
            features = self.pipeline.cv.transform([processed_text]).toarray()

            # Get prediction probabilities
            probabilities = self.pipeline.classifier.predict_proba(features)[0].tolist()

            # Get predicted class
            predicted_class = self.pipeline.classifier.predict(features)[0]

            # Get class labels if available
            try:
                class_labels = self.pipeline.encoder.inverse_transform(range(len(probabilities)))
                class_probs = {label: prob for label, prob in zip(class_labels, probabilities)}
            except:
                class_probs = {f"class_{i}": prob for i, prob in enumerate(probabilities)}

            return {
                'predictions': class_probs,
                'predicted_class': int(predicted_class),
                'confidence': float(max(probabilities))
            }
        except Exception as e:
            logger.error(f"Prediction error: {str(e)}")
            raise RuntimeError("Prediction failed")

# Initialize Flask app
app = Flask(__name__)
classifier_api = TextClassificationAPI()

@app.route('/predict', methods=['POST'])
def api_predict() -> Response:
    """API endpoint for text classification."""
    try:
        # Get JSON data from request
        data = request.get_json(force=True)

        if not data or 'description' not in data:
            return jsonify({
                'error': 'Missing description in request',
                'status': 'error'
            }), 400

        # Make prediction
        result = classifier_api.predict(data['description'])

        return jsonify({
            'result': result,
            'status': 'success'
        }), 200

    except Exception as e:
        logger.error(f"API error: {str(e)}")
        return jsonify({
            'error': str(e),
            'status': 'error'
        }), 500

def cli_predict(input_text: str) -> None:
    """Command line interface for predictions."""
    try:
        # Handle comma-separated input
        descriptions = input_text.split(',')
        results = []

        for description in descriptions:
            result = classifier_api.predict(description.strip())
            results.append({
                'text': description.strip(),
                'predictions': result
            })

        # Print results
        print(json.dumps(results))

    except Exception as e:
        logger.error(f"CLI error: {str(e)}")
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) > 1:
        # CLI mode
        cli_predict(sys.argv[1])
    else:
        # API mode
        app.run(host='0.0.0.0', port=5000, debug=False)