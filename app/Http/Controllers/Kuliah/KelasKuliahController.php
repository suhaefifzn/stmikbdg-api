<?php

namespace App\Http\Controllers\Kuliah;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - view
use App\Models\KelasKuliah\KelasKuliahView;
use App\Models\TahunAjaranView;

class KelasKuliahController extends Controller
{
    public function getKelasKuliahByDosen(Request $request) {

        try {
            /**
             * Alur sekarang:
             * - Get tahun ajaran aktif by query jns_mhs and kd_kampus
             * - Get semua matkul di kelas kuliah berdasarkan tahun ajaran aktif dan dosen id
             */

             $jnsMhs = $request->query('jns_mhs');
             $kdKampus = $request->query('kd_kampus');
             $dosen = $this->getUserAuth();

             if ($jnsMhs and $kdKampus) {
                $tempTahunAjaran = TahunAjaranView::where('jns_mhs', $jnsMhs)
                    ->where('kd_kampus', $kdKampus)
                    ->where('tgl_kuliah', '!=', null)
                    ->get();

                foreach ($tempTahunAjaran as $index => $item) {
                    $ketSmt = $item['smt'] === 1 ? 'Ganjil'
                        : ($item['smt'] === 2 ? 'Genap' : 'Tambahan');
                    $tempKelasKuliah = KelasKuliahView::where('tahun_id', $item['tahun_id'])
                        ->where('pengajar_id', $dosen['dosen_id'])
                        ->get();
                    $kelasKuliah[$index]['tahun_id'] = $item['tahun_id'];
                    $kelasKuliah[$index]['tahun'] = $item['tahun'];
                    $kelasKuliah[$index]['smt'] = $item['smt'];
                    $kelasKuliah[$index]['ket_smt'] = $ketSmt;
                    $kelasKuliah[$index]['kelas_kuliah'] = $tempKelasKuliah;
                }

                return $this->successfulResponseJSON([
                    'test' => $kelasKuliah,
                ]);
             }

             return response()->json([
                'status' => 'fail',
                'message' => 'Nilai jns_mhs dan kd_kampus diperlukan'
             ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
