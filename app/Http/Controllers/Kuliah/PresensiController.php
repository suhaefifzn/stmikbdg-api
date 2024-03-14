<?php

namespace App\Http\Controllers\Kuliah;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

// ? Models - table
use App\Models\KelasKuliah\Pertemuan;
use App\Models\KelasKuliah\Presensi;
use App\Models\KRS\KRSMatkul;
use App\Models\Users\Mahasiswa;

// ? Models - view
use App\Models\KelasKuliah\KelasKuliahJoinView;

class PresensiController extends Controller {
    public function kirimPinPresensi(Request $request) {
        try {
            $request->validate([
                'kelas_kuliah_id' => 'required',
                'pin' => 'required|min:6|max:6',
            ]);

            /**
             * Cek mahasiswa yang sah / mengambil kelas
             */
            $mahasiswa = $this->getUserAuth();
            $lastKRSMahasiswa = Mahasiswa::where('mhs_id', $mahasiswa['mhs_id'])->select('krs_id_last')->first();
            $hasKelasKuliah = KRSMatkul::where('krs_id', $lastKRSMahasiswa['krs_id_last'])
                ->where('kelas_kuliah_id', $request->kelas_kuliah_id)
                ->first();

            if (!$hasKelasKuliah) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Anda tidak mengambil kelas tersebut'
                ], 400);
            }

            /**
             * Cek pertemuan dan kelas kuliah yang dibuka
             */
            $pertemuan = Pertemuan::getCekPertemuanIdKelasDibuka($request->kelas_kuliah_id);

            if (!$pertemuan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Kelas kuliah belum dibuka'
                ], 400);
            }

            /**
             * Cek kemungkinan mahasiswa sudah mengisi presensi
             */
            $sudahMengirimPIN = Presensi::getCekPresensi($pertemuan['pertemuan_id'], $mahasiswa['mhs_id']);

            if ($sudahMengirimPIN->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Anda sudah mengirim PIN sebelumnya',
                ], 400);
            }

            /**
             * Cek pin apakah sama dengan yang tersimpan
             * pada cache di API
             */
            if (Cache::has((string) $request->kelas_kuliah_id)) {
                $isSamePIN = (string) $request->pin === Cache::get((string) $request->kelas_kuliah_id);

                if ($isSamePIN) {
                    /**
                     * Presensi mahasiswa terisi, isi pin dan waktu masuk
                     */
                    Presensi::where('pertemuan_id', $pertemuan['pertemuan_id'])
                        ->where('mhs_id', $mahasiswa['mhs_id'])
                        ->update([
                            'pin' => (string) $request->pin,
                            'masuk' => Carbon::now(),
                        ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Kehadiran Anda berhasil direkam'
                    ], 200);
                }
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'PIN tidak ditemukan'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getKehadiranMahasiswaByDosen($kelasKuliahId) {
        try {
            $dosen = $this->getUserAuth();
            $kelasKuliah = KelasKuliahJoinView::where('kelas_kuliah_id', $kelasKuliahId)
                ->where('pengajar_id', $dosen['dosen_id'])
                ->select('kelas_kuliah_id', 'kjoin_kelas', 'join_kelas_kuliah_id', 'pengajar_id')
                ->first();

            /**
             * Bukan dosen yang mengajar
             */
            if ($kelasKuliah['pengajar_id'] !== $dosen['dosen_id']) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Kelas kuliah id tidak ditemukan'
                ], 400);
            }

            /**
             * Kelas kuliah id tidak ditemukan
             */
            if (!$kelasKuliah) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Kelas kuliah id tidak ditemukan'
                ], 400);
            }

            /**
             * Cek kelas join
             */
            $kelasKuliahIdArr = $kelasKuliah['kjoin_kelas']
                ? [$kelasKuliah['kelas_kuliah_id'], $kelasKuliah['join_kelas_kuliah_id']]
                : [$kelasKuliah['kelas_kuliah_id']];

            $pertemuan = Pertemuan::getPertemuanKelasDibukaWithPresensi($kelasKuliahIdArr, $dosen['dosen_id']);

            if (count($pertemuan) > 0) {
                $presensiMahasiswa = $pertemuan->pluck('presensi')->flatten();
                $jumlahMahasiswa = $presensiMahasiswa->count();
                $jumlahMahasiswaHadir = $presensiMahasiswa->whereNotNull('masuk')->count();
                $jumlahMahasiswaBelumHadir = $presensiMahasiswa->whereNull('masuk')->count();

                return $this->successfulResponseJSON([
                    'jumlah_mahasiswa' => $jumlahMahasiswa,
                    'jumlah_mahasiswa_hadir' => $jumlahMahasiswaHadir,
                    'jumlah_mahasiswa_belum_hadir' => $jumlahMahasiswaBelumHadir,
                    'presensi_mahasiswa' => $presensiMahasiswa
                ]);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Kelas kuliah belum dibuka'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function kirimPinPresensiQrCode(Request $request) {
        try {
            if ($request->query('kelas') and $request->query('pin')) {
                $kelasKuliahIdArr = explode('-', $request->query('kelas'));
                $pin = $request->query('pin');
                $mahasiswa = $this->getUserAuth();
                $lastKRSMahasiswa = Mahasiswa::where('mhs_id', $mahasiswa['mhs_id'])->select('krs_id_last')->first();

                foreach ($kelasKuliahIdArr as $kelasKuliahId) {
                    /**
                     * Cek mahasiswa yang sah / mengambil kelas
                     */
                    $hasKelasKuliah = KRSMatkul::where('krs_id', $lastKRSMahasiswa['krs_id_last'])
                        ->where('kelas_kuliah_id', $kelasKuliahId)
                        ->first();

                    if ($hasKelasKuliah) {
                        /**
                         * Cek pertemuan dan kelas kuliah yang dibuka
                         */
                        $pertemuan = Pertemuan::getCekPertemuanIdKelasDibuka($kelasKuliahId);

                        if (!$pertemuan->exists()) {
                            return response()->json([
                                'status' => 'fail',
                                'message' => 'Kelas kuliah belum dibuka'
                            ], 400);
                        }

                        /**
                         * Cek kemungkinan mahasiswa sudah mengisi presensi
                         */
                        $sudahMengirimPIN = Presensi::getCekPresensi($pertemuan['pertemuan_id'], $mahasiswa['mhs_id']);

                        if ($sudahMengirimPIN->exists()) {
                            return response()->json([
                                'status' => 'fail',
                                'message' => 'Anda sudah mengirim PIN sebelumnya',
                            ], 400);
                        }

                        /**
                         * Cek pin apakah sama dengan yang tersimpan
                         * pada cache di API
                         */
                        if (Cache::has((string) $kelasKuliahId)) {
                            $isSamePIN = (string) $pin === Cache::get((string) $kelasKuliahId);

                            if ($isSamePIN) {
                                /**
                                 * Presensi mahasiswa terisi, isi pin dan waktu masuk
                                 */
                                Presensi::where('pertemuan_id', $pertemuan['pertemuan_id'])
                                    ->where('mhs_id', $mahasiswa['mhs_id'])
                                    ->update([
                                        'pin' => (string) $request->pin,
                                        'masuk' => Carbon::now(),
                                    ]);

                                return response()->json([
                                    'status' => 'success',
                                    'message' => 'Kehadiran Anda berhasil direkam'
                                ], 200);
                            }
                        }

                        return response()->json([
                            'status' => 'fail',
                            'message' => 'PIN tidak ditemukan'
                        ], 400);
                    }
                }

                return response()->json([
                    'status' => 'fail',
                    'message' => 'Anda tidak mengambil kelas tersebut'
                ], 400);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Nilai kelas dan pin tidak ditemukan'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
