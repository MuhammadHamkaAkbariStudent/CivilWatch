<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpvoteController extends Controller
{
    /**
     * Menambahkan atau menghapus upvote (toggle)
     */
    public function toggle(string $id)
    {
        // Cari laporan berdasarkan ID
        $report = Report::findOrFail($id);
        
        // Ambil user yang sedang login
        $user = Auth::user();

        // Fitur Many-to-Many Toggle: 
        // Jika user belum upvote, maka akan ditambahkan. 
        // Jika sudah upvote, maka akan dihapus (un-vote).
        $report->upvotes()->toggle($user->id);

        // Kembalikan user ke halaman sebelumnya tempat mereka menekan tombol
        return back()->with('success', 'Status dukungan laporan berhasil diperbarui.');
    }
}