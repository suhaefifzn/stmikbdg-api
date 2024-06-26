<?php

namespace App\Models\Wisuda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalWisuda extends Model
{
    /**
     * Model ini mengarah ke tabel jadwal wisuda di db simak baru skema wisuda
     * Digunakan untuk mengelola proses CUD
     */
    use HasFactory;

    protected $table = 'wisuda.jadwal_wisuda';
    protected $connection;
    protected $guarded = ['jadwal_wisuda_id'];

    public $primaryKey = 'jadwal_wisuda_id';
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
