<?php

namespace App\Models\Antrian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    /**
     * Model ini mengarah ke tabel tamu db simak baru skema antrian
     */
    use HasFactory;

    protected $table = 'antrian.tamu';
    protected $connection;
    protected $guarded = ['tamu_id'];

    public $primaryKey = 'tamu_id';
    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
