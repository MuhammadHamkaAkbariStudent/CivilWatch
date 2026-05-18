# 🚧 CivilWatch

**CivilWatch** adalah platform pengaduan masyarakat berbasis web modern untuk melaporkan masalah infrastruktur publik, seperti jalan berlubang, lampu jalan mati, pohon tumbang, dan isu lingkungan lainnya. Proyek ini dirancang untuk menjembatani komunikasi antara warga dan instansi pemerintah secara **transparan, partisipatif, dan terukur**.

## 📌 Daftar Isi

- [Tech Stack](#-tech-stack)
- [Fitur Utama](#-fitur-utama)
- [Skema Database & Relasi](#-skema-database--relasi)
- [Struktur Proyek & Pembagian Kerja Tim](#-struktur-proyek--pembagian-kerja-tim)
- [Panduan Instalasi](#-panduan-instalasi)
- [Tujuan Akademik](#-tujuan-akademik)

## 🧰 Tech Stack & Kebutuhan Teknis

Proyek ini dibangun menggunakan kombinasi bahasa pemrograman dasar, framework modern, serta alat pengembangan berikut:

### 🌐 Bahasa Pemrograman & Templating
* **PHP (v8.x+)**: Bahasa utama untuk pengembangan logika di sisi *backend* (server-side).
* **JavaScript (ES6+)**: Digunakan untuk menangani logika interaktif di sisi *client-side*.
* **CSS3**: Bahasa dasar untuk penataan gaya global dan *custom styling* antarmuka.
* **Laravel Blade Engine**: Mesin template bawaan Laravel yang digunakan untuk menyusun struktur HTML dinamis secara modular (komponen, *layouting*, dan *data binding*).

### 🚀 Framework & Library
* **Backend Framework**: **Laravel (v13.x+)** – Framework PHP yang tangguh untuk mengelola rute, kontroler, keamanan, dan ORM (Eloquent).
* **Authentication Kit**: **Laravel Breeze** – Direkomendasikan sebagai sistem otentikasi siap pakai karena berbasis Blade dan Tailwind, sangat fleksibel untuk dikonfigurasi menjadi *multi-role*.
* **Frontend CSS Framework**: **Tailwind CSS** – Framework CSS berbasis *utility-first* untuk mempercepat proses *slicing* UI yang responsif dan modern.
* **Frontend JS Library**: **Alpine.js** – Library JavaScript yang sangat ringan untuk menghidupkan komponen UI (seperti modal, pencarian dinamis, dan interaksi *upvote*) langsung di dalam file Blade tanpa *reload* halaman.

### 🗄️ Manajemen Data
* **Database**: **PostgreSQL** – Sistem manajemen basis data relasional tingkat perusahaan yang digunakan untuk mengelola relasi data kompleks (*One-to-Many* dan *Many-to-Many*) dengan performa tinggi.
## ✨ Fitur Utama

- 🔐 **Multi-User Authentication**
    - Sistem autentikasi multi-peran: **Admin** dan **Citizen**.
- 🌍 **Public Feed**
    - Semua warga dapat melihat laporan publik yang telah diverifikasi oleh admin.
- 🔎 **Smart Search & Filter**
    - Pencarian laporan berdasarkan subjek (contoh: _Pohon Jatuh_).
    - Filter laporan berdasarkan wilayah/kecamatan.
- 👍 **Interactive Upvote System**
    - Pengguna dapat memberikan dukungan pada laporan tertentu secara interaktif tanpa reload halaman.
    - Upvote merepresentasikan tingkat urgensi masalah di lapangan.
- 📊 **Exclusive Admin Dashboard**
    - Ringkasan total laporan masuk.
    - Visualisasi tren laporan bulanan.
    - Peta wilayah prioritas untuk pengambilan keputusan.
- 👤 **Citizen Dashboard**
    - Panel personal warga untuk memantau status laporan pribadi.
    - Riwayat aktivitas upvote pengguna.
- 🛠️ **Progress Tracking**
    - Riwayat tanggapan dan progres pengerjaan dari petugas.
    - Informasi progres dapat diakses publik untuk menjaga akuntabilitas.
- 🖼️ **File Management**
    - Fitur unggah foto bukti kerusakan fisik sebagai pendukung validitas laporan.

## 🗄️ Skema Database & Relasi

CivilWatch menggunakan pendekatan basis data relasional untuk memastikan integritas data dan mendukung kebutuhan fungsional sistem.

### 1) One-to-Many: Districts -> Reports

- Satu **district/wilayah** dapat memiliki banyak **reports/laporan**.
- Digunakan untuk segmentasi laporan berdasarkan kecamatan atau area administratif.

### 2) One-to-Many: Users -> Reports

- Satu **user (citizen)** dapat membuat banyak **reports**.
- Memudahkan pelacakan kontribusi serta histori pelaporan per pengguna.

### 3) One-to-Many: Reports -> Progress_Updates

- Satu **report** memiliki banyak **progress updates**.
- Setiap pembaruan mencatat perkembangan penanganan masalah dari petugas.

### 4) Many-to-Many: Users <-> Reports (Pivot: upvotes)

- Satu **user** dapat memberikan upvote pada banyak **report**.
- Satu **report** dapat menerima upvote dari banyak **user**.
- Relasi ini diimplementasikan melalui tabel pivot **upvotes**.

## 👥 Struktur Proyek & Pembagian Kerja Tim

Proyek tugas akhir ini dikerjakan oleh **tim berisi 3 orang** dengan pembagian peran, tanggung jawab *Controller*, dan file logika yang terisolasi untuk menghindari konflik repositori (*git merge conflict*):

## 👥 Struktur Proyek & Pembagian Kerja Tim

Proyek tugas akhir ini dikerjakan oleh **tim berisi 3 orang** dengan pembagian peran, tanggung jawab *Controller*, dan file logika yang terisolasi untuk menghindari konflik repositori (*git merge conflict*):

### 👨‍💻 Backend Developer 1 (Database Architect & Foundation)
Fokus pada arsitektur dasar basis data, sistem keamanan otentikasi, dan pengelolaan data master:
* **Inisiasi Proyek:** Setup awal repositori Git, inisialisasi proyek Laravel, dan konfigurasi koneksi database PostgreSQL lokal.
* **Database Architect:** Membuat seluruh file *Migration* (5 tabel) beserta definisi relasi *Eloquent* lengkap di dalam file Model Laravel sejak awal proyek.
* **Otentikasi Laravel Breeze (Core Auth):** Menginstalasi package Laravel Breeze (versi Blade), memodifikasi *migration* tabel `users` untuk kolom `role`, serta mengatur logika *Middleware* untuk pengalihan (*redirect*) otomatis: Admin ke Dashboard Admin, Citizen ke Public Feed.
* **Manajemen Wilayah (`DistrictController`):** Mengembangkan rute dan logika CRUD penuh untuk mengelola data master kecamatan/wilayah di panel Admin.

### 🧠 Backend Developer 2 (Core Business Logic & API)
Fokus pada alur bisnis pengaduan, manipulasi file media, relasi data dinamis, dan penyiapan data statistik:
* **Manajemen Laporan (`ReportController`):** Membuat logika CRUD laporan pengaduan dari sisi warga, termasuk validasi input data dan sistem penyimpanan file foto bukti kerusakan ke folder *storage*.
* **Logika Upvote (`UpvoteController`):** Mengembangkan logika *toggle upvote* (relasi *Many-to-Many*) untuk menambah atau menghapus baris data dukungan secara unik pada tabel pivot.
* **Tanggapan Progres (`ProgressUpdateController`):** Membuat fungsi bagi Admin untuk menginput catatan pembaruan status pengerjaan fasilitas fisik di lapangan.
* **Query Dashboard (`DashboardController`):** Menyusun query data statistik (total pengaduan global vs personal, tren bulanan, wilayah prioritas) untuk dikirimkan secara dinamis ke halaman frontend.

### 🎨 Frontend Developer (UI/UX & Interactive Specialist)
Fokus pada estetika visual, tata letak responsif, dan menghidupkan komponen interaktif menggunakan framework CSS dan library JS:
* **Base Layouting (Tailwind CSS):** Merancang arsitektur tampilan dasar aplikasi, mulai dari Navbar dinamis (berubah otomatis berdasarkan role user yang login), Sidebar Admin, hingga Footer.
* **Kustomisasi Tampilan Auth (Breeze Views):** Menata ulang dan mempercantik halaman Login dan Register yang di-generat oleh Laravel Breeze agar memiliki komponen visual dan tema warna yang serasi dengan aplikasi CivilWatch.
* **Slicing Views & Forms (Laravel Blade):** Membangun antarmuka halaman *Landing Page*, *Public Feed* (daftar kartu laporan), form pembuatan laporan warga, hingga panel Dashboard visual.
* **Interaktivitas Client-Side (Alpine.js):** Menghidupkan komponen interaktif seperti bilah pencarian (*search bar*) dinamis untuk menyaring subjek laporan secara instan di layar.
* **Integrasi Data & AJAX:** Menghubungkan tombol **Upvote** pada komponen Blade menggunakan Alpine.js (menggunakan Fetch/Axios) untuk menembak endpoint milik *Backend 2*, sehingga angka dukungan langsung terbarui secara *real-time* tanpa perlu memuat ulang (*reload*) halaman.

## ⚙️ Panduan Instalasi

Ikuti langkah berikut untuk menjalankan proyek CivilWatch di lingkungan lokal.

### 1) Clone repository

```bash
git clone https://github.com/username/CivilWatch.git
cd CivilWatch
```

### 2) Install dependency backend dan frontend

```bash
composer install
npm install
```

### 3) Konfigurasi environment

Salin file environment, lalu sesuaikan konfigurasi PostgreSQL.

```bash
cp .env.example .env
php artisan key:generate
```

Contoh konfigurasi database pada `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=civilwatch
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 4) Migrasi dan seeding database

```bash
php artisan migrate --seed
```

### 5) Jalankan aplikasi

Jalankan backend server Laravel dan frontend dev server secara terpisah.

```bash
php artisan serve
npm run dev
```

Setelah itu, akses aplikasi melalui alamat default Laravel, biasanya:

```text
http://127.0.0.1:8000
```

## 🎓 Tujuan Akademik

Repositori ini disusun sebagai bagian dari **proyek tugas akhir kuliah**, dengan fokus pada:

- Implementasi arsitektur aplikasi web modern berbasis Laravel.
- Penerapan relasi database relasional sesuai kebutuhan studi kasus nyata.
- Pengembangan fitur kolaboratif warga-pemerintah yang transparan dan berdampak sosial.
