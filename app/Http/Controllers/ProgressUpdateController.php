<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ProgressUpdateController extends Controller
{
    // Menyimpan catatan progres baru untuk laporan tertentu (Khusus Admin).
    public function store(Request $request, string $report_id)
    {
        // Validasi input
        $validated = $request->validate([
            'note' => 'required|string|min:5',
        ]);

        // Mencari Laporan berdasarkan id
        $report = Report::findOrFail($report_id);

        // Menyimpan catatan progres lewat relasi
        $report->progressUpdates()->create([
            'note' => $validated['note'],
        ]);

        return back()->with('success', 'Catatan progres berhasil ditambahkan.');
    }
}