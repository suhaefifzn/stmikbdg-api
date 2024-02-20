<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// ? Models -  View
use App\Models\KRS\NilaiAkhirView;

class MatKulView extends Model
{
    /**
     * Model ini digunakan untuk view mata_kuliah
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view mata_kuliah.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    public $table = 'vmata_kuliah';
    public $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetMatkul(Builder $query, $filter) {
        if ($filter['semester']) {
            return $query->where('aktif_kur', true)
                ->where('jur_id', $filter['jur_id'])
                ->where('semester', $filter['semester'])
                ->where('kur_id', $filter['kur_id'])
                ->where('smt', '!=', $filter['smt'])
                ->orderBy('mk_id', 'ASC')
                ->get();
        }

        return $query->where('aktif_kur', true)
            ->where('jur_id', $filter['jur_id'])
            ->where('kur_id', $filter['kur_id'])
            ->where('smt', '!=', $filter['smt'])
            ->orderBy('mk_id', 'ASC')
            ->get();
    }

    /**
     * Relasi view matakuliah ke view nilai akhir, one to many
     */
    public function nilaiAkhir() {
        return $this->hasMany(NilaiAkhirView::class, 'mk_id', 'mk_id');
    }
}
