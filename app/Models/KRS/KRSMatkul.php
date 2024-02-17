<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ? Models - tabel
use App\Models\KRS\KRS;

// ? Models - view
use App\Models\KRS\MatKulView;

class KRSMatkul extends Model
{
    /**
     * Model ini digunakan untuk tabel krs mk
     * untuk melakukan proses-proses Create, Update, Delete.
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' tabel krs mk.
     */
    use HasFactory;

    public $table = 'krs_mk';
    public $connection;
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    /**
     * Relasi tabel krs_mk ke krs, many to one
     */
    public function krs() {
        return $this->belongsTo(KRS::class, 'krs_id', 'krs_id');
    }

    public function matakuliah() {
        return $this->belongsTo(MatKulView::class, 'mk_id', 'mk_id');
    }
}
