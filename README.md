# Project Capstone 2: Sistem Manajemen Aset Laboratorium

Proyek ini dibangun menggunakan arsitektur **Microservices** terpisah:
- **Frontend**: Laravel 11 (Blade + TailwindCSS)
- **Backend**: Node.js (Express.js + Sequelize)
- **Database**: MySQL

---

## 🚀 Cara Menjalankan Proyek di Komputer Lokal

Karena proyek ini memiliki dua "mesin" (Frontend dan Backend), Anda dan teman kelompok Anda harus menjalankan keduanya secara bersamaan di terminal yang berbeda.

### Prasyarat (Pastikan sudah terinstal)
1. [Node.js](https://nodejs.org/) (Versi 18+ direkomendasikan)
2. [PHP](https://windows.php.net/download/) (Versi 8.2+ untuk Laravel 11)
3. [Composer](https://getcomposer.org/) (Package Manager PHP)
4. XAMPP / MySQL Server

---

### Tahap 1: Persiapan Database
1. Buka aplikasi **XAMPP** dan jalankan modul **MySQL**.
2. Pastikan port MySQL berjalan di `3306` (default).

### Tahap 2: Menjalankan Backend (Node.js & Database API)
Buka terminal / CMD baru, lalu masuk ke folder `backend`:
```bash
cd backend

# --- HANYA DILAKUKAN SAAT PERTAMA KALI CLONE PROYEK ---
npm install
node reset_db.js
node sync.js
# -------------------------------------------------------

# --- RUTINITAS HARIAN (Setiap kali mau ngoding) ---
node server.js
```
*Tanda sukses: Anda akan melihat pesan `Server is running on port 3000.`*

### Tahap 3: Menjalankan Frontend (Laravel)
Buka tab terminal / CMD KEDUA, tetap berada di folder utama proyek:
```bash
# --- HANYA DILAKUKAN SAAT PERTAMA KALI CLONE PROYEK ---
composer install
npm install
# -------------------------------------------------------

# --- RUTINITAS HARIAN (Setiap kali mau ngoding) ---
# Jalankan Vite (Untuk kompilasi UI Tailwind)
npm run dev
```

Buka tab terminal / CMD KETIGA, jalankan server PHP Laravel:
```bash
# --- RUTINITAS HARIAN (Setiap kali mau ngoding) ---
php artisan serve
```
*Tanda sukses: Aplikasi dapat diakses melalui browser di alamat: `http://localhost:8000`*

---

## 👥 Cara Berbagi Kode (Kolaborasi)

Agar Anda dan teman kelompok bisa mengerjakan file yang sama tanpa harus saling kirim file `.zip`, sangat disarankan menggunakan **Git dan GitHub**:

1. **Pemilik Proyek (Anda):**
   - Buat repositori kosong di GitHub.
   - Buka terminal di folder proyek ini dan jalankan:
     ```bash
     git init
     git add .
     git commit -m "Initial commit microservices"
     git branch -M main
     git remote add origin URL_GITHUB_ANDA
     git push -u origin main
     ```
2. **Teman Kelompok:**
   - Kloning repositori tersebut ke komputer mereka:
     ```bash
     git clone URL_GITHUB_ANDA
     ```
   - Lakukan Tahap 2 dan Tahap 3 di atas di komputer masing-masing. (Catatan: Pastikan mereka men-copy file `.env.example` menjadi `.env` di folder utama Laravel lalu mengisi `APP_KEY` dengan menjalankan `php artisan key:generate`).

---

## 🔑 Data Akun Uji Coba (Dummy)
Jika Anda sudah menjalankan `node sync.js`, database otomatis terisi dengan data berikut (Password semuanya: **`password`**):
- **Admin**: `admin@example.com`
- **Kepala Lab**: `kalab@example.com`
- **Kaprodi**: `kaprodi@example.com`
- **Staf Admin**: `stafadmin@example.com`
- **Staf Lab**: `staflab@example.com`
