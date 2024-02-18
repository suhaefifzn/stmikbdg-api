<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSitesView extends Model
{
    /**
     * Model ini digunakan untuk view user sitets
     * hanya digunakan untuk get data (SELECT).
     *
     * Saat ini menggunakan dua database
     */
    use HasFactory;

    protected $table = 'vuser_sites';
    protected $connection;
    protected $hidden = ['user_site_id'];

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
