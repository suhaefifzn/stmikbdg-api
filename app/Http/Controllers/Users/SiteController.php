<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\ErrorHandler;
use App\Exceptions\ExcelImportException;
use App\Http\Controllers\Controller;
use App\Imports\ImportUserSiteAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

// ? Models - table
use App\Models\Users\Site;
use App\Models\Users\User;
use App\Models\Users\UserSite;

// ? Models - view
use App\Models\Users\UserSitesView;

class SiteController extends Controller
{
    public function getAllSites(Request $request) {
        try {
            $siteId = $request->query('site_id');

            if ($siteId) {
                $tempSiteUsers = UserSitesView::where('site_id', $siteId)->get();
                $siteUsers = [];

                foreach ($tempSiteUsers as $index => $item) {
                    $userEmail = User::where('id', $item['user_id'])->first()['email'];
                    $item['user_email'] = $userEmail;
                    $siteUsers[$index] = $item;
                }

                return $this->successfulResponseJSON([
                    'site_users' => array_values($siteUsers),
                ]);
            }

            $sites = Site::all();

            return $this->successfulResponseJSON([
                'sites' => $sites,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function postUserSite(Request $request) {
        try {
            if ($request->query('import')) {
                if ($request->query('import') === 'excel') {
                    $request->validate([
                        'file' => 'required|mimes:xlsx,xls|max:2048'
                    ]);
                    $excel = $request->file('file');

                    return self::importUserSiteAccessFromExcel($excel);
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Metode import yang tersedia saat ini adalah menggunakan file excel'
                    ], 400);
                }
            }

            $request->validate([
                'user_id' => 'required|exists:users,id',
                'site_id' => 'required|exists:sites,id',
            ]);

            $checkAccess = UserSite::where('user_id', $request->user_id)
                ->where('site_id', $request->site_id)
                ->first();

            if ($checkAccess) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'User telah memiliki akses ke web tersebut'
                ], 400);
            }

            UserSite::insert([
                'user_id' => $request->user_id,
                'site_id' => $request->site_id,
            ]);

            return $this->successfulResponseJSON([
                'user_id' => $request->user_id,
                'site_id' => $request->site_id,
            ], 'Akses user ke web berhasil ditambahkan', 201);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function postNewSite(Request $request) {
        try {
            $data = $request->validate([
                'url' => 'required|string',
                'name' => 'required|string',
                'is_dev' => 'required|boolean',
                'is_mhs' => 'required|boolean',
                'is_admin' => 'required|boolean',
                'is_dosen' => 'required|boolean',
                'is_doswal' => 'required|boolean',
                'is_prodi' => 'required|boolean',
            ]);

            $validatedURL = filter_var($request->url, FILTER_VALIDATE_URL);
            $data['url'] = $validatedURL;
            $urlExists = Site::where('url', 'like', '%'  . $validatedURL . '%')->first();

            if ($urlExists) {
                return $this->failedResponseJSON('Alamat web sudah pernah ditambahkan', 400);
            }

            DB::beginTransaction();
            $insert = Site::insert($data);

            if ($insert) {
                DB::commit();
                return $this->successfulResponseJSONV2('Alamat web berhasil ditambahkan', 201);
            }

            DB::rollBack();
            return $this->failedResponseJSON('Alamat web gagal ditambahkan', 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function deleteUserSiteAccess(Request $request) {
        try {
            $request->validate([
                'site_id' => 'required',
                'user_id'=> 'required',
            ]);

            $deletedAccess = UserSite::where('site_id', $request->site_id)
                ->where('user_id', $request->user_id)
                ->delete();

            if ($deletedAccess) {
                return $this->successfulResponseJSON([
                    'user_id' => $request->user_id,
                    'site_id' => $request->site_id,
                ], 'Akses user ke url berhasil dihapus');
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Gagal menghapus akses user ke web'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function importUserSiteAccessFromExcel($excel) {
        try {
            $fileName = $excel->hashName();
            $path = $excel->storeAs('public/excel/', $fileName);

            Excel::import(new ImportUserSiteAccess, storage_path('app/public/excel/' . $fileName));
            Storage::delete($path);

            return response()->json([
                'status' => 'success',
                'message' => 'Akses user ke web berhasil ditambahkan'
            ], 201);
        } catch (ExcelImportException $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ], $e->getCode());
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
