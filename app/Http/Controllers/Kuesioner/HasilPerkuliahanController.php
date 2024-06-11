<?php

namespace App\Http\Controllers\Kuesioner;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - Tables
use App\Models\Kuesioner\KuesionerPerkuliahan;
use App\Models\KRS\KRSMatkul;
use App\Models\Kuesioner\KuesionerPerkuliahanMahasiswa;

// ? Models - Views
use App\Models\TahunAjaranView;
use App\Models\KampusView;
use App\Models\JurusanView;
use App\Models\Kuesioner\JawabanKuesionerPerkuliahanView;
use App\Models\Kuesioner\PertanyaanView;
use App\Models\Kuesioner\PointsView;

class HasilPerkuliahanController extends Controller
{
    public function getListTahunAjaran() {
        try {
            $tahunIdArr = KuesionerPerkuliahan::orderBy('kuesioner_perkuliahan_id', 'DESC')
                ->select('kuesioner_perkuliahan_id', 'tahun_id')
                ->get()
                ->pluck('tahun_id')
                ->toArray();
            $allTahunAjaran = TahunAjaranView::whereIn('tahun_id', $tahunIdArr)->get();

            $filteredTahunAjaran = [];

            foreach ($allTahunAjaran as $index => $item) {
                $jurusan = JurusanView::where('jur_id', $item['jur_id'])->first();
                $kampus = KampusView::where('kd_kampus', $item['kd_kampus'])
                    ->select('kd_kampus', 'lokasi', 'alamat')
                    ->first();

                $tahunAjaran = [
                    'tahun_id' => $item['tahun_id'],
                    'jur_id' => $item['jur_id'],
                    'tahun' => $item['tahun'],
                    'smt' => $item['smt'],
                    'jns_mhs' => $item['jns_mhs'],
                    'kd_kampus' => $item['kd_kampus'],
                    'sts_ta' => 'B',
                    'uraian' => trim($item['uraian']),
                    'ket_smt' => $item['smt'] == 1 ? 'Ganjil'
                        : ($item['smt'] == 2 ? 'Genap' : 'Tambahan'),
                    'ket_jns_mhs' => $item['jns_mhs'] == 'R' ? 'Reguler'
                    : ($item['jns_mhs'] == 'K' ? 'Karyawan' : 'Eksekutif'),
                    'detail_jurusan' => $jurusan,
                    'detail_kampus' => $kampus
                ];

                $filteredTahunAjaran[$index] = $tahunAjaran;
            }

            return $this->successfulResponseJSON([
                'kuesioner_perkuliahan' => [
                    'list_tahun' => $filteredTahunAjaran
                ]
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getListSemester(Request $request) {
        try {
            $tahunId = $request->query('tahun_id');

            if ($tahunId) {
                $allSemester = KuesionerPerkuliahanMahasiswa::where('tahun_id', (int) $tahunId)
                    ->select('tahun_id', 'semester')
                    ->distinct()
                    ->get();

                if (count($allSemester) > 0) {
                    return $this->successfulResponseJSON([
                        'kuesioner_perkuliahan' => [
                            'list_semester' => $allSemester
                        ]
                    ]);
                }
            }

            return $this->failedResponseJSON('Tahun ajaran pada hasil kuesioner tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getListDosen(Request $request) {
        try {
            $tahunId = $request->query('tahun_id');
            $semester = $request->query('semester');

            if ($tahunId and $semester) {
                $allDosen = KuesionerPerkuliahanMahasiswa::where('tahun_id', (int) $tahunId)
                    ->where('semester', (int) $semester)
                    ->select('kuesioner_perkuliahan_mahasiswa_id', 'pengajar_id', 'nm_dosen')
                    ->distinct()
                    ->get();

                if (count($allDosen) > 0) {
                    return $this->successfulResponseJSON([
                        'kuesioner_perkuliahan' => [
                            'list_dosen' => $allDosen
                        ]
                    ]);
                }
            }

            return $this->failedResponseJSON('Tahun ajaran atau semester pada kuesioner perkuliahan tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getListMatkul(Request $request) {
        try {
            $tahunId = $request->query('tahun_id');
            $semester = $request->query('semester');
            $dosenId = $request->query('dosen_id');

            if ($tahunId and $semester and $dosenId) {
                $allMatkul = KuesionerPerkuliahanMahasiswa::where('tahun_id', (int) $tahunId)
                    ->where('semester', (int) $semester)
                    ->where('pengajar_id', (int) $dosenId)
                    ->select('kuesioner_perkuliahan_mahasiswa_id', 'kelas_kuliah_id', 'mk_id', 'semester', 'nm_mk', 'nm_dosen')
                    ->distinct()
                    ->get();

                if (count($allMatkul) > 0) {
                    $tahunAjaran = KuesionerPerkuliahan::where('tahun_id', $tahunId)
                        ->select('tahun_id', 'tahun')
                        ->first();

                    /**
                     * hitung total yang sudah mengisi
                     */
                    foreach ($allMatkul as $index => $item) {
                        $kelasKuliahIdArr = explode('-', $item['kelas_kuliah_id']);
                        $countTotalMahasiswa = KRSMatkul::getKRSMatkulDisejutuiByKelasKuliahIdArr($kelasKuliahIdArr)->count();
                        $countMahasiswaMengisi = KuesionerPerkuliahanMahasiswa::whereIn('kelas_kuliah_id', $kelasKuliahIdArr)->count();
                        $item['kuesioner'] = [
                            'total_mahasiswa' => $countTotalMahasiswa,
                            'total_mahasiswa_mengisi_kuesioner' => $countMahasiswaMengisi,
                        ];

                        $allMatkul[$index] = $item;
                    }

                    return $this->successfulResponseJSON([
                        'kuesioner_perkuliahan' => [
                            'tahun' => $tahunAjaran['tahun'],
                            'list_matkul' => $allMatkul
                        ]
                    ]);
                }
            }

            return $this->failedResponseJSON('Nilai tahun ajaran, semester, atau dosen tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getListMahasiswa(Request $request) {
        try {
            $kelasKuliahId = $request->query('kelas_kuliah_id');
            
            if ($kelasKuliahId) {
                $allMahasiswa = KuesionerPerkuliahanMahasiswa::where('kelas_kuliah_id', (int) $kelasKuliahId)
                    ->select('kuesioner_perkuliahan_mahasiswa_id', 'tahun_id', 'kelas_kuliah_id', 'semester', 'nim', 'nm_mk', 'nm_dosen')
                    ->get();

                if (count($allMahasiswa) > 0) {
                    $tahun = KuesionerPerkuliahan::where('tahun_id', $allMahasiswa[0]['tahun_id'])
                        ->select('tahun_id', 'tahun')
                        ->first();

                    return $this->successfulResponseJSON([
                        'kuesioner_perkuliahan' => [
                            'tahun' => $tahun['tahun'],
                            'list_mahasiswa' => $allMahasiswa
                        ]
                    ]);
                }
            }

            return $this->failedResponseJSON('Kelas kuliah atau matkul tidak ditemukan pada kuesioner', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getJawabanMahasiswa(Request $request) {
        try {
            $kuesionerId = $request->query('kuesioner_perkuliahan_mahasiswa_id');

            if ($kuesionerId) {
                $kuesionerMahasiswa = KuesionerPerkuliahanMahasiswa::where('kuesioner_perkuliahan_mahasiswa_id', (int) $kuesionerId)
                    ->first();
                
                if ($kuesionerMahasiswa) {
                    $tahun = KuesionerPerkuliahan::where('tahun_id', $kuesionerMahasiswa['tahun_id'])
                        ->select('kuesioner_perkuliahan_id', 'tahun_id', 'tahun')
                        ->first();
                    $jawabanKuesioner = self::setJawabanToPertanyaan($kuesionerMahasiswa);

                    return $this->successfulResponseJSON([
                        'kuesioner_perkuliahan' => [
                            'kuesioner_perkuliahan_id' => $tahun['kuesioner_perkuliahan_id'],
                            'kuesioner_perkuliahan_mahasiswa_id' => $kuesionerMahasiswa['kuesioner_perkuliahan_mahasiswa_id'],
                            'tahun' => $tahun['tahun'],
                            'nim' => $kuesionerMahasiswa['nim'],
                            'nm_mk' => $kuesionerMahasiswa['nm_mk'],
                            'nm_dosen' => $kuesionerMahasiswa['nm_dosen'],
                            'semester' => $kuesionerMahasiswa['semester'],
                            'pertanyaan_dan_jawaban' => $jawabanKuesioner
                        ]
                    ]);
                }
            }

            return $this->failedResponseJSON('Nilai kuesioner perkuliahan mahasiswa id tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function setJawabanToPertanyaan($kuesionerMahasiswa)
    {
        $listPertanyaan = PertanyaanView::where('kd_jenis_pertanyaan', 'P')->get();

        foreach ($listPertanyaan as $index => $item) {
            $point = JawabanKuesionerPerkuliahanView::where('kuesioner_perkuliahan_mahasiswa_id', $kuesionerMahasiswa['kuesioner_perkuliahan_mahasiswa_id'])
                ->where('pertanyaan_id', $item['pertanyaan_id'])
                ->select('mutu')
                ->first();

            $jawaban = PointsView::where('mutu', $point['mutu'])->first();
            $item['jawaban'] = $jawaban;
            $listPertanyaan[$index] = $item;
        }

        return $listPertanyaan->groupBy('kelompok');
    }
}
