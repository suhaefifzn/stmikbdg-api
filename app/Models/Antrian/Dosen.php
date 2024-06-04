<?php

namespace App\Models\Antrian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    /**
     * Model ini mengarah ke tabel dosen di db simak baru skema antrian
     * a.k.a dosen pembimbing
     */
    use HasFactory;

    protected $table = 'antrian.dosen';
    protected $connection;
    protected $guarded = ['dosen_id'];

    public $primaryKey = 'dosen_id';
    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
