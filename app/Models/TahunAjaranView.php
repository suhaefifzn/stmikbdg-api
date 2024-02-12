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

    public $table = 'vta_aktif';
    public $connection = 'second_pgsql'; // database 'stmikbdg_dummy'

    /**
     * getTahunAjaranByTahunAndSmt
     * Digunakan untuk get tahun ajaran berdasarkan:
     *
     * @param tahun - tahun ajaran aktif;
     * @param smt - berupa nilai 1 = ganjil; 2 = genap;
     *
     * @return mixed
     */
    function scopeGetTahunAjaran(
        Builder $query, object $user, string $tahun = null, $smt = null
    ) {
        if (!is_null($smt)) {
            return $query->where('jur_id', $user['jur_id'])
                        ->where('jns_mhs', $user['jns_mhs'])
                        ->where('tahun', $tahun)
                        ->where('smt', $smt)
                        ->get();
        }

        return $query->where('jur_id', $user['jur_id'])
                    ->where('jns_mhs', $user['jns_mhs'])
                    ->get();
    }

    /**
     * Relasi view ta_aktif ke tabel krs, one to many
     */
    public function krs() {
        return $this->hasMany(KRS::class);
    }
}
