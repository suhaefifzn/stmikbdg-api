<?php

namespace App\Http\Controllers\Surat;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ? Models - Tables
use App\Models\Surat\Kategori;

class KategoriController extends Controller
{
    public function addKategori(Request $request) {
        try {
            $request->validate([
                'nama' => 'required|string',
            ]);

            DB::beginTransaction();

            $insert = Kategori::insert($request->all());

            if ($insert) {
                DB::commit();

                return $this->successfulResponseJSONV2('Kategori surat berhasil ditambahkan');
            }

            DB::rollBack();

            return $this->failedResponseJSON('Kategori surat gagal ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function getListKategori(Request $request) {
        try {
            $kategoriId = $request->query('kategori_id');

            if ($kategoriId) {
                $kategori = Kategori::where('kategori_id', (int) $kategoriId)->first();
            } else {
                $kategori = Kategori::orderBy('kategori_id', 'DESC')->get();
            }

            return $this->successfulResponseJSON([
                'kategori' => $kategori
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function updateKategori(Request $request) {
        try {
            $request->validate([
                'kategori_id' => 'required|integer',
                'nama' => 'required|string'
            ]);

            $kategori = Kategori::where('kategori_id', $request->kategori_id)->first();

            if ($kategori) {
                DB::beginTransaction();

                $update = Kategori::where('kategori_id', $request->kategori_id)
                    ->update([
                        'nama' => $request->nama
                    ]);

                if ($update) {
                    DB::commit();

                    return $this->successfulResponseJSONV2('Kategori berhasil diperbarui', 200);
                }

                DB::rollBack();

                return $this->failedResponseJSON('Kategori gagal diperbarui', 500);
            }

            return $this->failedResponseJSON('Kategori tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function deleteKategori(Request $request) {
        try {
            $request->validate([
                'kategori_id' => 'required|integer'
            ]);

            $kategori = Kategori::where('kategori_id', $request->kategori_id)->first();

            if ($kategori) {
                DB::beginTransaction();

                $update = Kategori::where('kategori_id', $request->kategori_id)->delete();

                if ($update) {
                    DB::commit();

                    return $this->successfulResponseJSONV2('Kategori berhasil dihapus', 200);
                }

                DB::rollBack();

                return $this->failedResponseJSON('Kategori gagal dihapus', 500);
            }

            return $this->failedResponseJSON('Kategori tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }
}
