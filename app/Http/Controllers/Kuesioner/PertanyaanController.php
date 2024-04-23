<?php

namespace App\Http\Controllers\Kuesioner;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - Views
use App\Models\Kuesioner\PertanyaanView;
use App\Models\Kuesioner\JenisPertanyaanView;
use App\Models\Kuesioner\KelompokPertanyaanView;

// ? Models - Tables
use App\Models\Kuesioner\Pertanyaan;

class PertanyaanController extends Controller {
    public function addPertanyaan(Request $request) {
        try {
            $request->validate([
                'jenis_pertanyaan_id' => 'required',
                'kelompok_pertanyaan_id' => 'required',
                'pertanyaan' => 'required|string|min:10'
            ]);

            /**
             * Cek jenis pertanyaan id dan kelompok pertanyaan id
             */
            $jenisPertanyaan = JenisPertanyaanView::getJenisPertanyaanById((integer) $request->jenis_pertanyaan_id);
            $kelompokPertanyaan = KelompokPertanyaanView::getKelompokPertanyaanById((integer) $request->kelompok_pertanyaan_id);

            if (!$jenisPertanyaan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai jenis_pertanyaan_id tidak ditemukan',
                ], 404);
            }

            if (!$kelompokPertanyaan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai kelompok_pertanyaan_id tidak ditemukan',
                ], 404);
            }

            /**
             * Jika jenis id pada kelompok pertanyaan tidak sesuai dengan request
             */
            if ($request->jenis_pertanyaan_id != $kelompokPertanyaan['jenis_pertanyaan_id']) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai jenis_pertanyaan_id tidak sesuai dengan yang dimiliki kelompok_pertanyaan_id',
                ], 400);
            }

            /**
             * Insert ke db
             */
            $pertanyaan = [
                'jenis_pertanyaan_id' => (integer) $request->jenis_pertanyaan_id,
                'kelompok_pertanyaan_id' => (integer) $request->kelompok_pertanyaan_id,
                'pertanyaan' => trim($request->pertanyaan),
            ];

            $insertedPertanyaanId = Pertanyaan::create($pertanyaan)->pertanyaan_id;

            if ($insertedPertanyaanId) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pertanyaan dengan jenis ' . $jenisPertanyaan['nama']
                        . ' pada kelompok ' . $kelompokPertanyaan['nama']
                        . ' berhasil ditambahkan'
                ], 201);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Pertanyaan baru gagal ditambahkan'
            ], 500);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getPertanyaanByJenisId(Request $request) {
        try {
            $jenisId = $request->query('jenis_id');

            /**
             * Cek jenis_id
             */
            $jenisPertanyaan = JenisPertanyaanView::getJenisPertanyaanById((integer) $jenisId);

            if (!$jenisId or !$jenisPertanyaan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai query jenis_id tidak ditemukan'
                ], 404);
            }

            $pertanyaan = PertanyaanView::getGroupedPertanyaanByJenisId((integer) $jenisId);

            return $this->successfulResponseJSON([
                'list_pertanyaan' => $pertanyaan
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getOnePertanyaanById($pertanyaanId) {
        try {
            $pertanyaan = PertanyaanView::getPertanyaanById((integer) $pertanyaanId);

            /**
             * Cek pertanyaan
             */
            if (!$pertanyaan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Pertanyaan dengan pertanyaan_id ' . $pertanyaanId . ' tidak ditemukan'
                ], 404);
            }

            return $this->successfulResponseJSON([
                'pertanyaan' => $pertanyaan,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function editPertanyaan(Request $request) {
        try {
            $request->validate([
                'pertanyaan_id' => 'required',
                'jenis_pertanyaan_id' => 'required',
                'kelompok_pertanyaan_id' => 'required',
                'pertanyaan' => 'required|string|min:10',
            ]);

            /**
             * Cek pertanyaan id, jenis pertanyaan id, dan kelompok pertanyaan id
             */
            $pertanyaan = PertanyaanView::getPertanyaanById((integer) $request->pertanyaan_id);
            $jenisPertanyaan = JenisPertanyaanView::getJenisPertanyaanById((integer) $request->jenis_pertanyaan_id);
            $kelompokPertanyaan = KelompokPertanyaanView::getKelompokPertanyaanById((integer) $request->kelompok_pertanyaan_id);

            if (!$pertanyaan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai pertanyaan_id tidak ditemukan',
                ], 404);
            }

            if (!$jenisPertanyaan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai jenis_pertanyaan_id tidak ditemukan',
                ], 404);
            }

            if (!$kelompokPertanyaan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai kelompok_pertanyaan_id tidak ditemukan',
                ], 404);
            }

            /**
             * Jika jenis id pada kelompok pertanyaan tidak sesuai dengan request
             */
            if ($request->jenis_pertanyaan_id != $kelompokPertanyaan['jenis_pertanyaan_id']) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai jenis_pertanyaan_id tidak sesuai dengan yang dimiliki kelompok_pertanyaan_id',
                ], 400);
            }

            /**
             * Update data di db
             */
            $updatedData = [
                'jenis_pertanyaan_id' => (integer) $request->jenis_pertanyaan_id,
                'kelompok_pertanyaan_id' => (integer) $request->kelompok_pertanyaan_id,
                'pertanyaan' => trim($request->pertanyaan),
            ];

            $updatePertanyaan = Pertanyaan::where('pertanyaan_id', (integer) $request->pertanyaan_id)
                ->update($updatedData);

            if ($updatePertanyaan) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pertanyaan dengan nilai pertanyaan_id ' . $request->pertanyaan_id . ' berhasil diperbarui'
                ], 200);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Pertanyaan dengan nilai pertanyaan_id ' . $request->pertanyaan_id . ' gagal diperbarui'
            ], 200);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
