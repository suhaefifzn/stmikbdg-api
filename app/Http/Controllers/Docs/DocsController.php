<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class DocsController extends Controller
{
    public function home() {
        return view('docs.home');
    }

    public function login() {
        return view('login');
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        // rate limiter
        $throttleKey = 'signin' . $credentials['email'];
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('loginError', 'Too many attempts! Try again in ' . $seconds . ' seconds.');
        }

        $token = auth()->attempt($credentials);

        if ($token) {
            // get user account info
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ];

            $urlUserInfo = config('app.url') . 'api/users/me';
            $userInfo = Http::withHeaders($headers)->get($urlUserInfo)->json();
            $isUserDeveloper = $userInfo['data']['account']['is_dev'];

            // jika user adalah developer masuk ke halaman utama docs/api
            if ($isUserDeveloper) {
                Session::put('docs_token', $token);
                RateLimiter::clear($throttleKey);

                return redirect()->intended('docs/api');
            }

            // jika user bukanlah developer logout paksa
            Http::withHeaders($headers)->delete(config('app.url') . 'api/authentications');

            // percobaan login gagal
            RateLimiter::hit($throttleKey);
            return back()
                ->with(
                    'loginError', 
                    'Mohon maaf akun Anda tidak memiliki izin untuk mengakses dokumentasi API'
                );
        }

        // percobaan login gagal
        RateLimiter::hit($throttleKey);

        return back()->with('loginError', 'Sig in failed!');
    }

    public function logout() {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . Session::get('docs_token'),
        ];

        Http::withHeaders($headers)->delete(config('app.url') . 'api/authentications');
        Session::remove('docs_token');

        return redirect()->route('docs_login');
    }
}
