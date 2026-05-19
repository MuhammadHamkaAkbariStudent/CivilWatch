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
* **PHP (v8.5+)**: Bahasa utama untuk pengembangan logika di sisi *backend* (server-side).
* **JavaScript**: Digunakan untuk menangani logika interaktif di sisi *client-side*.
* **CSS**: Bahasa dasar untuk penataan gaya global dan *custom styling* antarmuka.
* **Laravel Blade Engine**: Mesin template bawaan Laravel yang digunakan untuk menyusun struktur HTML dinamis secara modular (komponen, *layouting*, dan *data binding*).

### 🚀 Framework & Library
* **Backend Framework**: **Laravel (v13)** – Framework PHP yang tangguh untuk mengelola rute, kontroler, keamanan, dan ORM (Eloquent).
* **Authentication Kit**: **Laravel Breeze** – Direkomendasikan sebagai sistem otentikasi siap pakai karena berbasis Blade dan Tailwind, sangat fleksibel untuk dikonfigurasi menjadi *multi-role*.
* **Frontend CSS Framework**: **Tailwind CSS** – Framework CSS berbasis *utility-first* untuk mempercepat proses *slicing* UI yang responsif dan modern.
* **Frontend JS Library**: **Alpine.js** – Library JavaScript yang sangat ringan untuk menghidupkan komponen UI (seperti modal, pencarian dinamis, dan interaksi *upvote*) langsung di dalam file Blade tanpa *reload* halaman.

### 🗄️ Manajemen Data
* **Database**: **PostgreSQL** – Sistem manajemen basis data relasional tingkat perusahaan yang digunakan untuk mengelola relasi data kompleks (*One-to-Many* dan *Many-to-Many*) dengan performa tinggi.

## 🌐 Arsitektur & Peta Halaman Aplikasi (Routing Map)

CivilWatch dibangun dengan struktur rute yang terorganisir yang menghasilkan **13 halaman utama** yang terbagi dalam 3 kelompok akses: Publik (6), Citizen (3), dan Admin (4). Berikut adalah rincian mengenai isi komponen dan fungsi dari setiap halaman:

---

### 🌍 1. Akses Publik & Otentikasi (6 Halaman)

* **Halaman 1: Landing Page (`/`)**
  * **Isi Halaman:** Informasi pengenalan platform, cara kerja singkat sistem, statistik global interaktif, dan tombol aksi (*Call to Action*).
  * **Fungsi:** Mengenalkan platform CivilWatch kepada masyarakat umum dan mengarahkan warga untuk mulai berpartisipasi atau masuk ke sistem.

* **Halaman 2: Login (`/login`)**
  * **Isi Halaman:** Formulir input email dan kata sandi, tombol pengingat (*Remember Me*), serta tautan menuju halaman pendaftaran.
  * **Fungsi:** Gerbang autentikasi keamanan bagi pengguna (Admin & Citizen) untuk masuk ke hak akses masing-masing.

* **Halaman 3: Register (`/register`)**
  * **Isi Halaman:** Formulir pengisian nama lengkap, email unik, pembuatan kata sandi, dan konfirmasi kata sandi.
  * **Fungsi:** Tempat pendaftaran akun warga (*Citizen*) baru agar data pelapor tercatat secara valid di dalam basis data.

* **Halaman 4: Profil Pengguna (`/profile`)**
  * **Isi Halaman:** Informasi detail akun saat ini serta formulir pembaruan data personal dan pengubahan kata sandi.
  * **Fungsi:** Ruang bagi pengguna untuk mengelola keamanan akun dan memperbarui data pribadi secara mandiri.

* **Halaman 5: Public Feed (`/feed`)**
  * **Isi Halaman:** Kumpulan kartu (*cards*) laporan infrastruktur yang telah diverifikasi, tombol *upvote* interaktif pada setiap kartu laporan, bilah pencarian (*search bar*) kata kunci, dan tombol saringan (*filter dropdown*) kecamatan.
  * **Fungsi:** Wadah transparansi publik agar seluruh elemen masyarakat dapat melihat, mencari, memantau, dan memberikan dukungan terhadap keluhan fasilitas fisik di berbagai wilayah.

* **Halaman 6: Detail Laporan (`/reports/{id}`)**
  * **Isi Halaman:** Tampilan foto penuh bukti kerusakan fisik, deskripsi kronologis aduan, total akumulasi dukungan *upvote*, serta komponen linimasa perkembangan (*Progress Tracking*).
  * **Fungsi:** Menyediakan informasi menyeluruh mengenai satu aduan spesifik dan riwayat penanganannya dari awal hingga selesai.

---

### 👤 2. Panel Masyarakat (Citizen Workspace - 3 Halaman)

Rute di bawah proteksi keamanan *middleware* khusus warga (`role: citizen`):

* **Halaman 7: Citizen Dashboard (`/citizen/dashboard`)**
  * **Isi Halaman:** Kartu ringkasan status personal (jumlah laporan *pending*, *in_progress*, *resolved*) milik warga bersangkutan, daftar laporan pribadi beserta status masing-masing, tautan *shortcut* menuju form pembuatan laporan baru, serta daftar riwayat aktivitas *upvote* yang pernah diberikan.
  * **Fungsi:** Ruang kendali pribadi bagi warga untuk melacak kontribusi, memantau status aduannya sendiri secara berkala, dan mengakses laporan yang masih bisa diedit.

* **Halaman 8: Form Buat Laporan (`/citizen/reports/create`)**
  * **Isi Halaman:** Input judul/subjek masalah, deskripsi lengkap kerusakan, pilihan *dropdown* kecamatan lokasi kejadian, dan komponen unggah (*upload*) file foto bukti fisik.
  * **Fungsi:** Memfasilitasi warga untuk mengirimkan atau menginput pengaduan infrastruktur baru secara valid ke dalam sistem.

* **Halaman 9: Edit Laporan (`/citizen/reports/{id}/edit`)**
  * **Isi Halaman:** Formulir pengisian laporan yang telah terisi data lama pengguna untuk direvisi kembali.
  * **Fungsi:** Mengizinkan warga memperbaiki kesalahan input data atau memperbarui foto bukti selama status aduan tersebut masih berada dalam fase *Pending* (belum diproses oleh admin). Halaman ini hanya dapat diakses jika status laporan masih *Pending* — selain itu pengguna akan diarahkan kembali secara otomatis (*redirect*).

---

### 📊 3. Panel Admin (Admin Control Center - 4 Halaman)

Rute di bawah proteksi keamanan ketat *middleware* khusus petugas (`role: admin`):

* **Halaman 10: Admin Dashboard (`/admin/dashboard`)**
  * **Isi Halaman:** Ringkasan total aduan global masuk, grafik tren statistik bulanan, dan matriks pemetaan wilayah kecamatan dengan tingkat urgensi keluhan tertinggi.
  * **Fungsi:** Memberikan gambaran data makro bagi pengambil keputusan atau petugas instansi untuk melihat tren kerusakan fasilitas kota.

* **Halaman 11: Kelola Wilayah (`/admin/districts`)**
  * **Isi Halaman:** Tabel daftar seluruh kecamatan/wilayah administratif, dilengkapi tombol aksi tambah, edit, dan hapus data master.
  * **Fungsi:** Mengelola data referensi lokasi resmi (CRUD Data Master) sebagai jangkar utama pilihan wilayah di seluruh rute aplikasi.

* **Halaman 12: Manajemen Laporan (`/admin/reports`)**
  * **Isi Halaman:** Tabel daftar **seluruh** laporan masuk dari masyarakat tanpa terkecuali — termasuk laporan yang belum diverifikasi (*pending*) yang tidak tampil di *Public Feed* — dilengkapi filter berdasarkan status, fitur pencarian cepat, dan tombol aksi verifikasi.
  * **Fungsi:** Pusat kendali admin untuk menyaring, memeriksa, mengubah status, serta mengelola alur masuknya keluhan masyarakat secara sistematis. Berbeda dengan *Public Feed* yang hanya menampilkan laporan terverifikasi, halaman ini memberikan akses penuh ke seluruh data laporan.

* **Halaman 13: Detail + Input Progress Admin (`/admin/reports/{id}`)**
  * **Isi Halaman:** Tampilan berkas laporan warga secara mendalam, tombol validasi (Terima/Tolak), formulir khusus untuk menginput teks catatan pembaruan progres pengerjaan di lapangan, serta linimasa seluruh catatan *progress update* yang pernah diinput sebelumnya.
  * **Fungsi:** Tempat admin memvalidasi aduan secara mendalam sekaligus memperbarui lini masa penanganan masalah fisik agar bisa dipantau langsung oleh publik.

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

### 📂 Daftar Tabel Basis Data

Berikut adalah daftar tabel PostgreSQL yang diimplementasikan di dalam sistem:
* 👤 **`users`** : Menyimpan data informasi akun milik warga maupun admin/petugas.
* 🗺️ **`districts`** : Tabel master untuk menyimpan daftar kecamatan atau wilayah administratif.
* 📋 **`reports`** : Pusat data utama yang menampung seluruh berkas laporan pengaduan dari warga, termasuk kolom `status` untuk melacak fase penanganan (*pending*, *in_progress*, *resolved*, *rejected*).
* 🛠️ **`progress_updates`** : Menyimpan riwayat catatan perkembangan pengerjaan dari pihak petugas.
* 👍 **`upvotes`** : Tabel perantara (*pivot table*) untuk menangani sistem dukungan publik warga.

## 👥 Struktur Proyek & Pembagian Kerja Tim

Proyek tugas akhir ini dikerjakan oleh **tim berisi 3 orang** dengan pembagian peran, tanggung jawab *Controller*, dan file logika yang terisolasi untuk menghindari konflik repositori (*git merge conflict*):

### 👨‍💻 Backend Developer 1 (Database Architect & Foundation)
Fokus pada arsitektur dasar basis data, sistem keamanan otentikasi, dan pengelolaan data master:
* **Inisiasi Proyek:** Setup awal repositori Git, inisialisasi proyek Laravel, dan konfigurasi koneksi database PostgreSQL lokal.
* **Database Architect:** Membuat seluruh file *Migration* (5 tabel) beserta definisi relasi *Eloquent* lengkap di dalam file Model Laravel sejak awal proyek, termasuk kolom `status` pada tabel `reports`.
* **Otentikasi Laravel Breeze (Core Auth):** Menginstalasi package Laravel Breeze (versi Blade), memodifikasi *migration* tabel `users` untuk kolom `role`, serta mengatur logika *Middleware* untuk pengalihan (*redirect*) otomatis: Admin ke Dashboard Admin, Citizen ke Public Feed.
* **Otorisasi Laravel Policy (`ReportPolicy`):** Mengimplementasikan Laravel Policy untuk memastikan hanya pemilik laporan yang dapat mengakses rute edit/delete miliknya, mencegah akses tidak sah antar pengguna.
* **Manajemen Wilayah (`DistrictController`):** Mengembangkan rute dan logika CRUD penuh untuk mengelola data master kecamatan/wilayah di panel Admin.

### 🧠 Backend Developer 2 (Core Business Logic & API)
Fokus pada alur bisnis pengaduan, manipulasi file media, relasi data dinamis, dan penyiapan data statistik:
* **Manajemen Laporan (`ReportController`):** Membuat logika CRUD laporan pengaduan dari sisi warga, termasuk validasi input data, sistem penyimpanan file foto bukti kerusakan ke folder *storage*, serta konfigurasi `php artisan storage:link` dan pengaturan `FILESYSTEM_DISK` untuk manajemen file.
* **Verifikasi Status Laporan (`ReportController@updateStatus`):** Mengembangkan logika perubahan status laporan (Terima/Tolak/Selesai) untuk aksi verifikasi oleh Admin melalui rute `PATCH /admin/reports/{id}/status`.
* **Logika Upvote (`UpvoteController`):** Mengembangkan logika *toggle upvote* (relasi *Many-to-Many*) untuk menambah atau menghapus baris data dukungan secara unik pada tabel pivot.
* **Tanggapan Progres (`ProgressUpdateController`):** Membuat fungsi bagi Admin untuk menginput catatan pembaruan status pengerjaan fasilitas fisik di lapangan.
* **Query Dashboard (`DashboardController`):** Menyusun query data statistik (total pengaduan global vs personal, tren bulanan, wilayah prioritas) untuk dikirimkan secara dinamis ke halaman frontend.

### 🎨 Frontend Developer (UI/UX & Interactive Specialist)
Fokus pada estetika visual, tata letak responsif, dan menghidupkan komponen interaktif menggunakan framework CSS dan library JS:
* **Base Layouting (Tailwind CSS):** Merancang arsitektur tampilan dasar aplikasi, mulai dari Navbar dinamis (berubah otomatis berdasarkan role user yang login), Sidebar Admin, hingga Footer.
* **Kustomisasi Tampilan Auth (Breeze Views):** Menata ulang dan mempercantik halaman Login (`/login`) dan Register (`/register`) yang di-generat oleh Laravel Breeze agar memiliki komponen visual dan tema warna yang serasi dengan aplikasi CivilWatch.
* **Slicing Views & Forms (Laravel Blade):** Membangun antarmuka seluruh 13 halaman aplikasi, mencakup: *Landing Page*, *Login*, *Register*, *Profil Pengguna*, *Public Feed*, *Detail Laporan* (publik), *Citizen Dashboard*, *Form Buat Laporan*, *Edit Laporan*, *Admin Dashboard*, *Kelola Wilayah*, *Manajemen Laporan* (admin), dan *Detail + Input Progress* (admin).
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
