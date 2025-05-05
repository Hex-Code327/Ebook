<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi secara massal
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',        // Nama pengguna
        'email',       // Alamat email
        'password',    // Kata sandi
        'role',        // Peran (admin/user)
        'is_paid',     // Status pembayaran
        'avatar'       // Foto profil
    ];

    /**
     * Kolom yang harus disembunyikan saat serialisasi
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',           // Kata sandi
        'remember_token',     // Token remember
    ];

    /**
     * Konversi tipe data atribut
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',  // Tanggal verifikasi email
            'password' => 'hashed',             // Hash untuk password
            'is_paid' => 'boolean',             // Tipe boolean untuk status pembayaran
            'role' => 'string'                  // Tipe string untuk role
        ];
    }
}