<?php

namespace App\Http\Controllers\Antrian\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Exceptions\ErrorHandler;

// ? Models - Tables
use App\Models\Antrian\Dosen;

class AdminController extends Controller
{
    public function addDosen(Request $request)
    {
        try {
            $data = $request->validate([
                'kd_dosen' => 'required|string',
                'nm_dosen' => 'required|string',
                'no_card' => 'required|string',
            ]);

            /**
             * cek kemungkinan dosen ada di tabel
             */
            $dosen = Dosen::where('nm_dosen', 'like', '%' . strtoupper($request->nm_dosen) . '%')->first();

            if ($dosen) {
                return $this->failedResponseJSON('Dosen sudah pernah ditambahkan', 400);
            }

            $data = [
                'kd_dosen' => strtoupper($request->kd_dosen),
                'nm_dosen' => strtoupper($request->nm_dosen),
                'no_card' => $request->no_card,
                'created_at' => Carbon::now(),
            ];

            DB::beginTransaction();
            $insert = Dosen::insert($data);

            if ($insert) {
                DB::commit();
                return $this->successfulResponseJSONV2('Dosen berhasil ditambahkan', 201);
            }

            DB::rollBack();
            return $this->failedResponseJSON('Dosen gagal ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function getAllDosen() {
        try {
            $allDosen = Dosen::orderBy('created_at', 'DESC')->get();

            return $this->successfulResponseJSON([
                'list_dosen' => $allDosen
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function deleteDosen(Request $request) {
        try {
            $request->validate([
                'dosen_id' => 'required|integer',
            ]);

            /**
             * cek dosen id
             */
            $dosen = Dosen::where('dosen_id', $request->dosen_id)->first();

            if (!$dosen) {
                return $this->failedResponseJSON('Dosen tidak ditemukan', 404);
            }

            DB::beginTransaction();
            $delete = Dosen::where('dosen_id', $request->dosen_id)->delete();

            if ($delete) {
                DB::commit();
                return $this->successfulResponseJSONV2('Dosen berhasil dihapus');
            }

            DB::rollBack();
            return $this->failedResponseJSON('Dosen gagal ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function getDosen($dosenId) {
        try {
            if ($dosenId) {
                $dosen = Dosen::where('dosen_id', (int) $dosenId)->first();

                if ($dosen) {
                    return $this->successfulResponseJSON([
                        'dosen' => $dosen
                    ]);
                }
            }

            return $this->failedResponseJSON('Dosen tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function updateDosen(Request $request) {
        try {
            $request->validate([
                'dosen_id' => 'required|integer',
            ]);

            if ($request->dosen_id) {
                $dosen = Dosen::where('dosen_id', $request->dosen_id)->first();

                if ($dosen) {
                    $request->validate([
                        'kd_dosen' => 'required|string',
                        'nm_dosen' => 'required|string',
                        'no_card' => 'required|string',
                    ]);

                    $data = [
                        'kd_dosen' => strtoupper($request->kd_dosen),
                        'nm_dosen' => strtoupper($request->nm_dosen),
                        'no_card' => $request->no_card,
                        'created_at' => Carbon::now()
                    ];

                    DB::beginTransaction();
                    $update = Dosen::where('dosen_id', $request->dosen_id)->update($data);

                    if ($update) {
                        DB::commit();
                        return $this->successfulResponseJSONV2('Data Dosen berhasil diperbarui');
                    }

                    DB::rollBack();
                    return $this->failedResponseJSON('Data Dosen gagal diperbarui');
                }
            }

            return $this->failedResponseJSON('Dosen tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }
}
