<?php

namespace App\Http\Controllers\Kuesioner;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - Views
use App\Models\KelasKuliah\KelasKuliahJoinView;
use App\Models\TahunAjaranView;
use App\Models\KRS\MatkulDiselenggarakanView;

// ? Models - Tables
use App\Models\KRS\KRS;
use App\Models\KRS\KRSMatkul;
use App\Models\Kuesioner\KuesionerPerkuliahan;

class KuesionerController extends Controller
{
    /**
     * untuk mahasiswa get daftar mata kuliah
     */
    public function getMatkulByLastKRSMahasiswa() {
        try {
            $mahasiswa = $this->getUserAuth();
            $tahunAjaran = TahunAjaranView::getTahunAjaran($mahasiswa);
            $lastKRS = KRS::getLastKRSDisetujuiWithMatkul($tahunAjaran['tahun_id'], $mahasiswa['mhs_id']);

            /**
             * cek kuesioner tersedia di tahun ajarannya
             */
            $kuesioner = KuesionerPerkuliahan::where('tahun_id', $tahunAjaran['tahun_id'])->first();
            $isKuesionerOpen = $kuesioner ? true : false;

            if ($lastKRS->exists()) {
                // get pengajar melalui kelas kuliah id
                $krsMatkul = [];

                foreach ($lastKRS['krsMatkul'] as $index => $item) {
                    $kelasKuliah = $item->kelasKuliahJoin()->first();

                    if ($kelasKuliah['kjoin_kelas']) {
                        $kelasKuliahJoin = KelasKuliahJoinView::where(
                            'kelas_kuliah_id', $kelasKuliah['join_kelas_kuliah_id']
                            )->first();
                        $dosen = $kelasKuliahJoin->dosen()
                            ->select('dosen_id', 'nm_dosen', 'gelar')
                            ->first();
                        $trimmedDosen = self::trimNamaDosen($dosen);
                        $item['pengajar'] = $dosen;
                    } else {
                        $dosen = $kelasKuliah->dosen()
                            ->select('dosen_id', 'nm_dosen', 'gelar')
                            ->first();
                        $trimmedDosen = self::trimNamaDosen($dosen);
                        $item['pengajar'] = $trimmedDosen;
                    }

                    $item['kuesioner_open'] = $isKuesionerOpen;

                    // sementara hingga ada db
                    $item['sts_isi_kuesioner'] = false;

                    $krsMatkul[$index] = $item;
                }

                return $this->successfulResponseJSON([
                    'tahun_id' => $tahunAjaran['tahun_id'],
                    'tahun' => $tahunAjaran['tahun'],
                    'list_matkul' => $krsMatkul,
                ]);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Belum mengajukan atau KRS belum disetujui untuk tahun ajaran aktif saat ini'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * untuk admin get daftar mata kuliah
     */
    public function getMatkulByTahunAjaran(Request $request) {
        try {
            $tahunId = $request->query('tahun_id');
            $jnsMhs = $request->query('jns_mhs');
            $kdKampus = $request->query('kd_kampus');
            $semester = $request->query('semester');

            if (!$tahunId or !$jnsMhs or !$kdKampus) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Pastikan terdapat nilai tahun_id, jns_mhs, dan kd_kampus pada url sebagai query parameter',
                ], 400);
            }

            $listMatkul = MatkulDiselenggarakanView::getMatkulWithKelasKuliah($tahunId, $jnsMhs, $kdKampus, $semester);

            foreach ($listMatkul as $key => $item) {
                /**
                 * Jika kjoin_kelas true dan pengajar_id null
                 * maka dapatkan data pengajar dari join_kelas_kuliah_id
                 */
                if ($item['kelasKuliah'][0]['kjoin_kelas'] and is_null($item['kelasKuliah'][0]['pengajar_id'])) {
                    $joinKelasKuliahId = $item['kelasKuliah'][0]['join_kelas_kuliah_id'];
                    $tempkelasKuliah = KelasKuliahJoinView::where('kelas_kuliah_id', $joinKelasKuliahId)
                        ->select('pengajar_id')->with('dosen')->first();

                    $item['kelasKuliah'][0]['dosen'] = $tempkelasKuliah['dosen'];
                    $listMatkul[$key] = $item;
                }

                /**
                 * ! Kode di bawah ini sementara karena desain db belum ada
                 * Cek Data Kuesioner
                 */

                if (count($item['kelasKuliah']) > 0) {
                    /**
                     * Hitung total mahasiswa yang mengambil mata kuliahnya
                     */
                    $totalMahasiswa = 0;
                    $tempJoinedKelasKuliahIds = null;
                    $tempAllKelasKuliahIdArr = [];
                    $tempKelasKuliahArr = [];

                    /**
                     * Jika terdapat lebih dari satu kelas dan
                     * jns_mhs dan kd_kampus pada kelasnya sama
                     * tetapi kelas_kuliahnya berbeda, buat menjadi satu
                     */
                    foreach ($item['kelasKuliah'] as $kelas) {
                        array_push($tempAllKelasKuliahIdArr, $kelas['kelas_kuliah_id']);
                        array_push($tempKelasKuliahArr, $kelas['kelas_kuliah']);
                        $tempJoinedKelasKuliahIds = implode('-', $tempAllKelasKuliahIdArr);
                    }

                    // count total mahasiswa
                    $totalMahasiswa = KRSMatkul::getKRSMatkulDisejutuiByKelasKuliahIdArr($tempAllKelasKuliahIdArr)->count();
                }

                $item['kelas_kuliah'] = implode('-', $tempKelasKuliahArr);
                $item['tahun_id'] = $tahunId;
                $newFormattedItem = self::newFormattedItemForGetMatkulByTahunAjaran(
                    $item, $totalMahasiswa, $tempJoinedKelasKuliahIds
                );

                $listMatkul[$key] = $newFormattedItem;
            }

            return $this->successfulResponseJSON([
                'mata_kuliah' => $listMatkul,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * admin buka kuesioner perkuliahan
     */
    public function openKuesionerPerkuliahan(Request $request) {
        try {
            $request->validate([
                'tahun_id' => 'required',
            ]);

            /**
             * cek dan get data tahun_id
             */
            $tahunAjaran = TahunAjaranView::where('tahun_id', (integer) $request->tahun_id)->first();

            if (!$tahunAjaran) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai tahun_id tidak ditemukan'
                ], 404);
            }

            $data = [
                'tahun_id' => $tahunAjaran['tahun_id'],
                'jur_id' => $tahunAjaran['jur_id'],
                'smt' => $tahunAjaran['smt'],
                'tahun' => $tahunAjaran['tahun'],
                'jns_mhs' => $tahunAjaran['jns_mhs'],
                'kd_kampus' => $tahunAjaran['kd_kampus'],
            ];
            $ketSmt = $tahunAjaran['smt'] == 1 ? 'Ganjil'
                : ($tahunAjaran['smt'] == 2 ? 'Genap' : 'Tambahan');

            KuesionerPerkuliahan::insert($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Kuesioner perkuliahan untuk tahun ajaran '
                    . $tahunAjaran['tahun'] . ' semester '
                    . $ketSmt . ' berhasil dibuka',
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function  newFormattedItemForGetMatkulByTahunAjaran($item, $totalMahasiswa, $kelasKuliahIds) {
        $dataKuesionerArr = [
            'tahun_id' => (integer) $item['tahun_id'],
            'kelas_kuliah_ids' => $kelasKuliahIds,
            'total_mahasiswa' => $totalMahasiswa,
            'kelas_kuliah' => $item['kelas_kuliah'],
            'sts_open' => false, // sementara, karena belum ada db
        ];

        $dataDosen = null;
        if (count($item['kelasKuliah']) > 0) {
            if ($item['kelasKuliah'][0]['pengajar_id']) {
                $trimmedDataDosen = self::trimNamaDosen($item['kelasKuliah'][0]['dosen']);
                $dataDosen = [
                    'dosen_id' => $trimmedDataDosen['dosen_id'],
                    'kd_dosen' => $trimmedDataDosen['kd_dosen'],
                    'nm_dosen' => $trimmedDataDosen['nm_dosen'],
                    'gelar' => $trimmedDataDosen['gelar']
                ];
            }
        }

        $newFormattedItem = [
            'mk_id' => $item['mk_id'],
            'kur_id' => $item['kur_id'],
            'jur_id' => $item['jur_id'],
            'tahun_id' => (integer) $item['tahun_id'],
            'kd_mk' => $item['kd_mk'],
            'nm_mk' => $item['nm_mk'],
            'semester' => $item['semester'],
            'sks' => $item['sks'],
            'kd_kampus' => $item['kd_kampus'],
            'jns_mhs' => $item['jns_mhs'],
            'detail_kuesioner' => $dataKuesionerArr,
            'detail_dosen' => $dataDosen,

        ];

        return $newFormattedItem;
    }

    private function trimNamaDosen($dosen) {
        if ($dosen) {
            $dosen['nm_dosen'] = trim($dosen['nm_dosen']);
            $dosen['gelar'] = trim($dosen['gelar']);

            return $dosen;
        }

        return null;
    }
}
