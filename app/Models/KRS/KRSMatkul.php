<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// ? Models - tabel
use App\Models\KRS\KRS;

// ? Models - view
use App\Models\KRS\MatKulView;
use App\Models\KelasKuliah\KelasKuliahJoinView;
use App\Models\KelasKuliah\KelasKuliahView;

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

    public function scopeGetKRSMatkulWithKelasKuliah(Builder $query, $krsId) {
        return $query->where('krs_id', $krsId)
            ->select('krs_mk_id', 'krs_id', 'mk_id', 'kelas_kuliah_id')
            ->with(['kelasKuliahJoin' => function ($query) {
                $query->select(
                    'kelas_kuliah_id', 'tahun_id', 'jur_id', 'mk_id', 'join_kelas_kuliah_id', 'kjoin_kelas', 'kelas_kuliah', 'jns_mhs', 'sts_kelas', 'pengajar_id', 'join_jur'
                )->with(['dosen' => function ($query) {
                    $query->select('dosen_id', 'kd_dosen', 'nm_dosen', 'gelar');
                }])->with(['matakuliah' => function ($query) {
                    $query->select('mk_id', 'kur_id', 'kd_mk', 'nm_mk', 'semester', 'sks', 'sts_mk', 'smt', 'kd_kur');
                }]);
            }])->get();
    }

    public function krs() {
        return $this->belongsTo(KRS::class, 'krs_id', 'krs_id');
    }

    public function matakuliah() {
        return $this->belongsTo(MatKulView::class, 'mk_id', 'mk_id');
    }

    /**
     * Relasi ke view kelas kuliah
     */
    public function kelasKuliah() {
        return $this->belongsTo(KelasKuliahView::class, 'kelas_kuliah_id', 'kelas_kuliah_id');
    }

    /**
     * Relasi ke view kelas kuliah join
     */
    public function kelasKuliahJoin() {
        return $this->belongsTo(KelasKuliahJoinView::class, 'kelas_kuliah_id', 'kelas_kuliah_id');
    }
}
