<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//? Models - view
use App\Models\JurusanView;
use App\Models\KelasKuliah\JadwalView;
use Illuminate\Database\Eloquent\Builder;

class MahasiswaView extends Model
{
    /**
     * Model ini digunakan untuk view mahasiswa
     * hanya digunakan untuk get data (SELECT).
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    protected $table = 'vmahasiswa';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function scopeGetMahasiswa(Builder $query, $nim) {
        return $query->where('nim', $nim)->first();
    }

    /**
     * Relasi vmahasiswa ke vjurusan adalah, many to one
     */
    public function jurusan() {
        // param ke dua jur_id yang ada di vmahasiswa
        // param ke tiga jur_id yang merupakan primary key di vjurusan
        return $this->belongsTo(JurusanView::class, 'jur_id', 'jur_id');
    }

    public function jadwalKelas() {
        return $this->hasMany(JadwalView::class, 'mhs_id', 'mhs_id');
    }
}
