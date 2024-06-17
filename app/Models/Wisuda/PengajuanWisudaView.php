<?php

namespace App\Models\Wisuda;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanWisudaView extends Model
{
    /**
     * Model ini mengarah ke view pengajuan di db simak baru skema wisuda
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'wisuda.vpengajuan';
    protected $connection;

    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeGetPengajuan(Builder $query, $nim) {
        return $query->where('nim', $nim)
            ->orderBy('tgl_pengajuan', 'DESC')
            ->first();
    }

    public function scopeGetStatusPengajuan(Builder $query, $nim) {
        return $query->where('nim', $nim)
            ->select('pengajuan_id', 'kd_status', 'ket_status', 'tgl_wisuda', 'tgl_pengajuan', 'is_verified')
            ->orderBy('tgl_pengajuan', 'DESC')
            ->first();
    }

    public function scopeGetAllPengajuan(Builder $query, $kdStatus = null, $jadwalId = null) {
        return $query->when($kdStatus, function ($query) use ($kdStatus) {
                $query->where('kd_status', $kdStatus);
            })
            ->when($jadwalId, function ($query) use ($jadwalId) {
                $query->where('jadwal_wisuda_id', $jadwalId);
            })
            ->orderBy('tgl_pengajuan', 'DESC')
            ->get();
    }
}
