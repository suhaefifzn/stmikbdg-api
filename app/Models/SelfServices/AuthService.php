<?php

namespace App\Models\SelfServices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SelfServices\MyWebService;

class AuthService extends MyWebService
{
    use HasFactory;

    public function __construct()
    {
        parent::__construct('authentications');
    }

    public function validateUserSiteAccess($siteURL)
    {
        $query = "/check/site?url=$siteURL";

        return $this->get(null, $query);
    }

    public function checkToken($token)
    {
        $payload = [
            'token' => $token,
        ];

        return $this->get($payload, '/check');
    }
}
