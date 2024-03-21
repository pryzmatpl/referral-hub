#!/usr/bin/env python
from sklearn.neural_network import MLPClassifier
import pandas as pd
from sklearn.preprocessing import LabelEncoder
from sklearn.feature_extraction.text import CountVectorizer
from flask import Flask, jsonify, request
import json
import dill as pickle
import pickle
import re
from nltk.stem.porter import PorterStemmer
from nltk.corpus import stopwords
import server
import sys
import sklearn

dataset = pd.read_csv('/usr/share/nginx/refair/resources/pythonapis/match/API/models/nofluff.csv',
                      sep = ';',
                      encoding = 'ISO-8859-1'
)
dataset['category'] = dataset['category'].replace('Full stack', 'Full Stack')
encoder = LabelEncoder()
dataset['category_code'] = encoder.fit_transform(dataset['category'])

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

data = {}
data['description']='active,more,c++'
description =  json.loads(json.dumps(data))['description']
print( type(description) )

description = re.sub('[^a-zA-Z]', ' ', description)
description = description.lower()
description = description.split()
ps = PorterStemmer()
description = [ps.stem(word) for word in description if not word in set(stopwords.words('english'))]
description = ' '.join(description)
print( description )
print( type(description) )
print(cv.transform([description]).toarray())
print(type(cv.transform([description]).toarray()))
