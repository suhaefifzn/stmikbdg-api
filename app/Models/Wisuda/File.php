<?php

namespace App\Models\Wisuda;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * Model ini mengarah ke tabel file di db simak baru skema wisuda
     * Digunakan untuk mengelola proses CUD
     */
    use HasFactory;

    protected $table = 'wisuda.files';
    protected $connection;
    protected $guarded = ['file_id'];

    public $primaryKey = 'file_id';
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    public function scopeUpdateFiles(Builder $query, $pengajuanId, $filesArr) {
        return $query->where('pengajuan_id', $pengajuanId)->update($filesArr);
    }
}
