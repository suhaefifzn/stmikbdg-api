<?php

namespace App\Http\Controllers\SIKPS;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use App\Models\SIKPS\PengajuanSkripsiDiterimaView;
use Illuminate\Http\Request;

class SIKPSController extends Controller
{
    public function getAllPengajuanSkripsiDiterima() {
        try {
            $allPengajuanSkripsi = PengajuanSkripsiDiterimaView::select('proposal_id', 'judul', 'deskripsi', 'file_proposal')
                ->get();

                /**
                 * set link file proposal
                 */
                $tempPengajuan = [];
                
                foreach ($allPengajuanSkripsi as $index => $item) {
                    $linkFile = 'http://103.119.67.164:18088/sikps/' . $item['file_proposal'];

                    array_push($tempPengajuan, [
                        'judul' => $item['judul'],
                        'link_proposal' => $linkFile,
                        'deskripsi' => $item['deskripsi'],
                    ]);
                }

            return $this->successfulResponseJSON([
                'pengajuan_skripsi' => $tempPengajuan
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function addHasilDeteksi(Request $request) {
        /**
         * TODO:
         * store hasil deteksi kemiripan proposal skripsi ke db
         */
    }
}
