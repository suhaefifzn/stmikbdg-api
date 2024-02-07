<?php

namespace App\Http\Controllers\MatKul;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Exception
use App\Exceptions\ErrorHandler;

// ? Models - view
use App\Models\MatKul\MatKulView;
use App\Models\TahunAjaranView;
use App\Models\KurikulumView;

class MatKulController extends Controller
{
    public function getMataKuliah(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'tahun_ajaran' => 'required',
                'semester' => 'required'
            ]);

            if (!auth()->user()->is_dosen) {
                // * user adalah mahasiswa

                $tahunAjaran = TahunAjaranView::getTahunAjaran(
                    $this->getUserAuth(),
                    $validatedData['tahun_ajaran'],
                );

                // get kurikulum
                $kurikulum = KurikulumView::where('jur_id', $tahunAjaran['jur_id'])
                    ->where('tahun', $tahunAjaran['tahun'])
                    ->first();

                if ($kurikulum) {
                    // get mata kuliah
                    $matkul = MatKulView::where('kur_id', $kurikulum['kur_id'])
                        ->where('semester', $validatedData['semester'])
                        ->get();

                    return $this->successfulResponseJSON([
                        'semester' => $validatedData['semester'],
                        'matkul' => $matkul,
                        'total_matkul' => count($matkul),
                    ]);
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Tahun ajaran pada daftar kurikulum tidak ditemukan',
                    ]);
                }
            }
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
