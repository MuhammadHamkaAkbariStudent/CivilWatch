<?php

namespace App\Http\Controllers;

use App\Models\Report;
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
            'note' => 'required|string|min:5',
        ]);

        // 2. Cari laporan berdasarkan ID yang ada di URL
        $report = Report::findOrFail($report_id);

        // 3. Simpan catatan progres ke database melalui relasi Report
        $report->progressUpdates()->create([
            'note' => $validated['note'],
        ]);

        // 4. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Catatan progres berhasil ditambahkan.');
    }
}