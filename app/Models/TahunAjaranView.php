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
     * Koneksi database terhubung ke 'stmikbdg_dummy' view ta_aktif.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    protected $table = 'vta_aktif';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetTahunAjaran(Builder $query, object $user) {
        return $query->where('jur_id', $user['jur_id'])
                    ->where('jns_mhs', $user['jns_mhs'])
                    ->where('kd_kampus', $user['kd_kampus'])
                    ->orderBy('tahun_id', 'DESC')
                    ->first();
    }

    /**
     * Relasi view ta_aktif ke tabel krs, one to many
     */
    public function krs() {
        return $this->hasMany(KRS::class);
    }
}
