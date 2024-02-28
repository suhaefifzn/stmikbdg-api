<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - view
use App\Models\Users\DosenView;

class DosenController extends Controller
{
    public function getAllDosenAktif() {
        try {
            $allDosenAktif = DosenView::getListDosenAktif();

            foreach ($allDosenAktif as $index => $item) {
                $cleanDataDosen[$index] = [
                    'dosen_id' => $item['dosen_id'],
                    'nm_dosen' => trim($item['nm_dosen']),
                    'kd_dosen' => trim($item['kd_dosen']),
                    'gelar' => $item['gelar'],
                ];
            }

            return $this->successfulResponseJSON([
                'dosen_aktif' => $cleanDataDosen,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
