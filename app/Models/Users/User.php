<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    /**
     * Model ini digunakan untuk tabel users
     * untuk melakukan proses-proses Create, Update, Delete.
     *
     * Koneksi database terhubung ke 'simak_stmikbdg' tabel users.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar tabel users->dosen dan users->mahasiswa
     */
    use HasFactory, Notifiable, HasApiTokens;

    public $table = 'users';
    public $connection = 'pgsql';
    protected $guarded = ['id'];
    protected $hidden = ['password'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
