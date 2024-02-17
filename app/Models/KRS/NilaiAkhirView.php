<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ? Models - View
use App\Models\KRS\MatKulView;

class NilaiAkhirView extends Model
{
    /**
     * Model ini digunakan untuk view nilai akhir
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view nilai akhir.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    public $table = 'vnilaiakhir';
    public $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    /**
     * Relasi view nilai akhir ke view matakuliah, many to one
     */
    public function matakuliah() {
        return $this->belongsTo(MatKulView::class, 'mk_id', 'mk_id');
    }
}
