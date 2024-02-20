<?php

namespace App\Http\Controllers\KRS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\TahunAjaranController;

// ? Exception
use App\Exceptions\ErrorHandler;

// ? Models - view
use App\Models\KRS\MatKulView;
use App\Models\KurikulumView;
use App\Models\KRS\MatkulDiselenggarakanView;

// ? Models - table
use App\Models\Users\Mahasiswa;

class MatKulController extends Controller
{
    public $user;
    private $currentSemester;

    public function __construct() {
        if (auth()->user()) {
            if (!auth()->user()->is_dosen) {
                $tahunAjaranController = new TahunAjaranController();
                $this->currentSemester = $tahunAjaranController
                    ->getSemesterMahasiswaSekarang()
                    ->getData('data')['data'];
            }

            $this->user = $this->getUserAuth();
        }
    }

    public function getMataKuliah(Request $request) {
        try {
            if (!$request->query('tahun_id')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai query tahun_id pada url diperlukan'
                ], 400);
            }

            $isDosen = auth()->user()->is_dosen;
            $filter['tahun_id'] = $request->query('tahun_id');
            $filter['semester'] = $request->query('semester')
                        ? $request->query('semester')
                        : null;

            return self::getMataKuliahByMahasiswa($filter);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getMataKuliahByMahasiswa($filter) {
        // bet mahasiswa ke tabel, untuk dapet krs_id_last
        $mahasiswa = Mahasiswa::where('mhs_id', $this->user['mhs_id'])->first();

        // buat filter untuk kurikulum aktif
        $filter['jur_id'] = $this->user['jur_id'];
        $filter['angkatan'] = $this->user['angkatan'];
        $kurikulum = KurikulumView::getKurikulumMahasiswa($filter);

        /**
         * get matakuliah diselenggarakan dan gabunggkan
         * dengan matakuliah di view mata kuliah
         */
        $filter['kur_id'] = $kurikulum['kur_id'];
        $matkulDiselenggarakan = MatkulDiselenggarakanView::getMatkulDiselenggarakan($filter);
        $filter['smt'] = $matkulDiselenggarakan[0]['smt'];
        $matakuliah = MatKulView::getMatkul($filter);

        foreach ($matkulDiselenggarakan as $item) {
            $listUniqueMatkul = $matakuliah->filter(function ($mk) use ($item) {
                return $mk['mk_id'] !== $item['mk_id'];
            });
        }

        $mergedMatkul = $matkulDiselenggarakan->concat($listUniqueMatkul)->sortBy('semester');

        if ($filter['semester']) {
            $listMatkul = $mergedMatkul->filter(function ($item) use ($filter) {
                return $item['semester'] == $filter['semester'];
            });
        } else {
            $listMatkul =$mergedMatkul;
        }

        // initial value
        $totalSemuaSKS = 0;
        $totalSemuaIPK = 0;
        $countIPKPerSemester = 0;

        if (count($listMatkul) > 0) {
            // grouping per semester
            foreach ($listMatkul->all() as $mk) {
                $semester = $mk['semester'];
                $nilaiAkhir = $mk->nilaiAkhir()
                                ->where('mhs_id', $this->user['mhs_id'])
                                ->first();

                if ($nilaiAkhir) {
                    $mk['nilai_akhir'] = [
                        'nilai' => $nilaiAkhir['nilai'],
                        'mutu' => $nilaiAkhir['mutu']
                    ];
                } else {
                    $mk['nilai_akhir'] = null;
                }

                $latestKRS = $mahasiswa->krs()->first();
                $kdMK = $mk['kd_mk'];

                $isKrsMatkul = count($latestKRS->krsMatkul()
                                    ->where('mk_id', $mk['mk_id'])
                                    ->get()) > 0 ?? false;

                $mk['krs'] = [
                    'is_aktif' => $mk['smt'] === $this->currentSemester['smt'] ?? false,
                    'is_checked' => $isKrsMatkul,
                ];

                // mk pilihan
                if (strpos($kdMK, 'P-') === 0) {
                    $mk['krs'] = [
                        'is_aktif' => false,
                        'is_checked' => false,
                    ];
                } else {
                    $mk['krs'] = [
                        'is_aktif' => $mk['smt'] === $this->currentSemester['smt'] ?? false,
                        'is_checked' => is_null($latestKRS
                                                    ->krsMatkul()
                                                    ->where('mk_id', $mk['mk_id'])
                                                    ->first())
                                                    ? false
                                                    : true,
                    ];
                }

                $tempMatkul[$semester]['semester'] = $semester;
                $tempMatkul[$semester]['mata_kuliah'][] = $mk;
            }

            // hitung ipk dan total sks yang dipilih tiap semester
            foreach ($tempMatkul as $index => $item) {
                // initial value
                $countNilaiAkhir = 0;
                $totalNilaiAkhirSemester = 0;
                $totalSksDipilihDisemester = 0;

                foreach ($item['mata_kuliah'] as $mk) {
                    if (!is_null($mk['nilai_akhir'])) {
                        $totalNilaiAkhirSemester += (int) $mk['nilai_akhir']['mutu'];
                        $totalSksDipilihDisemester += (int) $mk['sks'];
                        $countNilaiAkhir++;
                    }
                }

                // hitung ipk - rata-rata nilai
                $averageNilaiAkhir = $countNilaiAkhir > 0
                                        ? (float) $totalNilaiAkhirSemester/$countNilaiAkhir
                                        : null;
                $ipk = $averageNilaiAkhir
                            ? number_format($averageNilaiAkhir, 3, '.', '')
                            : null;

                $tempMatkul[$index]['ipk'] = (float) $ipk;
                $tempMatkul[$index]['ipk_per_semester_dari_total_sks'] = $totalSksDipilihDisemester;

                // hitung sks dan ipk menyeluruh
                $totalSemuaSKS += $tempMatkul[$index]['ipk_per_semester_dari_total_sks'];

                if ($tempMatkul[$index]['ipk'] > 0) {
                    $totalSemuaIPK += (float) $tempMatkul[$index]['ipk'];
                    $countIPKPerSemester++;
                }

                // menentukan urutan response
                $desiredOrder = ['ipk', 'semester', 'ipk_per_semester_dari_total_sks', 'mata_kuliah'];
                $orderedData = array_replace(array_flip($desiredOrder), $tempMatkul[$index]);
                $tempMatkul[$index] = $orderedData;
            }
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Tidak ada matakuliah ditemukan pada semester ' . $filter['semester']
            ], 404);
        }

        // set response paling atas
        if (!$filter['semester']) {
            $response['total_semua_ipk'] = (float) number_format(
                (float) ($totalSemuaIPK / $countIPKPerSemester), 3, '.'. ''
            );
            $response['total_semua_sks_dipilih'] = $totalSemuaSKS;
        }

        $response['matkul_per_semester'] = array_values($tempMatkul);

        return $this->successfulResponseJSON([ $response ]);
    }
}
