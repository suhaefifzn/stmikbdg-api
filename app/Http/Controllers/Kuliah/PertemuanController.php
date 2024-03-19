<?php

namespace App\Http\Controllers\Kuliah;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use App\Models\KelasKuliah\JadwalView;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

// ? Models - view
use App\Models\KelasKuliah\KelasKuliahJoinView;

// ? Models - table
use App\Models\KelasKuliah\Pertemuan;
use App\Models\KelasKuliah\Presensi;

class PertemuanController extends Controller {
    public function bukaKelasKuliah(Request $request, $kelasKuliahId) {
        try {
            $dosen = $this->getUserAuth();
            $kelasKuliah = KelasKuliahJoinView::getJoinJurusanData($kelasKuliahId);

            /**
             * Kelas kuliah tidak ditemukan
             */
            if (!$kelasKuliah->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Kelas kuliah id tidak ditemukan',
                ], 400);
            }

            /**
             * Dosen yang membuka kelas haruslah pengajar di kelas tersebut
             */
            if ($kelasKuliah['pengajar_id'] !== $dosen['dosen_id']) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Maaf, kelas kuliah hanya dapat dibuka oleh pengajarnya'
                ], 403);
            }

            $jadwal = JadwalView::getTanggalDanJenisPertemuan($kelasKuliahId);
            $parsedCarbonDate = Carbon::parse($jadwal['tanggal']);
            $currentDate = Carbon::now();

            /**
             * Tanggal kelas akan dibuka tidak sama
             * dengan jadwal yang telah ditentukan
             */
            if (!$currentDate->isSameDay($parsedCarbonDate)) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Tanggal saat ini tidak sesuai dengan jadwal kelas kuliah'
                ], 400);
            }

            /**
             * Jika kelas_kuliah_id dan tanggal yang sama
             * sudah ada pada tabel pertemuan, artinya kelas pernah dibuka
             *
             * bila dosen ingin membuka kembali kelasnya
             * karena alasan seperti terdapat mahasiswa yang hadir di kelas
             * tapi lupa mengisi kehadiran. Maka cukup update nilai kelas_dibuka saja menjadi true
             */
            $kelasPernahDibuka = Pertemuan::cekKelasPernahDibukaInSameDay(
                $kelasKuliah['kelas_kuliah_id'], $kelasKuliah['pengajar_id'], $currentDate->format('Y-m-d')
            );

            // cek kelas join
            $kelasKuliahIdArr = [];

            /**
             * Jika kelas yang dibuka dan dijoin ke kelas lain
             */
            if ($kelasKuliah['kjoin_kelas']) {
                $kelasKuliahId = $kelasKuliah['join_kelas_kuliah_id'];
            }

            /**
             * Kelas-kelas yang dijoin
             */
            $kelasKuliahIdArr = KelasKuliahJoinView::where('join_kelas_kuliah_id', $kelasKuliahId)
                ->pluck('kelas_kuliah_id')->filter()->toArray();

            array_push($kelasKuliahIdArr, $kelasKuliahId);

            if ($kelasPernahDibuka->exists()) {
                Pertemuan::updateKelasDibuka($kelasKuliahIdArr, $kelasKuliah['pengajar_id'], true); // kelas_dibuka = true
            } else {
                /**
                 * Tanggal kelas akan dibuka sesuai tanggal di jadwal
                 */
                $pertemuan = [
                    'kelas_kuliah_id' => $kelasKuliahId,
                    'jns_pert' => $jadwal['jns_pert'],
                    'tanggal' => $jadwal['tanggal'],
                    'jam' => $jadwal['jam'],
                    'kelas_dibuka' => true,
                    'create_time' => Carbon::now(),
                    'dosen_id' => $kelasKuliah['pengajar_id'],
                ];

                self::createDaftarKehadiran($pertemuan, $kelasKuliahId);

                // jika ada kelas dijoin
                if (count($kelasKuliahIdArr) > 0) {
                    foreach ($kelasKuliahIdArr as $item) {
                        $pertemuan['kelas_kuliah_id'] = $item;
                        self::createDaftarKehadiran($pertemuan, $item);
                    }
                }
            }

            $randomPIN = self::generateRandomPIN($kelasKuliahIdArr);

            return $this->successfulResponseJSON([
                'pertemuan' => [
                    'kelas_kuliah_id_dibuka' => implode('-', $kelasKuliahIdArr),
                    'tanggal' => $jadwal['tanggal'],
                    'presensi' => $randomPIN,
                ],
            ]);

        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function tutupKelasKuliah(Request $request, $kelasKuliahId) {
        try {
            $dosen = $this->getUserAuth();
            $kelasKuliah = KelasKuliahJoinView::getJoinJurusanData($kelasKuliahId);

            /**
             * Kelas kuliah tidak ditemukan
             */
            if (!$kelasKuliah->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Kelas kuliah id tidak ditemukan',
                ], 400);
            }

            /**
             * Dosen yang menutup kelas haruslah pengajar di kelas tersebut
             */
            if ($kelasKuliah['pengajar_id'] !== $dosen['dosen_id']) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Maaf, kelas kuliah hanya dapat ditutup oleh pengajarnya'
                ], 403);
            }

            $jadwal = JadwalView::getTanggalDanJenisPertemuan($kelasKuliahId);
            $kelasDibuka = Pertemuan::getLastKelasDibuka(
                $kelasKuliah['kelas_kuliah_id'], $kelasKuliah['pengajar_id'], $jadwal['tanggal']
            );

            if ($kelasDibuka->exists()) {
                self::destroyRandomPIN($kelasKuliah['kelas_kuliah_id']);

                // cek kelas join
                $kelasKuliahIdArr = [];

                /**
                 * Jika kelas yang dibuka dan dijoin ke kelas lain
                 */
                if ($kelasKuliah['kjoin_kelas']) {
                    $kelasKuliahId = $kelasKuliah['join_kelas_kuliah_id'];
                }

                /**
                 * Kelas-kelas yang dijoin
                 */
                $kelasKuliahIdArr = KelasKuliahJoinView::where('join_kelas_kuliah_id', $kelasKuliahId)
                    ->pluck('kelas_kuliah_id')->filter()->toArray();

                array_push($kelasKuliahIdArr, $kelasKuliahId);

                $presensiMahasiswa = Pertemuan::getPertemuanKelasDibukaWithPresensi($kelasKuliahIdArr, $dosen['dosen_id'])
                    ->pluck('presensi')->flatten();
                $jumlahMahasiswa = $presensiMahasiswa->count();
                $jumlahMahasiswaHadir = $presensiMahasiswa->whereNotNull('masuk')->count();
                $jumlahMahasiswaBelumHadir = $presensiMahasiswa->whereNull('masuk')->count();

                Pertemuan::updateKelasDibuka($kelasKuliahIdArr, $kelasKuliah['pengajar_id'], false); // ditutup

                return response()->json([
                    'status' => 'success',
                    'message' => 'Kelas kuliah berhasil ditutup',
                    'data' => [
                        'jumlah_mahasiswa' => $jumlahMahasiswa,
                        'jumlah_mahasiswa_hadir' => $jumlahMahasiswaHadir,
                        'jumlah_mahasiswa_belum_hadir' => $jumlahMahasiswaBelumHadir,
                        'presensi_mahasiswa' => $presensiMahasiswa
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Belum ada kelas kuliah yang dibuka'
                ], 400);
            }
        } catch  (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function generateRandomPIN($kelasKuliahIdArr) {
        $randomPIN = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $implodedKelasId = implode('-', $kelasKuliahIdArr);

        // simpan pin ke cache dengan key kelas kuliah id
        foreach ($kelasKuliahIdArr as $item) {
            $key = (string) $item;
            Cache::put($key, $randomPIN);
        }

        return [
            'qrcode_value' => config('app.url') . 'api/kelas-kuliah/'
                . 'mahasiswa/presensi/qrcode?kelas=' . $implodedKelasId . '&pin=' . $randomPIN,
            'pin' => $randomPIN,
        ];
    }

    private function destroyRandomPIN($kelasKuliahId) {
        $key = (string) $kelasKuliahId;
        Cache::forget($key);
    }

    private function createDaftarKehadiran($pertemuan, $kelasKuliahId) {
        $insertedPertemuanId = Pertemuan::create($pertemuan)->pertemuan_id;
        $tempMahasiswa= JadwalView::getJadwalWithMahasiswa($kelasKuliahId)->toArray();
        $mahasiswa = array_map(function ($item) {
            return $item['mahasiswa'];
        }, $tempMahasiswa);
        $presensi = [];

        foreach ($mahasiswa as $index => $mhs) {
            $presensi[$index]['pertemuan_id'] = $insertedPertemuanId;
            $presensi[$index]['mhs_id'] = $mhs['mhs_id'];
            $presensi[$index]['nim'] = $mhs['nim'];
            $presensi[$index]['nm_mhs'] = $mhs['nm_mhs'];
            $presensi[$index]['pin'] = null;
            $presensi[$index]['masuk'] = null;
        }

        Presensi::insert($presensi);
    }
}
