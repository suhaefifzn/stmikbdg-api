<?php

namespace App\Http\Controllers\Kuliah;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// ? Models - view
use App\Models\KelasKuliah\KelasKuliahJoinView;
use App\Models\TahunAjaranView;
use App\Models\KelasKuliah\JadwalView;

// ? Models - table
use App\Models\KelasKuliah\Pertemuan;
use App\Models\KRS\KRS;
use App\Models\KRS\KRSMatkul;

class KelasKuliahController extends Controller {
    public function getKelasKuliahByDosen(Request $request) {
        try {
            $filterHari = $request->query('hari');
            $dosen = $this->getUserAuth();
            $allTahunAjaranAktif = TahunAjaranView::all();

            // get all kelas kuliah by tahun ajaran aktif and dosen id
            $kelasKuliah = [];

            foreach ($allTahunAjaranAktif as $index => $item) {
                $kelasKuliah[$index] = KelasKuliahJoinView::getKelasKuliahByDosen($item['tahun_id'], $dosen['dosen_id']);
            }

            $kelasKuliah = collect($kelasKuliah)->flatten();

            // cek jika ada kelas yang dijoin
            // $kelasKuliah = self::getParentJoinKelasIdArr($kelasKuliah);

            // get setiap jadwal
            foreach ($kelasKuliah as $index => $item) {
                if ($item['kjoin_kelas']) {
                    $jadwal = JadwalView::getJadwalKelasKuliah($item['join_kelas_kuliah_id'], $dosen['dosen_id'], true);
                } else {
                    $jadwal = JadwalView::getJadwalKelasKuliah($item['kelas_kuliah_id'], $dosen['dosen_id'], true);
                }

                $kelasKuliah[$index] = self::setKelasKuliahAndJadwalProperties($item, $jadwal);
            }

            // urutkan berdasarkan nama hari, Senin, Selasa, ... Minggu, Unknown
            $orderedKelasKuliahByNamaHari = self::orderingKelasKuliahByNamaHari($kelasKuliah);

            // terdapat query 'hari'
            if ($filterHari) {
                    return self::filterKelasKuliahByHari($orderedKelasKuliahByNamaHari, $filterHari);
            }

            // semua jadwal
            return $this->successfulResponseJSON([
                'kelas_kuliah' => $orderedKelasKuliahByNamaHari
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getKelasKuliahByMahasiswa(Request $request) {
        /**
         * Note:
         * - Jika kelas_dibuka true, maka mahasiswa dapat mengirim pin presensi
         */
        try {
            $filterHari = $request->query('hari');
            $mahasiswa = $this->getUserAuth();
            $tahunAjaranAktif = TahunAjaranView::getTahunAjaran($mahasiswa);
            $lastKRS = KRS::where('tahun_id', $tahunAjaranAktif['tahun_id'])
                ->where('mhs_id', $mahasiswa['mhs_id'])
                ->first();

            if ($lastKRS) {
                if ($lastKRS['sts_krs'] === 'S') {
                    $krsMatkul = KRSMatkul::getKRSMatkulWithKelasKuliah($lastKRS['krs_id'])->toArray();
                    $kelasKuliah = array_map(function ($item) {
                        return $item['kelas_kuliah_join'];
                    }, $krsMatkul);

                    foreach ($kelasKuliah as $index => $item) {
                        $jadwal = JadwalView::getJadwalKelasKuliah($item['kelas_kuliah_id'], $mahasiswa['mhs_id'], false);
                        $kelasKuliah[$index] = self::setKelasKuliahAndJadwalProperties($item, $jadwal);
                    }

                    // urutkan berdasarkan nama hari, Senin, Selasa, ... Minggu, Unknown
                    $orderedKelasKuliahByNamaHari = self::orderingKelasKuliahByNamaHari($kelasKuliah);

                    // terdapat query 'hari'
                    if ($filterHari) {
                        return self::filterKelasKuliahByHari($orderedKelasKuliahByNamaHari, $filterHari);
                    }

                    // semua jadwal
                    return $this->successfulResponseJSON([
                        'kelas_kuliah' => $orderedKelasKuliahByNamaHari
                    ]);
                }
            }

            return $this->successfulResponseJSON([
                'kelas_kuliah' => null
            ], null, 204);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function getParentJoinKelasIdArr($kelasKuliah) {
        $parentJoinKelasKuliahIdArr = array_values($kelasKuliah->pluck('join_kelas_kuliah_id')->filter()->toArray());

        if (count($parentJoinKelasKuliahIdArr) > 0) {
            $kelasKuliah = array_values(array_filter($kelasKuliah->toArray(), function ($item) use ($parentJoinKelasKuliahIdArr) {
                return !in_array($item['kelas_kuliah_id'], $parentJoinKelasKuliahIdArr);
            }));

            return $kelasKuliah;
        }

        return $kelasKuliah;
    }

    private function filterKelasKuliahByHari($kelasKuliah, $hari) {
        $ucHari = ucfirst(strtolower($hari));

        return $this->successfulResponseJSON([
            'kelas_kuliah' => [
                $ucHari => $kelasKuliah[$ucHari] ?? [],
            ]
        ]);
    }

    private function setKelasKuliahAndJadwalProperties($objKelasKuliah, $objJadwal) {
        if ($objKelasKuliah['join_jur']) {
            $objKelasKuliah['join_jur'] = trim($objKelasKuliah['join_jur']);
        }

        $objKelasKuliah['jadwal'] = $objJadwal->exists() ? $objJadwal : null;

        if ($objJadwal->exists()) {
            // format ke waktu lokal
            $carbonDate = Carbon::parse($objJadwal['tanggal']);
            $carbonDate->setLocale('id');

            $objKelasKuliah['jadwal']['kd_ruang'] = trim($objJadwal['kd_ruang']);
            $objKelasKuliah['jadwal']['nm_hari'] = $carbonDate->dayName;
            $objKelasKuliah['jadwal']['tanggal_lokal'] = $carbonDate->isoFormat('D MMMM Y');
        }

        $objKelasKuliah['dosen'] = self::trimNamaDanGelarDosen($objKelasKuliah['dosen']);

        /**
         * Untuk buka presensi, sementara dulu karena belum ada db
         */
        $objKelasKuliah['kelas_dibuka'] = self::setKelasDibuka($objKelasKuliah);

        return $objKelasKuliah;
    }

    private function orderingKelasKuliahByNamaHari($kelasKuliah) {
        $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'Unknown'];
        $orderedKelasKuliahByNamaHari = [];

        // return $kelasKuliah[0]['jadwal'];

        $groupedKelasKuliahByHari = collect($kelasKuliah)->groupBy(function ($item) {
            return $item['jadwal'] ? $item['jadwal']['nm_hari'] : 'Unknown';
        })->toArray();

        foreach ($groupedKelasKuliahByHari as $index => $item) {
            $orderedKelasKuliahByNamaHari[array_search($index, $urutanHari)] = $item;
        }

        ksort($orderedKelasKuliahByNamaHari);

        // ganti nomor urutan dengan nama hari
        $finalOrderedKelasKuliah = [];
        foreach ($orderedKelasKuliahByNamaHari as $index => $item) {
            $finalOrderedKelasKuliah[$urutanHari[$index]] = $item;
        }

        return $finalOrderedKelasKuliah;
    }

    private function trimNamaDanGelarDosen($objDosen) {
        if ($objDosen) {
            $objDosen['nm_dosen'] = trim($objDosen['nm_dosen']);
            $objDosen['gelar'] = trim($objDosen['gelar']);

            return $objDosen;
        }

        return null;
    }

    private function setKelasDibuka($objKelasKuliah) {
        if ($objKelasKuliah['jadwal']) {
            $pertemuan = Pertemuan::getKelasDibuka($objKelasKuliah);

            if ($pertemuan->exists()) {
                return true;
            }
        }

        return false;
    }
}
