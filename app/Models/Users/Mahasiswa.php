<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    /**
     * Model ini digunakan untuk tabel mahasiswa
     * untuk melakukan proses-proses Create, Update, Delete.
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' tabel mahasiswa.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar tabel mahasiswa->users.
     */
    use HasFactory;
    public $table = 'mahasiswa';
    public $connection = 'second_pgsql'; // database 'stmikbdg_dummy'
    protected $guarded = ['id'];
}
