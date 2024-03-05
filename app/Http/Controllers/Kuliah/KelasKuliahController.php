<?php

namespace App\Http\Controllers\Kuliah;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// ? Models - view
use App\Models\KelasKuliah\KelasKuliahView;
use App\Models\KRS\MatKulView;
use App\Models\TahunAjaranView;
use App\Models\KelasKuliah\JadwalView;
use App\Models\KRS\KRS;
use App\Models\KRS\KRSMatkul;

class KelasKuliahController extends Controller
{
    public function getKelasKuliahByDosen(Request $request) {
        try {
            /**
             * Alur sekarang:
             * - Get all tahun ajaran aktif
             * - Get semua matkul di kelas kuliah berdasarkan tahun ajaran aktif dan dosen id
             * - Grouping kelas kuliah berdasarkan hari
             * - Get detail masing-masing jadwal dan matkul untuk kelas kuliah
             */
            $filterHari = $request->query('hari');
            $dosen = $this->getUserAuth();
            $tempTahunAjaran = TahunAjaranView::all();

            // get all kelas kuliah by tahun ajaran aktif and dosen id
            $tempKelasKuliah = [];
            foreach ($tempTahunAjaran as $index => $item) {
                $tempKelasKuliah[$index] = KelasKuliahView::where('tahun_id', $item['tahun_id'])
                    ->where('pengajar_id', $dosen['dosen_id'])
                    ->orderBy('kelas_kuliah_id', 'DESC')
                    ->get();
            }

            // buat menjadi array satu dimensi
            $tempKelasKuliah = collect($tempKelasKuliah)->flatten()->toArray();

            // grouping kelas kuliah berdasarkan hari
            $kelasKuliah = [];
            $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu'];
            foreach ($tempKelasKuliah as $item) {
                // bersihin white space di tiap sisi nm_dosen
                $item['nm_dosen'] = trim($item['nm_dosen']);

                // detail matkul
                $matkul = MatKulView::where('mk_id', $item['mk_id'])
                    ->select('kd_mk', 'nm_mk', 'semester', 'nm_jurusan', 'kd_kur')
                    ->first();

                // tambahin ke item dan bersihin white space di tiap sisi
                $item['detail_matkul'] = [
                    'kd_mk' => trim($matkul['kd_mk']),
                    'nm_mk' => trim($matkul['nm_mk']),
                    'nm_jurusan' => trim($matkul['nm_jurusan']),
                    'kd_kur' => trim($matkul['kd_kur']),
                ];

                // detail jadwal
                $jadwal = JadwalView::where('kelas_kuliah_id', $item['kelas_kuliah_id'])
                    ->select('tanggal', 'jns_pert', 'jam', 'kd_ruang', 'n_akhir', 'kjoin_kelas')
                    ->first();
                $item['detail_jadwal'] = $jadwal;

                // bersihin white space di tiap sisi
                if ($item['detail_jadwal']) {
                    $item['detail_jadwal']['kd_ruang'] = trim($jadwal['kd_ruang']);
                }

                if ($item['jadwal_kuliah1'] || $item['detail_jadwal']) {
                    // ambil nama hari
                    $namaHari = '';
                    if ($item['detail_jadwal']['tanggal']) {
                        $carbonDate = Carbon::parse($item['detail_jadwal']['tanggal']);
                        $carbonDate->setLocale('id');

                        if ($carbonDate->dayName === 'Jumat') {
                            $namaHari = 'Jum\'at';
                        } else {
                            $namaHari = $carbonDate->dayName;
                        }
                    } else {
                        $namaHari = explode(',', $item['jadwal_kuliah1'])[0];
                    }

                    // grouping by nama hari
                    $kelasKuliah[array_search($namaHari, $urutanHari)][] = $item;
                } else {
                    // belum ada jadwal
                    $kelasKuliah['Unknown'][] = $item;
                }
            }

            // urutkan hasil grouping
            ksort($kelasKuliah);

            // ganti nomor urutan dengan nama hari
            foreach ($kelasKuliah as $index => $item) {
                if ($index !== 'Unknown') {
                    $orderedKelasKuliah[$urutanHari[$index]] = $item;
                } else {
                    $orderedKelasKuliah[$index] = $item;
                }
            }

            // terdapat query 'hari'
            if ($filterHari) {
                $ucHari = ucfirst(strtolower($filterHari));

                return $this->successfulResponseJSON([
                    'kelas_kuliah' => [
                        $ucHari => $orderedKelasKuliah[$ucHari] ?? [],
                    ]
                ]);
            }

            return $this->successfulResponseJSON([
                'kelas_kuliah' => $orderedKelasKuliah,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getKelasKuliahByMahasiswa(Request $request) {
        /**
         * Alur sekarang:
         * - Get kelas kuliah berdasarkan tahun ajaran aktif dan krs mk yang telah disetujui.
         * - Get detail matakuliah dan jadwal.
         * - Grouping berdasarkan hari di nilai jadwal_kuliah1, jika kjoin_true maka grouping
         * berdasarkan nilai hari dari tanggal yang ada di detail_jadwal.
         */
        try {
            $mahasiswa = $this->getUserAuth();
            $tahunAjaranAktif = TahunAjaranView::getTahunAjaran($mahasiswa);
            $currentKRS = KRS::where('tahun_id', $tahunAjaranAktif['tahun_id'])
                ->where('mhs_id', $mahasiswa['mhs_id'])
                ->first();

            if ($currentKRS) {
                if ($currentKRS['sts_krs'] === 'S') {
                    $krsMatkul = KRSMatkul::where('krs_id', $currentKRS['krs_id'])->get();

                    // get jadwal kelas kuliah
                    $tempKelasKuliah = [];
                    foreach ($krsMatkul as $mk) {
                        if ($mk['kelas_kuliah_id']) {
                            $tempKelasKuliah[] = KelasKuliahView::where(
                                    'kelas_kuliah_id', $mk['kelas_kuliah_id']
                                )->first();
                        }
                    }

                    // buat menjadi array satu dimensi
                    $tempKelasKuliah = collect($tempKelasKuliah)->flatten()->toArray();

                    // grouping kelas kuliah berdasarkan hari
                    $kelasKuliah = [];
                    $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu'];

                    foreach ($tempKelasKuliah as $item) {
                        // bersihin white space di tiap sisi nm_dosen
                        $item['nm_dosen'] = trim($item['nm_dosen']);

                        // detail matkul
                        $matkul = MatKulView::where('mk_id', $item['mk_id'])
                            ->select('kd_mk', 'nm_mk', 'semester', 'nm_jurusan', 'kd_kur')
                            ->first();

                        // tambahin ke item dan bersihin white space di tiap sisi
                        $item['detail_matkul'] = [
                            'kd_mk' => trim($matkul['kd_mk']),
                            'nm_mk' => trim($matkul['nm_mk']),
                            'nm_jurusan' => trim($matkul['nm_jurusan']),
                            'kd_kur' => trim($matkul['kd_kur']),
                        ];

                        // detail jadwal
                        $jadwal = JadwalView::where('kelas_kuliah_id', $item['kelas_kuliah_id'])
                            ->where('mhs_id', $mahasiswa['mhs_id'])
                            ->select(
                                'tanggal', 'jns_pert', 'jam', 'kd_ruang', 'n_akhir', 'kjoin_kelas'
                            )->first();

                        $item['detail_jadwal'] = $jadwal;

                        // bersihin white space di tiap sisi
                        if ($item['detail_jadwal']) {
                            $item['detail_jadwal']['kd_ruang'] = trim($jadwal['kd_ruang']);
                        }

                        if ($item['jadwal_kuliah1'] || $item['detail_jadwal']) {
                            // ambil nama hari
                            $namaHari = '';
                            if ($item['detail_jadwal']['tanggal']) {
                                $carbonDate = Carbon::parse($item['detail_jadwal']['tanggal']);
                                $carbonDate->setLocale('id');

                                if ($carbonDate->dayName === 'Jumat') {
                                    $namaHari = 'Jum\'at';
                                } else {
                                    $namaHari = $carbonDate->dayName;
                                }
                            } else {
                                $namaHari = explode(',', $item['jadwal_kuliah1'])[0];
                            }

                            // grouping by nama hari
                            $kelasKuliah[array_search($namaHari, $urutanHari)][] = $item;
                        } else {
                            // belum ada jadwal
                            $kelasKuliah['Unknown'][] = $item;
                        }
                    }

                    // urutkan hasil grouping
                    ksort($kelasKuliah);

                    // ganti nomor urutan dengan nama hari
                    foreach ($kelasKuliah as $index => $item) {
                        if ($index !== 'Unknown') {
                            $orderedKelasKuliah[$urutanHari[$index]] = $item;
                        } else {
                            $orderedKelasKuliah[$index] = $item;
                        }
                    }

                    return $this->successfulResponseJSON([
                        'kelas_kuliah' => $orderedKelasKuliah,
                    ]);
                }

                // KRS ditolak atau masih pengajuan
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Masih dalam proses pengajuan KRS'
                ], 400);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Belum melakukan pengajuan KRS di tahun ajaran sekarang'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
