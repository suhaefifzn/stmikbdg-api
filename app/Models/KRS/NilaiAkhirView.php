<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// ? Models - View
use App\Models\KRS\MatKulView;
use App\Models\KRS\MatkulDiselenggarakanView;

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

    public function scopeGetNilaiAkhirByMhsId(Builder $query, int $mhsId) {
        return $query->where('mhs_id', $mhsId)
            ->with(['matakuliah' => function ($query) {
                $query->select('mk_id', 'jur_id', 'kd_mk', 'nm_mk', 'semester', 'sks', 'kd_kur', 'smt');
            }])->get();
    }

    /**
     * Relasi view nilai akhir ke view matakuliah, many to one
     */
    public function matakuliah() {
        return $this->belongsTo(MatKulView::class, 'mk_id', 'mk_id');
    }

    public function matakuliahDiselenggarakan() {
        return $this->belongsTo(MatkulDiselenggarakanView::class, 'mk_id', 'mk_id');
    }
}
