<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerKegiatanView extends Model
{
    /**
     * Model ini mengarah ke view kuesioner kegiatan di db simak baru skema kuesioner
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'kuesioner.vkuesioner_kegiatan';
    protected $connection;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
