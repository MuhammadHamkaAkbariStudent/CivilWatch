<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpvoteController extends Controller
{
    /**
     * Menambahkan atau menghapus upvote (toggle).
     * Wajib merespon JSON jika expectsJson() — untuk Alpine.js AJAX.
     */
    public function toggle(Request $request, string $id)
    {
        // Cari laporan yang hanya boleh tampil publik
        $report = Report::whereIn('status', [
            Report::STATUS_PUBLISHED,
            Report::STATUS_IN_PROGRESS,
            Report::STATUS_RESOLVED,
        ])->findOrFail($id);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Fitur Many-to-Many Toggle:
        // Jika user belum upvote, maka akan ditambahkan.
        // Jika sudah upvote, maka akan dihapus.
        $result = $report->upvotes()->toggle($user->id);

        // Cek hasil toggle
        $isAttached = count($result['attached']) > 0;

        // Hitung ulang total upvote setelah toggle
        $newCount = $report->upvotes()->count();

        // Jika request datang dari AJAX, kembalikan JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'count' => $newCount,
                'upvoted' => $isAttached,
                'action' => $isAttached ? 'attached' : 'detached',
            ]);
        }

        // Fallback jika bukan AJAX
        return back()->with('success', 'Status dukungan laporan berhasil diperbarui.');
    }
}