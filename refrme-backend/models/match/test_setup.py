#!/usr/bin/env python
import pandas as pd
import numpy as np
from sklearn.preprocessing import LabelEncoder
from sklearn.feature_extraction.text import CountVectorizer
import nltk
from nltk.stem.porter import PorterStemmer
from nltk.corpus import stopwords
import json
import re
import logging

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

def test_setup():
    """Test if all components are working correctly."""
    try:
        # Test NLTK downloads
        logger.info("Testing NLTK resources...")
        nltk.download('stopwords', quiet=True)
        stopwords.words('english')
        logger.info("✓ NLTK resources loaded successfully")

        # Test data loading
        logger.info("Testing data loading...")
        dataset = pd.read_csv('./nofluff.csv',
                          sep=';',
                          encoding='utf-8')
        logger.info(f"✓ Dataset loaded successfully with {len(dataset)} rows")

        # Test preprocessing pipeline
        logger.info("Testing preprocessing pipeline...")
        test_text = "active,more,c++"
        ps = PorterStemmer()
        stop_words = set(stopwords.words('english'))

        # Clean text
        processed = re.sub('[^a-zA-Z]', ' ', test_text)
        processed = processed.lower()
        processed = [ps.stem(word) for word in processed.split()
                    if word not in stop_words]
        processed = ' '.join(processed)
        logger.info(f"✓ Text preprocessing working: '{test_text}' -> '{processed}'")

        # Test vectorization
        logger.info("Testing vectorization...")
        cv = CountVectorizer(max_features=1500)
        # Create a small corpus for testing
        cv.fit([processed])
        vector = cv.transform([processed]).toarray()
        logger.info(f"✓ Vectorization working: shape {vector.shape}")

        logger.info("\nAll setup tests completed successfully! ✓")
        return True

    except Exception as e:
        logger.error(f"Setup test failed: {str(e)}")
        return False

if __name__ == "__main__":
    test_setup()