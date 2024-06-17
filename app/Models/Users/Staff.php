<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    /**
     * skema: public
     * table: staff
     */
    use HasFactory;

    protected $connection;
    protected $table = 'staff';
    protected $guarded = ['staff_id'];

    public $timestamps = false;
    public $primaryKey = 'staff_id';

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
