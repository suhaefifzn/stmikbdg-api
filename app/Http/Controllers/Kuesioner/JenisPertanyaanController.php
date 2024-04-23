<?php

namespace App\Http\Controllers\Kuesioner;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - Views
use App\Models\Kuesioner\JenisPertanyaanView;

// ? Models - Tables
use App\Models\Kuesioner\JenisPertanyaan;

class JenisPertanyaanController extends Controller {
    public function getAllJenisPertanyaan() {
        try {
            $allJenisPertanyaan = JenisPertanyaanView::all();

            return $this->successfulResponseJSON([
                'jenis_pertanyaan' => $allJenisPertanyaan,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
