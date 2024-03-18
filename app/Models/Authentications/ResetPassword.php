<?php

namespace App\Models\Authentications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model {
    /**
     * DB Baru tabel users_reset_password
     *
     * Digunakan untuk menyimpan informasi
     * otp user untuk reset password
     */
    use HasFactory;

    protected $table = 'users_reset_password';
    protected $connection;
    protected $guarded = ['id'];

    public $timestamps = false;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }
}
