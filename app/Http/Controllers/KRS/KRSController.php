<?php

namespace App\Http\Controllers\KRS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

// ? Exceptions
use App\Exceptions\ErrorHandler;

// ? Models - table
use App\Models\KRS\KRS;
use App\Models\TahunAjaranView;

class KRSController extends Controller
{
    public function addKRSMahasiswa(Request $request) {
        try {
            /**
             * Tahapan:
             * add ke tabel krs terlebih dahulu, kemudian ambil id-nya
             * add setiap mata_kuliah yang dipilih ke tabel krs_mk dengan krs_id dari tahap sebelumnya
             */

            // validasi request
            $request->validate([
                'tahun_id' => 'required',
                'pengajuan_catatan' => 'nullable|string',
                'mata_kuliah' => 'required|array'
            ]);

            $mahasiswa = $this->getUserAuth();
            $tahunAjaran = TahunAjaranView::where('tahun_id', $request->tahun_id)->first();
            $krsData = self::setKRSData($mahasiswa, $tahunAjaran);
            $krsData['pengajuan_catatan'] = $request->pengajuan_catatan;

            return response()->json([
                'data' => $krsData
            ], 200);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function setKRSData($mahasiswa, $tahunAjaran) {
        $substrTahun = substr($tahunAjaran['tahun'], -2);
        $smt = $tahunAjaran['smt'];
        $kdJur = $mahasiswa->jurusan()->first()['kd_jur'];
        $jnsMhs = $mahasiswa['jns_mhs'];

        /**
         * Set nmr_krs
         * nomor urut / kdJur / jenis kelas / tahun
         */
        $nmrKRS = "/$kdJur" . "/K$jnsMhs" . "$smt/" . $substrTahun;
        $getLastNmrKRS = KRS::getLastNomorKRS($nmrKRS);

        // jika getLastNmrKRS null, berarti nomor urutnya adalah 001
        if (is_null($getLastNmrKRS)) {
            $fullNmrKRS = "001" . $nmrKRS;
        } else {
            $nomorUrut = (int) explode('/', $getLastNmrKRS)[0] + 1; // indeks 0 adalah nomor urut
            $formattedNomorUmur = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
            $fullNmrKRS = $formattedNomorUmur . $nmrKRS;
        }

        return [
            'tahun_id' => $tahunAjaran['tahun_id'],
            'nmr_krs' => $fullNmrKRS,
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'mhs_id' => $mahasiswa->mhs_id,
            'kd_kampus' => $tahunAjaran['kd_kampus'],
            'semester' => null,
            'sts_krs' => null,
            'dosen_id' => null,
            'ditolak_tanggal' => null,
            'ditolak_alasan' => null,
            'ditolak_stlh_sah' => null,
            'kd_channel' => null,
        ];
    }
}
