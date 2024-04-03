<?php

namespace App\Http\Controllers\Wisuda;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// ? Models - Views
use App\Models\Wisuda\PengajuanWisudaView;
use App\Models\Wisuda\StatusView;
use App\Models\Users\MahasiswaView;
use App\Models\Verdig\VerifikasiView;

// ? Models - Tables
use App\Models\Wisuda\File;
use App\Models\Wisuda\JadwalWisuda;
use App\Models\Wisuda\PengajuanWisuda;

class AdminPengajuanWisudaController extends Controller
{
    public function getListPengajuan(Request $request) {
        try {
            $kdStatus = $request->query('kd_status');
            $allPengajuan = PengajuanWisudaView::getAllPengajuan();

            if ($kdStatus) {
                $allPengajuan = PengajuanWisudaView::getAllPengajuan($kdStatus);
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

    public function addPengajuan(Request $request) {
        try {
            $request->validate([
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
            ]);

            /**
             * Cek nim mahasiswa ada atau tidak
             */
            $mahasiswa = MahasiswaView::getMahasiswa($request->nim);

            if (!$mahasiswa->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Mahasiswa dengan NIM ' . $request->nim . ' tidak ditemukan'
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
            ];

            /**
             * Cek ke status verifikasi digital
             */
            $skka1 = VerifikasiView::getVerdigMahasiswa($mahasiswa['nim'], 'skka1');
            $skka3 = VerifikasiView::getVerdigMahasiswa($mahasiswa['nim'], 'skka3');

            if ($skka1->exists() and $skka3->exists()) {
                if ($skka1['status'] == 'DITERIMA' and $skka3['status'] == 'DITERIMA') {
                    /**
                     * Jika status kedua jenis surat skripsi dan pra sidang diterima
                     * maka status pengajuannya adalah menunggu hasil review dari admin (M1)
                     */
                    $status = StatusView::getDetailStatus('M1');
                    $pengajuan['status_id'] = $status['status_id'];
                } else if ($skka1['status'] == 'DITOLAK' or $skka3['status'] == 'DITOLAK') {
                    /**
                     * Jika salah satu surat ditolak
                     * maka status pengajuannya adalah ditolak dari verdig (T2)
                     */
                    $status = StatusView::getDetailStatus('T2');
                    $pengajuan['status_id'] = $status['status_id'];
                } else {
                    /**
                     * Jika tidak keduanya berarti berarti sedang menunggu verifikasi suratnya (M2)
                     */
                    $status = StatusView::getDetailStatus('M2');
                    $pengajuan['status_id'] = $status['status_id'];
                }
            } else {
                /**
                 * Jika surat tidak ada sama sekali
                 * maka belum mengajukan verifikasi digital sama sekali dan lansung ditolak
                 */
                $status = StatusView::getDetailStatus('T1');
                $pengajuan['status_id'] = $status['status_id'];
            }

            $pengajuan['tgl_pengajuan'] = Carbon::now();
            $createPengajuanId = PengajuanWisuda::create($pengajuan)->pengajuan_id;

            /**
             * Insert ke table files berupa storage path,
             * karena file disimpan pada storage di front-end
             */
            $files = [
                'file_ktp' => $request->file_ktp,
                'file_bukti_pembayaran' => $request->file_bukti_pembayaran,
                'file_bukti_pembayaran_sumbangan' => $request->file_bukti_pembayaran_sumbangan,
                'pengajuan_id' => $createPengajuanId
            ];

            $createFileId = File::create($files)->file_id;
            PengajuanWisuda::where('pengajuan_id', $createPengajuanId)
                ->update([
                    'file_id' => $createFileId,
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan pendaftaran wisuda berhasil dikirim'
            ], 201);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

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
                'kd_status' => 'required|string',
            ]);

            /**
             * Cek nim mahasiswa ada atau tidak
             */
            $mahasiswa = MahasiswaView::getMahasiswa($request->nim);

            if (!$mahasiswa->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Mahasiswa dengan NIM ' . $request->nim . ' tidak ditemukan'
                ], 404);
            }

            /**
             * Cek pengajuan id
             */
            $pengajuan = PengajuanWisudaView::getPengajuan($request->nim);

            if (!$pengajuan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Pengajuan dengan pengajuan_id ' . $request->pengajuan_id . ' tidak ditemukan'
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
            ];

            /**
             * Cek kode status pengajuan jika disetujui atau ditolak oleh admin
             */
            if ($request->kd_status == 'S1') {
                $request->validate([
                    'tgl_wisuda' => 'required|string',
                ]);

                $createJadwalId = JadwalWisuda::create([
                    'pengajuan_id' => $request->pengajuan_id,
                    'nim' => $mahasiswa['nim'],
                    'tgl_wisuda' => $request->tgl_wisuda,
                ])->jadwal_id;

                $pengajuan['jadwal_id'] = $createJadwalId;
            }

            $status = StatusView::getDetailStatus($request->kd_status);
            $pengajuan['status_id'] = $status['status_id'];

            $files = [
                'file_ktp' => $request->file_ktp,
                'file_bukti_pembayaran' => $request->file_bukti_pembayaran,
                'file_bukti_pembayaran_sumbangan' => $request->file_bukti_pembayaran_sumbangan,
            ];

            File::updateFiles($request->pengajuan_id, $files);
            PengajuanWisuda::updatePengajuan($request->pengajuan_id, $mahasiswa['nim'], $pengajuan);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan berhasil diperbarui'
            ], 200);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function deletePengajuan($nim) {
        try {
            /**
             * Cek nim mahasiswa ada atau tidak
             */
            $mahasiswa = MahasiswaView::getMahasiswa($nim);

            if (!$mahasiswa->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Mahasiswa dengan NIM ' . $nim . ' tidak ditemukan'
                ], 404);
            }

            /**
             * Cek pengajuan milik mahasiswa
             */
             $pengajuan = PengajuanWisudaView::getPengajuan($mahasiswa['nim']);

             if (!$pengajuan->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Pengajuan mahasiswa dengan NIM ' . $mahasiswa['nim'] . ' tidak ditemukan'
                ], 404);
             }

             PengajuanWisuda::deletePengajuan($pengajuan['pengajuan_id'], $mahasiswa['nim']);

             return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan mahasiswa dengan NIM ' . $mahasiswa['nim'] . ' berhasil dihapus'
             ], 200);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getStatistikPengajuan() {
        try {
            $totalPengajuan = PengajuanWisudaView::all()->count();
            $totalPengajuanDiterima = PengajuanWisudaView::where('kd_status', 'S1')->get()->count();
            $totalPengajuanJurusanSIDiterima = PengajuanWisudaView::where('nim', 'LIKE', '32%')->get()->count();
            $totalPengajuanJurusanIFDiterima = PengajuanWisudaView::where('nim', 'LIKE', '12%')->get()->count();

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
}
