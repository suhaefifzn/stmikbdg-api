<?php

namespace App\Http\Controllers\SIKPS;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use App\Models\SIKPS\Fingerprints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ? Models - Table
use App\Models\SIKPS\Similarities;

/**
 * Semua method dalam class ini digunakan untuk mahasiswa
 */
class MahasiswaDeteksiProposalController extends Controller
{
    public function addHasilDeteksi(Request $request) {
        try {
            $data = $request->validate([
                'nim' => 'required|string',
                'nm_mhs' => 'required|string',
                'judul' => 'required|string',
                'file' => 'required|string',
                'persentase_kemiripan' => 'required',
                'original_name' => 'required|string',
            ]);

            $data['created_at'] = now();

            DB::beginTransaction();

            $insert = Similarities::insert($data);

            if ($insert) {
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Hasil deteksi berhasil ditambahkan'
                ], 201);
            }

            DB::rollBack();

            return response()->json([
                'status' => 'fail',
                'message' => 'Hasil kemiripan gagal ditambahkan'
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function getListHasilDeteksi() {
        try {
            $mahasiswa = $this->getUserAuth();
            $listHasilDeteksi = Similarities::where('nim', $mahasiswa['nim'])
                ->orderBy('similarity_id', 'DESC')
                ->get();

            return $this->successfulResponseJSON([
                'hasil_deteksi' => $listHasilDeteksi,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function updateProposal(Request $request) {
        try {
            $similarityId = $request->similarity_id;

            if ($similarityId) {
                $similarity = Similarities::where('similarity_id', (int) $similarityId)->first();

                if ($similarity) {
                    $data = $request->validate([
                        'judul' => 'required|string',
                        'file' => 'required|string',
                        'persentase_kemiripan' => 'required',
                        'original_name' => 'required|string',
                    ]);

                    $data['created_at'] = now();

                    DB::beginTransaction();

                    $update = Similarities::where('similarity_id', (int) $similarityId)->update($data);

                    if ($update) {
                        DB::commit();

                        return response()->json([
                            'status' => 'succes',
                            'message' => 'Proposal berhasil diperbarui'
                        ], 200);
                    }

                    DB::rollBack();

                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Proposal gagal diperbarui',
                    ], 500);
                }
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Proposal tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function deleteProposal(Request $request) {
        try {
            $similarityId = $request->similarity_id;

            if ($similarityId) {
                $similarity = Similarities::where('similarity_id', (int) $similarityId)->first();

                if ($similarity) {
                    DB::beginTransaction();

                    $delete = Similarities::where('similarity_id', (int) $similarityId)->delete();

                    if ($delete) {
                        DB::commit();

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Proposal berhasil dihapus'
                        ], 200);
                    }

                    DB::rollBack();

                    return response()->jsoN([
                        'status' => 'fail',
                        'message' => 'Proposal gagal dihapus'
                    ], 500);
                }
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Proposal tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getAllFingerprints() {
        try {
            $allFingerprints = Fingerprints::orderBy('fingerprint_id', 'DESC')->paginate(8);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'fingerprints' => $allFingerprints->items(),
                ],
                'meta' => [
                    'current_page' => $allFingerprints->currentPage(),
                    'per_page' => $allFingerprints->perPage(),
                    'total' => $allFingerprints->total(),
                    'last_page' => $allFingerprints->lastPage(),
                    'next_page_url' => $allFingerprints->nextPageUrl(),
                    'prev_page_url' => $allFingerprints->previousPageUrl(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getDetail(Request $request) {
        try {
            $similarityId = $request->similarity_id;

            if ($similarityId) {
                $similarity = Similarities::where('similarity_id', (int) $request->similarity_id)->first();

                if ($similarity) {
                    return $this->successfulResponseJSON([
                        'similarity' => $similarity
                    ]);
                }
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Proposal tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
