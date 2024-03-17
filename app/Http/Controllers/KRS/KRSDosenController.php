<?php

namespace App\Http\Controllers\KRS;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

// ? Models - table
use App\Models\KRS\KRS;
use App\Models\KRS\KRSMatkul;
use App\Models\Users\Dosen;

// ? Models - view
use App\Models\KRS\MatkulDiselenggarakanView;
use App\Models\KRS\NilaiAkhirView;

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
            $setKRSData = self::setKRSData($jurusanMahasiswa, $krsMahasiswa, $krsMatkulDipilih, $mahasiswa['mhs_id']);

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
            $page = $request->query('page') ?? null;
            $search = $request->query('search') ?? null;
            $tahunMasuk = $request->query('tahun_masuk') ?? null;
            $semester = $request->query('semester') ?? null;
            $listMahasiswa = Dosen::getListKRSMahasiswa($this->user['dosen_id'], $search, $tahunMasuk);

            // jika ada filter semester pada query params
            if ($semester) {
                $listMahasiswa = array_values(collect($listMahasiswa)->filter(function ($item) use ($semester) {
                    return $item['krs'][0]['semester'] == $semester;
                })->toArray());
            }

            // jika ada filter page pada query params
            if ($page and !$search) {
                $perPage = 10;
                $currentPage = (integer) $page ?? Paginator::resolveCurrentPage();
                $currentPageData = Collection::make($listMahasiswa)->slice(($currentPage - 1) * $perPage, $perPage);
                $paginator = new Paginator($currentPageData->all(), $perPage, $currentPage);
                $paginatedData = array_values($paginator->items());
                $totalNextItems = count($listMahasiswa) - ($currentPage == 1
                    ? $currentPageData->count()
                    : $currentPageData->count() + ($perPage * $currentPage)
                );

                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'list_krs_mahasiswa' => $paginatedData,
                    ],
                    'meta' => [
                        'current_page' => $currentPage,
                        'total_items' => count($listMahasiswa),
                        'items_per_page' => $paginator->perPage(),
                        'prev_page_url' => $currentPage == 1 ? null
                            :  config('app.url') . 'krs/mahasiswa/list' . substr($paginator->previousPageUrl(), 1),
                        'next_page_url' => ($totalNextItems > -1 and count($listMahasiswa) > 10)
                            ? config('app.url') . 'krs/mahasiswa/list?page=' . $currentPage + 1
                            : null,
                    ],
                ], 200);
            }

            return $this->successfulResponseJSON([
                'list_krs_mahasiswa' => $listMahasiswa,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function setKRSData($jurusan, $krs, $krsMatkul, $mhsId) {
        $tempMatkul = [];

        foreach ($krsMatkul as $index => $item) {
            $detailMatkul = MatkulDiselenggarakanView::where('jur_id', $jurusan['jur_id'])
                ->where('tahun_id', $krs['tahun_id'])
                ->where('mk_id', $item['mk_id'])
                ->first();

            // get nilai akhir
            $nilaiAkhirMatkul = NilaiAkhirView::where('mhs_id', $mhsId)
                ->where('mk_id', $item['mk_id'])
                ->select('nilai', 'mutu')
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
                'nilai_akhir' => $nilaiAkhirMatkul,
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
