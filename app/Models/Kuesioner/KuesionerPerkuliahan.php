<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerPerkuliahan extends Model
{
    /**
     * Model ini mengarah ke tabel kuesioner perkuliahan di db simak baru skema kuesioner
     */
    use HasFactory;

    protected $table = 'kuesioner.kuesioner_perkuliahan';
    protected $connection;
    protected $guarded = ['kuesioner_perkuliahan_id'];

    public $primaryKey = 'kuesioner_perkuliahan_id';
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
