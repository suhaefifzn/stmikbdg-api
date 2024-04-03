<?php

namespace App\Models\Verdig;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiView extends Model
{
    /**
     * Model ini mengarah ke view verifikasi di db simak baru skema verdig
     * Digunakan untuk mengelola proses Read atau select
     */
    use HasFactory;

    protected $table = 'verdig.v_verifikasi';
    protected $connection;

    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeGetVerdigMahasiswa(Builder $query, $nim, $jenisSurat) {
        return $query->where('nim', $nim)
            ->where('jenis_surat', $jenisSurat)
            ->first();
    }
}
