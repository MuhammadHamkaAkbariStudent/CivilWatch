<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // 💡 Tambahan untuk Accessor Modern

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
     */
    const STATUS_PENDING     = 'pending';
    const STATUS_PUBLISHED   = 'published';
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
            self::STATUS_PUBLISHED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_RESOLVED,
        ]);
    }

    // =========================================================
    // ACCESSOR (Gaya Modern Laravel 13)
    // =========================================================

    /**
     * Ambil URL lengkap foto bukti dari storage.
     * Akses di Blade: $report->image_url
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? asset('storage/' . $this->image) : null,
        );
    }

    /**
     * Mengambil total upvote secara aman dan efisien.
     * Akses di Blade: $report->upvote_count
     */
    protected function upvoteCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['upvotes_count'] ?? $this->upvotes()->count(),
            // 💡 Penjelasan: Jika di Controller menggunakan withCount('upvotes'), 
            // Ambil data yang sudah ada tanpa query ulang. Jika tidak, baru jalankan count().
        );
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
     */
    public function progressUpdates()
    {
        return $this->hasMany(ProgressUpdate::class)->orderBy('created_at', 'asc');
    }

    /**
     * Many-to-Many: User-user yang telah memberikan upvote pada laporan ini.
     */
    public function upvotes()
    {
        return $this->belongsToMany(User::class, 'upvotes')
                    ->withTimestamps();
    }
}