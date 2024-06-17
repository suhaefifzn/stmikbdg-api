<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllStaffView extends Model
{
    /**
     * skema: public
     * view: staff
     */
    use HasFactory;

    protected $connection;
    protected $table = 'vstaff_all';
    public $primaryKey = 'staff_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
