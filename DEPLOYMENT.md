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

## 2. Cara Deploy Python API

### Opsi: Railway.app (Sangat Stabil & Cepat) ⭐ PILIHAN UTAMA

[Railway.app](https://railway.app) adalah platform cloud modern yang sangat andal dan mudah dikonfigurasi.

#### Langkah-langkah di Railway:
1. **Daftar / Login**:
   - Buka [railway.app](https://railway.app) dan login menggunakan akun GitHub Anda.
2. **Buat Projek Baru**:
   - Klik tombol **+ New Project**.
   - Pilih **Deploy from GitHub repo**.
   - Pilih repository Anda: **`Gerrard567/talongkajaya-sentimen`**.
3. **Atur Root Directory ke `python-api`**:
   - Klik kotak layanan projek yang muncul di canvas Railway.
   - Buka tab **Settings**.
   - Cari bagian **Service** $\rightarrow$ **Root Directory**.
   - Masukkan: `/python-api` lalu klik simpan/save.
   - *(Railway akan otomatis mendeteksi `Dockerfile` dan `requirements.txt` yang sudah siap di dalam folder tersebut).*
4. **Buat Domain Publik**:
   - Masih di tab **Settings**, gulir ke bawah ke bagian **Networking** / **Public Networking**.
   - Klik tombol **Generate Domain**.
   - Railway akan memberikan URL publik, contohnya:
     `https://talongkajaya-api-production-xxxx.up.railway.app`
5. **Tes API**:
   - Buka domain tersebut di browser. Jika muncul:
     `{"message": "Sentiment Analysis API is running", "status": "Ready"}`
     berarti AI Anda sudah resmi online di Railway!

---

[Koyeb.com](https://www.koyeb.com) adalah platform cloud modern yang sangat ramah pengguna karena **tidak meminta nomor kartu kredit/debit** untuk mendaftar dan menjalankan service gratis.

#### Langkah-langkah di Koyeb:
1. **Daftar Akun**:
   - Buka [app.koyeb.com/auth/signup](https://app.koyeb.com/auth/signup) dan pilih **Sign up with GitHub**.
2. **Buat Service Baru**:
   - Klik tombol **Create Service** (atau **Create App**).
   - Pada pilihan *Deployment Method*, pilih **GitHub**.
   - Pilih repository Anda: **`Gerrard567/talongkajaya-sentimen`**.
3. **Konfigurasi Service**:
   - **Branch**: `main`
   - **Root Directory**: ketik `python-api` *(PENTING)*
   - **Builder**: Biarkan **Dockerfile** (otomatis terdeteksi) atau **Buildpack**.
   - **Instance Type**: Pilih **Eco (Free)**.
   - **Regions**: Pilih yang terdekat (misal: *Frankfurt* atau *Washington*).
   - **Ports / Routing**: Pastikan internal port terisi **`8000`** (Protokol HTTP).
   - **Service Name**: `talongkajaya-api` (atau nama lain yang Anda sukai).
4. **Deploy**:
   - Klik tombol hijau **Deploy**.
   - Tunggu sekitar 1–2 menit hingga status berubah menjadi **Healthy** (hijau).
   - Anda akan mendapatkan URL publik, contohnya:
     `https://talongkajaya-api-username.koyeb.app`
5. **Tes API**:
   - Buka URL tersebut di browser. Jika muncul respons JSON:
     `{"message": "Sentiment Analysis API is running", "status": "Ready"}`
     berarti AI Anda sudah resmi online!

---

### Opsi Alternatif: Render.com (Perlu Kartu Debit/Kredit)

Jika Anda memiliki kartu Visa/Mastercard dengan transaksi internasional aktif:
1. Buka [render.com](https://render.com) $\rightarrow$ Login via GitHub.
2. Klik **New +** $\rightarrow$ **Web Service** $\rightarrow$ Hubungkan repo `talongkajaya-sentimen`.
3. Set **Root Directory**: `python-api`, **Language**: `Python 3`, **Build**: `pip install -r requirements.txt`, **Start**: `uvicorn main:app --host 0.0.0.0 --port $PORT`, Plan: **Free**.
4. Selesaikan verifikasi dan deploy.

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
