<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    protected $table = 'kurikulum'; // belum ada viewnya jadi ke tabel dulu
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetKurikulumMahasiswa(Builder $query, array $filter) {
        return $query->where('jur_id', $filter['jur_id'])
                        ->where('tahun', $filter['angkatan'])
                        ->where('k_aktif', true)
                        ->first();
    }
}
