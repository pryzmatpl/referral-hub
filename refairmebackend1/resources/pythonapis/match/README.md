# README: Setting Up and Using NLTK in Your Project

This README provides step-by-step guidance for setting up the Natural Language Toolkit (NLTK) in a Python project, resolving common errors, and successfully downloading necessary resources.

---

## Steps for Setting Up NLTK

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
python ./runapi.py
```
You should see the following classification result:

```bash
(venv) piotro@prizm -> python ./runapi.py 
[nltk_data] Downloading package stopwords to /home/piotro/nltk_data...
[nltk_data]   Package stopwords is already up-to-date!
[nltk_data] Downloading package wordnet to /home/piotro/nltk_data...
[nltk_data]   Package wordnet is already up-to-date!
[0.9999940165587826, 5.552445418656792e-10, 4.120680246924878e-08, 4.7035042733952515e-07, 3.1822735569030827e-09, 5.412041788350114e-06, 3.970142405950206e-08, 4.691059983196553e-10, 3.309946329860151e-09, 9.912943091384819e-09, 2.7112616687107234e-09]
(venv) piotro@prizm -> 
```
This means that the classification API for keywords is working.

To run it as an API, run
```bash
python ./server.py
```

To perform a singular classification, run:
```bash
python ./run.py 
```
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

