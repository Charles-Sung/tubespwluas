# Capstone Project - Minggu 2: Autentikasi & Master Data

Proyek ini merupakan implementasi sistem autentikasi menggunakan JSON Web Token (JWT) dan CRUD data master oleh Administrator. Proyek dibangun menggunakan arsitektur microservices terpisah antara Frontend dan Backend.

---

## Arsitektur Microservices

Aplikasi ini dibagi menjadi dua bagian utama:
1. **Frontend (Laravel 11)**:
   - Digunakan murni untuk tampilan (Blade), routing halaman, pengelolaan form, dan berkomunikasi dengan backend melalui HTTP Client.
   - Token JWT yang diterima dari backend setelah login disimpan di session Laravel.
   - Setiap request CRUD ke backend akan menyertakan token JWT pada header `Authorization: Bearer <token>`.
   - Menggunakan styling modern dan responsif dengan **TailwindCSS**.
2. **Backend (Node.js / Express.js)**:
   - Bertindak sebagai REST API server yang melayani autentikasi dan operasi data master.
   - Menggunakan **Sequelize** sebagai ORM untuk koneksi dan pengelolaan database MySQL.
   - Melindungi endpoint CRUD menggunakan middleware JWT.
3. **Database (MySQL)**:
   - Digunakan untuk menyimpan data Users, Rooms, dan Items.

---

## Fitur Proyek

- **Autentikasi JWT**:
  - Login khusus admin dengan validasi password menggunakan `bcrypt`.
  - Token JWT digenerate oleh backend dan disimpan di session frontend Laravel.
  - Halaman dilindungi oleh middleware frontend (`AuthJWT`) dan middleware backend (`authMiddleware`).
- **Master Data Users**:
  - CRUD User oleh administrator (Name, Email, Password, Role).
- **Master Data Ruangan (Rooms)**:
  - CRUD Ruangan (Room Name, Location, Capacity).
- **Master Data Barang (Items)**:
  - CRUD Barang (Item Name, Category, Stock, Room ID) dengan dropdown data ruangan.

---

## Teknologi yang Digunakan

- **Frontend**: Laravel 11, Blade Templates, TailwindCSS, Laravel HTTP Client.
- **Backend**: Node.js, Express.js, Sequelize ORM, jsonwebtoken, bcryptjs.
- **Database**: MySQL.

---

## Struktur Folder Project

```text
tubespwluas-main/
├── app/
│   └── Http/
│       ├── Controllers/
│       │   ├── AuthController.php
│       │   ├── DashboardController.php
│       │   ├── UserController.php
│       │   ├── RoomController.php
│       │   └── ItemController.php
│       └── Middleware/
│           └── AuthJWT.php
├── bootstrap/
│   └── app.php (Registrasi middleware alias)
├── backend/
│   ├── config/
│   │   └── database.js (Koneksi Sequelize)
│   ├── controllers/
│   │   ├── authController.js
│   │   ├── userController.js
│   │   ├── roomController.js
│   │   └── itemController.js
│   ├── middleware/
│   │   └── authMiddleware.js
│   ├── models/
│   │   ├── index.js
│   │   ├── User.js
│   │   ├── Room.js
│   │   └── Item.js
│   ├── routes/
│   │   ├── authRoutes.js
│   │   ├── userRoutes.js
│   │   ├── roomRoutes.js
│   │   └── itemRoutes.js
│   ├── seeders/
│   │   └── adminSeeder.js (Sync DB & Seed Admin)
│   ├── server.js (Express server entry point)
│   ├── .env
│   └── package.json
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── layouts/
│       │   └── app.blade.php
│       ├── users/
│       ├── rooms/
│       ├── items/
│       └── dashboard.blade.php
├── routes/
│   └── web.php (Routing Laravel)
├── .env (Config Laravel)
└── README.md
```

---

## Akun Login Default Admin

- **Email**: `admin@gmail.com`
- **Password**: `admin123`
- **Role**: `admin`

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

---

## Endpoint API Backend

| Method | Endpoint | Keterangan | Proteksi JWT |
|---|---|---|---|
| **POST** | `/api/login` | Login admin, mengembalikan token JWT | Tidak |
| **GET** | `/api/users` | Mengambil seluruh data user | Ya |
| **POST** | `/api/users` | Menambahkan user baru | Ya |
| **GET** | `/api/users/:id` | Mengambil data user berdasarkan ID | Ya |
| **PUT** | `/api/users/:id` | Memperbarui data user berdasarkan ID | Ya |
| **DELETE** | `/api/users/:id` | Menghapus user berdasarkan ID | Ya |
| **GET** | `/api/rooms` | Mengambil seluruh data ruangan | Ya |
| **POST** | `/api/rooms` | Menambahkan ruangan baru | Ya |
| **GET** | `/api/rooms/:id` | Mengambil data ruangan berdasarkan ID | Ya |
| **PUT** | `/api/rooms/:id` | Memperbarui data ruangan berdasarkan ID | Ya |
| **DELETE** | `/api/rooms/:id` | Menghapus ruangan berdasarkan ID | Ya |
| **GET** | `/api/items` | Mengambil seluruh data barang + nama ruangan | Ya |
| **POST** | `/api/items` | Menambahkan barang baru | Ya |
| **GET** | `/api/items/:id` | Mengambil data barang berdasarkan ID | Ya |
| **PUT** | `/api/items/:id` | Memperbarui data barang berdasarkan ID | Ya |
| **DELETE** | `/api/items/:id` | Menghapus barang berdasarkan ID | Ya |

---

## Progress Minggu 2

- [x] Konfigurasi Sequelize & Model Database (User, Room, Item)
- [x] Pembuatan REST API Backend (Express.js)
- [x] Middleware Keamanan JWT Backend & Frontend
- [x] Integrasi HTTP Client Laravel dengan REST API Backend
- [x] Dashboard Master Data dengan Tampilan Premium TailwindCSS
- [x] Operasi CRUD Master Data Terintegrasi Penuh
