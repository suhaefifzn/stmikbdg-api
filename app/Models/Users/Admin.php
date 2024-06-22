<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    /**
     * skema: public
     * table: admins
     */
    use HasFactory;

    protected $connection;
    protected $table = 'admins';
    protected $guarded = ['admin_id'];

    public $timestamps = false;
    public $primaryKey = 'admin_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
