<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaView extends Model
{
    /**
     * Model ini digunakan untuk view dosen
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'simak_stmikbdg' view dosen.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar view mahasiswa->users.
     */
    use HasFactory;

    public $table = 'vmahasiswa';
    public $connection = 'second_pgsql'; // database 'stmikbdg_dummy'
}
