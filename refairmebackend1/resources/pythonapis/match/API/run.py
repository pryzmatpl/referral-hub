#!/usr/bin/env python
from sklearn.neural_network import MLPClassifier
import pandas as pd
from sklearn.preprocessing import LabelEncoder
from sklearn.feature_extraction.text import CountVectorizer
from flask import Flask, jsonify, request
import json
import dill as pickle
import re
from nltk.stem.porter import PorterStemmer
from nltk.corpus import stopwords
import server
import sys
import sklearn

def string_preprocessing(input_string):
    import re
    from nltk.stem.porter import PorterStemmer
    from nltk.corpus import stopwords
    input_string = re.sub('[^a-zA-Z]', ' ', input_string)
    input_string = input_string.lower()
    input_string = input_string.split()
    ps = PorterStemmer()
    input_string = [ps.stem(word) for word in input_string if not word in set(stopwords.words('english'))]
    return ' '.join(input_string)

def apicall(description):
    #choosing model
    clf = 'classifier.pk'
    aname = 'refairme'
    with open('./models/' + clf, 'rb') as f:
        classifier, cv = pickle.load(f)

        #Text preprocessing - formating, stemming, removing irrelevant words
        description = [string_preprocessing(description)]
        
        #Transforming text to numerical vector
        description = cv.transform(description).toarray()
        
        #Using neural network for prediction and unpacking list
        prediction = [item for sublist in classifier.predict_proba(description).tolist() for item in sublist]
        
        responses = json.dumps({'predictions': prediction})
        return print(responses)
    
if __name__ == "__main__":
    data = {}
    data['description']=sys.argv[1]
    apicall(json.dumps(data))
    sys.exit()
    
