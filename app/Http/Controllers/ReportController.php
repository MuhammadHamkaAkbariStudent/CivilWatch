<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\District;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // PANEL WARGA (CITIZEN WORKSPACE)

    /**
     * Menampilkan daftar laporan milik warga yang sedang login (Halaman 7).
     * Dialihkan ke Dashboard Warga demi efisiensi dan keselarasan UI.
     */
    public function index()
    {
        return redirect()->route('citizen.dashboard');
    }

    // Menampilkan form pembuatan laporan baru (Halaman 8).
    public function create()
    {
        $districts = District::all();
        return view('citizen.reports.create', compact('districts'));
    }

    // Menyimpan data laporan baru ke database (Mengamankan aset foto nullable).
    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();

        // Antisipasi jika foto tidak diunggah / bersifat opsional
        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('reports', 'public');
        }

        Report::create([
            'user_id' => Auth::id(),
            'district_id' => $validated['district_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'status' => Report::STATUS_PENDING,
        ]);

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Laporan berhasil dibuat dan sedang menunggu verifikasi.');
    }

    /**
     * Menampilkan detail spesifik laporan internal milik warga bersangkutan.
     * Dialihkan ke detail publik agar terintegrasi dengan data riwayat linimasa yang kaya.
     */
    public function show(string $id)
    {
        $report = Report::findOrFail($id);

        if ($report->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        return redirect()->route('reports.show', $report->id);
    }

    // Menampilkan form edit laporan (Menggunakan Otorisasi Policy).
    public function edit(string $id)
    {
        $report = Report::findOrFail($id);

        // Memanggil Satpam Policy untuk cek kepemilikan & status pending sekaligus 👈 Perbaikan Revisi 1
        Gate::authorize('update', $report);

        $districts = District::all();
        return view('citizen.reports.edit', compact('report', 'districts'));
    }

    // Memperbarui data laporan di database (Menggunakan Otorisasi Policy).
    public function update(UpdateReportRequest $request, string $id)
    {
        $report = Report::findOrFail($id);

        // Proteksi mutlak lewat Policy
        Gate::authorize('update', $report);

        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            if ($report->image) {
                Storage::disk('public')->delete($report->image);
            }
            $validated['image'] = $request->file('photo')->store('reports', 'public');
        } else {
            $validated['image'] = $report->image;
        }

        $report->update([
            'district_id' => $validated['district_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $validated['image'],
        ]);

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    // Menghapus laporan beserta aset fotonya (Menggunakan Otorisasi Policy).
    public function destroy(string $id)
    {
        $report = Report::findOrFail($id);

        // Proteksi mutlak lewat Policy sebelum eksekusi hapus file
        Gate::authorize('delete', $report);

        if ($report->image) {
            Storage::disk('public')->delete($report->image);
        }

        $report->delete();

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    // AKSES PUBLIK (PENGUNJUNG UMUM)
    // Menampilkan Public Feed dengan sistem Filter Kecamatan dan Search (Halaman 5).
    public function publicFeed(Request $request)
    {
        // Admin tidak memiliki akses ke halaman publik, langsung redirect ke dashboard admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $statusFilter = $request->input('status');
        if (in_array($statusFilter, [Report::STATUS_PUBLISHED, Report::STATUS_IN_PROGRESS, Report::STATUS_RESOLVED])) {
            $query = Report::with(['user', 'district'])
                ->where('status', $statusFilter)
                ->withCount('upvotes');
        } else {
            $query = Report::with(['user', 'district'])
                ->whereIn('status', [
                    Report::STATUS_PUBLISHED,
                    Report::STATUS_IN_PROGRESS,
                    Report::STATUS_RESOLVED
                ])->withCount('upvotes');
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        if ($request->has('district_id') && $request->district_id != '') {
            $query->where('district_id', $request->district_id);
        }

        // Urutan berdasarkan Upvote Terbanyak vs Terbaru vs Tersedikit
        if ($request->has('sort_by') && $request->sort_by === 'upvotes') {
            $query->orderBy('upvotes_count', 'desc');
        } elseif ($request->has('sort_by') && $request->sort_by === 'least_upvotes') {
            $query->orderBy('upvotes_count', 'asc');
        } else {
            $query->latest();
        }

        $reports = $query->paginate(12);
        $districts = District::all();

        return view('feed', compact('reports', 'districts'));
    }

    // Menampilkan rincian aduan terbuka untuk publik (Halaman 6).
    public function publicShow(string $id)
    {
        // Admin langsung ke halaman detail admin, bukan halaman publik
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.reports.show', $id);
        }

        $report = Report::with(['user', 'district', 'progressUpdates'])
            ->withCount('upvotes')
            ->whereIn('status', [
                Report::STATUS_PUBLISHED,
                Report::STATUS_IN_PROGRESS,
                Report::STATUS_RESOLVED,
            ])
            ->findOrFail($id);

        return view('reports.show', compact('report'));
    }

    // PANEL ADMIN (ADMIN CONTROL PANEL)
    // KHUSUS ADMIN: Menampilkan daftar semua laporan + Fitur Filter Status (Halaman 12).
    public function adminIndex(Request $request)
    {
        $query = Report::with(['user', 'district'])->withCount('upvotes');

        // Filter 1: Berdasarkan Pencarian Teks Kata Kunci
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        // Filter 2: Berdasarkan Dropdown Kategori Status (Pending/Published/dll)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter 3: Berdasarkan Dropdown Wilayah/Kecamatan
        if ($request->has('district_id') && $request->district_id != '') {
            $query->where('district_id', $request->district_id);
        }

        // Filter 4: Urutan berdasarkan Upvote Terbanyak (Prioritas) vs Terbaru vs Tersedikit
        if ($request->has('sort_by') && $request->sort_by === 'upvotes') {
            $query->orderBy('upvotes_count', 'desc');
        } elseif ($request->has('sort_by') && $request->sort_by === 'least_upvotes') {
            $query->orderBy('upvotes_count', 'asc');
        } else {
            $query->latest();
        }

        $reports = $query->paginate(15);
        $districts = District::orderBy('name', 'asc')->get();

        return view('admin.reports.index', compact('reports', 'districts'));
    }

    // KHUSUS ADMIN: Layar pemeriksaan mendalam + Penghitung Total Dukungan (Halaman 13).
    public function adminShow(string $id)
    {
        // Menambahkan hitungan upvote agar admin tahu tingkat kedaruratan laporan
        $report = Report::with(['user', 'district', 'progressUpdates'])
            ->withCount('upvotes')
            ->findOrFail($id);

        return view('admin.reports.show', compact('report'));
    }

    // Aksi Eksekusi Validasi Status oleh Admin.
    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,published,in_progress,resolved,rejected',
        ]);

        $report = Report::findOrFail($id);

        $report->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Status laporan berhasil diverifikasi menjadi: ' . $validated['status']);
    }

    /**
     * KHUSUS ADMIN: Menghapus laporan milik warga beserta aset foto dan data terkait.
     * Admin berwenang menghapus laporan apa pun, tanpa batasan status.
     */
    public function adminDestroy(string $id)
    {
        $report = Report::findOrFail($id);

        // Hapus foto bukti dari storage jika ada
        if ($report->image) {
            Storage::disk('public')->delete($report->image);
        }

        // Hapus relasi terkait (upvotes & progress_updates) lalu hapus laporan
        $report->upvotes()->detach();
        $report->progressUpdates()->delete();
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan "' . $report->title . '" berhasil dihapus secara permanen.');
    }
}
