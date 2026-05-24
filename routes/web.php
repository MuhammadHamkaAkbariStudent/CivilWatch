<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\District;
use Illuminate\Http\Request;

// =========================================================================
// 🌍 1. AKSES PUBLIK & OTENTIKASI (Tanpa Login)
// =========================================================================

Route::get('/', function () {
    return view('welcome');
})->name('home'); // 👈 Penamaan rute ditambahkan

// Halaman 5: Public Feed
Route::get('/feed', function (Request $request) {

    $districts = District::orderBy('name', 'asc')->get();

    $query = Report::with(['user', 'district'])->latest();

    if ($request->filled('search')) {
        $searchTerm = strtolower($request->search); 
        $query->whereRaw('LOWER(title) LIKE ?', ['%' . $searchTerm . '%']);
    }

    if ($request->filled('district_id')) {
        $query->where('district_id', $request->district_id);
    }

    $reports = $query->paginate(9);

    return view('feed', compact('reports', 'districts'));
})->name('feed');// 👈 Penamaan rute ditambahkan

// Halaman 6: Detail Laporan Publik
Route::get('/reports/{report}', function (Report $report) {
    $report->load(['user', 'district', 'progressUpdates']);
    return view('reports.show', compact('report'));
})->name('reports.show'); // 👈 Penamaan rute ditambahkan


// =========================================================================
// 🔐 2. AUTHENTICATED ACCESS (Harus Login - Umum)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Halaman 4: Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/reports/{id}/upvote', function ($id) {
        $user = auth()->user();
        $user->upvotedReports()->toggle($id);
        return redirect()->back();
    })->name('reports.upvote');
});

require __DIR__.'/auth.php';


// =========================================================================
// 👤 3. CITIZEN ONLY WORKSPACE (Hak Akses Warga)
// =========================================================================
// 👇 Menambahkan prefix URL '/citizen' dan prefix nama 'citizen.'
Route::middleware(['auth', 'role:citizen'])->prefix('citizen')->name('citizen.')->group(function () {
    
    // Halaman 7: Citizen Dashboard (Cukup ketik '/dashboard', otomatis dibaca '/citizen/dashboard')
    Route::get('/dashboard', function () {
        return '
            <h1 style="color: green; text-align: center; margin-top: 50px;">Ini Citizen Dashboard Khusus Warga 👨‍👩‍👧‍👦, Frontend silakan ganti dengan view.</h1>
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
    })->name('dashboard'); // 👈 Nama otomatis menjadi 'citizen.dashboard'

    Route::get('/reports/create', function () {
        return '<h1>[DUMMY] Halaman Form Buat Laporan Baru. Frontend silakan ganti dengan view.</h1>';
    })->name('reports.create');

    // Tempat penulisan rute CRUD Laporan Warga (create, store, edit, update, destroy) oleh Backend Dev 2 nanti
});


// =========================================================================
// 📊 4. ADMIN ONLY CONTROL CENTER (Hak Akses Petugas)
// =========================================================================
// 👇 Menambahkan prefix URL '/admin' dan prefix nama 'admin.'
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman 10: Admin Dashboard (Cukup ketik '/dashboard', otomatis dibaca '/admin/dashboard')
    Route::get('/dashboard', function () {
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
    })->name('dashboard'); // 👈 Nama otomatis menjadi 'admin.dashboard'

    // Tempat penulisan rute CRUD Kelola Wilayah (District) dan Manajemen Laporan Admin oleh Backend Dev 1 & 2 nanti
});