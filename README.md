## Teknologi yang Digunakan

- **Frontend**: Laravel 11, Blade Templates, TailwindCSS, Laravel HTTP Client.
- **Backend**: Node.js, Express.js, Sequelize ORM, jsonwebtoken, bcryptjs.
- **Database**: MySQL.

---

## Cara Menjalankan Project

### 1. Persiapan Database
1. Buat sebuah database kosong di MySQL (misal menggunakan phpMyAdmin atau MySQL CLI) dengan nama `capstone2`:
   ```sql
   CREATE DATABASE capstone2;
   ```

### 2. Menjalankan Backend Node.js
1. Buka terminal baru dan masuk ke folder `backend`:
   ```bash
   cd backend
   ```
2. Salin `.env.example` ke `.env` dan sesuaikan kredensial database jika perlu:
   ```bash
   copy .env.example .env
   ```
3. Install dependensi Node.js:
   ```bash
   npm install
   ```
4. Jalankan seeder untuk membuat tabel dan data admin default:
   ```bash
   node seeders/adminSeeder.js
   ```
5. Jalankan server backend:
   ```bash
   node server.js
   ```
   Server backend akan berjalan di `http://localhost:3000`.

### 3. Menjalankan Frontend Laravel
1. Buka terminal baru di root folder project (`tubespwluas-main`).
2. Salin `.env.example` ke `.env` dan pastikan konfigurasi API base URL telah disesuaikan:
   ```bash
   copy .env.example .env
   ```
   Pastikan baris berikut ada di file `.env` Laravel:
   ```text
   API_BASE_URL=http://localhost:3000/api
   SESSION_DRIVER=file
   ```
3. Install dependensi composer:
   ```bash
   composer install
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Install dependensi npm & compile aset CSS/JS:
   ```bash
   npm install
   npm run dev
   ```
6. Jalankan server Laravel:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di browser pada alamat `http://127.0.0.1:8000`.

