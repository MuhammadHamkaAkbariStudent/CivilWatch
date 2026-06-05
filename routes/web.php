<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UpvoteController;
use App\Http\Controllers\ProgressUpdateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistrictController;

// =========================================================================
// 🌍 1. AKSES PUBLIK & OTENTIKASI (Tanpa Login)
// =========================================================================

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Halaman 5: Public Feed (Diarahkan langsung ke fungsi yang sudah kita buat)
Route::get('/feed', [ReportController::class, 'publicFeed'])->name('feed');

// Halaman 6: Detail Laporan Publik (Akan dibuatkan fungsinya oleh Backend 2)
Route::get('/reports/{id}', [ReportController::class, 'publicShow'])->name('reports.show');


// =========================================================================
// 🔐 2. AUTHENTICATED ACCESS (Harus Login - Umum)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Halaman 4: Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/reports/{id}/upvote', [UpvoteController::class, 'toggle'])->name('reports.upvote');
    // Tempat penulisan rute POST Upvote (Many-to-Many) oleh Backend Dev 2 nanti
    // Route::post('/reports/{id}/upvote', ...)->name('reports.upvote');
});

require __DIR__.'/auth.php';


// =========================================================================
// 👤 3. CITIZEN ONLY WORKSPACE (Hak Akses Warga)
// =========================================================================
// 👇 Menambahkan prefix URL '/citizen' dan prefix nama 'citizen.'
Route::middleware(['auth', 'role:citizen'])->prefix('citizen')->name('citizen.')->group(function () {
    // Tempat penulisan rute CRUD Laporan Warga (create, store, edit, update, destroy) oleh Backend Dev 2 nanti
    // Rute Dashboard kini diarahkan ke controller
    Route::get('/dashboard', [DashboardController::class, 'citizen'])->name('dashboard');

    // Rute CRUD Laporan Warga
    Route::resource('reports', ReportController::class);
});


// =========================================================================
// 📊 4. ADMIN ONLY CONTROL CENTER (Hak Akses Petugas)
// =========================================================================
// 👇 Menambahkan prefix URL '/admin' dan prefix nama 'admin.'
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman 10: Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Halaman 11: Kelola Wilayah (Dioptimasi dengan except 'show' agar tidak overkill) 👈 Implementasi Revisi
    Route::resource('districts', DistrictController::class)->except(['show']);

    // Halaman 12: Manajemen Laporan Admin
    Route::get('/reports', [ReportController::class, 'adminIndex'])->name('reports.index');

    // Halaman 13: Detail Laporan Admin (Tampilan mendalam untuk validasi)
    Route::get('/reports/{id}', [ReportController::class, 'adminShow'])->name('reports.show');

    // Halaman 13 (Bagian Progress): Rute Progress Update
    Route::post('/reports/{report_id}/progress', [ProgressUpdateController::class, 'store'])->name('reports.progress.store');

    // Halaman 13 (Bagian Status): Rute Verifikasi Status oleh Admin
    Route::patch('/reports/{id}/status', [ReportController::class, 'updateStatus'])->name('reports.update-status');

    // Hapus Laporan Admin
    Route::delete('/reports/{id}', [ReportController::class, 'adminDestroy'])->name('reports.destroy');
});