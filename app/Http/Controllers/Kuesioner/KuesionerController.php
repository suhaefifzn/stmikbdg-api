<?php

namespace App\Http\Controllers\Kuesioner;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models
use App\Models\KelasKuliah\KelasKuliahJoinView;
use App\Models\TahunAjaranView;
use App\Models\KRS\KRS;
use App\Models\KRS\KRSMatkul;
use App\Models\KRS\MatkulDiselenggarakanView;

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
            $tahunIds = $request->query('tahun_ids');
            $jnsMhs = $request->query('jns_mhs');
            $kdKampus = $request->query('kd_kampus');
            $semester = $request->query('semester');

            if (!$tahunIds or !$jnsMhs or !$kdKampus) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Pastikan terdapat nilai tahun_id, jns_mhs, dan kd_kampus pada url sebagai query parameter',
                ], 400);
            }

            $tahunIdArr = explode('-', $tahunIds);
            $listMatkul = MatkulDiselenggarakanView::getMatkulWithKelasKuliah($tahunIdArr, $jnsMhs, $kdKampus, $semester);

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

                    /**
                     * Terdapat kemungkinan dosen mengajar matkul yang sama, di kampus yang sama, di tahun yang sama,
                     * tetapi kelas berbeda.
                     */
                    foreach ($item['kelasKuliah'] as $kelas) {
                        if ($kelas['kjoin_kelas']) {
                            /**
                             * Kelas ($item) dijoin dengan kelas lain
                             */
                            $tempJoinKelasKuliahId = $kelas['join_kelas_kuliah_id'];
                            $tempJoinKelasKuliahIdArr = KelasKuliahJoinView::where('join_kelas_kuliah_id', $tempJoinKelasKuliahId)
                                ->get()->pluck('kelas_kuliah_id')->flatten()->toArray();

                            array_push($tempAllKelasKuliahIdArr, $tempJoinKelasKuliahId);

                            $tempAllKelasKuliahIdArr = array_merge($tempAllKelasKuliahIdArr, $tempJoinKelasKuliahIdArr);
                            $tempJoinedKelasKuliahIds = implode('-', $tempAllKelasKuliahIdArr);

                            // count total mahasiswa
                            $totalMahasiswa = KRSMatkul::getKRSMatkulDisejutuiByKelasKuliahIdArr($tempAllKelasKuliahIdArr)->count();
                        } else {
                            /**
                             * Jika kelas kuliah tidak dijoin
                             * tetapi kelas kuliah menjadi parent atau target join
                             */
                            $tempKelasKuliahIdArr = KelasKuliahJoinView::where('join_kelas_kuliah_id', $kelas['kelas_kuliah_id'])
                                ->get()->pluck('kelas_kuliah_id')->flatten()->toArray();

                            array_push($tempKelasKuliahIdArr, $kelas['kelas_kuliah_id']);
                            $tempAllKelasKuliahIdArr = array_merge($tempAllKelasKuliahIdArr, $tempKelasKuliahIdArr);
                            $tempJoinedKelasKuliahIds = implode('-', $tempAllKelasKuliahIdArr);

                            // count total mahasiswa
                            $totalMahasiswa = KRSMatkul::getKRSMatkulDisejutuiByKelasKuliahIdArr($tempAllKelasKuliahIdArr)->count();
                        }
                    }
                }

                $item['tahun_ids'] = $tahunIds;
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

    private function  newFormattedItemForGetMatkulByTahunAjaran($item, $totalMahasiswa, $kelasKuliahIds) {
        $dataKuesionerArr = [
            'total_mahasiswa' => $totalMahasiswa,
            'kelas_kuliah_ids' => $kelasKuliahIds,
            'tahun_ids' => $item['tahun_ids']
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
            'tahun_id' => $item['tahun_id'],
            'kd_mk' => $item['kd_mk'],
            'nm_mk' => $item['nm_mk'],
            'semester' => $item['semester'],
            'sks' => $item['sks'],
            'kd_kampus' => $item['kd_kampus'],
            'jns_mhs' => $item['jns_mhs'],
            'data_kuesioner' => $dataKuesionerArr,
            'data_dosen' => $dataDosen,

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
