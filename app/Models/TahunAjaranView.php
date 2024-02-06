<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TahunAjaranView extends Model
{
    /**
     * Model ini digunakan untuk view dosen
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'simak_stmikbdg' view dosen.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar view dosen->mahasiswa.
     */
    use HasFactory;

    public $table = 'vta_aktif';
    public $connection = 'second_pgsql'; // database 'stmikbdg_dummy'

    function scopeGetTahunAjaran(Builder $query, object $user, string $tahun) {
        return $query->where('jur_id', $user['jur_id'])
            ->where('tahun', $tahun)
            ->where('jns_mhs', $user['jns_mhs'])
            ->get();
    }
}
