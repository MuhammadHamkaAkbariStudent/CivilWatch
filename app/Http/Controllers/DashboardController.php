<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\District;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard Warga: Menampilkan statistik personal
     */
    public function citizen()
    {
        $userId = Auth::id();

        // Statistik Personal
        $myTotal = Report::where('user_id', $userId)->count();

        $myPending = Report::where('user_id', $userId)
            ->where('status', Report::STATUS_PENDING)
            ->count();

        $myResolved = Report::where('user_id', $userId)
            ->where('status', Report::STATUS_RESOLVED)
            ->count();

        // Ambil 5 laporan terbaru milik user
        $recentReports = Report::where('user_id', $userId)
            ->with('district')
            ->latest()
            ->take(5)
            ->get();

        $user = \App\Models\User::findOrFail(Auth::id());

        $upvotedReports = $user->upvotedReports()
            ->with('district')
            ->orderByPivot('created_at', 'desc')
            ->take(5)
            ->get();

        return view('citizen.dashboard', compact(
            'myTotal',
            'myPending',
            'myResolved',
            'recentReports',
            'upvotedReports'
        ));
    }

    /**
     * Dashboard Admin: Menampilkan analitik global, tren, dan prioritas
     */
    public function admin()
    {
        // 1. Statistik Global
        $totalReports = Report::count();

        $pendingReports = Report::where('status', Report::STATUS_PENDING)->count();

        $resolvedReports = Report::where('status', Report::STATUS_RESOLVED)->count();

        // 2. Tren Bulanan (Banyaknya laporan per bulan, 6 bulan terakhir)
        // Menggunakan DB::raw untuk mengekstrak bulan-tahun dari timestamp
        $monthlyTrend = Report::select(
            DB::raw('count(id) as total'),
            DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month")
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->sortBy('month') // biar urut dari bulan lama ke baru
            ->values();

        // 3. Wilayah Prioritas (Kecamatan dengan laporan terbanyak menggunakan withCount)
        $priorityDistricts = District::withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalReports',
            'pendingReports',
            'resolvedReports',
            'monthlyTrend',
            'priorityDistricts'
        ));
    }
}
