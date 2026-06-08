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
// 1. AKSES PUBLIK & OTENTIKASI (Tanpa Login)
// =========================================================================

Route::get('/', [DashboardController::class, 'welcome'])->name('home');

Route::get('/feed', [ReportController::class, 'publicFeed'])->name('feed');
Route::get('/reports/{id}', [ReportController::class, 'publicShow'])->name('reports.show');

// =========================================================================
// 2. AUTHENTICATED ACCESS (Harus Login - Umum)
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Upvote
    Route::post('/reports/{id}/upvote', [UpvoteController::class, 'toggle'])->name('reports.upvote');
});

require __DIR__ . '/auth.php';

// =========================================================================
// 3. CITIZEN ONLY WORKSPACE (Hak Akses Warga)
// =========================================================================
Route::middleware(['auth', 'role:citizen'])->prefix('citizen')->name('citizen.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'citizen'])->name('dashboard');
    Route::resource('reports', ReportController::class);
});

// =========================================================================
// 4. ADMIN ONLY CONTROL CENTER (Hak Akses Petugas)
// =========================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('districts', DistrictController::class)->except(['show']);

    Route::get('/reports', [ReportController::class, 'adminIndex'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'adminShow'])->name('reports.show');

    // Diseragamkan menjadi {id}
    Route::post('/reports/{id}/progress', [ProgressUpdateController::class, 'store'])->name('reports.progress.store');
    Route::patch('/reports/{id}/status', [ReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::delete('/reports/{id}', [ReportController::class, 'adminDestroy'])->name('reports.destroy');
});