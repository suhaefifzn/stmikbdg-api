<?php

namespace App\Http\Controllers\KRS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Exception
use App\Exceptions\ErrorHandler;

// ? Models - view
use App\Models\KRS\MatKulView;

class MatKulController extends Controller
{
    public function getMataKuliah(Request $request)
    {
        try {
            $isDosen = auth()->user()->is_dosen;
            $filter['semester'] = $request->query('semester')
                        ? $request->query('semester')
                        : null;

            if ($isDosen) {
                return self::getMataKuliahByDosen($filter);
            }

            // user adalah mahasiswa
            return self::getMataKuliahByMahasiswa($filter);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getMataKuliahByDosen($filter) {
        // TODO: atur response list mata kuliah jika dosen yang hit endpoint
        return response()->json([
            'message' => 'Get matakuliah untuk dosen belum difungsikan'
        ], 200);
    }

    public function getMataKuliahByMahasiswa($filter) {
        $user = $this->getUserAuth();
        $filter['jur_id'] = $user['jur_id'];
        $mataKuliah = MatKulView::getMatkul($filter);

        // hitung setiap nilai mk
        $countNilaiAkhir = 0;
        $totalNilaiAkhir = 0;
        $totalSKS = 0;

        foreach ($mataKuliah as $mk) {
            $nilaiAkhir = $mk->nilaiAkhir()
                            ->where('mhs_id', $user['mhs_id'])
                            ->first();

            if ($nilaiAkhir) {
                $mk['nilai_akhir'] = [
                    'nilai' => $nilaiAkhir['nilai'],
                    'mutu' => $nilaiAkhir['mutu']
                ];
                $totalSKS += (int) $mk['sks'];
                $totalNilaiAkhir += (int) $nilaiAkhir['mutu'];
                $countNilaiAkhir++;
            } else {
                $mk['nilai_akhir'] = null;
            }
        }

        // hitung ipk - rata-rata nilai
        $averageNilaiAkhir = $countNilaiAkhir > 0
                                ? (float) $totalNilaiAkhir/$countNilaiAkhir
                                : null;
        $ipk = $averageNilaiAkhir
                    ? number_format($averageNilaiAkhir, 3, '.', '')
                    : null;

        return $this->successfulResponseJSON([
            'ipk' => $ipk,
            'total_sks_dipilih' => $totalSKS,
            'mata_kuliah' => $mataKuliah,
        ]);
    }
}
