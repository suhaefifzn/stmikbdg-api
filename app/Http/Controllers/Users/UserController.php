<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

// ? Models - Table
use App\Models\Users\User;
use App\Models\Users\UserSite;

// ? Models - View
use App\Models\Users\UserView;

// ? Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportUser;
use App\Exceptions\ExcelImportException;

class UserController extends Controller {
    public function addNewUser(Request $request) {
        try {
            if ($request->query('import')) {
                if ($request->query('import') === 'excel') {
                    $request->validate([
                        'file' => 'required|mimes:xlsx,xls|max:2048'
                    ]);
                    $excel = $request->file('file');
                    return self::importUserFromExcel($excel);
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Metode import yang tersedia saat ini adalah menggunakan file excel'
                    ], 400);
                }
            }

            $validatedData = $request->validate([
                'kd_user' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|max:64|regex:/^\S*$/u',
                'is_admin' => 'nullable|boolean',
                'is_mhs' => 'nullable|boolean',
                'is_dev' => 'nullable|boolean',
                'is_dosen' => 'nullable|boolean',
                'is_doswal' => 'nullable|boolean',
                'is_prodi' => 'nullable|boolean',
            ]);

             if ($validatedData['is_admin'] and $validatedData['is_mhs']) {
                // jika admin dan mhs true
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Maaf, akun mahasiswa tidak bisa menjadi Admin'
                ], 400);
            } else if ($validatedData['is_admin'] and !$validatedData['is_dosen']) {
                // jika admin saja
                $tempKdUser = 'ADM-' . $validatedData['kd_user'];
            } else {
                // jika admin dan dosen true, maka jadikan sebagai dosen
                // selain itu maka ia mahasiswa
                $tempKdUser = $validatedData['is_dosen']
                    ? 'DSN-' . $validatedData['kd_user']
                    : 'MHS-' . $validatedData['kd_user'];
            }

            $hashPassword = Hash::make($validatedData['password']);
            $validatedData['kd_user'] = $tempKdUser;
            $validatedData['password'] = $hashPassword;
            $validatedData['created_at'] = now();
            $validatedData['updated_at'] = now();
            $validatedData['is_dosen'] = $request->is_dosen ?? false;
            $validatedData['is_admin'] = $request->is_admin ?? false;
            $validatedData['is_mhs'] = $request->is_mhs ?? false;
            $validatedData['is_dev'] = $request->is_dev ?? false;
            $validatedData['is_doswal'] = $request->is_doswal ?? false;
            $validatedData['is_prodi'] = $request->is_prodi ?? false;
            $validatedData['image'] = 'college_student.png';

            // validate kd_user lagi
            Validator::make(['kd_user' => $tempKdUser], [
                'kd_user' => 'unique:users,kd_user'
            ])->validate();

            User::insert($validatedData);

            return $this->successfulResponseJSON([
                'user' => [
                    'kd_user' => $validatedData['kd_user'],
                    'email' => $validatedData['email'],
                ],
            ], 'User berhasil ditambahkan', 201);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function importUserFromExcel($excel) {
        try {
            $fileName = $excel->hashName();
            $path = $excel->storeAs('public/excel/', $fileName);

            Excel::import(new ImportUser, storage_path('app/public/excel/' . $fileName));

            Storage::delete($path);

            return response()->json([
                'status' => 'success',
                'message' => 'Data user pada file excel berhasil ditambahkan'
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

    public function getMyProfile() {
        try {
            $user = $this->getUserAuth();
            $account = auth()->user();

            return $this->successfulResponseJSON([
                'profile' => $user,
                'account' => [
                    'email' => $account['email'],
                    'image' => config('app.url')
                        . 'storage/users/images/'
                        . auth()->user()->image,
                    'is_dosen' => $account['is_dosen'],
                    'is_admin' => $account['is_admin'],
                    'is_mhs' => $account['is_mhs'],
                    'is_dev' => $account['is_dev'],
                    'is_doswal' => $account['is_doswal'],
                    'is_prodi' => $account['is_prodi'],
                ],
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function putMyPassword(Request $request) {
        try {
            $validatedData = $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|max:64|regex:/^\S*$/u'
            ]);

            if (!Hash::check($validatedData['current_password'], auth()->user()->password)) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Password sekarang tidak sesuai'
                ], 404);
            }

            $newPassword = Hash::make($validatedData['new_password']);

            User::where('kd_user', auth()->user()->kd_user)
                    ->update([
                        'password'=> $newPassword,
                        'updated_at' => now(),
                    ]);

            return $this->successfulResponseJSON([
                'email' => auth()->user()->email,
            ], 'Password berhasil diperbaharui');
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getUserList(Request $request) {
        try {
            $isDosen = $request->query('is_dosen')
                        ? filter_var($request->query('is_dosen'), FILTER_VALIDATE_BOOLEAN)
                        : null;
            $isAdmin = $request->query('is_admin')
                        ? filter_var($request->query('is_admin'), FILTER_VALIDATE_BOOLEAN)
                        : null;
            $isMhs = $request->query('is_mhs')
                        ? filter_var($request->query('is_mhs'), FILTER_VALIDATE_BOOLEAN)
                        : null;
            $isDev = $request->query('is_dev')
                        ? filter_var($request->query('is_dev'), FILTER_VALIDATE_BOOLEAN)
                        : null;

            // initial value
            $users = [];

            if ($isDosen or $isAdmin or $isMhs or $isDev) {
                $filter = [
                    'is_dosen' => $isDosen,
                    'is_admin' => $isAdmin,
                    'is_mhs' => $isMhs,
                    'is_dev' => $isDev,
                ];

                $users = UserView::getAllUsers($filter);
            } else {
                $users = UserView::getAllUsers();
            }

            return $this->successfulResponseJSON([
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            return $e;
            return ErrorHandler::handle($e);
        }
    }

    public function addProfileImage(Request $request) {
        try {
            $request->validate([
                'image' => 'required|mimes:png,jpg|max:1024',
            ]);

            $image = $request->file('image');
            $fileName = $image->hashName();
            $image->storeAs('public/users/images/', $fileName);

            // cek old image
            $oldImage = auth()->user()->image;

            if ($oldImage !== 'college_student.png') {
                $pathOldImage = 'public/users/images/' . auth()->user()->image;
                Storage::delete($pathOldImage);
            }

            User::where('id', auth()->user()->id)
                ->update([
                    'image' => $fileName,
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Foto profile berhasil diperbaharui.',
                'data' => [
                    'image' => config('app.url')
                        . 'storage/users/images/'
                        . $fileName,
                ],
            ], 200);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function deleteUserById($userId) {
        try {
            $user = UserView::where('id', $userId)->first();

            if ($user) {
                /**
                 * Menghapus user maka akan menghapus seluruh akses ke web jua
                 */
                UserSite::where('user_id', $userId)->delete();
                User::where('id', $userId)->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'User dengan email ' . $user['email'] . ' berhasil dihapus'
                ], 200);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'User dengan id ' . $userId . ' tidak ditemukan'
            ], 400);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
