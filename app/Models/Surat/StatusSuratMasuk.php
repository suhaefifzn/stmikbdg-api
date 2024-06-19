<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusSuratMasuk extends Model
{
    /**
     * skema: surat
     * table: status_surat_masuk
     */
    use HasFactory;

    protected $connection;
    protected $table = 'surat.status_surat_masuk';
    protected $guarded = ['status_id'];

    public $timestamps = false;
    public $primaryKey = 'status_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
