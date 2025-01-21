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

def apicall(test_json):
    aname = 'refairme'
    dataset = pd.read_csv('./models/nofluff.csv',
                          sep = ';',
                          encoding = 'ISO-8859-1'
    )
    dataset['category'] = dataset['category'].replace('Full stack', 'Full Stack')
    encoder = LabelEncoder()
    dataset['category_code'] = encoder.fit_transform(dataset['category'])

    import re
    import nltk
    nltk.download('stopwords')
    nltk.download('wordnet')
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
    
    # Fitting Neural Network to the Training set
    from sklearn.neural_network import MLPClassifier
    classifier = MLPClassifier(solver = 'lbfgs',
                               alpha = 1e-5,
                               hidden_layer_sizes = (300),
                               random_state = 0)
    classifier.fit(X, y)
    
    
    with open("./models/classifier.pk", "wb") as f:
        pickle.dump((classifier, cv), f)
        
        '''
        API call
        Ranking of each IT job category sent as a payload from API call
        '''
    try:
        description =  json.loads(test_json)['description']
    except Exception as e:
        raise e
    
    #choosing model
    clf = 'classifier.pk'
    with open('./models/' + clf, 'rb') as f:
        classifier, cv = pickle.load(f)

        #Text preprocessing - formating, stemming, removing irrelevant words
        description = [string_preprocessing(description)]
        
        #Transforming text to numerical vector
        description = cv.transform(description).toarray()
        
        #Using neural network for prediction and unpacking list
        prediction = [item for sublist in classifier.predict_proba(description).tolist() for item in sublist]
        
        responses = json.dumps({'predictions': prediction})
        return responses
    
if __name__ == "__main__":
    data = {}
    data['description']='active,more,c++'
    aresponse = apicall(json.dumps(data))
    print(json.loads(aresponse)['predictions'])
    
