<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class JenisPertanyaanView extends Model
{
    /**
     * Model ini mengarah ke view jenis pertanyaan di db simak baru skema kuesioner
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'kuesioner.vjenis_pertanyaan';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeGetJenisPertanyaanById(Builder $query, $jenisId) {
        return $query->where('jenis_pertanyaan_id', $jenisId)->first();
    }
}
