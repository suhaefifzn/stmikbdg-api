<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    /**
     * Model ini digunakan untuk tabel sites
     *
     * Koneksi database terhubung ke first connection dan tabel sites.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    protected $table = 'sites';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
