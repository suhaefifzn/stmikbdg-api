<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// ? Exception
use App\Exceptions\ErrorHandler;

// ? Models - view
use App\Models\Users\MahasiswaView;
use App\Models\Users\Dosen;

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
            $kdUserArr = explode('-', auth()->user()->kd_user);
            $userIdentifier = $kdUserArr[1]; // bisa berisi kd_dosen atau nim

            if ($isDosen) {
                $dosen = Dosen::where('kd_dosen', $userIdentifier)->first();

                return $dosen;
            }

            $mahasiswa = MahasiswaView::where('nim', $userIdentifier)->first();

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
}
