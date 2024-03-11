<?php

namespace App\Http\Controllers\Kuesioner;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models
use App\Models\KelasKuliah\KelasKuliahJoinView;
use App\Models\TahunAjaranView;
use App\Models\KRS\KRS;

class KuesionerController extends Controller
{
    public function getMatkulByLastKRSMahasiswa() {
        try {
            $mahasiswa = $this->getUserAuth();
            $tahunAjaran = TahunAjaranView::getTahunAjaran($mahasiswa);
            $lastKRS = KRS::getLastKRSDisetujuiWithMatkul($tahunAjaran['tahun_id'], $mahasiswa['mhs_id']);

            if ($lastKRS->exists()) {
                // get pengajar melalui kelas kuliah id
                $krsMatkul = [];

                foreach ($lastKRS['krsMatkul'] as $index => $item) {
                    $kelasKuliah = $item->kelasKuliahJoin()->first();

                    if ($kelasKuliah['kjoin_kelas']) {
                        $kelasKuliahJoin = KelasKuliahJoinView::where(
                            'kelas_kuliah_id', $kelasKuliah['join_kelas_kuliah_id']
                            )->first();
                        $dosen = $kelasKuliahJoin->dosen()
                            ->select('dosen_id', 'nm_dosen', 'gelar')
                            ->first();
                        $trimmedDosen = self::trimNamaDosen($dosen);
                        $item['pengajar'] = $dosen;
                    } else {
                        $dosen = $kelasKuliah->dosen()
                            ->select('dosen_id', 'nm_dosen', 'gelar')
                            ->first();
                        $trimmedDosen = self::trimNamaDosen($dosen);
                        $item['pengajar'] = $trimmedDosen;
                    }

                    // sementara hingga ada db
                    $item['sts_isi_kuesioner'] = false;

                    $krsMatkul[$index] = $item;
                }

                return $this->successfulResponseJSON([
                    'tahun_id' => $tahunAjaran['tahun_id'],
                    'tahun' => $tahunAjaran['tahun'],
                    'list_matkul' => $krsMatkul,
                ]);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Belum mengajukan atau KRS belum disetujui untuk tahun ajaran aktif saat ini'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function trimNamaDosen($dosen) {
        if ($dosen) {
            $dosen['nm_dosen'] = trim($dosen['nm_dosen']);
            $dosen['gelar'] = trim($dosen['gelar']);

            return $dosen;
        }

        return null;
    }
}
