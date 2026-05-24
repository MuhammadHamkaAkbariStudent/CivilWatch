<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressUpdate extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'report_id',
        'note',
    ];

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Many-to-One: Catatan progres ini milik satu laporan.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}