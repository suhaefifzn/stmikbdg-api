<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    /**
     * skema: surat
     * table: arsip
     */
    use HasFactory;

    protected $connection;
    protected $table = 'surat.arsip';
    protected $guarded = ['arsip_id'];

    public $timestamps = false;
    public $primaryKey = 'arsip_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
