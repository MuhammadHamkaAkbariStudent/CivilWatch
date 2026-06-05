<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard Warga: Menampilkan statistik personal sesuai spesifikasi GEMINI.md
     * 
     * 3 Metrik: "Total Laporanku", "Laporan Diverifikasi", "Total Upvote yang Saya Berikan"
     */
    public function citizen()
    {
        $userId = Auth::id();

        // 1. Total Laporanku
        $myTotal = Report::where('user_id', $userId)->count();

        // 2. Laporan Diverifikasi (status: published, in_progress, atau resolved)
        $myVerified = Report::where('user_id', $userId)
            ->whereIn('status', [
                Report::STATUS_PUBLISHED,
                Report::STATUS_IN_PROGRESS,
                Report::STATUS_RESOLVED,
            ])->count();

        // 3. Total Upvote yang Saya Berikan (ke laporan orang lain)
        $myUpvotesGiven = DB::table('upvotes')
            ->where('user_id', $userId)
            ->count();

        // 4. Daftar Laporan Pribadi (termasuk status pending/rejected)
        $reports = Report::with('district')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('citizen.dashboard', compact('myTotal', 'myVerified', 'myUpvotesGiven', 'reports'));
    }

    /**
     * Dashboard Admin: Menampilkan analitik global, tren, dan prioritas
     */
    public function admin()
    {
        // 1. Matriks Eksekutif Global
        $totalReports = Report::count();
        $pendingReports = Report::where('status', Report::STATUS_PENDING)->count();
        $inProgressReports = Report::where('status', Report::STATUS_IN_PROGRESS)->count();
        $resolvedReports = Report::where('status', Report::STATUS_RESOLVED)->count();

        // 2. Tren Bulanan (6 bulan terakhir) — PostgreSQL syntax dengan TO_CHAR
        $monthlyTrend = Report::select(
            DB::raw('count(id) as total'),
            DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month")
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        // 3. Matriks Prioritas Wilayah (Kecamatan dengan laporan + upvote terbanyak)
        $priorityDistricts = District::withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalReports',
            'pendingReports',
            'inProgressReports',
            'resolvedReports',
            'monthlyTrend',
            'priorityDistricts'
        ));
    }

    /**
     * Landing Page / Welcome Page: Menampilkan statistik global sesuai spesifikasi GEMINI.md
     */
    public function welcome()
    {
        // Admin langsung ke dashboard admin, tidak perlu melihat landing page
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $totalReports = Report::count();
        $inProgressReports = Report::where('status', Report::STATUS_IN_PROGRESS)->count();
        $resolvedReports = Report::where('status', Report::STATUS_RESOLVED)->count();
        $totalUpvotes = DB::table('upvotes')->count();

        return view('welcome', compact('totalReports', 'inProgressReports', 'resolvedReports', 'totalUpvotes'));
    }
}