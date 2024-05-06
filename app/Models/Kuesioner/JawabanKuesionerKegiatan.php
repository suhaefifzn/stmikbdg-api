<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanKuesionerKegiatan extends Model
{
    /**
     * Model ini mengarah ke tabel jawaban kuesioner kegiatan di db simak baru skema kuesioner
     * Digunakan untuk mengelola proses CUD (Create, Update, Delete)
     */
    use HasFactory;

    protected $table = 'kuesioner.jawaban_kuesioner_kegiatan';
    protected $connection;
    protected $guarded = ['jawaban_kuesioner_kegiatan_id'];

    public $primaryKey = 'jawaban_kuesioner_kegiatan_id';
    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
