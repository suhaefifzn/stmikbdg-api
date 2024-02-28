<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorHandler;
use Illuminate\Http\Request;

// ? Models - view
use App\Models\TahunAjaranView;

class TahunAjaranController extends Controller
{
    public function getTahunAjaran(Request $request)
    {
        try {
            $isDosen = auth()->user()->is_dosen;
            $user = $this->getUserAuth();

            if ($isDosen) {
                // TODO: jika user adalah dosen
                return response()->json([
                    'message' => 'Belum difungsikan'
                ], 200);
            }

            // user adalah mahasiswa
            $mahasiswa = $user;
            $tahunAjaran = TahunAjaranView::getTahunAjaran($mahasiswa);

            return $this->successfulResponseJSON([
                'tahun_ajaran' => $tahunAjaran,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getSemesterMahasiswaSekarang() {
        try {
            $mahasiswa = $this->getUserAuth();
            $tahunAjaran = TahunAjaranView::getTahunAjaran($mahasiswa);
            $gap = $tahunAjaran['tahun'] - $mahasiswa['angkatan'];
            $semester = $tahunAjaran['smt'] === 1
                        ? $gap * 2 + 1
                        : $gap * 2 + 2;

            return $this->successfulResponseJSON([
                'tahun' => $tahunAjaran['tahun'],
                'smt' => $tahunAjaran['smt'],
                'keterangan_smt' => $tahunAjaran['smt'] === 1 ? 'Ganjil' : 'Genap',
                'semester'=> $semester,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getTahunAjaranByQueries(Request $request) {
        if (auth()->user()->is_mhs) {
            return response()->json([
                'status' => 'fail',
                'message'  => 'Forbidden access'
            ], 403);
        }

        try {
            $jurusanId = $request->query('jur_id');
            $jnsMhs = $request->query('jns_mhs');
            $kdKampus = $request->query('kd_kampus');

            if ($jurusanId and $jnsMhs and $kdKampus) {
                $filter = [
                    'jur_id' => $jurusanId,
                    'jns_mhs' => $jnsMhs,
                    'kd_kampus' => $kdKampus,
                ];

                $tahunAjaran = TahunAjaranView::getTahunAjaran($filter);

                return $this->successfulResponseJSON([
                    'tahun_ajaran' => $tahunAjaran,
                ]);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Nilai jur_id, jns_mhs, dan kd_kampus dibutuhkan'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
