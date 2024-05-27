<?php

namespace App\Models\Antrian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidang extends Model
{
    /**
     * Model ini mengarah ke tabel sidang db simak baru skema antrian
     */
    use HasFactory;

    protected $table = 'antrian.sidang';
    protected $connection;
    protected $guarded = ['sidang_id'];

    public $primaryKey = 'sidang_id';
    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
