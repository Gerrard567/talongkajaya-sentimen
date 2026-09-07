import os
import sys
import re
import joblib

def preprocess_negation(text):
    text_lower = text.lower().strip()
    words = re.findall(r'\b\w+\b', text_lower)
    
    pos_words = {'bagus', 'gagah', 'mantap', 'rapi', 'aman', 'cepat', 'kokoh', 'mulus', 'puas', 'ramah', 'indah', 'top', 'rekomendasi', 'sesuai', 'suka', 'keren', 'baik', 'lengkap', 'bersih'}
    neg_words = {'cacat', 'rusak', 'lecet', 'patah', 'lama', 'lambat', 'robek', 'hancur', 'jelek', 'kecewa', 'parah', 'retak', 'longgar', 'pecah', 'buruk', 'rugi'}
    negation_words = {'tidak', 'nda', 'ndak', 'tdk', 'tak', 'bukan', 'tarada', 'trada', 'kurang', 'belum', 'gak', 'ngak'}

    transformed = []
    i = 0
    n = len(words)
    while i < n:
        w = words[i]
        if w in negation_words and i + 1 < n:
            next_w = words[i + 1]
            if next_w in neg_words:
                transformed.append("bagus")
                i += 2
                continue
            elif next_w in pos_words:
                transformed.append("jelek")
                i += 2
                continue
        transformed.append(w)
        i += 1

    return " ".join(transformed)

def main():
    if len(sys.argv) < 2:
        print("Error: No text provided.")
        sys.exit(1)
        
    raw_text = sys.argv[1]
    if not raw_text.strip():
        print("Negatif")
        sys.exit(0)

    # 1. Preprocessing Negation Transformation
    text = preprocess_negation(raw_text)

    # 2. 100% Machine Learning Prediction via Naive Bayes Model
    base_dir = os.path.dirname(os.path.abspath(__file__))
    model_path = os.path.join(base_dir, "model_naive_bayes.pkl")
    vectorizer_path = os.path.join(base_dir, "vectorizer.pkl")

    try:
        model = joblib.load(model_path)
        vectorizer = joblib.load(vectorizer_path)
        
        X = vectorizer.transform([text])
        prediction = model.predict(X)[0]
        
        pred_str = str(prediction).strip().lower()
        if pred_str in ['1', 'positive', 'positif', 'pos', 'true', 'yes']:
            print("Positif")
        else:
            print("Negatif")
    except Exception as e:
        print(f"Error: {str(e)}", file=sys.stderr)
        sys.exit(1)

if __name__ == "__main__":
    main()
