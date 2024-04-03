<?php

namespace App\Models\Wisuda;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanWisuda extends Model
{
    /**
     * Model ini mengarah ke tabel pengajuan di db simak baru skema wisuda
     * Digunakan untuk mengelola proses CUD
     */
    use HasFactory;

    protected $table = 'wisuda.pengajuan';
    protected $connection;
    protected $guarded = ['pengajuan_id'];

    public $primaryKey = 'pengajuan_id';
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeUpdatePengajuan(Builder $query, $pengajuanId, $nim, $dataPengajuanArr) {
        return $query->where('pengajuan_id', $pengajuanId)
            ->where('nim', $nim)
            ->update($dataPengajuanArr);
    }

    public function scopeDeletePengajuan(Builder $query, $pengajuanId, $nim) {
        return $query->where('pengajuan_id', $pengajuanId)
            ->where('nim', $nim)
            ->delete();
    }
}
