<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// ? JWT
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserView extends Authenticatable implements JWTSubject
{
    /**
     * Model ini digunakan untuk view users
     * hanya digunakan untuk get data (SELECT).
     *
     * Koneksi database terhubung ke 'simak_stmikbdg' view users.
     *
     * Saat ini masih menggunakan dua database berbeda sehingga
     * belum mendukung relasi antar view users->dosen.
     */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'vusers';
    protected $connection;

    public function __construct() {
        $this->connection = config('myconfig.database.first_connection');
    }

    protected $hidden = ['password', 'remember_token', 'created_at', 'updated_at'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function scopeGetAllUsers(Builder $query, $filter = null) {
        if ($filter) {
            // query is_dosen
            if (!is_null($filter['is_dosen'])) {
                return $query->where('kd_user', '!=', auth()->user()->kd_user)
                    ->where('is_dosen', $filter['is_dosen'])
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }

            // query is_admin
            if (!is_null($filter['is_admin'])) {
                return $query->where('kd_user', '!=', auth()->user()->kd_user)
                    ->where('is_admin', $filter['is_admin'])
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }

            // query is_mhs
            if (!is_null($filter['is_mhs'])) {
                return $query->where('kd_user', '!=', auth()->user()->kd_user)
                    ->where('is_mhs', $filter['is_mhs'])
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }

            // query is_dev
            if (!is_null($filter['is_dev'])) {
                return $query->where('kd_user', auth()->user()->kd_user)
                    ->where('is_dev', $filter['is_dev'])
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }
        }

        // tanpa query
        return $query->where('kd_user', '!=', auth()->user()->kd_user)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
