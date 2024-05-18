<?php

namespace App\Http\Controllers\SIKPS;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ? Models - Table
use App\Models\SIKPS\Fingerprints;
use App\Models\SIKPS\Similarities;

/**
 * Semua method dalam class ini digunakan untuk admin
 */
class DeteksiProposalController extends Controller
{
    public function addFingerprints(Request $request) {
        try {
            $request->validate([
                'fingerprints' => 'required|array'
            ]);

            DB::beginTransaction();

            $insert = Fingerprints::insert($request->fingerprints);

            if ($insert) {
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Fingerprints berhasil dibuat'
                ], 201);
            }

            DB::rollBack();

            return response()->json([
                'status' => 'fail',
                'message' => 'Fingerprints gagal dibuat. Unknown error'
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();

            return ErrorHandler::handle($e);
        }
    }

    public function getAllFingerprints() {
        try {
            $fingerprints = Fingerprints::orderBy('fingerprint_id', 'DESC')->get();

            return $this->successfulResponseJSON([
                'fingerprints' => $fingerprints
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function deleteFingerprint(Request $request) {
        try {
            $fingerprintId = $request->fingerprint_id;

            if ($fingerprintId) {
                $fingerprint = Fingerprints::where('fingerprint_id', (int) $fingerprintId)->first();

                if ($fingerprint) {
                    DB::beginTransaction();
                    $delete = Fingerprints::where('fingerprint_id', (int) $fingerprintId)->delete();
                    
                    if ($delete) {
                        DB::commit();

                        return response()->json([
                            'status' => 'success', 
                            'message' => 'Fingerprint proposal berhasil dihapus'
                        ], 200);
                    }
                }
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Fingerprint tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function updateFingerprint(Request $request) {
        try {
            $fingerprintId = $request->fingerprint_id;

            if ($fingerprintId) {
                $fingerprint = Fingerprints::where('fingerprint_id', (int) $fingerprintId)->first();

                if ($fingerprint) {
                    $request->validate([
                        'judul' => 'required|string',
                        'file_dokumen' => 'nullable|string',
                        'n_gram' => 'required|string',
                        'hashing' => 'required|string',
                        'winnowing' => 'required|string',
                        'fingerprint' => 'required|string',
                        'total_fingerprint' => 'nullable',
                        'total_ngram' => 'nullable',
                        'total_hash' => 'nullable',
                        'total_window' => 'nullable'
                    ]);

                    $data = [
                        'judul' => $request->judul,
                        'n_gram' => $request->n_gram,
                        'hashing' => $request->hashing,
                        'winnowing' => $request->winnowing,
                        'fingerprint' => $request->fingerprint,
                        'total_fingerprint' => $request->total_fingerprint,
                        'total_ngram' => $request->total_ngram,
                        'total_hash' => $request->total_hash,
                        'total_window' => $request->total_window,
                        'created_at' => now(),
                    ];

                    DB::beginTransaction();

                    if ($request->file_dokumen) {
                        $data['file_dokumen'] = $request->file_dokumen;
                        $data['is_generated'] = false;
                    }
                    
                    $update = Fingerprints::where('fingerprint_id', $fingerprintId)->update($data);

                    if ($update) {
                        DB::commit();

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Fingerprint berhasil diperbarui'
                        ], 200);
                    }

                    DB::rollBack();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Fingerprint gagal diperbarui'
                    ], 500);
                }
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Fingerprint tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function getAllRiwayatDeteksi() {
        try {
            $listHasilDeteksi = Similarities::orderBy('similarity_id', 'DESC')->get();

            return $this->successfulResponseJSON([
                'hasil_deteksi' => $listHasilDeteksi
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
