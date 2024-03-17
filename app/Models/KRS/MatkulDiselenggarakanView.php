<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// ? Models - view
use App\Models\KRS\NilaiAkhirView;
use App\Models\KRS\NilaiLamaView;
use App\Models\KRS\NilaiGabunganView;

class MatkulDiselenggarakanView extends Model
{
    /**
     * Model ini digunakan untuk view mk_diselenggarakan
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view mk_diselenggarakan.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    protected $table = 'vmk_diselenggarakan';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetMatkulDiselenggarakan(Builder $query, $filter) {
        return $query->where('tahun_id', $filter['tahun_id'])
                    ->orderBy('mk_id', 'ASC')
                    ->get();
    }

    public function scopeGetOneMatkul(Builder $query, $filter) {
        return $query->where('mk_id', $filter['mk_id'])
                    ->where('tahun_id', $filter['tahun_id'])
                    ->where('kd_kampus', $filter['kd_kampus'])
                    ->where('jns_mhs', $filter['jns_mhs'])
                    ->where('jur_id', $filter['jur_id'])
                    ->get();
    }

    public function nilaiAkhir() {
        return $this->hasMany(NilaiAkhirView::class, 'mk_id', 'mk_id');
    }

    public function nilaiLama() {
        return $this->hasMany(NilaiLamaView::class, 'mk_id', 'mk_id');
    }

    public function nilaiGabungan() {
        return $this->hasMany(NilaiGabunganView::class, 'mk_id', 'mk_id');
    }
}
