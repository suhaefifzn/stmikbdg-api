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

            // ada query tahun dan smt
            if ((!is_null($request->query('tahun'))) and (!is_null($request->query('smt')))) {
                $tahun = $request->query('tahun');
                $smt = $request->query('smt');
                $tahunAjaran = TahunAjaranView::getTahunAjaran($mahasiswa, $tahun, $smt);

                return $this->successfulResponseJSON([
                    'tahun_ajaran' => $tahunAjaran,
                ]);
            }

            // tanpa query
            $tahunAjaran = TahunAjaranView::getTahunAjaran($mahasiswa);

            return $this->successfulResponseJSON([
                'tahun_ajaran' => $tahunAjaran,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
