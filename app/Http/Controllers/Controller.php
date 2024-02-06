<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
    public function successfulResponseJSON(array $data, string $message = null, int $statusCode = 200)
    {
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
}
