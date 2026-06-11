<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\District;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Dashboard Warga
    public function citizen()
    {
        $userId = Auth::id();

        // Menghitung total laporan user
        $myTotal = Report::where('user_id', $userId)->count();

        // Menghitung laporan yang sudah diverifikasi
        $myVerified = Report::where('user_id', $userId)
            ->whereIn('status', [
                Report::STATUS_PUBLISHED,
                Report::STATUS_IN_PROGRESS,
                Report::STATUS_RESOLVED,
            ])->count();

        // Menghitung total upvote yang diberikan
        $myUpvotesGiven = DB::table('upvotes')
            ->where('user_id', $userId)
            ->count();

        // Mengambil daftar laporan pribadi
        $reports = Report::with('district')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('citizen.dashboard', compact('myTotal', 'myVerified', 'myUpvotesGiven', 'reports'));
    }

    // Dashboard Admin
    public function admin()
    {
        // Menghitung statistik laporan global
        $totalReports = Report::count();
        $pendingReports = Report::where('status', Report::STATUS_PENDING)->count();
        $inProgressReports = Report::where('status', Report::STATUS_IN_PROGRESS)->count();
        $resolvedReports = Report::where('status', Report::STATUS_RESOLVED)->count();

        // Mengambil tren laporan bulanan (6 bulan terakhir) 
        $monthlyTrend = Report::select(
            DB::raw('count(id) as total'),
            DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month")
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        // Mengambil kecamatan prioritas (Kecamatan dengan laporan + upvote terbanyak)
        $priorityDistricts = District::withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalReports', 'pendingReports', 'inProgressReports', 'resolvedReports', 'monthlyTrend', 'priorityDistricts'));
    }

    // Landing Page
    public function welcome()
    {
        // Redirect admin ke dashboard admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Mengambil statistik untuk landing page
        $totalReports = Report::count();
        $inProgressReports = Report::where('status', Report::STATUS_IN_PROGRESS)->count();
        $resolvedReports = Report::where('status', Report::STATUS_RESOLVED)->count();
        $totalUpvotes = DB::table('upvotes')->count();

        return view('welcome', compact('totalReports', 'inProgressReports', 'resolvedReports', 'totalUpvotes'));
    }
}