<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MatkulDiselenggarakanView extends Model
{
    /**
     * Model ini digunakan untuk view mk_diselenggarakan
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view mk_diselenggarakan.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    protected $table = 'vmk_diselenggarakan';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetMatkulDiselenggarakan(Builder $query, $filter) {
        return $query->where('tahun_id', $filter['tahun_id'])
                    ->orderBy('mk_id', 'ASC')
                    ->get();
    }

    /**
     * Relasi view mk diselenggarakan ke view nilai akhir, one to many
     */
    public function nilaiAkhir() {
        return $this->hasMany(NilaiAkhirView::class, 'mk_id', 'mk_id');
    }
}
