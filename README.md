# 📦 Sistem Evaluasi Kinerja Kurir Logistik (K-Means & TOPSIS)

Sistem berbasis web ini dikembangkan untuk mengevaluasi dan meranking kinerja kurir pengiriman (studi kasus: GoTo Logistik) berdasarkan data pengiriman historis. Sistem ini mengkombinasikan algoritma **K-Means Clustering** untuk pengelompokan dan metode **TOPSIS** (Technique for Order Preference by Similarity to Ideal Solution) untuk peringkatan absolut.

## 🚀 Fitur Utama
*   **Pengelompokan Data (Clustering):** Menggunakan K-Means untuk membagi kurir ke dalam kelompok performa berdasarkan kesamaan atribut kerja.
*   **Evaluasi Klaster:** Terintegrasi dengan pengujian **Davies-Bouldin Index (DBI)** untuk mengukur validitas dan optimalisasi jarak antar klaster.
*   **Sistem Peringkat (Ranking):** Menggunakan metode TOPSIS untuk memberikan peringkat performa terbaik pada masing-masing kelompok.
*   **Manajemen Data Kurir:** Fitur CRUD (Create, Read, Update, Delete) untuk data performa kurir harian/bulanan.

## 🛠️ Teknologi yang Digunakan
*   **Backend:** PHP 8.x, Laravel Framework
*   **Frontend:** Blade Templating Engine, HTML, CSS, JavaScript (Bootstrap/Tailwind)
*   **Database:** MySQL
*   **Algoritma:** K-Means, TOPSIS, Evaluasi DBI

## 📊 Kriteria Evaluasi (Dataset)
Sistem ini memproses atribut data kinerja berikut:
1.  Total Order Paket
2.  Jumlah Paket Tepat Waktu
3.  Jumlah Paket Terlambat
4.  Jumlah Paket Gagal Dikirim

## ⚙️ Cara Instalasi
1. Clone repositori ini: `git clone https://github.com/okibagus123/pemeringkatan-kelompok-kinerja-kurir.git`
2. Masuk ke direktori proyek: `cd pemeringkatan-kelompok-kinerja-kurir`
3. Salin file `.env.example` menjadi `.env` dan atur konfigurasi database Anda.
4. Instal dependensi PHP: `composer install`
5. Generate *application key*: `php artisan key:generate`
6. Jalankan migrasi database: `php artisan migrate`
7. Jalankan *local server*: `php artisan serve`

---
*Dikembangkan oleh Oki Bagus Srijianto sebagai bagian dari riset Decision Support System (DSS).*
