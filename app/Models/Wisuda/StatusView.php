<?php

namespace App\Models\Wisuda;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusView extends Model
{
    /**
     * Model ini mengarah ke view status di db simak baru skema wisuda
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'wisuda.vstatus';
    protected $connection;

    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeGetDetailStatus(Builder $query, $kdStatus) {
        return $query->where('kd_status', $kdStatus)->first();
    }
}
