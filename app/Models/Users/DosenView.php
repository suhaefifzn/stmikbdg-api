<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenView extends Model
{
    /**
     * Model ini digunakan untuk view dosen
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view dosen.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar view dosen->mahasiswa.
     */
    use HasFactory;

    public $table = 'vdosen';
    public $connection = 'second_pgsql'; // database 'stmikbdg_dummy'
}
