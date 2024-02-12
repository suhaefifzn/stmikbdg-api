<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ? Models - view
use App\Models\TahunAjaranView;
use App\Models\Users\MahasiswaView;

class JurusanView extends Model
{
    /**
     * Model ini digunakan untuk view jurusan
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view jurusan
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    public $table = 'vjurusan';
    public $connection = 'second_pgsql';

    /**
     * Relasi vjurusan ke vta_aktif, one to many
     */
    public function tahunAjaran() {
        return $this->hasMany(TahunAjaranView::class);
    }

    /**
     * Relasi vjurusan ke vmahasiswa, one to many
     */
    public function mahasiswa() {
        return $this->hasMany(MahasiswaView::class);
    }
}