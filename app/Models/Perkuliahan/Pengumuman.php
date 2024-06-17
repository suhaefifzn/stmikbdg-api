<?php

namespace App\Models\Perkuliahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    /**
     * skema: kuliah
     * table: pengumuman
     */
    use HasFactory;

    protected $table = 'kuliah.pengumuman';
    protected $connection;
    protected $guarded = ['pengumuman_id'];

    public $primaryKey = 'pengumuman_id';
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
