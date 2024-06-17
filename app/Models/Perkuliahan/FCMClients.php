<?php

namespace App\Models\Perkuliahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FCMClients extends Model
{
    /**
     * skema: kuliah
     * table: fcm_clients
     * */
    use HasFactory;

    protected $table = 'kuliah.fcm_clients';
    protected $connection;
    protected $guarded = ['fcm_client_id'];

    public $primaryKey = 'fcm_client_id';
    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
