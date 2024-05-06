<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerKegiatan extends Model
{
    /**
     * Model ini mengarah ke tabel kuesioner kegiatan di db simak baru skema kuesioner
     */
    use HasFactory;

    protected $table = 'kuesioner.kuesioner_kegiatan';
    protected $connection;
    protected $guarded = ['kuesioner_kegiatan_id'];

    public $primaryKey = 'kuesioner_kegiatan_id';
    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
