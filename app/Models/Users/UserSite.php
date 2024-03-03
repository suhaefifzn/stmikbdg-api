<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSite extends Model
{
    /**
     * Model ini digunakan untuk tabel user_sites
     *
     * Koneksi database terhubung ke first connection dan tabel user_sites.
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    protected $connection;
    protected $table = 'user_sites';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
