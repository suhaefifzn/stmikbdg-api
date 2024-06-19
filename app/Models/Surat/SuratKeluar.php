<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    /**
     * skema: surat
     * table: surat_keluar
     */
    use HasFactory;

    protected $connection;
    protected $table = 'surat.surat_keluar';
    protected $guarded = ['surat_keluar_id'];

    public $timestamps = false;
    public $primaryKey = 'surat_keluar_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function status() {
        return $this->belongsTo(StatusSuratKeluar::class, 'status_id', 'status_id');
    }
}
