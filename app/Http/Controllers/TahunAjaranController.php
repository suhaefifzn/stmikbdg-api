<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorHandler;
use Illuminate\Http\Request;

// ? Models - view
use App\Models\TahunAjaranView;
use App\Models\Users\MahasiswaView;

class TahunAjaranController extends Controller
{
    public function getTahunAjaran(Request $request) {
        try {
            $isDosen = auth()->user()->is_dosen;
            $userIdentifier = explode('-', auth()->user()->kd_user)[1];

            // validasi
            $validatedData = $request->validate([
                'tahun_ajaran' => 'required',
            ]);

            if ($isDosen) {
                // TODO: jika user adalah dosen
            }

            // user adalah mahasiswa
            $mahasiswa = MahasiswaView::where('nim', $userIdentifier)->first();
            $tahunAjaran = TahunAjaranView::getTahunAjaran($mahasiswa, $validatedData['tahun_ajaran']);

            return $this->successfulResponseJSON([
                'mahasiswa' => [
                    'nim' => $mahasiswa['nim'],
                    'nama' => $mahasiswa['nm_mhs'],
                ],
                'tahun_ajaran' => $tahunAjaran,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}