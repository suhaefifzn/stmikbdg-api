<?php

namespace App\Http\Controllers\Antrian\Sidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

// ? Models - Tables
use App\Models\Antrian\Sidang;
use App\Models\Antrian\Dosen;

class AdminController extends Controller
{
    public function getAllAntrianSidang() {
        try {
            $allSidang = Sidang::orderBy('created_at', 'DESC')->get();

            return $this->successfulResponseJSON([
                'list_antrian' => $allSidang,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getAntrianSidang($sidangId) {
        try {
            if ($sidangId) {
                $sidang = Sidang::where('sidang_id', (int) $sidangId)->first();

                if ($sidang) {
                    return $this->successfulResponseJSON([
                        'antrian' => $sidang
                    ]);
                }
            }

            return $this->failedResponseJSON('Antrian sidang tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function deleteAntrianSidang(Request $request) {
        try {
            $request->validate([
                'sidang_id' => 'required|integer'
            ]);

            $sidang = Sidang::where('sidang_id', $request->sidang_id)->first();

            if ($sidang) {
                DB::beginTransaction();
                $delete = Sidang::where('sidang_id', $request->sidang_id)->delete();

                if ($delete) {
                    DB::commit();
                    return $this->successfulResponseJSONV2('Antrian sidang berhasil dihapus');
                }

                DB::rollBack();
                return $this->failedResponseJSON('Antrian sidang gagal dihapus');
            }

            return $this->failedResponseJSON('Antrian sidang tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function addAntrianSidang(Request $request) {
        try {
            $request->validate([
                'dosen_pembimbing' => 'required|string',
                'nim' => 'required|string',
                'nm_mhs' => 'required|string',
                'tgl_sidang' => 'required|string',
                'dosen_penguji1' => 'required|string',
                'dosen_penguji2' => 'required|string'
            ]);

            $data = [
                'nim' => $request->nim,
                'nm_mhs' => strtoupper($request->nm_mhs),
                'dosen_penguji1' => strtoupper($request->dosen_penguji1),
                'dosen_penguji2' => strtoupper($request->dosen_penguji2)
            ];

            /**
             * cek dosen
             */
            $dosen = Dosen::where('nm_dosen', 'like', '%' . strtoupper($request->dosen_pembimbing) . '%')
                ->first();
            
            if (!$dosen) {
                return $this->failedResponseJSON('Data dosen pembimbing tidak ditemukan', 404);
            }

            $data['dosen_id'] = $dosen['dosen_id'];
            $data['kd_dosen'] = $dosen['kd_dosen'];
            $data['nm_dosen'] = $dosen['nm_dosen'];
            $data['tgl_sidang'] = Carbon::createFromFormat('d-m-Y', $request->tgl_sidang);
            $data['created_at'] = Carbon::now();

            DB::beginTransaction();
            $insert = Sidang::insert($data);

            if ($insert) {
                DB::commit();
                return $this->successfulResponseJSONV2('Antrian sidang berhasil ditambahkan', 201);
            }

            DB::rollBack();
            return $this->failedResponseJSON('Antrian sidang gagal ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function updateAntrianSidang(Request $request) {
        try {
            $request->validate([
                'sidang_id' => 'required|integer'
            ]);

            $sidang = Sidang::where('sidang_id', $request->sidang_id)->first();

            if ($sidang) {
                $request->validate([
                    'dosen_pembimbing' => 'required|string',
                    'nim' => 'required|string',
                    'nm_mhs' => 'required|string',
                    'tgl_sidang' => 'required|string',
                    'dosen_penguji1' => 'required|string',
                    'dosen_penguji2' => 'required|string'
                ]);

                $data = [
                    'nim' => $request->nim,
                    'nm_mhs' => strtoupper($request->nm_mhs),
                    'dosen_penguji1' => strtoupper($request->dosen_penguji1),
                    'dosen_penguji2' => strtoupper($request->dosen_penguji2)
                ];

                /**
                 * cek dosen
                 */
                $dosen = Dosen::where('nm_dosen', 'like', '%' . strtoupper($request->dosen_pembimbing) . '%')
                    ->first();

                if (!$dosen) {
                    return $this->failedResponseJSON('Data dosen pembimbing tidak ditemukan', 404);
                }

                $data['dosen_id'] = $dosen['dosen_id'];
                $data['kd_dosen'] = $dosen['kd_dosen'];
                $data['nm_dosen'] = $dosen['nm_dosen'];
                $data['tgl_sidang'] = Carbon::createFromFormat('d-m-Y', $request->tgl_sidang);
                $data['created_at'] = Carbon::now();

                DB::beginTransaction();
                $update = Sidang::where('sidang_id', $request->sidang_id)->update($data);

                if ($update) {
                    DB::commit();
                    return $this->successfulResponseJSONV2('Antrian sidang berhasil diperbarui', 200);
                }

                DB::rollBack();
                return $this->failedResponseJSON('Antrian sidang gagal diperbarui');
            }

            return $this->failedResponseJSON('Antrian sidang tidak ditemukan', 404);
        } catch (\Exception $e){
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }
}
