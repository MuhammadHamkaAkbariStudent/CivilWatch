<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpvoteController extends Controller
{
    // Menambahkan atau menghapus upvote
    public function toggle(Request $request, string $id)
    {
        // Cari laporan berdasarkan ID
        $report = Report::findOrFail($id);
        
        // Ambil user yang sedang login
        $user = Auth::user();

        // Jika laporan sudah ditangani, tidak bisa di-upvote lagi
        if ($report->status === Report::STATUS_RESOLVED) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Laporan yang sudah selesai ditangani tidak dapat didukung lagi.'
                ], 422);
            }
            return back()->with('error', 'Laporan yang sudah selesai ditangani tidak dapat didukung lagi.');
        }

        // Mengubah status upvote
        $report->upvotes()->toggle($user->id);

        // Hitung ulang total upvote setelah toggle
        $newCount = $report->upvotes()->count();
        $isUpvoted = $report->upvotes()->where('user_id', $user->id)->exists();

        // Jika request datang dari AJAX (Alpine.js), kembalikan JSON
        if ($request->expectsJson()) {
            return response()->json([
                'upvoted' => $isUpvoted,
                'count'   => $newCount,
            ]);
        }

        // Fallback: Kembalikan user ke halaman sebelumnya jika bukan AJAX
        return back()->with('success', 'Status dukungan laporan berhasil diperbarui.');
    }
}