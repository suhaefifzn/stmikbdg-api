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

    public $table = 'vusers';
    public $connection = 'pgsql';
    protected $hidden = ['password', 'id', 'remember_token', 'created_at', 'updated_at'];
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

    public function scopeGetAllUsers(Builder $query, $filter) {
        // query hanya is_dosen saja
        if (!is_null($filter['is_dosen']) and is_null($filter['is_admin'])) {
            return $query->where('kd_user', '!=', auth()->user()->kd_user)
                            ->where('is_dosen', $filter['is_dosen'])
                            ->orderBy('created_at', 'DESC')
                            ->get();
        }

        // query hanya is_admin saja
        if (is_null($filter['is_dosen']) and !is_null($filter['is_admin'])) {
            return $query->where('kd_user', '!=', auth()->user()->kd_user)
                            ->where('is_admin', $filter['is_admin'])
                            ->orderBy('created_at', 'DESC')
                            ->get();
        }

        // query is_dosen dan is_admin
        if (!is_null($filter['is_dosen']) and !is_null($filter['is_admin'])) {
            return $query->where('kd_user', '!=', auth()->user()->kd_user)
                            ->where('is_dosen', $filter['is_dosen'])
                            ->where('is_admin', $filter['is_admin'])
                            ->orderBy('created_at', 'DESC')
                            ->get();
        }

        // tanpa query
        return $query->where('kd_user', '!=', auth()->user()->kd_user)
                        ->orderBy('created_at', 'DESC')
                        ->get();
    }
}
