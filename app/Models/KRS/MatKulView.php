<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
    public $connection = 'second_pgsql';

    public function scopeGetMatkul(Builder $query, $filter) {
        if (!is_null($filter['smt']) and is_null($filter['semester'])) {
            return $query->where('smt', $filter['smt'])
                            ->where('jur_id', $filter['jur_id'])
                            ->where('aktif_kur', true)
                            ->orderBy('mk_id', 'ASC')
                            ->get();
        }

        if (is_null($filter['smt']) and !is_null($filter['semester'])) {
            return $query->where('semester', $filter['semester'])
                            ->where('jur_id', $filter['jur_id'])
                            ->where('aktif_kur', true)
                            ->orderBy('mk_id', 'ASC')
                            ->get();
        }

        if ($filter['smt'] and $filter['semester']) {
            return $query->where('smt', $filter['smt'])
                            ->where('semester', $filter['semester'])
                            ->where('jur_id', $filter['jur_id'])
                            ->where('aktif_kur', true)
                            ->orderBy('mk_id', 'ASC')
                            ->get();
        }

        return $query->where('aktif_kur', true)
                        ->where('jur_id', $filter['jur_id'])
                        ->orderBy('mk_id', 'ASC')
                        ->get();
    }
}
