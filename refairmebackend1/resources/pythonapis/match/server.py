from flask import Flask, jsonify, request
import json
import dill as pickle
import re
from nltk.stem.porter import PorterStemmer
from nltk.corpus import stopwords

app = Flask(__name__)

@app.route('/')
def home():
    return "Dermot king!"


@app.route('/predict', methods = ['POST'])
def apicall():
    '''
    API call
    Ranking of each IT job category sent as a payload from API call
    '''
    try:
        test_json = request.get_json()
        description = test_json['description']
    except Exception as e:
        raise e3

    #choosing model
    clf = 'classifier.pk'

    with open('./models/' + clf, 'rb') as f:
        classifier, string_transforming, string_preprocessing = pickle.load(f)
    
    #Text preprocessing - formating, stemming, removing irrelevant words
    description = [string_preprocessing(description)]
    
    #Transforming text to numerical vector
    description = string_transforming.transform(description).toarray()
    
    #Using neural network for prediction and unpacking list
    prediction = [item for sublist in classifier.predict_proba(description).tolist() for item in sublist]

    responses = json.dumps({'predictions': prediction})
    return responses

if __name__ == '__main__':
    app.run(debug = True)