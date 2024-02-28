<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorHandler;
use Illuminate\Http\Request;

// ? Models - view
use App\Models\KampusView;

class KampusController extends Controller
{
    public function getListKampus() {
        try {
            $listKampus = KampusView::all();

            return $this->successfulResponseJSON([
                'list_kampus' => $listKampus,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
