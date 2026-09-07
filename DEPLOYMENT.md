# Panduan Deployment & Hosting Profesional: Talongkajaya Sentimen

Panduan ini menjelaskan cara mempublikasikan (*deploy*) aplikasi **Talongkajaya Sentimen** ke internet agar dapat diakses oleh publik secara stabil, cepat, dan aman.

---

## 1. Arsitektur Sistem (Microservice Pattern)

Aplikasi ini menggunakan pola arsitektur **dua komponen**:
1. **Laravel Web Application (Frontend & Database)**
   - Berisi formulir publik ulasan, autentikasi admin, manajemen leksikon, dan dashboard analitik.
   - Dapat di-hosting di: **Vercel** (Serverless), **cPanel** (Shared Hosting), atau **VPS**.
2. **Python FastAPI (AI & Machine Learning Microservice)**
   - Berisi model Naive Bayes (`model_naive_bayes.pkl`) dan TF-IDF Vectorizer.
   - Melayani endpoint `/predict` dan `/metrics`.
   - Dapat di-hosting gratis di: **Render.com** atau **Railway.app**.

Keduanya berkomunikasi melalui HTTP REST API menggunakan variabel `PYTHON_API_URL` di file `.env`.

---

## 2. Cara Deploy Python API ke Render.com (Gratis & Mudah)

[Render.com](https://render.com) menyediakan tier gratis yang sangat cocok untuk menjalankan FastAPI.

### Langkah-langkah:
1. **Push Projek ke GitHub**:
   - Pastikan folder `python-api/` ikut ter-commit ke repository GitHub Anda (termasuk folder `models/` yang berisi `model_naive_bayes.pkl` dan `vectorizer.pkl`, serta file `Procfile` dan `requirements.txt`).
2. **Buat Akun di Render**:
   - Buka [render.com](https://render.com) dan login menggunakan akun GitHub Anda.
3. **Buat Web Service Baru**:
   - Klik **New +** $\rightarrow$ pilih **Web Service**.
   - Hubungkan repository GitHub Anda.
4. **Konfigurasi Web Service**:
   - **Name**: `talongkajaya-api` (atau nama lain yang Anda inginkan)
   - **Root Directory**: `python-api` *(PENTING: isi dengan `python-api`)*
   - **Runtime**: `Python 3`
   - **Build Command**: `pip install -r requirements.txt`
   - **Start Command**: `uvicorn main:app --host 0.0.0.0 --port $PORT`
   - **Plan Type**: `Free`
5. **Deploy**:
   - Klik **Deploy Web Service**.
   - Tunggu hingga proses build selesai. Anda akan mendapatkan URL publik, contohnya:
     `https://talongkajaya-api.onrender.com`
6. **Tes API**:
   - Buka URL tersebut di browser. Jika muncul pesan `{"message": "Sentiment Analysis API is running", "status": "Ready"}`, berarti API AI Anda sudah aktif di internet!

---

## 3. Cara Deploy Laravel

### Opsi A: Deploy ke Vercel (Serverless)

Projek ini sudah dilengkapi file `vercel.json` dan folder `api/index.php`.

1. **Import Project ke Vercel**:
   - Login ke [vercel.com](https://vercel.com) menggunakan GitHub.
   - Klik **Add New...** $\rightarrow$ **Project**.
   - Pilih repository GitHub Anda.
2. **Isi Environment Variables di Vercel**:
   Di bagian **Environment Variables**, tambahkan setelan berikut:
   - `APP_NAME`: `Talongka Jaya Sentimen`
   - `APP_ENV`: `production`
   - `APP_KEY`: *(Salin nilai APP_KEY dari file .env lokal Anda)*
   - `APP_DEBUG`: `false`
   - `APP_URL`: `https://nama-projek-anda.vercel.app`
   - `DB_CONNECTION`: `mysql` (atau `pgsql`)
   - `DB_HOST`: *(Host database online Anda, misal dari PlanetScale / Supabase / Aiven)*
   - `DB_DATABASE`: *(Nama database online)*
   - `DB_USERNAME`: *(User database)*
   - `DB_PASSWORD`: *(Password database)*
   - `PYTHON_API_URL`: `https://talongkajaya-api.onrender.com` *(URL dari Render pada langkah 2)*
3. **Deploy**:
   - Klik **Deploy**. Vercel akan mem-build aplikasi web Anda.

---

### Opsi B: Deploy ke cPanel (Shared Hosting Biasa)

Jika Anda membeli hosting seperti Niagahoster, Hostinger, DomaiNesia, dll:

1. **Export Database MySQL**:
   - Buka phpMyAdmin di Laragon lokal, export database `alongkajaya_sentimen` ke file `.sql`.
   - Buat database baru di cPanel (MySQL Database Wizard) dan import file `.sql` tadi via phpMyAdmin cPanel.
2. **Upload File Projek**:
   - Kompres semua file projek menjadi `.zip` (kecuali folder `vendor`, `node_modules`, dan `python-api/venv`).
   - Upload ke `public_html` atau satu level di atas `public_html`.
   - Pastikan isi folder `public` Laravel berada di dalam `public_html`.
3. **Sesuaikan File `.env` di cPanel**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domainanda.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_db_cpanel
   DB_USERNAME=user_db_cpanel
   DB_PASSWORD=password_db_cpanel

   PYTHON_API_URL=https://talongkajaya-api.onrender.com
   ```

---

## 4. Keunggulan Arsitektur yang Baru Diperbaiki

1. **Resilien & Anti Crash**:
   Jika server Python di Render sedang *sleep* / offline sementara waktu, formulir ulasan **TIDAK AKAN ERROR 500**. Sistem otomatis beralih ke analisis aturan leksikon sehingga pelanggan tetap bisa mengirim ulasan dengan lancar.
2. **Performa Tinggi**:
   Admin dashboard sekarang memuat data secara instan tanpa melakukan perulangan berat (*loop recalculate*) di setiap kali halaman dibuka.
3. **Aman & Stateless**:
   Tidak ada lagi operasi penyalinan file dinamis di dalam controller publik, memenuhi standar arsitektur *Read-Only Cloud Filesystem*.
4. **Sinkronisasi Fleksibel**:
   Admin memiliki tombol khusus **"Sinkronkan Sentimen"** di halaman `/admin/ulasan` untuk menghitung ulang data secara manual hanya saat dibutuhkan.
