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

            foreach ($listMatkul as $index => $item) {
                /**
                 * Jika kjoin_kelas true dan pengajar_id null
                 * maka dapatkan data pengajar dari join_kelas_kuliah_id
                 */
                if ($item['kelasKuliah'][0]['kjoin_kelas'] and is_null($item['kelasKuliah'][0]['pengajar_id'])) {
                    $joinKelasKuliahId = $item['kelasKuliah'][0]['join_kelas_kuliah_id'];
                    $tempkelasKuliah = KelasKuliahJoinView::where('kelas_kuliah_id', $joinKelasKuliahId)
                        ->select('pengajar_id')->with('dosen')->first();

                    $item['kelasKuliah'][0]['dosen'] = $tempkelasKuliah['dosen'];
                    $listMatkul[$index] = $item;
                }

                /**
                 * ! Kode di bawah ini sementara karena desain db belum ada
                 * Cek Data Kuesioner
                 */

                /**
                 * Hitung total mahasiswa yang mengambil mata kuliahnya
                 */
                $totalMahasiswa = 0;
                $tempJoinedKelasKuliahIds = null;
                $tempAllKelasKuliahIdArr = [];

                if ($item['kelasKuliah'][0]['kjoin_kelas']) {
                    /**
                     * Kelas ($item) dijoin dengan kelas lain
                     */
                    $tempJoinKelasKuliahId = $item['kelasKuliah'][0]['join_kelas_kuliah_id'];
                    $tempJoinKelasKuliahIdArr = KelasKuliahJoinView::where('join_kelas_kuliah_id', $tempJoinKelasKuliahId)
                        ->get()->pluck('kelas_kuliah_id')->flatten()->toArray();

                    array_push($tempAllKelasKuliahIdArr, $tempJoinKelasKuliahId);
                    $tempAllKelasKuliahIdArr = array_merge($tempAllKelasKuliahIdArr, $tempJoinKelasKuliahIdArr);

                    $tempJoinedKelasKuliahIds = implode('-', $tempJoinKelasKuliahIdArr);

                    // count total mahasiswa
                    $totalMahasiswa = KRSMatkul::whereIn('kelas_kuliah_id', $tempAllKelasKuliahIdArr)->get()->count();
                } else {
                    $totalMahasiswa = KRSMatkul::where('kelas_kuliah_id', $item['kelasKuliah'][0]['kelas_kuliah_id'])
                        ->get()->count();
                }

                $item['kuesioner'] = [
                    'total_mahasiswa' => $totalMahasiswa,
                    'joined_kelas_kuliah_ids' => $tempJoinedKelasKuliahIds,
                    'all_kelas_kuliah_id' => $tempAllKelasKuliahIdArr,
                ];

                $listMatkul[$index] = $item;
            }

            return $this->successfulResponseJSON([
                'mata_kuliah' => $listMatkul,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
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
