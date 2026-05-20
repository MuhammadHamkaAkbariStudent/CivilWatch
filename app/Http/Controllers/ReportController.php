<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\District;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Menampilkan daftar laporan milik warga yang sedang login.
     */
    public function index()
    {
        // Mengambil laporan khusus milik user yang login, urutkan dari yang terbaru
        $reports = Report::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('citizen.reports.index', compact('reports'));
    }

    /**
     * Menampilkan form pembuatan laporan baru.
     */
    public function create()
    {
        // Mengambil data kecamatan untuk dropdown form
        $districts = District::all();
        
        return view('citizen.reports.create', compact('districts'));
    }

    /**
     * Menyimpan data laporan baru ke database.
     */
    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();

        // Proses upload file foto
        // File akan disimpan di folder 'storage/app/public/reports'
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
     * Menampilkan detail spesifik laporan.
     */
    public function show(string $id)
    {
        $report = Report::findOrFail($id);

        // Otorisasi: Pastikan laporan ini milik user yang sedang login
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        return view('citizen.reports.show', compact('report'));
    }

    /**
     * Menampilkan form edit laporan.
     */
    public function edit(string $id)
    {
        $report = Report::findOrFail($id);

        if ($report->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        // Mengecek status menggunakan helper dari Model
        if (!$report->isEditable()) {
            return redirect()->route('citizen.reports.index')
                             ->with('error', 'Laporan yang sudah diproses tidak dapat diedit.');
        }

        $districts = District::all();

        return view('citizen.reports.edit', compact('report', 'districts'));
    }

    /**
     * Memperbarui data laporan di database.
     */
    public function update(UpdateReportRequest $request, string $id)
    {
        $report = Report::findOrFail($id);

        if ($report->user_id !== Auth::id() || !$report->isEditable()) {
            abort(403, 'Tindakan tidak diizinkan.');
        }

        $validated = $request->validated();

        // Jika user mengunggah foto baru saat update
        if ($request->hasFile('photo')) {
            // Hapus foto lama dari storage
            if ($report->image) {
                Storage::disk('public')->delete($report->image);
            }
            // Simpan foto baru
            $validated['image'] = $request->file('photo')->store('reports', 'public');
        } else {
            // Pertahankan path gambar lama jika tidak ada upload baru
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
     * Menghapus laporan beserta aset fotonya.
     */
    public function destroy(string $id)
    {
        $report = Report::findOrFail($id);

        if ($report->user_id !== Auth::id() || !$report->isEditable()) {
            abort(403, 'Laporan tidak dapat dihapus.');
        }

        // Hapus file fisik gambar dari server sebelum menghapus record database
        if ($report->image) {
            Storage::disk('public')->delete($report->image);
        }

        $report->delete();

        return redirect()->route('citizen.reports.index')
                         ->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Memperbarui status laporan (Khusus Admin)
     */
    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,rejected',
        ]);

        $report = Report::findOrFail($id);
        $report->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Status laporan berhasil diverifikasi menjadi: ' . $validated['status']);
    }
}