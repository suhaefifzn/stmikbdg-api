<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// ? Models - tabel
use App\Models\KRS\KRS;
use App\Models\Users\Dosen;

class Mahasiswa extends Model
{
    /**
     * Model ini digunakan untuk tabel mahasiswa
     * untuk melakukan proses-proses Create, Update, Delete.
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' tabel mahasiswa.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar tabel mahasiswa->users.
     */
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $connection;

    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['test_tanggal', 'test_link', 'test_pwd', 'test_nilai', 'test_diterima'];

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeSearchMahasiswa(Builder $query, $filter, $search = null) {
        if ($search)  {
            return $query->where('dosen_id', $filter['dosen_id'])
                        ->where('angkatan_id', $filter['angkatan_id'])
                        ->where('jur_id', $filter['jur_id'])
                        ->where('jns_mhs', $filter['jns_mhs'])
                        ->where('nm_mhs', 'like', '%' . strtoupper($search) . '%')
                        ->orWhere('nim', 'like', '%' . strtoupper($search) . '%')
                        ->get();
        }

        return $query->where('dosen_id', $filter['dosen_id'])
                ->where('angkatan_id', $filter['angkatan_id'])
                ->where('jur_id', $filter['jur_id'])
                ->where('jns_mhs', $filter['jns_mhs'])
                ->get();
    }

    /**
     * Relasi tabel mahasiswa ke krs, one to many
     */
    public function krs() {
        return $this->hasMany(KRS::class, 'krs_id', 'krs_id_last');
    }

    /**
     * Relasi tabel mahasiswa ke dosen, many to one
     */
    public function dosen() {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'dosen_id');
    }

}
