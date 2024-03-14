<?php

namespace App\Models\KelasKuliah;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

// ? Models - view
use App\Models\Users\MahasiswaView;

class JadwalView extends Model
{
    /**
     * Model ini digunakan untuk view jadwal
     * hanya digunakan untuk get data (SELECT).
     */

    protected $table = 'vjadwal';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetJadwalKelasKuliah(Builder $query, $kelasKuliahId, $userId, $isDosen) {
        if ($isDosen) {
            return $query->where('kelas_kuliah_id', $kelasKuliahId)
                ->where('dosen_id', $userId)
                ->select('kelas_kuliah_id', 'mk_id', 'dosen_id', 'tanggal', 'jns_pert', 'jam', 'kd_ruang')
                ->first();
        }

        return $query->where('kelas_kuliah_id', $kelasKuliahId)
            ->where('mhs_id', $userId)
            ->select('kelas_kuliah_id', 'mk_id', 'mhs_id', 'dosen_id', 'tanggal', 'jns_pert', 'jam', 'kd_ruang')
            ->first();
    }

    public function scopeGetTanggalDanJenisPertemuan(Builder $query, $kelasKuliahId) {
        return $query->where('kelas_kuliah_id', $kelasKuliahId)
            ->select('tanggal', 'jam', 'jns_pert')
            ->first();
    }

    public function scopeGetJadwalWithMahasiswa(Builder $query, $kelasKuliahId) {
        return $query->where('kelas_kuliah_id', $kelasKuliahId)
            ->select('kelas_kuliah_id', 'mhs_id')
            ->with(['mahasiswa' => function ($query) {
                $query->select('mhs_id', 'nim', 'nm_mhs');
            }])->get();
    }

    public function mahasiswa() {
        return $this->belongsTo(MahasiswaView::class, 'mhs_id', 'mhs_id');
    }
}
