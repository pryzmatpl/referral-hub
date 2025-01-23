# Referral Hub Natural Language Toolkit processor for job categories
This README provides step-by-step guidance for setting up the Natural Language Toolkit (NLTK) in a Python project, resolving common errors, and successfully downloading necessary resources.


### [Source - the NLTK lib](https://www.nltk.org/howto/wordnet.html)

## Prerequisites: Steps for Setting Up NLTK

### 1. Create a Virtual Environment
It is recommended to use a virtual environment to isolate dependencies.
```bash
python -m venv venv
source ./venv/bin/activate
pip install -r ./requirements.txt
```

### 2. Create a Directory for NLTK Data
To store NLTK resources locally (e.g., `wordnet`, `stopwords`), create a directory:
```bash
mkdir nltk_data
```

### 3. Download Required NLTK Resources
Run the NLTK downloader to fetch resources like `wordnet`. Specify the custom directory for storage:
```bash
python -m nltk.downloader -d ./nltk_data
```
Follow the on-screen instructions to select and download the required datasets.
You need at least the stopwords and wordnet corpora for NL processing of tags.

### 4. Running the api for testing first
In order to run the API, perform:
```bash
python ./train.py # training the model for classifications based on the CSV
python ./runapi.py # example run 
```

# Text Classification Pipeline - running it whole

This project implements a machine learning pipeline for text classification using neural networks. It includes data preprocessing, model training, and a REST API for making predictions.

## Project Structure

```
.
├── nofluff.csv         # Training data
├── venv/               # Virtual environment
├── classifier.pk       # Trained model
├── run.py              # API server
├── train.py            # Model training
└── test_setup.py       # Setup verification
```

In this section, just verify the setup:

```bash
python test_setup.py
```

## Training the Model

The model can be trained using the provided training script:

```bash
python train.py
```

This will:
- Load and preprocess the training data
- Train a neural network classifier
- Save the model to `classifier.pk`

## Using the API

### Starting the Server

```bash
python run.py
```

### Making Predictions

#### Via CLI
```bash
python run.py "photoshop,machine learning"
```

#### Via HTTP API
```bash
curl -X POST http://localhost:5000/predict \
     -H "Content-Type: application/json" \
     -d '{"description": "photoshop,machine learning"}'
```

## Model Details

The pipeline includes:
- Text preprocessing (cleaning, stemming, stop word removal)
- Bag of Words vectorization (max 1500 features)
- Neural Network classifier with early stopping

## Error Handling

The API includes comprehensive error handling and logging:
- Input validation
- Model loading verification
- Preprocessing error handling
- Detailed error messages

## Performance

The model includes several optimizations:
- Cached text preprocessing
- Efficient vectorization
- Early stopping in neural network training
- Batch prediction support

---

## Common Errors and Resolutions

### Error: `Resource wordnet not found`
If you encounter an error like this:
```
LookupError: Resource wordnet not found.
```
Solution:
1. Ensure that the `wordnet` resource is downloaded:
   ```python
   import nltk
   nltk.download('wordnet')
   ```
2. If using a custom directory, specify it during download:
   ```bash
   python -m nltk.downloader -d ./nltk_data
   ```

### Error: `No module named 'nltk'`
If you see this error while running the downloader:
```
ModuleNotFoundError: No module named 'nltk'
```
Solution:
1. Ensure that NLTK is installed in your virtual environment:
   ```bash
   pip install nltk
   ```
2. Activate the virtual environment before running the downloader.

---

## Verifying Installation
Once setup is complete, test your NLTK installation and resource availability:
```python
import nltk
from nltk.corpus import wordnet

nltk.data.path.append('./nltk_data')  # Use custom directory if applicable
nltk.download('stopwords')
print(wordnet.synsets('example'))
```

---

## Notes
- Use the `nltk_data` directory to avoid permission issues with system directories.
- Add `nltk_data` to your `.gitignore` if it’s not required in version control.

---

## Example Project Directory Structure
```
project_root/
├── nltk_data/          # Directory for NLTK resources
├── venv/               # Virtual environment
├── main.py             # Your Python script
├── requirements.txt    # List of dependencies
```

---

With this setup, you can now successfully use NLTK in your project!

