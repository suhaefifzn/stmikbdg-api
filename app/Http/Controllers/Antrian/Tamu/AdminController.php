<?php

namespace App\Http\Controllers\Antrian\Tamu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

// ? Models - Tables
use App\Models\Antrian\Tamu;

class AdminController extends Controller
{
    public function getAllAntrianTamu() {
        try {
            $allTamu = Tamu::orderBy('created_at', 'DESC')->get();

            return $this->successfulResponseJSON([
                'list_antrian' => $allTamu
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
                'tgl' => 'required|string',
                'keperluan' => 'required|string'
            ]);

            DB::beginTransaction();
            $insert = Tamu::insert([
                'nama' => strtoupper($request->nama),
                'alamat' => $request->alamat,
                'pihak_tujuan' => strtoupper($request->pihak_tujuan),
                'tgl' => Carbon::createFromFormat('d-m-Y', $request->tgl),
                'keperluan' => $request->keperluan,
                'created_at' => Carbon::now()
            ]);

            if ($insert) {
                DB::commit();
                return $this->successfulResponseJSONV2('Antrian tamu berhasil ditambahkan', 201);
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
                    'tgl' => 'required|string',
                    'keperluan' => 'required|string'
                ]);

                $data = [
                    'nama' => strtoupper($request->nama),
                    'alamat' => $request->alamat,
                    'pihak_tujuan' => strtoupper($request->pihak_tujuan),
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
