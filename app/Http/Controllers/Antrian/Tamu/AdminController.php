<?php

namespace App\Http\Controllers\Antrian\Tamu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

// ? Models - Tables
use App\Models\Antrian\Tamu;
use App\Models\Antrian\Dosen;

class AdminController extends Controller
{
    public function getAllAntrianTamu(Request $request) {
        try {
            $antrianTamu = null;
            $isSudah = $request->query('is_sudah');
            $kdDosen = $request->query('kd_dosen');
            
            if ($isSudah and !$kdDosen) {
                // filter is_sudah saja
                $filteredIsSudah = filter_var($isSudah, FILTER_VALIDATE_BOOLEAN);
                $antrianTamu = Tamu::where('is_sudah', $filteredIsSudah)->get();
            } else if ($kdDosen and !$isSudah) {
                // filter kd_dosen saja
                $antrianTamu = Tamu::where('kd_dosen', $kdDosen)->get();
            } else if ($isSudah and $kdDosen) {
                // filter is_sudah dan kd_dosen
                $filteredIsSudah = filter_var($isSudah, FILTER_VALIDATE_BOOLEAN);
                $antrianTamu = Tamu::where('is_sudah', $filteredIsSudah)
                    ->where('kd_dosen', $kdDosen)
                    ->get();
            } else {
                // tanpa filter dan jika filter tidak sesuai
                $antrianTamu = Tamu::orderBy('created_at', 'DESC')->get();
            }

            return $this->successfulResponseJSON([
                'list_antrian' => $antrianTamu
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
    
    public function getAntrianTamu($tamuId) {
        try {
            if ($tamuId) {
                $tamu = Tamu::where('tamu_id', (int) $tamuId)->first();

                if ($tamu) {
                    return $this->successfulResponseJSON([
                        'antrian' => $tamu
                    ]);
                }
            }

            return $this->failedResponseJSON('Antrian tamu tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function deleteAntrianTamu(Request $request) {
        try {
            $request->validate([
                'tamu_id' => 'required|integer'
            ]);

            $tamu = Tamu::where('tamu_id', $request->tamu_id)->first();

            if (!$tamu) {
                return $this->failedResponseJSON('Antrian tamu tidak ditemukan', 404);
            }

            DB::beginTransaction();
            $delete = Tamu::where('tamu_id', $request->tamu_id)->delete();

            if ($delete) {
                DB::commit();
                return $this->successfulResponseJSONV2('Antrian tamu berhasil dihapus');
            }

            DB::rollBack();
            return $this->failedResponseJSON('Antrian tamu gagal dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function addAntrianTamu(Request $request) {
        try {
            $request->validate([
                'nama' => 'required|string',
                'alamat' => 'required|string',
                'pihak_tujuan' => 'required|string',
                'kd_dosen' => 'required|string',
                'tgl' => 'required|string',
                'keperluan' => 'required|string'
            ]);

            $dosen = Dosen::where('kd_dosen', strtoupper($request->kd_dosen))->first();

            if (!$dosen) {
                return $this->failedResponseJSON('Dosen tidak ditemukan', 404);
            }

            if ($dosen) {    
                DB::beginTransaction();
                $insert = Tamu::insert([
                    'nama' => strtoupper($request->nama),
                    'alamat' => $request->alamat,
                    'pihak_tujuan' => strtoupper($request->pihak_tujuan),
                    'dosen_id' => $dosen['dosen_id'],
                    'kd_dosen' => $dosen['kd_dosen'],
                    'tgl' => Carbon::createFromFormat('d-m-Y', $request->tgl),
                    'keperluan' => $request->keperluan,
                    'created_at' => Carbon::now()
                ]);
                
                if ($insert) {
                    DB::commit();
                    return $this->successfulResponseJSONV2('Antrian tamu berhasil ditambahkan', 201);
                }
            }

            DB::rollBack();
            return $this->failedResponseJSON('Antrian tamu gagal ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function updateAntrianTamu(Request $request) {
        try {
            $request->validate([
                'tamu_id' => 'required|integer'
            ]);

            $tamu = Tamu::where('tamu_id', $request->tamu_id)->first();

            if ($tamu) {
                $request->validate([
                    'nama' => 'required|string',
                    'alamat' => 'required|string',
                    'pihak_tujuan' => 'required|string',
                    'kd_dosen' => 'required|string',
                    'tgl' => 'required|string',
                    'keperluan' => 'required|string'
                ]);

                $dosen = Dosen::where('kd_dosen', $request->kd_dosen)->first();

                if (!$dosen) {
                    return $this->failedResponseJSON('Dosen tidak ditemukan', 404);
                }

                $data = [
                    'nama' => strtoupper($request->nama),
                    'alamat' => $request->alamat,
                    'pihak_tujuan' => $dosen['nm_dosen'],
                    'kd_dosen' => $dosen['kd_dosen'],
                    'dosen_id' => $dosen['dosen_id'],
                    'tgl' => Carbon::createFromFormat('d-m-Y', $request->tgl),
                    'keperluan' => $request->keperluan,
                    'created_at' => Carbon::now()
                ];

                DB::beginTransaction();
                $update = Tamu::where('tamu_id', $request->tamu_id)->update($data);

                if ($update) {
                    DB::commit();
                    return $this->successfulResponseJSONV2('Antrian tamu berhasil diperbarui');
                }

                DB::rollBack();
                return $this->failedResponseJSON('Antrian tamu gagal diperbarui');
            }

            return $this->failedResponseJSON('Antrian tamu tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }
}
