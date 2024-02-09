<?php

namespace App\Http\Controllers\KRS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Exception
use App\Exceptions\ErrorHandler;

// ? Models - view
use App\Models\KRS\MatKulView;
use App\Models\TahunAjaranView;

class MatKulController extends Controller
{
    public function getMataKuliah(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'tahun_ajaran' => 'required',
                'semester' => 'nullable',
                'smt' => 'nullable'
            ]);
            $isDosen = auth()->user()->is_dosen;

            if ($isDosen) {
                // TODO: Jika user adalah dosen
            }

            // TODO: Jika user adalah mahasiswa
            return self::getMataKuliahByMahasiswa($validatedData);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getMataKuliahByMahasiswa($filter) {
        $mahasiswa = $this->getUserAuth();
        $filter['jur_id'] = $mahasiswa['jur_id'];
        $mataKuliah = MatKulView::getMatkul($filter);

        return $this->successfulResponseJSON([
            'mata_kuliah' => $mataKuliah,
        ]);
    }
}
