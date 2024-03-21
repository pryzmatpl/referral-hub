from flask import Flask, jsonify, request
from recommender import SkillRecommender
import json
import io
import base64
import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt

HOST = '127.0.0.1'
PORT = 5000

app = Flask(__name__)

recommender = SkillRecommender()
recommender.set_vocabularies('vocabularies.json')
recommender.set_matrix('skill_skill_matrix.dat')
recommender.set_vectorizer('vectorizer.pkl')

salaries = pd.read_csv('salary_comparison.csv', sep = ';')

@app.route('/recommend_skills', methods = ['GET', 'POST'])
def recommend_skills():
    req_data  = json.loads(request.get_data())
    keywords = req_data['keywords']
    return jsonify(recommender.recommend(keywords = keywords))

@app.route('/compare_salary', methods = ['GET', 'POST'])
def build_plot():
    req_data  = json.loads(request.get_data())
    salary = float(req_data['salary'])
    
    img = io.BytesIO()
    sns.set_style("whitegrid")
    ax = sns.stripplot(y="category", x="Salary", color = "#17a2b8",
                       data = salaries, size=5)
    ax.set(xlabel = 'Salary', ylabel = '')
    ax.axvline(salary, linestyle = '--')
    sns.despine(left = True)
    plt.savefig(img, format='png')
    img.seek(0)
    
    plot_url = base64.b64encode(img.getvalue()).decode()
    return jsonify({'plot_url': plot_url})

if __name__ == '__main__':
    app.run(host = HOST, port = PORT)
