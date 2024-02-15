<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//? Models - view
use App\Models\JurusanView;

class MahasiswaView extends Model
{
    /**
     * Model ini digunakan untuk view mahasiswa
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view mahasiswa.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar view mahasiswa->users.
     */
    use HasFactory;

    protected $table = 'vmahasiswa';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    /**
     * Relasi vmahasiswa ke vjurusan adalah, many to one
     */
    public function jurusan() {
        // param ke dua jur_id yang ada di vmahasiswa
        // param ke tiga jur_id yang merupakan primary key di vjurusan
        return $this->belongsTo(JurusanView::class, 'jur_id', 'jur_id');
    }
}
