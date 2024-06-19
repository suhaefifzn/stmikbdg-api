<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipCatatan extends Model
{
    /**
     * skema: surat
     * table: arsip_catatan
     */
    use HasFactory;

    protected $connection;
    protected $table = 'surat.arsip_catatan';
    protected $guarded = ['arsip_catatan_id'];

    public $timestamps = false;
    public $primaryKey = 'arsip_catatan_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
