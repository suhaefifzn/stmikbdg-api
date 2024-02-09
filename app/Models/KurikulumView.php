<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KurikulumView extends Model
{
    /**
     * Model ini digunakan untuk view kurikulum
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view kurikulum.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    public $table = 'kurikulum'; // belum ada viewnya jadi ke tabel dulu
    public $connection = 'second_pgsql';
}
