<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    /**
     * skema: surat
     * table: disposisi
     */
    use HasFactory;

    protected $connection;
    protected $table = 'surat.disposisi';
    protected $guarded = ['disposisi_id'];

    public $timestamps = false;
    public $primaryKey = 'disposisi_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function suratMasuk() {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id', 'surat_masuk_id');
    }
}
