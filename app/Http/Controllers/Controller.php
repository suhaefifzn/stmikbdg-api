<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// ? Exception
use App\Exceptions\ErrorHandler;
use App\Models\Users\Admin;
// ? Models - view
use App\Models\Users\MahasiswaView;
use App\Models\Users\Dosen;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * successfulResponseJSON
     * Fungsi untuk mengembalikan response yang berhasil
     *
     * @param array $data berisi data yang sukses dieksekusi, misalnya berupa 'id' => $insertedId
     * @param string $message pesan yang bersifat optional (dapat dikosongkan)
     * @param int $statusCode HTTP code, nilai defaultnya adalah 200
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function successfulResponseJSON(array $data, string $message = null, int $statusCode = 200) {
        if (is_null($message)) {
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], $statusCode);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ], $statusCode);
        }
    }

    /**
     * Digunakan untuk mendapatkan data user yang telah terautentikasi
     * Data diambil dari view dosen atau view mahasiswa dari database 'stmikbdg_dummy'
     */
    public function getUserAuth() {
        try {
            $isDosen = auth()->user()->is_dosen;
            $isAdmin = auth()->user()->is_admin;
            $kdUserArr = explode('-', auth()->user()->kd_user);
            $userIdentifier = $kdUserArr[1]; // bisa berisi kd_dosen atau nim

            // user adalah dosen saja atau sekaligus admin
            if ($isDosen) {
                $dosen = Dosen::where('kd_dosen', $userIdentifier)->first();

                // buat nm_dosen menjadi key nama
                $tempNama = $dosen['nm_dosen'];
                unset($dosen['nm_dosen']);

                // cek jika field nama telah memiliki gelar
                $explodedNamaDosen =  explode(',', trim($tempNama));
                $dosen['nama'] = $explodedNamaDosen[0];
                $dosen['nama_dan_gelar'] = $dosen['nama'] . ', ' . $dosen['gelar'];

                return $dosen;
            }

            // user adalah admin saja
            if (!$isDosen and $isAdmin) {
                $admin = Admin::where('kd_admin', $userIdentifier)->first();

                // buat nm_admin menjadi key nama
                $tempNama = $admin['nm_admin'];
                unset($admin['nm_admin']);
                $admin['nama'] = $tempNama;

                return $admin;
            }

            // user adalah mahasiswa
            $mahasiswa = MahasiswaView::where('nim', $userIdentifier)->first();

            // buat nm_mhs menjadi key nama
            $tempNama = $mahasiswa['nm_mhs'];
            unset($mahasiswa['nm_mhs']);
            $mahasiswa['nama'] = $tempNama;

            return $mahasiswa;
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function isDosenWali($dosen, $mhsId) {
        try {
            $mahasiswa = MahasiswaView::where('mhs_id', $mhsId)->first();

            return $dosen['dosen_id'] === $mahasiswa['dosen_id'] ?? false;
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function failedResponseJSON($message = 'Gagal', $statusCode = 500) {
        return response()->json([
            'status' => 'fail',
            'message' => $message,
        ], $statusCode);
    }

    public function successfulResponseJSONV2($message = 'Sukses', $statusCode = 200) {
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ], $statusCode);
    }
}
