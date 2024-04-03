<?php

namespace App\Http\Controllers\Wisuda;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// ? Models - Views
use App\Models\Verdig\VerifikasiView;
use App\Models\Wisuda\StatusView;
use App\Models\Wisuda\PengajuanWisudaView;

// ? Models - Tables
use App\Models\Wisuda\PengajuanWisuda;
use App\Models\Wisuda\File;

class MahasiswaPengajuanWisudaController extends Controller
{
    public function addPengajuan(Request $request) {
        try {
            $mahasiswa = $this->getUserAuth();

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
             * Cek nim sesuai dengan mahasiswa yang mengajukan atau tidak
             */
            if ($mahasiswa['nim'] !== ((string) $request->nim)) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'NIM tidak sesuai. Pastikan NIM yang Anda kirimkan sesuai dengan pemilik akun'
                ], 400);
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
                 * maka belum mengajukan verifikasi digital sama sekali dan langsung ditolak
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

    public function getStatusPengajuan($nim) {
        try {
            $mahasiswa = $this->getUserAuth();

            /**
             * Cek nim mahasiswa
             */
            if ($nim != $mahasiswa['nim']) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'NIM tidak sesuai. Pastikan NIM yang digunakan sesuai dengan pemilik akun'
                ], 400);
            }

            $pengajuan = PengajuanWisudaView::getStatusPengajuan($mahasiswa['nim']);

            if ($pengajuan->exists()) {
                if ($pengajuan['kd_status'] == 'S1') {
                    /**
                     * Jika pengajuan disetujui (S1)
                     * maka tampilkan response berisi tanggal wisuda
                     */

                     return $this->successfulResponseJSON([
                        'pengajuan_wisuda' => $pengajuan
                     ]);
                }

                return $this->successfulResponseJSON([
                    'pengajuan_wisuda' => [
                        'pengajuan_id' => $pengajuan['pengajuan_id'],
                        'kd_status' => $pengajuan['kd_status'],
                        'ket_status' => $pengajuan['ket_status'],
                        'tgl_pengajuan' => $pengajuan['tgl_pengajuan']
                    ]
                ], null, 200);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Belum mengajukan pendaftaran wisuda'
            ], 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getDetailPengajuan($nim) {
        try {
            $mahasiswa = $this->getUserAuth();

            /**
             * Cek nim mahasiswa
             */
            if ($nim != $mahasiswa['nim']) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'NIM tidak sesuai. Pastikan NIM yang digunakan sesuai dengan pemilik akun'
                ], 400);
            }

            $pengajuan = PengajuanWisudaView::getPengajuan($mahasiswa['nim']);

            if ($pengajuan->exists()) {
                return $this->successfulResponseJSON([
                    'pengajuan_wisuda' => $pengajuan,
                ]);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Belum mengajukan pendaftaran wisuda'
            ], 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
