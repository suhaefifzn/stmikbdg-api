<?php

namespace App\Models\KelasKuliah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ? Models
use App\Models\KelasKuliah\Pertemuan;
use Illuminate\Database\Eloquent\Builder;

class Presensi extends Model
{
    /**
     * Model ini mengarah ke tabel presensi di db simak baru skema kuliah
     * Digunakan apabila kelas kuliah dibuka dan mahasiswa mengisi kehadiran
     */
    use HasFactory;

    protected $connection;
    protected $table = 'kuliah.presensi';
    protected $guarded = ['presensi_id'];

    public $primaryKey = 'presensi_id';
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeGetCekPresensi(Builder $query, $pertemuanId, $mahasiswaId) {
        return $query->where('pertemuan_id', $pertemuanId)
            ->where('mhs_id', $mahasiswaId)
            ->where('pin', '!=', null)
            ->first();
    }

    public function pertemuan() {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id', 'pertemuan_id');
    }
}
