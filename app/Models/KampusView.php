<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KampusView extends Model
{
    /**
     * Model ini digunakan untuk view kampus
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'stmikbdg_dummy' view kampus.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    protected $table = 'kampus'; // belum ada viewnya jadi ke tabel dulu
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }
}
