<?php

namespace App\Http\Controllers\Kuesioner;

use App\Http\Controllers\Controller;
use App\Exceptions\ErrorHandler;
use Illuminate\Http\Request;

// ? Models - view
use App\Models\KelasKuliah\KelasKuliahView;
use App\Models\KRS\MatKulView;
use App\Models\TahunAjaranView;
use App\Models\Users\DosenView;

class MatkulDiampuController extends Controller
{
    public function getMatkulByDosenIdInKelasKuliah($dosenId) {
        try {
            $mahasiswa = $this->getUserAuth();

            if ($dosenId) {
                $tahunAjaranAktif = TahunAjaranView::getTahunAjaran($mahasiswa);
                $dosenProfile = DosenView::where('dosen_id', (int) $dosenId)->first();

                // filter untuk get matkul diampu berdasarkan matkul yang ada di vkelas_kuliah
                $filter = [
                    'dosen_id' => $dosenId,
                    'jns_mhs' => $mahasiswa['jns_mhs'],
                    'kd_kampus' => $mahasiswa['kd_kampus'],
                    'tahun_id'=> $tahunAjaranAktif['tahun_id'],
                ];
                $allMatkulDiampu = KelasKuliahView::getMatkulDiampuArray($filter);
                $uniqueMatkul = array_values(array_unique($allMatkulDiampu));

                foreach ($uniqueMatkul as $index => $item) {
                    $matkulData = MatKulView::where('mk_id', $item)->first();
                    $completedMatkulDiampu[$index] = [
                        'mk_id' => $matkulData['mk_id'],
                        'nm_mk' => $matkulData['nm_mk'],
                    ];
                }

                return $this->successfulResponseJSON([
                    'dosen' => [
                        'dosen_id' => $dosenProfile['dosen_id'],
                        'nama' => trim($dosenProfile['nm_dosen']),
                        'gelar' => trim($dosenProfile['gelar']),
                        'kd_dosen' => $dosenProfile['kd_dosen'],
                        'matkul_diampu' => $completedMatkulDiampu,
                    ],
                ]);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Nilai dosen_id diperlukan'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
