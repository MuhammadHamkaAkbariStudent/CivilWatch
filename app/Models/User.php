<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================
    // HELPER METHODS
    // =========================================================

    /**
     * Cek apakah user ini adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user ini adalah warga/citizen.
     */
    public function isCitizen(): bool
    {
        return $this->role === 'citizen';
    }

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * One-to-Many: User ini telah membuat banyak laporan.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Many-to-Many: Laporan-laporan yang di-upvote oleh user ini.
     */
    public function upvotedReports()
    {
        return $this->belongsToMany(Report::class, 'upvotes')
                    ->withTimestamps();
    }
}
