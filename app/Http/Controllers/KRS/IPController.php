<?php

namespace App\Http\Controllers\KRS;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models
use App\Models\KRS\NilaiAkhirView;

class IPController extends Controller
{
    public function getIPSemester(Request $request) {
        try {
            $user = $this->getUserAuth(); // get data mhs yg req
            $listNilai = NilaiAkhirView::getNilaiAkhirByMhsId($user['mhs_id']);
            $collectionListNilai = $listNilai;
            $countTotal = count($listNilai);

            if ($countTotal > 0) {
                $countTotalMutu = $collectionListNilai->sum('mutu');
                $countNilai = $collectionListNilai->countBy('nilai');

                $countTotalSks = $collectionListNilai->sum(function ($matkul) {
                    return $matkul['matakuliah']['sks'];
                });

                $groupedBySemester = $collectionListNilai->groupBy(function ($matkul) {
                    return  $matkul['matakuliah']['semester'];
                });

                // nilai keseluruhan
                $data = [
                    'total_sks' => $countTotalSks,
                    'total_ip' => ((float) $countTotalMutu / $countTotal),
                    'total_nilai_a' => isset($countNilai['A']) ? $countNilai['A'] : 0,
                    'total_nilai_b' => isset($countNilai['B']) ? $countNilai['B'] : 0,
                    'total_nilai_c' => isset($countNilai['C']) ? $countNilai['C'] : 0,
                    'total_nilai_d' => isset($countNilai['D']) ? $countNilai['D'] : 0,
                    'total_nilai_e' => isset($countNilai['E']) ? $countNilai['E'] : 0,
                ];

                $tempIPSemester = [];

                foreach ($groupedBySemester->toArray() as $index => $item) {
                    $countItem = count($item);
                    $collectionSemester = collect($item);
                    $countMutuSemester = $collectionSemester->sum('mutu');
                    $countNilaiSemester = $collectionSemester->countBy('nilai');

                    $countTotalSksSemester = $collectionSemester->sum(function ($matkul) {
                        return $matkul['matakuliah']['sks'];
                    });

                    $ipSemester = [
                        'semester' => $index,
                        'total_sks' => $countTotalSksSemester,
                        'total_ip' => ((float) $countMutuSemester / $countItem),
                        'total_nilai_a' => isset($countNilaiSemester['A']) ? $countNilaiSemester['A'] : 0,
                        'total_nilai_b' => isset($countNilaiSemester['B']) ? $countNilaiSemester['B'] : 0,
                        'total_nilai_c' => isset($countNilaiSemester['C']) ? $countNilaiSemester['C'] : 0,
                        'total_nilai_d' => isset($countNilaiSemester['D']) ? $countNilaiSemester['D'] : 0,
                        'total_nilai_e' => isset($countNilaiSemester['E']) ? $countNilaiSemester['E'] : 0,
                    ];

                    array_push($tempIPSemester, $ipSemester);
                }

                $data['ip_per_semester'] = $tempIPSemester;

                return $this->successfulResponseJSON([
                    'ip_mahasiswa' => $data
                ]);
            }

            return $this->failedResponseJSON('Data nilai mahasiswa belum tersedia', 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
