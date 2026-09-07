# Sentiment Analysis API (FastAPI)

Layanan API Python pendamping untuk aplikasi Laravel Alongkajaya Sentimen. API ini memuat model Naive Bayes dan Vectorizer untuk melakukan prediksi sentimen secara real-time.

## Cara Menjalankan Layanan

1. Buka terminal baru dan masuk ke direktori `python-api`:
   ```bash
   cd python-api
   ```

2. Aktifkan Virtual Environment:
   * **Windows (Command Prompt / Laragon terminal):**
     ```cmd
     venv\Scripts\activate
     ```
   * **Windows (PowerShell):**
     ```powershell
     .\venv\Scripts\Activate.ps1
     ```
   * **Linux / macOS:**
     ```bash
     source venv/bin/activate
     ```

3. Jalankan server FastAPI menggunakan `uvicorn`:
   ```bash
   uvicorn main:app --reload
   ```

Server akan berjalan secara default di `http://127.0.0.1:8000`.

## API Endpoints

* **POST `/predict`**: Menerima JSON teks ulasan dan mengembalikan prediksi sentimen (`Positif` atau `Negatif`).
  * Request Body:
    ```json
    {
      "text": "meja ini gagah sekali"
    }
    ```
  * Response:
    ```json
    {
      "text": "meja ini gagah sekali",
      "prediction_raw": "Positif",
      "label": "Positif"
    }
    ```
* **GET `/metrics`**: Mengambil metrik evaluasi model (akurasi, precision, recall, confusion matrix) dari file `metrics.json`.
* **POST `/metrics`**: Memperbarui metrik evaluasi model di file `metrics.json`.
