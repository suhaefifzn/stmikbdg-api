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

            /**
             * Grouping berdasarkan jns_mhs dan kd_kampus
             */
            $tempGroupedTahunAjaran = [];
            foreach ($tahunAjaranArr as $item) {
                $key = $item['jns_mhs'];
                $key2 = $item['kd_kampus'];
                $tempGroupedTahunAjaran[$key][$key2][] = $item;
            }

            $groupedTahunAjaran = [];
            foreach ($tempGroupedTahunAjaran as $jnsMhs) {
                foreach ($jnsMhs as $key => $kdKampus) {
                    $tahunIdArr = collect($kdKampus)->pluck('tahun_id')->filter()->toArray();
                    $tahunIds = implode('-', $tahunIdArr);
                    $tempJnsMhs = $kdKampus[0]['jns_mhs'];
                    $tempKdKampus = $kdKampus[0]['kd_kampus'];
                    $tempSmt = $kdKampus[0]['smt'];

                    $formattedItem = [
                        'tahun' => $kdKampus[0]['tahun'],
                        'tahun_ids' => $tahunIds,
                        'jns_mhs' => $tempJnsMhs == 'R' ? 'Reguler'
                            : ($tempJnsMhs == 'E' ? 'Eksekutif'
                                : 'Karyawan'),
                        'sts_ta' => $kdKampus[0]['sts_ta'],
                        'smt' => $tempSmt,
                        'ket_smt' => $tempSmt == 1 ? 'Ganjil'
                            : ($tempSmt == 2 ? 'Genap'
                                : 'Tambahan'),
                        'kd_kampus' => $tempKdKampus,
                        'detail_kampus' => KampusView::getDetailKampus($tempKdKampus)
                    ];

                    array_push($groupedTahunAjaran, $formattedItem);
                }
            }

            return $this->successfulResponseJSON([
                'tahun_ajaran_aktif' => $groupedTahunAjaran
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
