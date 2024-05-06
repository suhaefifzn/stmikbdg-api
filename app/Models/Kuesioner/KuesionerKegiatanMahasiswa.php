<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerKegiatanMahasiswa extends Model
{
    /**
     * Model ini mengarah ke tabel kuesioner kegiatan mahasiswa di db simak baru skema kuesioner
     * Digunakan untuk mengelola proses CUD (Create, Update, Delete)
     */
    use HasFactory;

    protected $table = 'kuesioner.kuesioner_kegiatan_mahasiswa';
    protected $connection;
    protected $guarded = ['kuesioner_kegiatan_mahasiswa_id'];

    public $primaryKey = 'kuesioner_kegiatan_mahasiswa_id';
    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
