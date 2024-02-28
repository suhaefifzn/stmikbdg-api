<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasKuliahView extends Model
{
    /**
     * Model ini digunakan untuk view kelas_kuliah
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view kelas_kuliah.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    protected $table = 'vkelas_kuliah';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetMatkulDiampuArray(Builder $query, $filter) {
        return $query->where('pengajar_id', $filter['dosen_id'])
            ->where('jns_mhs', $filter['jns_mhs'])
            ->where('kd_kampus', $filter['kd_kampus'])
            ->where('tahun_id', $filter['tahun_id'])
            ->pluck('mk_id')
            ->toArray();
    }
}
