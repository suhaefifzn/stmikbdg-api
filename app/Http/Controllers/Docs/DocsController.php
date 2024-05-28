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
        return view('docs.contents.home.index', [
            'title' => 'Home',
        ]);
    }

    public function homeTabs($tabName) {
        switch (strtolower($tabName)) {
            case 'update':
                $content = view('docs.contents.home.update')->render();
                break;
            case 'penggunaan':
                $content = view('docs.contents.home.penggunaan')->render();
                break;
            case 'lainnya':
                $content = view('docs.contents.home.lainnya')->render();
                break;
            default:
                $content = 'Tab not found.';
                break;
        }

        return response()->json([
            'content' => $content,
        ]);
    }

    public function login() {
        return view('login');
    }

    public function authentications() {
        return view('docs.contents.auth', [
            'title' => 'Authentications',
        ]);
    }

    public function users() {
        return view('docs.contents.users', [
            'title' => 'Users',
        ]);
    }

    public function krsMahasiswa() {
        return view('docs.contents.krs_mhs', [
            'title' => 'KRS - SisiMahasiswa'
        ]);
    }

    public function krsDosenWali() {
        return view('docs.contents.krs_dosen_wali', [
            'title' => 'KRS - Sisi Dosen Wali'
        ]);
    }

    public function sikps() {
        return view('docs.contents.deteksi-proposal.index', [
            'title' => 'SIKPS - Deteksi Proposal'
        ]);
    }

    public function sikpsTabs($tabName) {
        switch (strtolower($tabName)) {
            case 'admin':
                $content = view('docs.contents.deteksi-proposal.admin')->render();
                break;
            case 'mahasiswa':
                $content = view('docs.contents.deteksi-proposal.mahasiswa')->render();
                break;
            default:
                $content = 'Tab not found.';
                break;
        }

        return response()->json([
            'content' => $content,
        ]);
    }

    public function kuesioner() {
        return view('docs.contents.kuesioner.index', [
            'title' => 'Kuesioner'
        ]);
    }

    public function kuesionerTabs($tabName) {
        switch (strtolower($tabName)) {
            case 'admin':
                $content = view('docs.contents.kuesioner.admin')->render();
                break;
            case 'mahasiswa':
                $content = view('docs.contents.kuesioner.mahasiswa')->render();
                break;
            default:
                $content = 'Tab not found.';
                break;
        }

        return response()->json([
            'content' => $content,
        ]);
    }

    public function acl() {
        return view('docs.contents.acl', [
            'title' => 'ACL',
        ]);
    }

    public function kelasKuliahMahasiswa() {
        return view('docs.contents.kelas_mhs', [
            'title' => 'Kelas Kuliah Mahasiswa'
        ]);
    }

    public function kelasKuliahDosen() {
        return view('docs.contents.kelas_dosen', [
            'title' => 'Kelas Kuliah Dosen'
        ]);
    }

    public function kamus() {
        return view('docs.contents.kamus', [
            'title' => 'Kamus'
        ]);
    }

    public function additionalRoutes() {
        return view('docs.contents.additional', [
            'title' => 'Additional Routes'
        ]);
    }

    public function marketing() {
        return view('docs.contents.marketing', [
            'title' => 'Sistem Marketing'
        ]);
    }

    public function surat() {
        return view('docs.contents.surat', [
            'title' => 'Sistem Penomoran Surat'
        ]);
    }

    public function beritaAcara() {
        return view('docs.contents.berita', [
            'title' => 'Sistem Berita Acara'
        ]);
    }

    public function pengajuanWisuda() {
        return view('docs.contents.pengajuan-wisuda.index', [
            'title' => 'Pengajuan Wisuda'
        ]);
    }

    public function pengajuanWisudaTabs($tabName) {
        switch (strtolower($tabName)) {
            case 'admin':
                $content = view('docs.contents.pengajuan-wisuda.admin')->render();
                break;
            case 'mahasiswa':
                $content = view('docs.contents.pengajuan-wisuda.mahasiswa')->render();
                break;
            default:
                $content = 'Tab not found.';
                break;
        }

        return response()->json([
            'content' => $content,
        ]);
    }

    public function antrian() {
        return view('docs.contents.antrian.index', [
            'title' => 'Antrian'
        ]);
    }

    public function antrianTabs($tabName) {
        switch (strtolower($tabName)) {
            case 'dosen':
                $content = view('docs.contents.antrian.dosen')->render();
                break;
            case 'bimbingan':
                $content = view('docs.contents.antrian.bimbingan')->render();
                break;
            case 'sidang':
                $content = view('docs.contents.antrian.sidang')->render();
                break;
            case 'tamu':
                $content = view('docs.contents.antrian.tamu')->render();
                break;
            default:
                $content = 'Tab not found.';
                break;
        }

        return response()->json([
            'content' => $content,
        ]);
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
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
