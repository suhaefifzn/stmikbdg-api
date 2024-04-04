<?php

namespace App\Models\Wisuda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalWisudaAktifView extends Model
{
    /**
     * Model ini mengarah ke view jadwal wisuda aktif di db simak baru skema wisuda
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'wisuda.vjadwal_wisuda_aktif';
    protected $connection;

    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
