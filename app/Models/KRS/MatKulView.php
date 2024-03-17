<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// ? Models -  View
use App\Models\KRS\NilaiAkhirView;
use App\Models\KelasKuliah\KelasKuliahJoinView;
use App\Models\KRS\NilaiLamaView;
use App\Models\KRS\NilaiGabunganView;

class MatKulView extends Model
{
    /**
     * Model ini digunakan untuk view mata_kuliah
     * hanya digunakan untuk get data (SELECT).
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
                ->orderBy('mk_id', 'ASC')
                ->get();
        }

        return $query->where('aktif_kur', true)
            ->where('jur_id', $filter['jur_id'])
            ->where('kur_id', $filter['kur_id'])
            ->orderBy('mk_id', 'ASC')
            ->get();
    }

    public function nilaiAkhir() {
        return $this->hasMany(NilaiAkhirView::class, 'mk_id', 'mk_id');
    }

    public function kelasKuliahJoin() {
        return $this->hasMany(KelasKuliahJoinView::class, 'mk_id', 'mk_id');
    }

    public function nilaiLama() {
        return $this->hasMany(NilaiLamaView::class, 'mk_id', 'mk_id');
    }

    public function nilaiGabungan() {
        return $this->hasMany(NilaiGabunganView::class, 'mk_id', 'mk_id');
    }
}
