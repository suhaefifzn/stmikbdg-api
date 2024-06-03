<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffMarketingView extends Model
{
    /**
     * Model ini mengarah ke view staff marketing diterima di db simak baru skema public
     * Digunakan untuk mengelola proses read atau select
     */
    use HasFactory;

    protected $table = 'public.vstaff_marketing';
    protected $connection;

    public $timestamps = false;

    public function __construct()
    {
        $this->connection = config('myconfig.database.first_connection');
    } 
}
