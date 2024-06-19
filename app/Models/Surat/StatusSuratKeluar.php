<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusSuratKeluar extends Model
{
    /**
     * skema: surat
     * table: status_surat_keluar
     */
    use HasFactory;

    protected $connection;
    protected $table = 'surat.status_surat_keluar';
    protected $guarded = ['status_id'];

    public $timestamps = false;
    public $primaryKey = 'status_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
