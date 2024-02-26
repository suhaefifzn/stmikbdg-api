<?php

namespace App\Http\Controllers\Authentications;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Illuminate\Http\Request;

// ? JWT
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

// ? Models - view
use App\Models\Users\UserSitesView;
use App\Models\Users\UserView;

class AuthController extends Controller
{
    public function userLogin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');
            $platform = $request->query('platform');

            if (!$platform) {
                return response()->json([
                    'success' => 'fail',
                    'message' => 'Nilai query platform pada url diperlukan',
                ], 400);
            }

            $token = auth()->attempt($credentials);
            $ttl = self::setExpirationToken($platform);
            $token = auth()->setTTL($ttl)->attempt($credentials);

            if ($token) {
                $data = [
                    'token' => [
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => $platform === 'android' ?
                                        null
                                        : "$ttl minutes",
                    ],
                    'platform' => $platform,
                    'role' => [
                        'is_admin' => auth()->user()->is_admin,
                        'is_dosen' => auth()->user()->is_dosen,
                        'is_mhs' => auth()->user()->is_mhs,
                        'is_dev' => auth()->user()->is_dev,
                    ]
                ];
            } else {
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

    public function validateToken() {
        try {
            return $this->successfulResponseJSON([
                'token' => JWTAuth::getToken()->get(),
            ], 'Access token OK!');
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function validateUserSiteAccess(Request $request) {
        try {
            $userId = auth()->user()->id;
            $site = filter_var($request->query('url'), FILTER_VALIDATE_URL);
            $userSite = UserSitesView::where('user_id', $userId)
                            ->where('url', 'like', '%' . $site .'%')
                            ->get();

            if (count($userSite) > 0) {
                return $this->successfulResponseJSON([
                    'access' => true,
                    'site' => $userSite,
                ]);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Pengguna dengan email <b>'
                    . auth()->user()->email
                    .  " tidak memiliki akses</b> ke alamat <i>"
                    . $site
                    . "</i>.<br/>Please <a href='$site/logout' rel='noopener'><b>Logout</b></a>.",
            ], 403);
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
        if ($platform === 'android') return 60 * 24 * 30 * 12 * 1000; // kurang lebih 1000 tahun
        else if ($platform === 'web') return 60 * 6; // 6 hours
    }
}
