<?php

namespace App\Http\Controllers\Antrian\Bimbingan;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

// ? Models - Tables
use App\Models\Antrian\Bimbingan;
use App\Models\Antrian\Dosen;

class AdminController extends Controller
{
    public function addAntrianBimbingan(Request $request) {
        try {
            $request->validate([
                'nim' => 'required|string',
                'nm_mhs' => 'required|string',
                'dosen_pembimbing' => 'required|string',
                'tgl_bimbingan' => 'required|string'
            ]);


            /**
             * cek dosen pembimbing
             */
            $dosen = Dosen::where('nm_dosen', 'like', '%' . strtoupper($request->dosen_pembimbing) . '%')->first();

            if (!$dosen) {
                return $this->failedResponseJSON('Dosen tidak ditemukan', 404);
            }

            /**
             * insert ke db
             */
            $data = [
                'nim' => (string) $request->nim,
                'nm_mhs' => (string) $request->nm_mhs,
                'dosen_id' => $dosen['dosen_id'],
                'kd_dosen' => $dosen['kd_dosen'],
                'nm_dosen' => $dosen['nm_dosen'],
                'tgl_bimbingan' => Carbon::createFromFormat('d-m-Y', $request->tgl_bimbingan),
                'created_at' => Carbon::now()
            ];

            DB::beginTransaction();
            $insert = Bimbingan::insert($data);

            if ($insert) {
                DB::commit();
                return $this->successfulResponseJSONV2('Antrian bimbingan berhasil ditambahkan', 201);
            }

            DB::rollBack();
            return $this->failedResponseJSON('Antrian bimbingan gagal ditambahkan', 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function getAllAntrianBimbingan(Request $request) {
        try {
            if ($request->query('sudah')) {
                $sudah = filter_var($request->query('sudah'), FILTER_VALIDATE_BOOLEAN);
                $allAntrian = Bimbingan::where('sudah', $sudah)->orderBy('created_at', 'DESC')->get();
            } else {
                $allAntrian = Bimbingan::orderBy('created_at', 'DESC')->get();
            }

            return $this->successfulResponseJSON([
                'list_antrian' => $allAntrian,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getAntrianBimbingan($bimbinganId) {
        try {
            if ($bimbinganId) {
                $bimbingan = Bimbingan::where('bimbingan_id', (int) $bimbinganId)->first();

                if ($bimbingan) {
                    return $this->successfulResponseJSON([
                        'antrian' => $bimbingan
                    ]);
                }
            }

            return $this->failedResponseJSON('Antrian bimbingan tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function deleteAntrianBimbingan(Request $request) {
        try {
            $request->validate([
                'bimbingan_id' => 'required|integer'
            ]);

            $bimbingan = Bimbingan::where('bimbingan_id', $request->bimbingan_id)->first();

            if ($bimbingan) {
                DB::beginTransaction();
                $delete = Bimbingan::where('bimbingan_id', $request->bimbingan_id)->delete();

                if ($delete) {
                    DB::commit();
                    return $this->successfulResponseJSONV2('Antrian bimbingan berhasil dihapus');
                }

                DB::rollBack();
                return $this->failedResponseJSON('Antrian bimbingan gagal dihapus', 500);
            }

            return $this->failedResponseJSON('Antrian bimbingan tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function updateAntrianBimbingan(Request $request) {
        try {
            $request->validate([
                'bimbingan_id' => 'required|integer'
            ]);

            $bimbingan = Bimbingan::where('bimbingan_id', $request->bimbingan_id)->first();

            if ($bimbingan) {
                $request->validate([
                    'nim' => 'required|string',
                    'nm_mhs' => 'required|string',
                    'dosen_pembimbing' => 'required|string',
                    'tgl_bimbingan' => 'required|string',
                ]);
                
                $data = [
                    'nim' => $request->nim,
                    'nm_mhs' => $request->nm_mhs,
                    'nm_dosen' => $request->dosen_pembimbing,
                    'tgl_bimbingan' => Carbon::createFromFormat('d-m-Y', $request->tgl_bimbingan),
                    'created_at' => Carbon::now()
                ];

                /**
                 * cek nama dosen
                 */
                $dosen = Dosen::where('nm_dosen', 'like', '%' . strtoupper($data['nm_dosen']) . '%')->first();

                if (!$dosen) {
                    return $this->failedResponseJSON('Data dosen tidak ditemukan', 404);
                }

                $data['dosen_id'] = $dosen['dosen_id'];
                $data['kd_dosen'] = $dosen['kd_dosen'];
                $data['nm_dosen'] = $dosen['nm_dosen'];

                DB::beginTransaction();
                $update = Bimbingan::where('bimbingan_id', $request->bimbingan_id)->update($data);

                if ($update) {
                    DB::commit();
                    return $this->successfulResponseJSONV2('Antrian bimbingan berhasil diperbarui');
                }

                DB::rollBack();
                return $this->failedResponseJSON('Antrian bimbingan gagal diperbarui', 500);
            }

            return $this->failedResponseJSON('Antrian bimbingan tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function updateStatusAntrianBimbingan(Request $request) {
        try {
            $request->validate([
                'bimbingan_id' => 'required|integer'
            ]);

            $bimbingan = Bimbingan::where('bimbingan_id', $request->bimbingan_id)->first();

            if ($bimbingan) {
                $data = $request->validate([
                    'sudah' => 'required|boolean'
                ]);

                DB::beginTransaction();
                $update = Bimbingan::where('bimbingan_id', $request->bimbingan_id)->update($data);

                if ($update) {
                    DB::commit();
                    return $this->successfulResponseJSONV2('Status antrian bimbingan berhasil diperbarui');
                }

                DB::rollBack();
                return $this->failedResponseJSON('Status antrian bimbingan gagal diperbarui', 500);
            }

            return $this->failedResponseJSON('Antrian bimbingan tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }
}
