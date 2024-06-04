<?php

namespace App\Http\Controllers\Antrian\Tamu;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models
use App\Models\Antrian\Dosen;
use App\Models\Antrian\Tamu;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    public function getListTamu(Request $request) {
        try {
            $antrianTamu = null;
            $user = $this->getUserAuth();
            $isSudah = $request->query('is_sudah');
            $dosen = Dosen::where('kd_dosen', strtoupper(trim($user['kd_dosen'])))->first();

            if ($dosen) {
                if ($isSudah) {
                    // filter berdasarkan is_sudah
                    $filteredIsSudah = filter_var($isSudah, FILTER_VALIDATE_BOOLEAN);
                    $antrianTamu = Tamu::where('dosen_id', $dosen['dosen_id'])
                        ->where('is_sudah', $filteredIsSudah)
                        ->orderBy('tamu_id', 'DESC')
                        ->get();
                } else {
                    // tanpa filter
                    $antrianTamu = Tamu::where('dosen_id', $dosen['dosen_id'])
                        ->orderBy('tamu_id', 'DESC')
                        ->get();
                }

                return $this->successfulResponseJSON([
                    'list_antrian' => $antrianTamu
                ]);
            }

            return $this->failedResponseJSON('Dosen tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function updateStatus(Request $request) {
        try {
            $request->validate([
                'tamu_id' => 'required|integer',
                'is_sudah' => 'required|boolean'
            ]);

            $user = $this->getUserAuth();
            $antrian = Tamu::where('tamu_id', $request->tamu_id)
                ->where('kd_dosen', strtoupper(trim($user['kd_dosen'])))
                ->first();

            if ($antrian) {
                DB::beginTransaction();
                $update = Tamu::where('tamu_id', $request->tamu_id)
                    ->where('kd_dosen', strtoupper(trim($user['kd_dosen'])))
                    ->update([
                        'is_sudah' => $request->is_sudah
                    ]);

                if ($update) {
                    DB::commit();
                    return $this->successfulResponseJSONV2('Status antrian tamu berhasil diperbarui', 200);
                }

                DB::rollBack();
                return $this->failedResponseJSON('Status antrian tamu gagal diperbarui', 500);
            }
            
            DB::rollBack();
            return $this->failedResponseJSON('Antrian tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }
}
