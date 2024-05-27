<?php

namespace App\Models\Antrian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    /**
     * Model ini mengarah ke tabel bimbingan db simak baru skema antrian
     */
    use HasFactory;

    protected $table = 'antrian.bimbingan';
    protected $connection;
    protected $guarded = ['bimbingan_id'];

    public $primaryKey = 'bimbingan_id';
    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
