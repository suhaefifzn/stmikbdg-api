<?php

namespace App\Http\Controllers\KRS;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - table
use App\Models\KRS\KRS;
use App\Models\KRS\KRSMatkul;
use App\Models\KRS\MatkulDiselenggarakanView;

class KRSDosenController extends Controller
{
    private $user;

    public function __construct() {
        $this->user = $this->getUserAuth();
    }

    public function getKRSMahasiswa(Request $request) {
        try {
            // is user dosen wali?
            self::getStatusDosenWali($request);

            $mahasiswa = $this->user->mahasiswa()->where('mhs_id', $request->mhs_id)->first();
            $jurusanMahasiswa = $mahasiswa->jurusan()->first();
            $krsMahasiswa = $mahasiswa->krs()->first();
            $krsMatkulDipilih = $krsMahasiswa->krsMatkul()->get();
            $setKRSData = self::setKRSData($jurusanMahasiswa, $krsMahasiswa, $krsMatkulDipilih);

            return $this->successfulResponseJSON([
                'mahasiswa' => [
                    'mhs_id' => $mahasiswa['mhs_id'],
                    'nim' => $mahasiswa['nim'],
                    'nama' => $mahasiswa['nm_mhs'],
                    'jurusan' => [
                        'jur_id' => $jurusanMahasiswa['jur_id'],
                        'nama_jurusan' => $jurusanMahasiswa['nama_jurusan'],
                        'nm_singkat' => $jurusanMahasiswa['nm_singkat'],
                    ],
                    'krs' => $setKRSData,
                ],
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function updateStatusKRSMahasiswa(Request $request) {
        try {
            // is user dosen wali?
            self::getStatusDosenWali($request);

            $request->validate([
                'mhs_id' => 'required',
                'krs_id' => 'required',
                'sts_krs' =>  'required',
                'krs_matkul' => 'required|array',
                'krs_matkul.*.k_disetujui' => 'required|boolean',
                'krs_matkul.*.krs_mk_id' => 'required',
            ]);

            $currentStatusKRS = KRS::where('krs_id', $request->krs_id)->first()['sts_krs'];

            $krsData = [
                'sts_krs' => $request->sts_krs,
            ];
            $krsMatkul = $request->krs_matkul;

            // krs ditolak
            if ($request->sts_krs === 'D') {
                $request->validate([
                    'ditolak_alasan' => 'required',
                ]);
                $ditolakStlhSah = $currentStatusKRS === 'S' ?? false;

                $krsData['ditolak_alasan'] = $request->ditolak_alasan;
                $krsData['ditolak_tanggal'] = now();
                $krsData['ditolak_stlh_sah'] = $ditolakStlhSah;
            }

            // update ke table krs
            KRS::where('krs_id', $request->krs_id)
                ->update($krsData);

            // update setiap mk di table krs_mk
            foreach ($krsMatkul as $matkul) {
                KRSMatkul::where('krs_id', $request->krs_id)
                    ->where('krs_mk_id', $matkul['krs_mk_id'])
                    ->update([
                        'k_disetujui' => $matkul['k_disetujui']
                    ]);
            }

            return $this->successfulResponseJSON([
                'krs_id' => $request->krs_id,
            ], 'KRS mahasiswa berhasil diperbaharui');
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getListKRSMahasiswa(Request $request) {
        try {
            $filter = [
                'dosen_id' => $this->user['dosen_id'],
            ];
            $listMahasiswa = $this->user->mahasiswa()->searchMahasiswa($filter, $request->query('search'));

            return $this->successfulResponseJSON([
                'mahasiswa' => self::setVisibilityMahasiswaProperties($listMahasiswa),
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function setVisibilityMahasiswaProperties($mahasiswa) {
        $tempMahasiswa = [];

        if (count($mahasiswa) > 0) {
            foreach ($mahasiswa as $index => $mhs) {
                $tempMahasiswa[$index] = [
                    'mhs_id' => $mhs['mhs_id'],
                    'angkatan_id' => $mhs['angkatan_id'],
                    'dosen_id' => $mhs['dosen_id'],
                    'jur_id' => $mhs['jur_id'],
                    'nim' => $mhs['nim'],
                    'nama' => $mhs['nm_mhs'],
                    'jns_mhs' => $mhs['jns_mhs'],
                    'sts_mhs' => $mhs['sts_mhs'],
                    'kd_kampus' => $mhs['kd_kampus'],
                    'kelas' => 'A',
                    'jk' => $mhs['jk'],
                    'masuk_tahun' => $mhs['masuk_tahun'],
                    'krs_id_last' => $mhs['krs_id_last'],
                    'tanggal_krs' => $mhs->krs()->first()['tanggal']
                ];
            }
        }

        return $tempMahasiswa;
    }

    private function setKRSData($jurusan, $krs, $krsMatkul) {
        $tempMatkul = [];

        foreach ($krsMatkul as $index => $item) {
            $detailMatkul = MatkulDiselenggarakanView::where('jur_id', $jurusan['jur_id'])
                                ->where('tahun_id', $krs['tahun_id'])
                                ->where('mk_id', $item['mk_id'])
                                ->first();
            $tempMatkul[$index] = [
                'krs_mk_id' => $item['krs_mk_id'],
                'mk_id' => $item['mk_id'],
                'sts_mk_krs' => $item['sts_mk_krs'],
                'tgl_perubahan' => $item['tgl_perubahan'],
                'kd_mk' => $detailMatkul['kd_mk'],
                'nm_mk' => $detailMatkul['nm_mk'],
                'sks' => $detailMatkul['sks'],
                'k_disetujui' => $item['k_disetujui'],
            ];
        }

        $krsData = [
            'krs_id' => $krs['krs_id'],
            'tahun_id' => $krs['tahun_id'],
            'nmr_krs' => $krs['nmr_krs'],
            'tanggal' => $krs['tanggal'],
            'semester' => $krs['semester'],
            'sts_krs' => $krs['sts_krs'],
            'kd_kampus'  => $krs['kd_kampus'],
            'kd_chanel' => $krs['kd_chanel'],
            'pengajuan_catatan' => $krs['pengajuan_catatan'],
            'ditolak_tanggal' => $krs['ditolak_tanggal'],
            'ditolak_alasan' => $krs['ditolak_alasan'],
            'ditolak_stlh_sah' => $krs['ditolak_stlh_sah'],
            'krs_matkul' => $tempMatkul,
        ];

        return $krsData;
    }

    private function getStatusDosenWali($request) {
        $isDosenWali = $this->isDosenWali($this->user, $request->query('mhs_id'));

        if (!$isDosenWali) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Bukan wali dosen dari mahasiswa',
            ], 403);
        }
    }
}
