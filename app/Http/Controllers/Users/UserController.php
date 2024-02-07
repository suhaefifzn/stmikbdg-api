<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// ? Models - Table
use App\Models\Users\User;

// ? Models - View
use App\Models\Users\UserView;

class UserController extends Controller
{
    public function addNewUser(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'kd_user' => 'required|string',
                'is_dosen' => 'required|boolean',
                'email' => 'required|string|email:dns|unique:users',
                'password' => 'required|string|min:8|max:64|regex:/^\S*$/u'
            ]);

            $tempKdUser = $validatedData['is_dosen']
                ? 'DSN-' . $validatedData['kd_user']
                : 'MHS-' . $validatedData['kd_user'];

            $hashPassword = Hash::make($validatedData['password']);
            $validatedData['kd_user'] = $tempKdUser;
            $validatedData['password'] = $hashPassword;
            $validatedData['created_at'] = now();
            $validatedData['updated_at'] = now();

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

    public function getMyProfile()
    {
        try {
            $user = $this->getUserAuth();
            $account = auth()->user();

            return $this->successfulResponseJSON([
                'user_info' => $user,
                'account_info' => $account,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
