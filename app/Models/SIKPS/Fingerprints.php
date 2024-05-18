<?php

namespace App\Models\SIKPS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fingerprints extends Model
{
    /**
     * Model ini mengarah ke tabel fingerprints di db simak baru skema deteksi_proposal
     * Digunakan untuk mengelola proses CRUD
     */
    use HasFactory;

    protected $table = 'deteksi_proposal.fingerprints';
    protected $connection;
    protected $guarded = ['fingerprint_id'];

    public $primaryKey = 'fingerprint_id';
    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
