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
}
