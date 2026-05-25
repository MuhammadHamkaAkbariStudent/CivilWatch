<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ProgressUpdate;
use Illuminate\Http\Request;

class ProgressUpdateController extends Controller
{
    /**
     * Menyimpan catatan progres baru untuk laporan tertentu (Khusus Admin).
     */
    public function store(Request $request, string $report_id)
    {
        // 1. Validasi input dari Admin
        $validated = $request->validate([
            'note'   => 'required|string|min:5',
            'status' => 'required|in:pending,published,in_progress,resolved,rejected',
        ]);

        // 2. Cari laporan berdasarkan ID yang ada di URL
        $report = Report::findOrFail($report_id);

        $lockedStatuses = ['resolved', 'rejected'];
        if (in_array($report->status, $lockedStatuses)) {
            return back()->with('error', 'Laporan yang sudah selesai atau ditolak tidak dapat diubah statusnya.');
        }

        // 3. Simpan catatan progres ke database
        ProgressUpdate::create([
            'report_id' => $report->id,
            'note'      => $validated['note'],
        ]);

        // 4. Update status laporan jika admin mengubah statusnya di form
        if ($report->status !== $validated['status']) {
            $report->update(['status' => $validated['status']]);
        }

        // 5. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Catatan progres berhasil ditambahkan dan status laporan diperbarui.');
    }
}
