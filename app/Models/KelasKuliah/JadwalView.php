<?php

namespace App\Models\KelasKuliah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalView extends Model
{
    /**
     * Model ini digunakan untuk view jadwal
     * hanya digunakan untuk get data (SELECT).
     */
    use HasFactory;

    protected $table = 'vjadwal';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }
}
