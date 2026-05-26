# 🚧 CivilWatch

**CivilWatch** adalah platform pengaduan masyarakat berbasis web modern untuk melaporkan masalah infrastruktur publik, seperti jalan berlubang, lampu jalan mati, pohon tumbang, dan isu lingkungan lainnya. Proyek ini dirancang untuk menjembatani komunikasi antara warga dan instansi pemerintah secara **transparan, partisipatif, dan terukur**.

## 📌 Daftar Isi

- [Tech Stack](#-tech-stack)
- [Fitur Utama](#-fitur-utama)
- [Skema Database & Relasi](#-skema-database--relasi)
- [Struktur Proyek & Pembagian Kerja Tim](#-struktur-proyek--pembagian-kerja-tim)
- [Panduan Instalasi](#-panduan-instalasi)
- [Tujuan Akademik](#-tujuan-akademik)

## 🏗️ Architecture & Tech Stack

- **Backend:** PHP 8.5+ / Laravel 13 (Latest Stable)
- **Frontend:** Blade Templating, Tailwind CSS (Vite), Alpine.js
- **Database:** PostgreSQL (Primary)
- **Authentication:** Laravel Breeze (Session-based)
- **Authorization:** Custom `role` middleware (`citizen`, `admin`) and Laravel Policies (`ReportPolicy`)
- **Testing:** Pest PHP

### 🌍 1. Akses Publik & Otentikasi (6 Halaman)

* **Halaman 1: Landing Page (`/`)**
  * **Isi Halaman:** Informasi pengenalan platform, komponen visual urutan langkah alur pengaduan, 4 buah widget matriks ringkasan statistik global (total masuk, aktif dikerjakan, total sukses `status: resolved`, dan total klik dukungan), serta tombol aksi (*Call to Action*).
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
  * **Isi Halaman:** Kumpulan kartu laporan (*Report Cards*) yang **sudah divalidasi admin** (status: `published`, `in_progress`, atau `resolved`). Menampilkan foto fisik, badge penanda status, deskripsi ringkas, dan nama wilayah. Dilengkapi tombol *upvote* interaktif berbasis AJAX, bilah pencarian judul (operator `ilike` PostgreSQL), dan dropdown saringan kecamatan.
  * **Fungsi:** Wadah transparansi publik agar masyarakat dapat melihat, memantau, dan memberikan dukungan suara (*upvote*) terhadap keluhan infrastruktur yang sah secara *real-time*.

* **Halaman 6: Detail Laporan (`/reports/{id}`)**
  * **Isi Halaman:** Tampilan foto penuh bukti kerusakan fisik, deskripsi kronologis aduan, total akumulasi dukungan *upvote*, serta komponen linimasa kronologis vertikal (*Progress Tracking Nodes*) membaca tanggal dari terlama ke terbaru.
  * **Fungsi:** Menyediakan informasi menyeluruh mengenai satu aduan spesifik dan riwayat penanganannya dari awal hingga selesai.

---

### 👤 2. Panel Masyarakat (Citizen Workspace - 3 Halaman)

Rute di bawah proteksi keamanan *middleware* khusus warga (`role: citizen`):

* **Halaman 7: Citizen Dashboard (`/citizen/dashboard`)**
  * **Isi Halaman:** Widget *Personal Mini Matrix* (Total Laporanku, Laporanku yang Terverifikasi, dan Total Upvote yang Pernah Diberikan). Dilengkapi tabel rekap riwayat laporan pribadi berisi kolom judul, tanggal, wilayah, label badge status, dan tombol aksi cepat (*Quick Actions*) Edit dan Hapus.
  * **Fungsi:** Ruang kendali pribadi bagi warga untuk melacak kontribusi dan memantau status aduannya sendiri secara berkala.

* **Halaman 8: Form Buat Laporan (`/citizen/reports/create`)**
  * **Isi Halaman:** Input judul keluhan, area teks deskripsi kronologis (minimal 20 karakter), menu pilihan (*select option*) kecamatan resmi, tombol pilih file gambar (`type="file"`), dan kotak pratinjau foto (*Image Preview*).
  * **Fungsi:** Memfasilitasi warga mengirimkan pengaduan. Laporan yang baru dikirim akan **otomatis berstatus `pending`** dan masuk ke antrean ruang tunggu admin (belum tayang ke publik).

* **Halaman 9: Edit Laporan (`/citizen/reports/{id}/edit`)**
  * **Isi Halaman:** Formulir pengisian laporan yang telah terisi data lama pengguna untuk direvisi kembali beserta komponen pratinjau foto.
  * **Fungsi:** Mengizinkan warga memperbaiki kesalahan input data atau memperbarui foto bukti. **Catatan Keamanan (Laravel Policy):** Tombol edit/hapus pada dashboard dan rute halaman ini HANYA aktif dan dapat dieksekusi jika status laporan masih murni bernilai `pending`. Jika status sudah berubah, hak akses otomatis hangus.

---

### 📊 3. Panel Admin (Admin Control Center - 4 Halaman)

Rute di bawah proteksi keamanan ketat *middleware* khusus petugas (`role: admin`):

* **Halaman 10: Admin Dashboard (`/admin/dashboard`)**
  * **Isi Halaman:** Kotak matriks eksekutif global (total aduan kota, antrean *Pending Inbox*), area placeholder grafik perkembangan data bulanan, dan tabel urutan prioritas kecamatan berdasarkan total laporan terbanyak.
  * **Fungsi:** Memberikan gambaran data makro bagi pengambil keputusan untuk melihat tren kerusakan fasilitas kota.

* **Halaman 11: Kelola Wilayah (`/admin/districts`)**
  * **Isi Halaman:** Formulir teks inline/modal untuk menambahkan nama kecamatan baru, tabel daftar alfabetis kecamatan resmi, tombol **"Edit Wilayah"** untuk memperbaiki nama, dan tombol "Hapus".
  * **Fungsi:** Mengelola data referensi lokasi resmi (CRUD Data Master). Dilengkapi proteksi *restrict constraint* untuk menggagalkan aksi hapus jika wilayah terikat dengan laporan warga.

* **Halaman 12: Manajemen Laporan (`/admin/reports`)**
  * **Isi Halaman:** Tabel log pengaduan berskala besar yang menampung seluruh aduan dari semua warga. Dilengkapi bilah navigasi tab pemisah status (memisahkan menu *Pending Inbox* dari laporan aktif), detail data pelapor, serta tombol aksi cepat ikon mata "Periksa Laporan".
  * **Fungsi:** Pos Patroli utama admin untuk melakukan validasi (*Quality Control*). Di sini admin bertugas menyeleksi laporan *pending* untuk diteruskan menjadi *published* (tayang ke publik) atau ditolak menjadi *rejected* (diblokir karena *spam*).

* **Halaman 13: Detail + Input Progress Admin (`/admin/reports/{id}`)**
  * **Isi Halaman:** Tampilan berkas laporan warga secara mendalam, kluster 4 tombol pilihan penentu status (`published`, `rejected`, `in_progress`, `resolved`), area `textarea` catatan lapangan petugas untuk menyuntikkan data ke linimasa, serta komponen **Zona Bahaya (*Danger Zone*)** berupa kotak tegas bergaris merah berisi tombol **"Hapus Laporan Ini"** yang terintegrasi dengan modal konfirmasi Alpine.js.
  * **Fungsi:** Tempat admin memvalidasi aduan secara mendalam, memperbarui lini masa penanganan masalah fisik, serta menghapus permanen laporan bermasalah/palsu beserta seluruh file relasinya dari basis data.

## 👥 Pembagian Tugas Tim (Role & Task Distribution)

### 👨‍💻 1. Backend Developer 1 (Database Architect & Foundation) - Hamka
Fokus pada fondasi arsitektur basis data, keamanan gerbang otentikasi/otorisasi, dan pengelolaan data master referensi.
* **Setup & Arsitektur Data:** Inisiasi Laravel 13, repositori Git, dan migrasi struktur relasi 5 tabel utama (`users`, `districts`, `reports`, `progress_updates`, `upvotes`).
* **Otentikasi & Middleware:** Instalasi Laravel Breeze, modifikasi kolom `role` (`admin`/`citizen`), dan konfigurasi pengalihan rute otomatis pasca-login.
* **Keamanan Otorisasi (Laravel Policy):** Mengamankan `ReportPolicy` untuk membatasi hak akses edit/delete hanya bagi pemilik laporan asli, dan mengunci tombol edit agar otomatis hangus jika status laporan sudah bukan `pending`.
* **Master CRUD Wilayah:** Mengembangkan fungsi CRUD penuh pada `DistrictController` (Halaman 11) termasuk rute pembaruan nama (*edit/update*) dan proteksi *restrict constraint* saat hapus data.

### 🧠 2. Backend Developer 2 (Core Business Logic & API) - Ulyani
Fokus pada alur bisnis utama pengaduan, manipulasi file media storage, optimasi query, dan penyediaan data AJAX/JSON.
* **File Kerja Utama:** Bertanggung jawab penuh atas penulisan logika di `ReportController.php`, `UpvoteController.php`, `ProgressUpdateController.php`, dan `DashboardController.php`.
* **Alur Pengaduan Warga (ReportController):** Mengelola lifecycle laporan warga (Halaman 8 & 9) menggunakan Form Request Validation (`StoreReportRequest` & `UpdateReportRequest`), serta logika penghapusan file foto lama saat update berkas di folder storage.
* **Eksplorasi Publik (ReportController):** Mengoptimalkan query `Public Feed` (Halaman 5) menggunakan Eager Loading bebas dari N+1 query, menyaring status valid via `whereIn`, hitungan `withCount('upvotes')`, serta pencarian *case-insensitive* (`ilike` PostgreSQL).
* **Kontrol Verifikasi & Logika Hapus Admin (ReportController):** Membuat endpoint `PATCH` status laporan dan fungsi `destroy` (Halaman 13) untuk menghapus permanen laporan palsu beserta seluruh file medianya di dalam *Danger Zone*.
* **API Upvote & Analytics (UpvoteController & DashboardController):** Menyediakan response kondisional JSON pada `UpvoteController` menggunakan fungsi `toggle()` pivot untuk mendukung interaktivitas Alpine.js tanpa reload, serta menyusun query grouping data statistik pada `DashboardController`.

### 🎨 3. Frontend Developer (UI/UX & Interactive Specialist) - Erischa
Fokus pada estetika visual, tata letak responsif, kustomisasi komponen Blade, dan menghidupkan interaktivitas AJAX client-side.
* **Visual Autonomy (Bebas Kreatif):** Memiliki kebebasan penuh dalam menentukan gaya visual, estetika layout, palet warna, dan tipografi komponen antarmuka aplikasi (termasuk visualisasi 5 warna badge status).
* **Base Layouting & Slicing Views:** Merancang master layout (`layouts.app`), menu navbar dinamis berbasis role, kustomisasi form bawaan Breeze, serta melakukan *slicing* HTML/Blade untuk seluruh 13 halaman utama aplikasi.
* **Sinkronisasi Elemen & Panel Admin:** Memastikan ketersediaan elemen visual tombol edit wilayah (Halaman 11), panel 4 tombol verifikasi status, form catatan progres lapangan, serta pop-up modal konfirmasi pada area *Danger Zone* hapus laporan (Halaman 13).
* **Interaktivitas Alpine.js (AJAX):** Menghidupkan bilah pencarian dinamis, filter dropdown kecamatan, dan mengintegrasikan Fetch API pada tombol **Upvote** agar counter angka berubah instan di layar tanpa memicu *refreshing* halaman.

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
