<?php

namespace App\Models\SIKPS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Similarities extends Model
{
    /**
     * Model ini mengarah ke tabel similarities di db simak baru skema deteksi_proposal
     * Digunakan untuk mengelola proses CRUD
     */
    use HasFactory;

    protected $table = 'deteksi_proposal.similarities';
    protected $connection;
    protected $guarded = ['similarity_id'];

    public $primaryKey = 'similarity_id';
    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
