import os
import sys
import json
import csv
import joblib
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.metrics import accuracy_score, precision_recall_fscore_support, confusion_matrix, classification_report
from sklearn.model_selection import train_test_split

def load_dataset(base_dir):
    texts = []
    labels = []
    
    # 1. Look for baseline dataset (dataset_1800.csv / dataset.csv / ulasan.csv)
    dataset_dirs = [
        os.path.join(base_dir, "..", "..", "python-api", "dataset"),
        os.path.join(base_dir, "..", "..", "python-api"),
        base_dir
    ]
    
    csv_file = None
    for d in dataset_dirs:
        for fname in ["dataset_1800.csv", "dataset.csv", "ulasan.csv"]:
            target = os.path.join(d, fname)
            if os.path.exists(target):
                csv_file = target
                break
        if csv_file:
            break
            
    if csv_file and os.path.exists(csv_file):
        try:
            with open(csv_file, mode="r", encoding="utf-8", errors="ignore") as f:
                reader = csv.DictReader(f)
                for row in reader:
                    t = row.get("teks_asli") or row.get("teks") or row.get("ulasan") or row.get("komentar") or ""
                    l = row.get("label_sentimen") or row.get("label") or row.get("sentimen") or ""
                    if t.strip() and l.strip():
                        l_clean = "Positif" if str(l).strip().lower() in ["positif", "1", "positive", "pos"] else "Negatif"
                        texts.append(t.strip())
                        labels.append(l_clean)
            print(f"Loaded {len(texts)} samples from baseline CSV: {csv_file}")
        except Exception as e:
            print(f"Warning: Failed to load baseline CSV: {e}", file=sys.stderr)
            
    # 2. Look for DB json data passed from Laravel
    json_path = os.path.join(base_dir, "db_ulasan.json")
    if os.path.exists(json_path):
        try:
            with open(json_path, mode="r", encoding="utf-8") as f:
                db_data = json.load(f)
                for item in db_data:
                    t = item.get("teks_normalisasi") or item.get("teks_asli") or ""
                    l = item.get("label_sentimen") or ""
                    if t.strip() and l.strip():
                        l_clean = "Positif" if str(l).strip().lower() in ["positif", "1", "positive", "pos"] else "Negatif"
                        texts.append(t.strip())
                        labels.append(l_clean)
            print(f"Loaded additional DB samples. Total combined samples: {len(texts)}")
        except Exception as e:
            print(f"Warning: Failed to load DB json: {e}", file=sys.stderr)

    return texts, labels

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    texts, labels = load_dataset(base_dir)
    
    if len(texts) < 5:
        print("Error: Insufficient dataset to train. Minimum 5 samples required.", file=sys.stderr)
        sys.exit(1)
        
    # Split train/test (80:20)
    X_train, X_test, y_train, y_test = train_test_split(
        texts, labels, test_size=0.2, random_state=42, stratify=labels if len(set(labels)) > 1 else None
    )
    
    # Vectorizer & Model
    vectorizer = TfidfVectorizer(max_features=2500, ngram_range=(1, 2))
    X_train_vec = vectorizer.fit_transform(X_train)
    X_test_vec = vectorizer.transform(X_test)
    
    model = MultinomialNB()
    model.fit(X_train_vec, y_train)
    
    # Predict on test set
    y_pred = model.predict(X_test_vec)
    
    # Metrics
    acc = accuracy_score(y_test, y_pred)
    prec, rec, f1, _ = precision_recall_fscore_support(y_test, y_pred, average='macro', zero_division=0)
    
    # Confusion matrix (Order: ['Positif', 'Negatif'])
    labels_order = ['Positif', 'Negatif']
    cm = confusion_matrix(y_test, y_pred, labels=labels_order)
    
    tp = int(cm[0][0])
    fn = int(cm[0][1])
    fp = int(cm[1][0])
    tn = int(cm[1][1])
    
    report_dict = classification_report(y_test, y_pred, output_dict=True, zero_division=0)
    
    formatted_report = {
        "negatif": {
            "precision": f"{report_dict.get('Negatif', {}).get('precision', 0):.2f}",
            "recall": f"{report_dict.get('Negatif', {}).get('recall', 0):.2f}",
            "f1_score": f"{report_dict.get('Negatif', {}).get('f1-score', 0):.2f}",
            "support": int(report_dict.get('Negatif', {}).get('support', 0))
        },
        "positif": {
            "precision": f"{report_dict.get('Positif', {}).get('precision', 0):.2f}",
            "recall": f"{report_dict.get('Positif', {}).get('recall', 0):.2f}",
            "f1_score": f"{report_dict.get('Positif', {}).get('f1-score', 0):.2f}",
            "support": int(report_dict.get('Positif', {}).get('support', 0))
        },
        "macro_avg": {
            "precision": f"{report_dict.get('macro avg', {}).get('precision', 0):.2f}",
            "recall": f"{report_dict.get('macro avg', {}).get('recall', 0):.2f}",
            "f1_score": f"{report_dict.get('macro avg', {}).get('f1-score', 0):.2f}",
            "support": int(report_dict.get('macro avg', {}).get('support', 0))
        },
        "weighted_avg": {
            "precision": f"{report_dict.get('weighted avg', {}).get('precision', 0):.2f}",
            "recall": f"{report_dict.get('weighted avg', {}).get('recall', 0):.2f}",
            "f1_score": f"{report_dict.get('weighted avg', {}).get('f1-score', 0):.2f}",
            "support": int(report_dict.get('weighted avg', {}).get('support', 0))
        }
    }
    
    metrics_data = {
        "akurasi": f"{acc * 100:.2f}%",
        "precision": f"{prec * 100:.1f}%",
        "recall": f"{rec * 100:.1f}%",
        "f1_score": f"{f1 * 100:.1f}%",
        "total_sample": len(texts),
        "test_sample": len(y_test),
        "cm": {
            "tp": tp,
            "fn": fn,
            "fp": fp,
            "tn": tn
        },
        "classification_report": formatted_report
    }
    
    # Save model, vectorizer, and metrics
    model_paths = [
        os.path.join(base_dir, "model_naive_bayes.pkl"),
        os.path.join(base_dir, "..", "..", "python-api", "models", "model_naive_bayes.pkl")
    ]
    vec_paths = [
        os.path.join(base_dir, "vectorizer.pkl"),
        os.path.join(base_dir, "..", "..", "python-api", "models", "vectorizer.pkl")
    ]
    metrics_paths = [
        os.path.join(base_dir, "metrics.json"),
        os.path.join(base_dir, "..", "..", "python-api", "metrics.json")
    ]
    
    for p in model_paths:
        os.makedirs(os.path.dirname(p), exist_ok=True)
        joblib.dump(model, p)
        
    for p in vec_paths:
        os.makedirs(os.path.dirname(p), exist_ok=True)
        joblib.dump(vectorizer, p)
        
    for p in metrics_paths:
        os.makedirs(os.path.dirname(p), exist_ok=True)
        with open(p, "w", encoding="utf-8") as f:
            json.dump(metrics_data, f, indent=2)
            
    print(f"SUCCESS: Model re-trained on {len(texts)} samples. Accuracy: {acc*100:.2f}%")

if __name__ == "__main__":
    main()
