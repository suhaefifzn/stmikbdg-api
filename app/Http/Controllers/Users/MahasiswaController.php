<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Exceptions\ErrorHandler;
use App\Exports\MahasiswaExport;
use Illuminate\Http\Request;

// ? Mahasiswa - View
use App\Models\Users\Mahasiswa;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    public function getAllMahasiswa(Request $request) {
        try {
            if (count($request->query()) > 0) {
                $isSkripsi = $request->query('skripsi')
                    ? filter_var($request->query('skripsi'), FILTER_VALIDATE_BOOLEAN)
                    : null;
                $tahunMasuk = $request->query('tahun_masuk')
                    ? $request->query('tahun_masuk')
                    : null;
                $isDownload = $request->query('download')
                    ? filter_var($request->query('download'), FILTER_VALIDATE_BOOLEAN)
                    : null;

                $fileName = $isDownload ? 'DATA-MAHASISWA' : '';

                if ($isSkripsi and $tahunMasuk) { // jIKA ADA FILTER SKRIPSI DAN TAHUN MASUK
                    $filter = [
                        'skripsi' => $isSkripsi,
                        'tahun_masuk' => $tahunMasuk,
                    ];

                    if ($isDownload) {
                        $excelFileName = $fileName
                            . '_WITH-JUDUL-SKRIPSI_MASUK-TAHUN-' . $tahunMasuk . '.xlsx';

                        return Excel::download(new MahasiswaExport($filter), $excelFileName);
                    }
                } else if ($isSkripsi) { // JIKA ADA FILTER SKRIPSI SAJA
                    $filter = [
                        'skripsi' => $isSkripsi,
                    ];

                    if ($isDownload) {
                        $excelFileName = $fileName
                            . '_WITH-JUDUL-SKRIPSI.xlsx';

                        return Excel::download(new MahasiswaExport($filter), $excelFileName);
                    }
                } else if ($tahunMasuk) { // JIKA ADA FILTER TAHUN MASUK SAJA
                    $filter = [
                        'tahun_masuk' => $tahunMasuk,
                    ];

                    if ($isDownload) {
                        $excelFileName = $fileName
                            . '_MASUK-TAHUN-' . $tahunMasuk . '.xlsx';

                        return Excel::download(new MahasiswaExport($filter), $excelFileName);
                    }
                } else if ($isDownload) { // JIKA ADA FILTER DOWNLOAD SAJA
                    $excelFileName = $fileName . '.xlsx';

                    return Excel::download(new MahasiswaExport(), $excelFileName);
                }

                $mahasiswa = Mahasiswa::getMahasiswaByFilter($filter);

                return $this->successfulResponseJSON([
                    'mahasiswa' => $mahasiswa
                ]);
            }

            $mahasiswa = Mahasiswa::all();

            return $this->successfulResponseJSON([
                'mahasiswa' => $mahasiswa
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
