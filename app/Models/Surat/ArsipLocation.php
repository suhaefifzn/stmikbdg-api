<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipLocation extends Model
{
    /**
     * skema: surat
     * table: arsip_lokasi
     */
    use HasFactory;

    protected $connection;
    protected $table = 'surat.arsip_lokasi';
    protected $guarded = ['arsip_lokasi_id'];

    public $timestamps = false;
    public $primaryKey = 'arsip_lokasi_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
