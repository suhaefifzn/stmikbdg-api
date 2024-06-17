<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    /**
     * skema: surat
     * table: kategori
     */
    use HasFactory;

    protected $connection;
    protected $table = 'surat.kategori';
    protected $guarded = ['kategori_id'];

    public $timestamps = false;
    public $primaryKey = 'kategori_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
