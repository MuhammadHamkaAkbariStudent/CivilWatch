<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'district_id',
        'title',
        'description',
        'image',
        'status',
    ];

    /**
     * Daftar nilai valid untuk kolom status.
     * Gunakan konstanta ini di Controller untuk menghindari typo.
     */
    const STATUS_PENDING     = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED    = 'resolved';
    const STATUS_REJECTED    = 'rejected';

    // =========================================================
    // HELPER METHOD
    // =========================================================

    /**
     * Cek apakah laporan ini masih bisa diedit oleh warga (hanya saat pending).
     */
    public function isEditable(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Cek apakah laporan ini sudah terverifikasi dan tampil di Public Feed.
     */
    public function isVerified(): bool
    {
        return in_array($this->status, [
            self::STATUS_IN_PROGRESS,
            self::STATUS_RESOLVED,
        ]);
    }

    /**
     * Ambil URL lengkap foto bukti dari storage.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Many-to-One: Laporan ini dibuat oleh satu user/warga.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Many-to-One: Laporan ini berada di satu wilayah/kecamatan.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * One-to-Many: Laporan ini bisa memiliki banyak catatan progres.
     * Diurutkan dari yang terlama agar tampil sebagai linimasa kronologis.
     */
    public function progressUpdates()
    {
        return $this->hasMany(ProgressUpdate::class)->orderBy('created_at', 'asc');
    }

    /**
     * Many-to-Many: User-user yang telah memberikan upvote pada laporan ini.
     * Menggunakan tabel pivot: upvotes
     */
    public function upvotes()
    {
        return $this->belongsToMany(User::class, 'upvotes')
                    ->withTimestamps();
    }

    /**
     * Hitung total upvote laporan ini (lebih efisien dari count() langsung).
     * Gunakan withCount('upvotes') di Controller untuk menghindari query N+1.
     */
    public function getUpvoteCountAttribute(): int
    {
        return $this->upvotes()->count();
    }
}