<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// ? Models - table
use App\Models\KRS\KRS;

class TahunAjaranView extends Model
{
    /**
     * Model ini digunakan untuk view tahun ajaran aktif
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view ta_aktif
     */
    use HasFactory;

    protected $table = 'vta_aktif';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetTahunAjaran(Builder $query, $filter) {
        return $query->where('jur_id', $filter['jur_id'])
            ->where('jns_mhs', $filter['jns_mhs'])
            ->where('kd_kampus', $filter['kd_kampus'])
            ->orderBy('tahun_id', 'DESC')
            ->first();
    }

    public function scopeGetTahunAjaranWithKRS(Builder $query) {
        return $query->with('krs')->orderBy('tahun_id', 'ASC')->get();
    }

    /**
     * Relasi view ta_aktif ke tabel krs, one to many
     */
    public function krs() {
        return $this->hasMany(KRS::class, 'tahun_id', 'tahun_id');
    }
}
