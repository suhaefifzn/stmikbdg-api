<?php

namespace App\Models\Users;

use App\Models\KelasKuliah\KelasKuliahJoinView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenView extends Model
{
    /**
     * Model ini digunakan untuk view dosen
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view dosen.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar view dosen->mahasiswa.
     */
    use HasFactory;

    protected $table = 'vdosen';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetListDosenAktif(Builder $query) {
        return $query->where('sts_dosen', 'A')
            ->select('dosen_id', 'nm_dosen', 'kd_dosen', 'gelar')
            ->orderBy('dosen_id', 'DESC')
            ->get();
    }

    public function kelasKuliahJoin() {
        return $this->hasMany(KelasKuliahJoinView::class, 'pengajar_id', 'dosen_id');
    }
}
