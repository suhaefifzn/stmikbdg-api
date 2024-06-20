<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Surat\SuratMasuk;
use App\Models\Surat\SuratKeluar;

use Illuminate\Database\Eloquent\Builder;

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

    public function suratMasuk() {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    public function suratKeluar() {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }

    public function scopeGetSuratMasukWhereNotId(Builder $query, int $id = null) {
        return $query->whereNot('surat_masuk_id', $id)
            ->orderBy('arsip_id', 'DESC')
            ->with(['suratMasuk' => function ($query) {
                $query->with(['kategori', 'status', 'disposisi']);
            }])->get();
    }

    public function scopeGetSuratKeluarWhereNotId(Builder $query, int $id = null) {
        return $query->whereNot('surat_keluar_id', $id)
            ->orderBy('arsip_id', 'DESC')
            ->with(['suratKeluar' => function ($query) {
                $query->with(['status']);
            }])->get();
    }
}
