<?php

namespace App\Models\MatKul;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatKulView extends Model
{
    /**
     * Model ini digunakan untuk view dosen
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view mata_kuliah.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     */
    use HasFactory;

    public $table = 'vmata_kuliah';
    public $connection = 'second_pgsql';
}
