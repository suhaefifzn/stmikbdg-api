<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// ? Models - table
use App\Models\KRS\KRSMatkul;

class KRS extends Model
{
    /**
     * Model ini digunakan untuk tabel krs
     * untuk melakukan proses-proses Create, Update, Delete.
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' tabel krs.
     */
    use HasFactory;

    public $table = 'krs';
    public $connection = 'second_pgsql';

    public function scopeGetLastNomorKRS(Builder $query, string $similarNmrKRS) {
        $result =  $query->where('nmr_krs', 'like', '%'. $similarNmrKRS . '%')
                        ->orderBy('nmr_krs', 'DESC')
                        ->first();
        return is_null($result) ? null : $result['nmr_krs'];
    }

    /**
     * Relasi tabel krs ke krs_matkul, one to many
     */
    public function krsMatkul() {
        return $this->hasMany(KRSMatkul::class);
    }
}
