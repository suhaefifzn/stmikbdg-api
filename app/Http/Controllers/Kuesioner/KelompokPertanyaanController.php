<?php

namespace App\Http\Controllers\Kuesioner;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - Views
use App\Models\Kuesioner\KelompokPertanyaanView;

// ? Models - Tables
use App\Models\Kuesioner\KelompokPertanyaan;

class KelompokPertanyaanController extends Controller {
    public function getKelompokPertanyaan(Request $request) {
        try {
            $jenisId = $request->query('jenis_id');

            /**
             * Jika query paramas jenis_id tidak ditemukan
             */
            if (!$jenisId) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai query jenis_id tidak ditemukan'
                ], 404);
            }

            $kelompokPertanyaan = KelompokPertanyaanView::getKelompokPertanyaanByJenisId((integer) $jenisId);

            return $this->successfulResponseJSON([
                'kelompok_pertanyaan' => $kelompokPertanyaan,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
