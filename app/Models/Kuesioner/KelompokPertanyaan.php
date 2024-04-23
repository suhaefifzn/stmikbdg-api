<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPertanyaan extends Model
{
    /**
     * Model ini mengarah ke tabel kelompok pertanyaan di db simak baru skema kuesioner
     * Digunakan untuk mengelola proses CUD (Create, Update, Delete)
     */
    use HasFactory;

    protected $table = 'kuesioner.kelompok_pertanyaan';
    protected $connection;
    protected $guarded = ['kelompok_pertanyaan_id'];

    public $primaryKey = 'kelompok_pertanyaan_id';
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
