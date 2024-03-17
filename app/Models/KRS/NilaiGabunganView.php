<?php

namespace App\Models\KRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ? Models - view
use App\Models\KRS\MatKulView;
use App\Models\KRS\MatkulDiselenggarakanView;

class NilaiGabunganView extends Model
{
    /**
     * Model ini digunakan untuk view nilai gabungan
     * hanya digunakan untuk get data (SELECT).
     *
     * Saat ini masih menggunakan dua database berbeda
     */
    use HasFactory;

    public $table = 'vnilai_gab';
    public $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.second_connection');
    }

    public function matakuliah() {
        return $this->belongsTo(MatKulView::class, 'mk_id', 'mk_id');
    }

    public function matakuliahDiselenggarakan() {
        return $this->belongsTo(MatkulDiselenggarakanView::class, 'mk_id', 'mk_id');
    }
}
