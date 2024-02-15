<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// ? Models - table
use App\Models\KRS\KRSMatkul;
use App\Models\Users\Mahasiswa;

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

    public $primaryKey = 'krs_id';
    public $timestamps = false;
    protected $guarded = ['krs_id'];

    public function scopeGetLastNomorKRS(Builder $query, string $similarNmrKRS) {
        $result = $query->where('nmr_krs', 'like', '%'. $similarNmrKRS . '%')
                        ->orderBy('nmr_krs', 'DESC')
                        ->get();

        return count($result) > 0 ? $result[0]['nmr_krs'] : false;
    }

    public function scopeGetLastSemesterKRS(Builder $query, Model $mahasiswa) {
        $result = $query->where('mhs_id', $mahasiswa['mhs_id'])
                        ->orderBy('tanggal', 'DESC')
                        ->get();

        return count($result) > 0 ? $result[0]['semester'] : false;
    }

    public function scopeCheckCurrentKRS(Builder $query, int $tahunAjaranId, Model $mahasiswa) {
        $isLastSemesterKRS = KRS::getLastSemesterKRS($mahasiswa);
        $semester = !$isLastSemesterKRS ? 1 : $isLastSemesterKRS;

        $result = $query->where('tahun_id', $tahunAjaranId)
                        ->where('mhs_id', $mahasiswa['mhs_id'])
                        ->where('semester', $semester)
                        ->get();

        return count($result) > 0 ? $result[0] : false;
    }

    /**
     * Relasi tabel krs ke krs_matkul, one to many
     */
    public function krsMatkul() {
        return $this->hasMany(KRSMatkul::class, 'krs_id');
    }

    /**
     * Relasi tabel krs ke mahasiwa, many to one
     */
    public function mahasiswa() {
        return $this->belongsTo(Mahasiswa::class, 'mhs_id', 'mhs_id');
    }
}
