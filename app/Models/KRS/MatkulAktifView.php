<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatkulAktifView extends Model
{
    /**
     * Model ini digunakan untuk view mk_aktif
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view mk_aktif.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    protected $table = 'vmk_aktif';
    protected $connection;

    public function __construct()
    {
        $this->connection = config('myconfig.database.second_connection');
    }
}
