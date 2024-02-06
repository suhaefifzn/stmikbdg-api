<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    /**
     * Model ini digunakan untuk tabel dosen
     * untuk melakukan proses-proses Create, Update, Delete.
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' tabel dosen.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar tabel dosen->users.
     */
    use HasFactory;

    public $table = 'dosen';
    public $connection = 'second_pgsql'; // database 'stmikbdg_dummy'
    protected $guarded = ['id'];
}
