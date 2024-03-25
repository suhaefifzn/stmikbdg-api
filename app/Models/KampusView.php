<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KampusView extends Model
{
    /**
     * Model ini digunakan untuk view kampus
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' vkampus
     */
    use HasFactory;

    protected $table = 'vkampus';
    protected $connection;

    public function scopeGetDetailKampus(Builder $query, $kdKampus) {
        return $query->where('kd_kampus', $kdKampus)->first();
    }

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }
}
