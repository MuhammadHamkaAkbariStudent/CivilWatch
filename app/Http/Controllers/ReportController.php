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
    /**
     * =====================================================================
     * 👤 PANEL WARGA (CITIZEN WORKSPACE)
     * =====================================================================
     */

    /**
     * Menampilkan daftar laporan milik warga yang sedang login (Halaman 7).
     */
    public function index()
    {
        $reports = Report::where('user_id', Auth::id())
            ->with('district')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('citizen.reports.index', compact('reports'));
    }

    /**
     * Menampilkan form pembuatan laporan baru (Halaman 8).
     */
    public function create()
    {
        $districts = District::all();
        return view('citizen.reports.create', compact('districts'));
    }

    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();

        // Foto wajib sebagai bukti laporan
        $imagePath = $request->file('photo')->store('reports', 'public');

        Report::create([
            'user_id'     => Auth::id(),
            'district_id' => $validated['district_id'],
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'image'       => $imagePath,
            'status'      => Report::STATUS_PENDING,
        ]);

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Laporan berhasil dibuat dan sedang menunggu verifikasi.');
    }

    /**
     * Menampilkan detail spesifik laporan internal milik warga bersangkutan.
     */
    public function show(string $id)
    {
        $report = Report::with('district')->findOrFail($id);

        // Mengganti pengecekan manual dengan Policy Gate demi konsistensi kode 💻
        Gate::authorize('view', $report);

        return view('citizen.reports.show', compact('report'));
    }

    /**
     * Menampilkan form edit laporan (Menggunakan Otorisasi Policy).
     */
    public function edit(string $id)
    {
        $report = Report::findOrFail($id);

        // Memanggil Satpam Policy untuk cek kepemilikan & status pending sekaligus 👈 Perbaikan Revisi 1
        Gate::authorize('update', $report);

        $districts = District::all();
        return view('citizen.reports.edit', compact('report', 'districts'));
    }

    /**
     * Memperbarui data laporan di database (Menggunakan Otorisasi Policy).
     */
    public function update(UpdateReportRequest $request, string $id)
    {
        $report = Report::findOrFail($id);

        // Proteksi mutlak lewat Policy 👈 Perbaikan Revisi 1
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
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'image'       => $validated['image'],
        ]);

        return redirect()->route('citizen.reports.show', $report->id)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    /**
     * Menghapus laporan beserta aset fotonya (Menggunakan Otorisasi Policy).
     */
    public function destroy(string $id)
    {
        $report = Report::findOrFail($id);

        // Proteksi mutlak lewat Policy sebelum eksekusi hapus file 👈 Perbaikan Revisi 1
        Gate::authorize('delete', $report);

        if ($report->image) {
            Storage::disk('public')->delete($report->image);
        }

        $report->delete();

        return redirect()->route('citizen.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }


    /**
     * =====================================================================
     * 🌍 AKSES PUBLIK (PENGUNJUNG UMUM)
     * =====================================================================
     */

    /**
     * Menampilkan Public Feed dengan sistem Filter Kecamatan dan Search (Halaman 5).
     */
    public function publicFeed(Request $request)
    {
        $query = Report::with(['user', 'district'])
            ->whereIn('status', [
                Report::STATUS_PUBLISHED,
                Report::STATUS_IN_PROGRESS,
                Report::STATUS_RESOLVED
            ])->withCount('upvotes');

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        if ($request->has('district_id') && $request->district_id != '') {
            $query->where('district_id', $request->district_id);
        }

        $reports = $query->latest()->paginate(12);
        $districts = District::all();

        return view('feed', compact('reports', 'districts'));
    }

    /**
     * Menampilkan rincian aduan terbuka untuk publik (Halaman 6).
     */
    public function publicShow(string $id)
    {
        $allowedStatuses = [
            Report::STATUS_PUBLISHED,
            Report::STATUS_IN_PROGRESS,
            Report::STATUS_RESOLVED,
        ];

        $report = Report::with(['user', 'district', 'progressUpdates'])
            ->withCount('upvotes')
            ->whereIn('status', $allowedStatuses)
            ->findOrFail($id);

        return view('reports.show', compact('report'));
    }


    /**
     * =====================================================================
     * 📊 PANEL ADMIN (ADMIN CONTROL PANEL)
     * =====================================================================
     */

    /**
     * KHUSUS ADMIN: Menampilkan daftar semua laporan + Fitur Filter Status (Halaman 12).
     */
    public function adminIndex(Request $request)
    {
        $query = Report::with(['user', 'district']);

        // Filter 1: Berdasarkan Pencarian Teks Kata Kunci
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        // Filter 2: Berdasarkan Dropdown Kategori Status (Pending/Published/dll) 👈 Perbaikan Revisi 2
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }

    /**
     * KHUSUS ADMIN: Layar pemeriksaan mendalam + Penghitung Total Dukungan (Halaman 13).
     */
    public function adminShow(string $id)
    {
        // Menambahkan hitungan upvote agar admin tahu tingkat kedaruratan laporan 👈 Perbaikan Revisi 4
        $report = Report::with(['user', 'district', 'progressUpdates'])
            ->withCount('upvotes')
            ->findOrFail($id);

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Aksi Eksekusi Validasi Status oleh Admin.
     */
    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,published,in_progress,resolved,rejected',
        ]);

        $report = Report::findOrFail($id);

        $lockedStatuses = ['resolved', 'rejected'];

        if (in_array($report->status, $lockedStatuses)) {
            return back()->with('error', 'Laporan yang sudah selesai atau ditolak tidak dapat diubah statusnya.');
        }

        if ($report->status === $validated['status']) {
            return back()->with('error', 'Status tidak berubah.');
        }

        $report->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Status laporan berhasil diverifikasi menjadi: ' . $validated['status']);
    }
}
