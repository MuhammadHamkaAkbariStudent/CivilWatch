<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',         // Tambahan CivilWatch: menentukan admin atau citizen
    ];

    /**
     * Kolom yang disembunyikan saat model dikonversi ke array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data kolom.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // =========================================================
    // HELPER METHOD
    // =========================================================

    /**
     * Cek apakah user ini adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user ini adalah citizen.
     */
    public function isCitizen(): bool
    {
        return $this->role === 'citizen';
    }

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * One-to-Many: Satu user bisa membuat banyak laporan.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Many-to-Many: Laporan-laporan yang pernah di-upvote user ini.
     * Menggunakan tabel pivot: upvotes
     */
    public function upvotedReports()
    {
        return $this->belongsToMany(Report::class, 'upvotes')
                    ->withTimestamps();
    }
}