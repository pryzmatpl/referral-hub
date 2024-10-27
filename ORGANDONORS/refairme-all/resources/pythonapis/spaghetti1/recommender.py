import numpy as np
import re
import json
import pickle

class SkillRecommender():
    def __init__(self):
        # Input, output
        self.input = None
        self.input_strings = []
        self.output = []
        # Vocabularies placeholder
        self.valid_strings = None
        self.string_to_index = None
        self.index_to_string = None
        self.string_to_skill = None
        self.skill_to_result = None
        # Precomputed algorithm parts placeholder
        self.skill_skill_matrix = None
        self.vectorizer = None
        
    def set_vocabularies(self, filename):
        vocabularies = None
        with open(filename, 'r') as f:
            vocabularies = json.load(f)
        self.valid_strings = set(vocabularies['valid_strings'])
        self.string_to_index = vocabularies['string_to_index']
        self.index_to_string = vocabularies['index_to_string']
        self.string_to_skill = vocabularies['string_to_skill']
        self.skill_to_result = vocabularies['skill_to_result']
        
    def set_matrix(self, filename):
        self.skill_skill_matrix = np.load(filename)
    
    def set_vectorizer(self, filename):
        self.vectorizer = pickle.load(open(filename, "rb"))
        
    def set_input(self, keywords):
        self.clear_output()
        self.input = keywords
    
    def clear_input(self):
        self.input = None
        self.input_strings = []
        
    def clear_output(self):
        self.output = []
        
    def preprocess(self):
        for keyword in self.input:
            # Convert to lowercase and split keyword on slash
            strings = [string.strip() for string in keyword.lower().split('/')]
            # Split once again on ' '
            strings = [string.split(' ') for string in strings]
            # Flatten list
            strings = [string for sublist in strings for string in sublist]
            # Preprocess with regex
            strings = [re.sub('[^a-z\.\+]', '', string) for string in strings]
            # Remove repetitions and return result
            self.output += list(set(strings))
            self.input_strings += list(set(strings))
            
    def validate(self):
        self.output = [string for string in self.output if string in self.valid_strings]
    
    def transform(self):
        self.output = [' '.join(self.output)]
        self.output = self.vectorizer.transform(self.output)
        self.output = self.output.toarray()
        self.output = self.output.reshape(tuple(reversed(self.output.shape)))
        
    def rank(self):
        self.output = np.dot(self.skill_skill_matrix, self.output)
        self.output = list(self.output[:, 0])
        self.output = list(enumerate(self.output))
        self.output = sorted(self.output, key = lambda x: x[1], reverse = True)
    
    def pick_top(self, num_of_skills = 5):
        # Get indices of top matched skills
        self.output = [str(k) for k,v in self.output[:len(self.input_strings) + num_of_skills]]
        # Translate them to skills strings
        self.output = [self.index_to_string[i] for i in self.output]
        # Remove possible skills from input
        self.output = [string for string in self.output if string not in self.input_strings]
        # Get demanded number of skills, default = 5
        self.output = self.output[:num_of_skills]
        # Translate them to final skill names
        self.output = [self.string_to_skill[string] for string in self.output]
    
    def return_(self):
        self.output = [self.skill_to_result[string] for string in self.output]
        
    def recommend(self, keywords = [], num_of_skills = 5):
        self.set_input(keywords)
        self.preprocess()
        self.validate()
        self.transform()
        self.rank()
        self.pick_top(num_of_skills)
        self.return_()
        self.clear_input()
        return self.output
        
if __name__ == '__main__':
    # Step 1. Get instance of class
    recommender = SkillRecommender()
    
    # Step 2. Load all three files, important!
    recommender.set_vocabularies('vocabularies.json')
    recommender.set_matrix('skill_skill_matrix.dat')
    recommender.set_vectorizer('vectorizer.pkl')
    
    # Step 3. Get results, optional parameter: num_of_skills (default = 5)
    result = recommender.recommend(keywords = ['Java', 'Spring'])
