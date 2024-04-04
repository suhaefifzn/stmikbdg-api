<?php

namespace App\Models\Wisuda;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalWisudaAllView extends Model
{
    /**
     * Model ini mengarah ke view jadwal wisuda all di db simak baru skema wisuda
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'wisuda.vjadwal_wisuda_all';
    protected $connection;

    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeGetJadwalWisuda(Builder $query, $tahun) {
        return $query->where('tahun', $tahun)->first();
    }

    public function scopeGetLatestJadwalWisuda(Builder $query) {
        return $query->orderBy('tahun', 'DESC')->first();
    }
}
