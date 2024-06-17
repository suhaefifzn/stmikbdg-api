<?php

namespace App\Http\Controllers\Pengumuman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;

// ? Models - Tables
use App\Models\Perkuliahan\Pengumuman;

class AdminController extends Controller
{
    public function getListPengumuman() {
        try {
            $listPengumuman = Pengumuman::orderBy('tgl_dikirim', 'DESC')
                ->distinct('tgl_dikirim')
                ->get();

            return $this->successfulResponseJSON([
                'list_pengumuman' => $listPengumuman
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
