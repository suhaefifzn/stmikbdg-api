<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsView extends Model
{
    /**
     * Model ini mengarah ke view points di db simak baru skema kuesioner
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'kuesioner.vpoints';
    protected $connection;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    }
}
