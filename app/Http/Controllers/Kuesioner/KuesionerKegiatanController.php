<?php

namespace App\Http\Controllers\Kuesioner;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use App\Models\Kuesioner\JawabanKuesionerKegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

// ? Models - Tables
use App\Models\Kuesioner\KuesionerKegiatan;
use App\Models\Kuesioner\KuesionerKegiatanMahasiswa;

// ? Models - Views
use App\Models\Kuesioner\KuesionerKegiatanView;
use App\Models\Kuesioner\PertanyaanView;
use App\Models\Kuesioner\PointsView;
use App\Models\Kuesioner\SaranKuesionerKegiatan;

class KuesionerKegiatanController extends Controller
{
    /**
     * admin add kuesioner untuk kegiatan
     */
    public function addKuesioner(Request $request) {
        try {
            $request->validate([
                'tanggal_mulai' => 'required|string',
                'tanggal_akhir' => 'required|string',
                'organisasi' => 'required|string',
                'kegiatan' => 'required|string',
            ]);

            $tanggalMulai = Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai);
            $tanggalAkhir = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir);

            $data = [
                'tahun' => $tanggalMulai->year,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_akhir' => $tanggalAkhir,
                'organisasi' => $request->organisasi,
                'kegiatan' => $request->kegiatan,
            ];

            KuesionerKegiatan::insert($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Kuesioner untuk kegiatan tersebut berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * admin get list kuesioner kegiatan
     */
    public function getListKuesioner() {
        try {
            $kuesionerKegiatanAll = KuesionerKegiatanView::all();

            return $this->successfulResponseJSON([
                'kuesioner_kegiatan' => $kuesionerKegiatanAll,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * mahasiswa get list kuesioner kegiatan
     */
    public function getListKuesionerByMahasiswa() {
        try {
            $mahasiswa = $this->getUserAuth();
            $kuesionerKegiatanAll = KuesionerKegiatanView::all();

            /**
             * add properti sts_isi_kuesioner
             */
            foreach ($kuesionerKegiatanAll as $index => $item) {
                $kuesionerKegiatanMahasiswa = KuesionerKegiatanMahasiswa::where('kuesioner_kegiatan_id', $item['kuesioner_kegiatan_id'])
                    ->where('mhs_id', $mahasiswa['mhs_id'])
                    ->first();

                $stsIsiKuesioner = $kuesionerKegiatanMahasiswa ? true : false;
                $item['sts_isi_kuesioner'] = $stsIsiKuesioner;
                $kuesionerKegiatanAll[$index] = $item;     
            }

            return $this->successfulResponseJSON([
                'kuesioner_kegiatan' => $kuesionerKegiatanAll,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * mahasiswa get list pertanyaan kuesioner
     */
    public function getPertanyaanKuesioner(Request $request) {
        try {
            $mahasiswa = $this->getUserAuth();

            if ($request->query('kuesioner_kegiatan_id')) {
                /**
                 * cek kuesioner kegiatan id
                 */
                $kuesionerKegiatan = KuesionerKegiatan::where('kuesioner_kegiatan_id', (int) $request->query('kuesioner_kegiatan_id'))
                    ->first();

                if ($kuesionerKegiatan) {
                    /**
                     * cek kemungkinan mahasiswa sudah mengisi kuesioner
                     */
                    $kuesionerKegiatanMahasiswa = KuesionerKegiatanMahasiswa::where(
                            'kuesioner_kegiatan_id', $kuesionerKegiatan['kuesioner_kegiatan_id']
                        )->where('mhs_id', $mahasiswa['mhs_id'])
                        ->first();

                    if ($kuesionerKegiatanMahasiswa) {
                        return response()->json([
                            'status' => 'fail',
                            'message' => 'Anda sudah pernah mengisi kuesioner kegiatan tersebut'
                        ], 400);
                    }

                    /**
                     * get pertanyaan untuk kuesioner kegiatan
                     * kd_jenis_pertanyaan 'K'
                     */
                    $pertanyaan = PertanyaanView::where('kd_jenis_pertanyaan', 'K')->get()->groupBy('kelompok');

                    return $this->successfulResponseJSON([
                        'kuesioner' => [
                            'kuesioner_kegiatan_id' => $kuesionerKegiatan['kuesioner_kegiatan_id'],
                            'tahun' => $kuesionerKegiatan['tahun'],
                            'tanggal_mulai' => $kuesionerKegiatan['tanggal_mulai'],
                            'tanggal_akhir' => $kuesionerKegiatan['tanggal_akhir'],
                            'organisasi' => $kuesionerKegiatan['organisasi'],
                            'kegiatan' => $kuesionerKegiatan['kegiatan'],
                            'list_pertanyaan' => $pertanyaan,
                        ],
                    ]);
                }
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Nilai kuesioner_kegiatan_id tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * mahasiswa kirim jawaban kuesioner
     */
    public function addJawabanMahasiswa(Request $request) {
        try {
            $mahasiswa = $this->getUserAuth();

            $request->validate([
                'kuesioner_kegiatan_id' => 'required',
                'list_jawaban' => 'required|array',
            ]);

            /**
             * cek kuesioner kegiatan id
             */
            $kuesionerKegiatan = KuesionerKegiatan::where('kuesioner_kegiatan_id', (int) $request->kuesioner_kegiatan_id)->first();

            if (!$kuesionerKegiatan) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai kuesioner_kegiatan_id tidak ditemukan',
                ], 404);
            }

            /**
             * cek kemungkinan mahasiswa telah mengisi kuesioner
             */
            $kuesionerKegiatanMahasiswa = KuesionerKegiatanMahasiswa::where(
                    'kuesioner_kegiatan_id', $kuesionerKegiatan['kuesioner_kegiatan_id']
                )->where('mhs_id', $mahasiswa['mhs_id'])
                ->first();

            if ($kuesionerKegiatanMahasiswa) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Anda sudah pernah mengisi kuesioner kegiatan tersebut'
                ], 400);
            }

            /**
             * cek total pertanyaan yang dijawab dan kemungkinan adanya
             * pertanyaan id yang tidak ada di db diinputkan.
             * total jawaban harus sama dengan total pertanyaan kuesioner kegiatan
             */
            $countPertanyaan = PertanyaanView::where('kd_jenis_pertanyaan', 'K')->get()->count();
            $tempPertanyaanId = collect($request->list_jawaban)->pluck('pertanyaan_id')->toArray();
            $countJawaban = PertanyaanView::whereIn('pertanyaan_id', $tempPertanyaanId)
                ->where('kd_jenis_pertanyaan', 'K')
                ->get()
                ->count();

            if ($countJawaban != $countPertanyaan) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Mohon jawab semua pertanyaan kuesioner kegiatan yang diberikan'
                ], 400);
            }

            /**
             * data yang mengisi kuesioner kegiatan mahasiswa
             */
            $dataKuesioner = [
                'kuesioner_kegiatan_id' => $kuesionerKegiatan['kuesioner_kegiatan_id'],
                'mhs_id' => $mahasiswa['mhs_id'],
            ];

            $kuesionerKegiatanMahasiswaId = KuesionerKegiatanMahasiswa::create($dataKuesioner)->kuesioner_kegiatan_mahasiswa_id;

            /**
             * set data jawaban
             */
            $listJawaban = self::setDataJawaban($request->list_jawaban, $kuesionerKegiatanMahasiswaId);

            JawabanKuesionerKegiatan::insert($listJawaban);

            return $this->successfulResponseJSON([
                'kuesioner' => [
                    'kuesioner_kegiatan_mahasiswa_id' => $kuesionerKegiatanMahasiswaId,
                ],
            ], 'Jawaban berhasil dikirim');
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * mahasiswa kirim saran untuk kegiatan
     */
    public function addSaranForKegiatan(Request $request) {
        try {
            $request->validate([
                'kuesioner_kegiatan_mahasiswa_id' => 'required',
                'saran' => 'required|string|min:10'
            ]);

            /**
             * cek kuesioner kegiatan mahasiswa id
             */
            $kuesionerKegiatanMahasiswa = KuesionerKegiatanMahasiswa::where(
                'kuesioner_kegiatan_mahasiswa_id', (int) $request->kuesioner_kegiatan_mahasiswa_id
            )->first();

            if (!$kuesionerKegiatanMahasiswa) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Nilai kuesioner_kegiatan_mahasiswa_id tidak ditemukan'
                ], 404);
            }

            /**
             * cek kemungkinan mahasiswa telah mengirim saran
             */
            $saranMahasiswa = SaranKuesionerKegiatan::where(
                'kuesioner_kegiatan_mahasiswa_id', $kuesionerKegiatanMahasiswa['kuesioner_kegiatan_mahasiswa_id']
            )->first();

            if ($saranMahasiswa) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Anda sudah pernah mengirim saran untuk kegiatan tersebut'
                ], 400);
            }

            /**
             * set data saran
             */
            $saran = [
                'kuesioner_kegiatan_mahasiswa_id' => $kuesionerKegiatanMahasiswa['kuesioner_kegiatan_mahasiswa_id'],
                'saran' => $request->saran,
            ];

            SaranKuesionerKegiatan::insert($saran);

            return response()->json([
                'status' => 'success',
                'message' => 'Saran berhasil dikirim'
            ], 201);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function setDataJawaban($listJawaban, $kuesionerKegiatanMahasiswaId) {
        $formattedListJawaban = [];

        foreach ($listJawaban as $item) {
            $point = PointsView::where('kd_point', strtoupper($item['jawaban']))->first();

            $newItem = [
                'kuesioner_kegiatan_mahasiswa_id' => $kuesionerKegiatanMahasiswaId,
                'pertanyaan_id' => $item['pertanyaan_id'],
                'point_id' => $point['point_id'],
                'kd_point' => $point['kd_point'],
            ];

            array_push($formattedListJawaban, $newItem);
        }

        return $formattedListJawaban;
    }
}
