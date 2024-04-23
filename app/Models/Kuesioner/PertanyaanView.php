<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanView extends Model
{
    /**
     * Model ini mengarah ke view pertanyaan di db simak baru skema kuesioner
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'kuesioner.vpertanyaan';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeGetGroupedPertanyaanByJenisId(Builder $query, $jenisId) {
        return $query->where('jenis_pertanyaan_id', $jenisId)
            ->get()->groupBy('kelompok');
    }

    public function scopeGetPertanyaanById(Builder $query, $pertanyaanId) {
        return $query->where('pertanyaan_id', $pertanyaanId)->first();
    }
}
