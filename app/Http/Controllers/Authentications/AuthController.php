<?php

namespace App\Http\Controllers\Authentications;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Error;
use Illuminate\Http\Request;

// ? JWT
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function userLogin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email:dns',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');
            $platform = $request->query('platform');

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Kredensial yang Anda berikan tidak sesuai',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Gagal generate token',
            ], 500);
        }

        $ttl = self::setExpirationToken($platform);
        $token = auth()->setTTL($ttl)->attempt($credentials);
        $expirationDate = \Carbon\Carbon::createFromTimestamp(auth()->payload()->get('exp'));

        $data = [
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                // 'expires_in' => is_null($ttl) ? null : "$ttl minutes",
                'expires_in' => $expirationDate->toDateTimeString(),
            ],
            'platform' => $platform,
        ];

        return $this->successfulResponseJSON($data, null, 201);
    }

    public function userLogout()
    {
        $token = JWTAuth::getToken()->get();

        if ($token) {
            JWTAuth::invalidate(true);
        }

        auth()->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil. Access token telah dihapus',
        ], 200);
    }

    public function getNewToken(Request $request)
    {
        try {
            $platform = $request->query('platform');

            if (is_null($platform)) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Jenis platform tidak diketahui'
                ], 400);
            }

            if ($platform === 'android') {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Access token tidak memiliki waktu kadaluarsa',
                    'data' => [
                        'platform' => $platform,
                    ],
                ], 400);
            }

            $ttl = self::setExpirationToken($platform);
            $token = auth()->refresh(true, true);

            $data = [
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => "$ttl minutes",
                ],
                'platform' => $platform,
            ];

            return $this->successfulResponseJSON($data, 'Token berhasil diperbaharui');
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * setExpirationToken
     * Fungsi untuk mengatur waktu kadaluarsa access token
     *
     * @param string $platform berisi nilai 'android' atau 'web'
     *
     * Jika 'android', maka tidak memiliki kadaluarsa.
     * Jika 'web', maka memiliki waktu kadaluarsa selama 6 jam
     */
    private function setExpirationToken(string $platform)
    {
        if ($platform === 'android') return null; // permanent
        else if ($platform === 'web') return 60 * 6; // 6 hours
    }
}
