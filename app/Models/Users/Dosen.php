<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ? Models - table
use App\Models\Users\Mahasiswa;

class Dosen extends Model
{
    /**
     * Model ini digunakan untuk tabel dosen
     * untuk melakukan proses-proses Create, Update, Delete.
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' tabel dosen.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar tabel dosen->users.
     */
    use HasFactory;

    protected $connection;
    protected $table = 'dosen';
    protected $guarded = ['id'];

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }


    /**
     * Relasi tabel dosen ke mahasiswa, one to many
     */
    public function mahasiswa() {
        return $this->hasMany(Mahasiswa::class, 'dosen_id', 'dosen_id');
    }
}
