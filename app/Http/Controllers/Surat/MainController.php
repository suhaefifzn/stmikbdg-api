<?php

namespace App\Http\Controllers\Surat;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - Tables
use App\Models\Surat\Disposisi;
use App\Models\Surat\SuratKeluar;
use App\Models\Surat\SuratMasuk;
use App\Models\Surat\Arsip;
use App\Models\Surat\ArsipLocation;

// ? Models - Views
use App\Models\Users\UserView;

class MainController extends Controller
{
    public function getStatistik() {
        try {
            $countSuratMasuk = SuratMasuk::all()->count();
            $countSuratKeluar = SuratKeluar::all()->count();
            $countDisposisi = Disposisi::all()->count();
            $countUsers = UserView::where('is_staff', true)->get()->count();

            return $this->successfulResponseJSON([
                'statistik' => [
                    'total_surat_masuk' => $countSuratMasuk,
                    'total_surat_keluar' => $countSuratKeluar,
                    'total_disposisi' => $countDisposisi,
                    'total_pengguna' => $countUsers,
                ]
                ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getArsip() {
        try {
            $suratMasuk = Arsip::whereNot('surat_masuk_id', null)
                ->orderBy('tgl_arsip', 'DESC')
                ->get();

            $suratKeluar = Arsip::whereNot('surat_keluar_id', null)
                ->orderBy('tgl_arsip', 'DESC')
                ->get();

            return $this->successfulResponseJSON([
                'list_arsip' => [
                    'surat_masuk' => $suratMasuk,
                    'surat_keluar' => $suratKeluar
                ]
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getArsipLokasi() {
        try {
            $lokasiArsip = ArsipLocation::all();

            return $this->successfulResponseJSON([
                'list_lokasi_arsip' => $lokasiArsip
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
