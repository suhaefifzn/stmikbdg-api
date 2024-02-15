<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorHandler;
use Illuminate\Http\Request;

// ? Models - view
use App\Models\JurusanView;

class JurusanController extends Controller
{
    public function getJurusanAktif() {
        try {
            $listJurusan = JurusanView::getJurusanAktif();

            return $this->successfulResponseJSON([
                'jurusan_aktif' => $listJurusan,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
