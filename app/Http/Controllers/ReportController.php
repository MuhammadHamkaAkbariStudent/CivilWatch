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
    // Mengarahkan ke dashboard warga 
    public function index()
    {
        return redirect()->route('citizen.dashboard');
    }

    // Menampilkan form pembuatan laporan baru
    public function create()
    {
        $districts = District::all();
        return view('citizen.reports.create', compact('districts'));
    }

    // Menyimpan data laporan baru ke database
    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();

        // Antisipasi jika foto tidak diunggah
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

    // Melihat detail laporan miliknya sendiri
    public function show(string $id)
    {
        $report = Report::findOrFail($id);

        if ($report->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        return redirect()->route('reports.show', $report->id);
    }

    // Menampilkan form edit laporan
    public function edit(string $id)
    {
        $report = Report::findOrFail($id);
        Gate::authorize('update', $report);
        $districts = District::all();
        return view('citizen.reports.edit', compact('report', 'districts'));
    }

    // Memperbarui data laporan di database
    public function update(UpdateReportRequest $request, string $id)
    {
        $report = Report::findOrFail($id);
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

    // Menghapus laporan beserta aset fotonya
    public function destroy(string $id)
    {
        $report = Report::findOrFail($id);
        Gate::authorize('delete', $report);

        if ($report->image) {
            Storage::disk('public')->delete($report->image);
        }

        $report->delete();

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    // Menampilkan feed laporan publik
    public function publicFeed(Request $request)
    {
        // Admin langsung diarahkan ke dashboard admin.
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

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

    // Menampilkan rincian aduan terbuka untuk publik
    public function publicShow(string $id)
    {
        // Admin langsung ke halaman detail admink
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

    // Menampilkan daftar semua laporan di panel admin
    public function adminIndex(Request $request)
    {
        $query = Report::with(['user', 'district'])->withCount('upvotes');

        // Mencari laporan berdasarkan judul
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        // Mencari laporan berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Mencari laporan berdasarkan wilayah/kecamatan
        if ($request->has('district_id') && $request->district_id != '') {
            $query->where('district_id', $request->district_id);
        }

        // Urutan berdasarkan Upvote Terbanyak atau Terbaru
        if ($request->has('sort_by') && $request->sort_by === 'upvotes') {
            $query->orderBy('upvotes_count', 'desc');
        } else {
            $query->latest();
        }

        $reports = $query->paginate(15);
        $districts = District::orderBy('name', 'asc')->get();

        return view('admin.reports.index', compact('reports', 'districts'));
    }

    // Menampilkan detail laporan untuk admin
    public function adminShow(string $id)
    {
        // Menambahkan hitungan upvote agar admin tahu tingkat kedaruratan laporan
        $report = Report::with(['user', 'district', 'progressUpdates'])
            ->withCount('upvotes')
            ->findOrFail($id);

        return view('admin.reports.show', compact('report'));
    }

    // Mengubah status laporan untuk admin
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

    // Menghapus laporan warga
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
