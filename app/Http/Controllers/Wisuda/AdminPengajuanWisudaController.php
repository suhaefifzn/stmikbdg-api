<?php

namespace App\Http\Controllers\Wisuda;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// ? Models - Views
use App\Models\Wisuda\PengajuanWisudaView;
use App\Models\Wisuda\StatusView;
use App\Models\Users\MahasiswaView;
use App\Models\Wisuda\JadwalWisudaAktifView;
use App\Models\Wisuda\JadwalWisudaAllView;

// ? Models - Tables
use App\Models\Wisuda\File;
use App\Models\Wisuda\JadwalWisuda;
use App\Models\Wisuda\PengajuanWisuda;

class AdminPengajuanWisudaController extends Controller
{
    public function getListPengajuan(Request $request) {
        try {
            $kdStatus = $request->query('kd_status');
            $tahun = $request->query('tahun');
            $allPengajuan = PengajuanWisudaView::getAllPengajuan();

            if ($kdStatus and !$tahun) {
                $allPengajuan = PengajuanWisudaView::getAllPengajuan($kdStatus);
            } else if ($kdStatus and $tahun) {
                $jadwal = JadwalWisudaAllView::where('tahun', $tahun)->first();

                if (!is_null($jadwal)) {
                    $allPengajuan = PengajuanWisudaView::getAllPengajuan($kdStatus, $jadwal['jadwal_wisuda_id']);
                } else {
                    $allPengajuan = [];
                }
            }

            return $this->successfulResponseJSON([
                'list_pengajuan' => $allPengajuan
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getDetailPengajuan($nim) {
        try {
            $pengajuan = PengajuanWisudaView::getPengajuan($nim);

            if ($pengajuan->exists()) {
                return $this->successfulResponseJSON([
                    'detail_pengajuan' => $pengajuan
                ]);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Pengajuan pendaftaran wisuda dengan NIM ' . $nim . ' tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getListStatus() {
        try {
            $allStatus = StatusView::all();

            return $this->successfulResponseJSON([
                'list_status' => $allStatus
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * ! perlu didiskusikan kembali
     * kenapa admin dapat edit pengajuan milik mahasiswa? - di mockup
     */
    public function updatePengajuan(Request $request, $nim) {
        try {
            $request->validate([
                'pengajuan_id' => 'required',
                'nim' => 'required|string',
                'nama' => 'required|string',
                'nik' => 'required|string',
                'tempat_lahir' => 'required|string',
                'tgl_lahir' => 'required|string',
                'email' => 'required|email',
                'no_hp' => 'required|string',
                'tgl_sidang_akhir' => 'required|string',
                'file_bukti_pembayaran' => 'required|string',
                'file_ktp' => 'required|string',
                'file_bukti_pembayaran_sumbangan' => 'required|string',
                'file_ijazah' => 'required|string',
                'judul_skripsi' => 'required|string'
            ]);

            /**
             * Cek nim pada payload body dan path parameter
             */
            if ((string) $request->nim != $nim) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'NIM yang Anda kirim pada payload body tidak sama dengan NIM pada URL'
                ], 400);
            }

            /**
             * Cek nim mahasiswa ada atau tidak
             */
            $mahasiswa = MahasiswaView::getMahasiswa((string) $nim);

            if (!$mahasiswa->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Mahasiswa dengan NIM ' . $nim . ' tidak ditemukan'
                ], 404);
            }

            /**
             * Cek pengajuan id
             */
            $pengajuan = PengajuanWisudaView::getPengajuan((string) $nim);

            if (!$pengajuan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Pengajuan dengan NIM ' . $nim . ' tidak ditemukan'
                ], 404);
            }

            $pengajuan = [
                'nim' => $request->nim,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'tgl_sidang_akhir' => $request->tgl_sidang_akhir,
                'judul_skripsi' => $request->judul_skripsi,
                'is_bayar' => null, // default
                'is_ditolak' => null, // default
                'ditolak_alasan' => null, // default
            ];

            /**
             * Jika diupdate maka status kembali ke menunggu
             */
            $status = StatusView::getDetailStatus('M');
            $pengajuan['status_id'] = $status['status_id'];
            $pengajuan['tgl_pengajuan'] = Carbon::now();
            $pengajuan['jadwal_wisuda_id'] = null;

            $files = [
                'file_ktp' => $request->file_ktp,
                'file_bukti_pembayaran' => $request->file_bukti_pembayaran,
                'file_bukti_pembayaran_sumbangan' => $request->file_bukti_pembayaran_sumbangan,
                'file_ijazah' => $request->file_ijazah
            ];

            File::updateFiles((integer) $request->pengajuan_id, $files);
            PengajuanWisuda::updatePengajuan((integer) $request->pengajuan_id, $mahasiswa['nim'], $pengajuan);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan berhasil diperbarui'
            ], 200);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getStatistikPengajuan() {
        try {
            $totalPengajuan = PengajuanWisudaView::all()->count();
            $totalPengajuanDiterima = PengajuanWisudaView::where('kd_status', 'S')->get()->count();
            $totalPengajuanJurusanSIDiterima = PengajuanWisudaView::where('nim', 'LIKE', '32%')
                ->where('kd_status', 'S')->get()->count();
            $totalPengajuanJurusanIFDiterima = PengajuanWisudaView::where('nim', 'LIKE', '12%')
                ->where('kd_status', 'S')->get()->count();

            $data = [
                'total_pengajuan' => $totalPengajuan,
                'total_pengajuan_diterima' => $totalPengajuanDiterima,
                'total_pengajuan_jurusan_si_diterima' => $totalPengajuanJurusanSIDiterima,
                'total_pengajuan_jurusan_if_diterima' => $totalPengajuanJurusanIFDiterima
            ];

            return $this->successfulResponseJSON([
                'statistik_pengajuan' => $data
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function verifikasiPengajuan(Request $request, $nim) {
        try {
            $request->validate([
                'pengajuan_id' => 'required',
                'nim' => 'required|string',
                'is_bayar' => 'required|boolean',
            ]);

            /**
             * Cek nim pada payload body dan path parameter
             */
            if ((string) $request->nim != $nim) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'NIM yang Anda kirim pada payload body tidak sama dengan NIM pada URL'
                ], 400);
            }

            DB::beginTransaction();

            /**
             * Cek mahasiswa dan pengajuannya
             */
            $pengajuanMahasiswa = PengajuanWisudaView::getPengajuan((string) $request->nim);

            if (!$pengajuanMahasiswa->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Pengajuan mahasiswa dengan NIM ' . $request->nim . ' tidak ditemukan'
                ], 404);
            }

            $pengajuan = [
                'is_bayar' => $request->is_bayar,
            ];

            if ($request->is_bayar) {
                $pengajuan['is_ditolak'] = false;
                $pengajuan['ditolak_alasan'] = null;

                $status = StatusView::getDetailStatus('S');
                $jadwalWisudaAktif = JadwalWisudaAktifView::first();

                $pengajuan['jadwal_wisuda_id'] = $jadwalWisudaAktif['jadwal_wisuda_id'];
                $pengajuan['tgl_wisuda'] = $jadwalWisudaAktif['tgl_wisuda'];
                $pengajuan['tahun_wisuda'] = $jadwalWisudaAktif['tahun'];
                $pengajuan['angkatan_wisuda'] = $jadwalWisudaAktif['angkatan_wisuda'];
                $pengajuan['status_id'] = $status['status_id'];
                $pengajuan['is_verified'] = true;
            } else {
                $request->validate([
                    'ditolak_alasan' => 'required|string',
                ]);

                $pengajuan['is_ditolak'] = true;
                $pengajuan['ditolak_alasan'] = $request->ditolak_alasan;
                $pengajuan['jadwal_wisuda_id'] = null;
                $pengajuan['tgl_wisuda'] = null;
                $pengajuan['tahun_wisuda'] = null;
                $pengajuan['angkatan_wisuda'] = null;

                $status = StatusView::getDetailStatus('T');
                $pengajuan['status_id'] = $status['status_id'];
                $pengajuan['is_verified'] = false;
            }

            $update = PengajuanWisuda::updatePengajuan(
                (integer) $request->pengajuan_id, (string) $request->nim, $pengajuan
            );

            if ($update) {
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Status pengajuan wisuda mahasiswa dengan NIM ' . $request->nim . ' berhasil diperbarui'
                ], 200);
            }

            DB::rollBack();

            return response()->json([
                'status' => 'fail',
                'message' => 'Pengajuan mahasiswa gagal diverifikasi'
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function getJadwalWisuda(Request $request) {
        try {
            $isAktif = filter_var($request->query('aktif'), FILTER_VALIDATE_BOOLEAN);
            $jadwalWisuda = $isAktif ? JadwalWisudaAktifView::first() : JadwalWisudaAllView::all();

            return $this->successfulResponseJSON([
                'jadwal_wisuda' => $jadwalWisuda
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function addJadwalWisuda(Request $request) {
        try {
            $request->validate([
                'tgl_wisuda' => 'required|string',
                'angkatan_wisuda' => 'required|integer'
            ]);

            $carbonDate = Carbon::createFromFormat('d-m-Y', $request->tgl_wisuda);
            $year = (string) $carbonDate->year;

            /**
             * Cek apakah tahun wisuda yang ditambah sudah ada atau belum
             */
            $tempJadwal = JadwalWisudaAllView::getJadwalWisuda($year);

            if ($tempJadwal->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Jadwal wisuda di tahun ' . $year . ' sudah ada'
                ], 400);
            }

            $jadwal = [
                'tahun' => $year,
                'tgl_wisuda' => $request->tgl_wisuda,
                'angkatan_wisuda' => $request->angkatan_wisuda,
            ];

            JadwalWisuda::create($jadwal);

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal dan angkatan wisuda tahun ' . $year . ' berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function updateJadwalWisuda(Request $request, $tahun) {
        try {
            $request->validate([
                'tgl_wisuda' => 'required|string',
                'angkatan_wisuda' => 'required|integer',
            ]);

            $oldJadwal = JadwalWisudaAllView::getJadwalWisuda($tahun);

            /**
             * Cek jadwal ada atau tidak
             */
            if (!$oldJadwal->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Tahun wisuda tidak ditemukan'
                ], 400);
            }

            $carbonDate = Carbon::createFromFormat('d-m-Y', $request->tgl_wisuda);
            $year = (string) $carbonDate->year;
            $jadwal = [
                'tgl_wisuda' => $request->tgl_wisuda,
                'tahun' => $year,
                'angkatan_wisuda' => (integer) $request->angkatan_wisuda,
            ];

            DB::beginTransaction();

            $updateJadwal = JadwalWisuda::where('jadwal_wisuda_id', $oldJadwal['jadwal_wisuda_id'])
                ->update($jadwal);

            /**
             * update juga field tgl_wisuda, tahun_wisuda,
             * dan angkatan_wisuda di tabel pengajuan
             */
            PengajuanWisuda::where('jadwal_wisuda_id', $oldJadwal['jadwal_wisuda_id'])
                ->update([
                    'tgl_wisuda' => $jadwal['tgl_wisuda'],
                    'tahun_wisuda' => (int) $jadwal['tahun'],
                    'angkatan_wisuda' => (int) $jadwal['angkatan_wisuda']
                ]);

            if ($updateJadwal) {
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Jadwal wisuda berhasil diperbarui'
                ], 200);
            }

            DB::rollBack();

            return response()->json([
                'status' => 'fail',
                'message' => 'Jadwal wisuda gagal diperbarui',
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function deleteJadwalWisuda(Request $request) {
        try {
            $jadwalWisudaId = $request->jadwal_wisuda_id;

            if ($jadwalWisudaId) {
                $jadwalWisuda = JadwalWisudaAllView::where('jadwal_wisuda_id', (int) $jadwalWisudaId)->first();

                if ($jadwalWisuda) {
                    DB::beginTransaction();

                    $delete = JadwalWisuda::where('jadwal_wisuda_id', (int) $jadwalWisudaId)->delete();

                    if ($delete) {
                        DB::commit();

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Jadwal wisuda berhasil dihapus'
                        ], 200);
                    }

                    DB::rollBack();

                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Jadwal wisuda gagal dihapus'
                    ], 500);
                }
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Jadwal wisuda tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
