<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'name',
    ];

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * One-to-Many: Satu wilayah bisa memiliki banyak laporan.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}