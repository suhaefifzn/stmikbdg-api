<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorHandler;
use App\Models\JurusanView;
use App\Models\KampusView;
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

    /**
     * Get tahun ajaran aktif untuk si kuesioner
     * Tahun ajaran yang didapat haruslah yang memiliki daftar mata kuliah
     *
     * Digunakan oleh admin
     */
    public function getTahunAjaranAktifForKuesioner() {
        try {
            $tahunAjaranArr = TahunAjaranView::getTahunAjaranWithKRS()->filter(function ($item) {
                if ($item['krs']->count() > 0) {
                    return $item;
                }
            })->flatten();

            $filteredTahunArajanArr = [];

            foreach ($tahunAjaranArr as $item) {
                $dataKampus = KampusView::getDetailKampus($item['kd_kampus']);
                $dataJurusan = JurusanView::where('jur_id', $item['jur_id'])->first();
                $tahunAjaran = [
                    'tahun_id' => $item['tahun_id'],
                    'jur_id' => $item['jur_id'],
                    'tahun' => $item['tahun'],
                    'smt' => $item['smt'],
                    'ket_smt' => $item['smt'] == 1 ? 'Ganjil'
                        : ($item['smt'] == 2 ? 'Genap' : 'Tambahan'),
                    'jns_mhs' => $item['jns_mhs'],
                    'jurusan' => $dataJurusan,
                    'detail_kampus' => $dataKampus
                ];

                array_push($filteredTahunArajanArr, $tahunAjaran);
            }

            return $this->successfulResponseJSON([
                'tahun_ajaran_aktif' => $filteredTahunArajanArr
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
