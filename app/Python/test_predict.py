import os
import joblib

base_dir = os.path.dirname(os.path.abspath(__file__))
model = joblib.load(os.path.join(base_dir, "model_naive_bayes.pkl"))
vec = joblib.load(os.path.join(base_dir, "vectorizer.pkl"))

samples = [
    "lemari pe gagah mantap skali bagus",
    "lemari pe gagah, mantap skali, nda cacat",
    "barang pe gagah mantap skali",
    "\"\"barang pe gagah mantap skali",
    "packing rapi",
    "kirim cepat",
    "kirim cepat tapi aman",
    "packing rapi dengan kirim cepat tapi aman"
]

for s in samples:
    X = vec.transform([s])
    pred = model.predict(X)[0]
    print(f"Text: '{s}' => Prediction: '{pred}'")
