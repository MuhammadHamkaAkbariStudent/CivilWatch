<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// 1. Rute Khusus Admin
Route::get('/admin/dashboard', function () {
    return '
        <h1 style="color: blue; text-align: center; margin-top: 50px;">Ini Panel Admin CivilWatch 👮‍♂️, Frontend silakan ganti dengan view.</h1>
        
        <div style="text-align: center; margin-top: 20px;">
            <form method="POST" action="/logout">
                ' . csrf_field() . '
                <button type="submit" style="padding: 10px 20px; background-color: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    Log Out Admin 🚪
                </button>
            </form>
        </div>
    ';
})->middleware(['auth', 'role:admin']);

// 2. Rute Khusus Warga (Citizen Workspace)
Route::get('/citizen/dashboard', function () {
    return '
        <h1 style="color: green; text-align: center; margin-top: 50px;">Ini Citizen Dashboard Khusus Warga 👨‍👩‍👧‍👦</h1>
        <p style="text-align: center; font-family: sans-serif;">(Ruang kendali untuk melihat riwayat laporannya sendiri)</p>
        
        <div style="text-align: center; margin-top: 20px;">
            <form method="POST" action="/logout">
                ' . csrf_field() . '
                <button type="submit" style="padding: 10px 20px; background-color: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    Log Out Warga 🚪
                </button>
            </form>
        </div>
    ';
})->middleware(['auth', 'role:citizen']); // 👈 Pelindung khusus warga dipasang di sini!

// 3. Rute Public Feed (Bisa diakses semua orang)
Route::get('/feed', function () {
    $tampilan = '<h1>[DUMMY] Ini Public Feed Warga. Frontend silakan ganti dengan view.</h1>';

    // Gunakan Facade Auth agar VS Code tidak cerewet lagi
    if (Auth::check()) {
        $tampilan .= '
            <p style="color: blue;">(Halo, kamu sedang login!)</p>
            <form method="POST" action="/logout">
                ' . csrf_field() . '
                <button type="submit" style="padding: 10px 20px; background-color: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    Test Log Out 🚪
                </button>
            </form>
        ';
    } else {
        $tampilan .= '
            <p style="color: gray;">(Kamu sedang melihat halaman ini sebagai Pengunjung Publik / Belum Login).</p>
        ';
    }

    return $tampilan;
});