<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    /**
     * Model ini mengarah ke tabel pertanyaan di db simak baru skema kuesioner
     * Digunakan untuk mengelola proses CUD (Create, Update, Delete)
     */
    use HasFactory;

    protected $table = 'kuesioner.pertanyaan';
    protected $connection;
    protected $guarded = ['pertanyaan_id'];

    public $primaryKey = 'pertanyaan_id';
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
