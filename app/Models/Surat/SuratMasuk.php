<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Surat\StatusSuratMasuk;
use App\Models\Surat\Kategori;
use App\Models\Surat\Disposisi;
use App\Models\Surat\Arsip;

class SuratMasuk extends Model
{
    /**
     * skema: surat
     * table: surat_masuk
     */
    use HasFactory;

    protected $connection;
    protected $table = 'surat.surat_masuk';
    protected $guarded = ['surat_masuk_id'];

    public $timestamps = false;
    public $primaryKey = 'surat_masuk_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function status() {
        return $this->belongsTo(StatusSuratMasuk::class, 'status_id', 'status_id');
    }

    public function kategori() {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }

    public function disposisi() {
        return $this->hasOne(Disposisi::class, 'surat_masuk_id');
    }

    public function arsip() {
        return $this->hasOne(Arsip::class, 'surat_masuk_id');
    }
}
