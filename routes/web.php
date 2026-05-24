<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UpvoteController;
use App\Http\Controllers\ProgressUpdateController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// === CITIZEN: Report CRUD ===
Route::middleware(['auth', 'role:citizen'])->prefix('citizen')->group(function () {
    Route::get('/reports/create', [ReportController::class, 'create'])->name('citizen.reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('citizen.reports.store');
    Route::get('/reports/{id}/edit', [ReportController::class, 'edit'])->name('citizen.reports.edit');
    Route::put('/reports/{id}', [ReportController::class, 'update'])->name('citizen.reports.update');
    Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('citizen.reports.destroy');
});

// === ADMIN: Status & Progress ===
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::patch('/reports/{id}/status', [ReportController::class, 'updateStatus'])->name('admin.reports.updateStatus');
    Route::post('/reports/{id}/progress', [ProgressUpdateController::class, 'store'])->name('admin.progress.store');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

// === PUBLIC: Upvote (auth required) ===
Route::middleware('auth')->post('/reports/{id}/upvote', [UpvoteController::class, 'toggle'])->name('reports.upvote');