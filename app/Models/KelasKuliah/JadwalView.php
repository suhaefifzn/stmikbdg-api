<?php

namespace App\Models\KelasKuliah;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
}
