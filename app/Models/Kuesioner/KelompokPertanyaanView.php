<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPertanyaanView extends Model
{
    /**
     * Model ini mengarah ke view kelompok pertanyaan di db simak baru skema kuesioner
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'kuesioner.vkelompok_pertanyaan';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeGetKelompokPertanyaanByJenisId(Builder $query, $jenisId) {
        return $query->where('jenis_pertanyaan_id', $jenisId)->get();
    }

    public function scopeGetKelompokPertanyaanById(Builder $query, $kelompokId) {
        return $query->where('kelompok_pertanyaan_id', $kelompokId)->first();
    }
}
