<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// ? Models - Tables
use App\Models\Users\Staff;
use App\Models\Users\User;

// ? Models - Views
use App\Models\Users\AllStaffView;

class StaffController extends Controller
{
    public function addUser(Request $request) {
        try {
            $request->validate([
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|max:64|regex:/^\S*$/u',
                'nama' => 'required|string',
                'no_hp' => 'required|string',
                'is_marketing' => 'nullable|boolean',
                'is_akademik' => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            // insert ke table staff dulu
            $insertStaff = Staff::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'is_marketing' => $request->is_marketing ?? false,
                'is_akademik' => $request->is_akademik ?? false,
            ]);

            if ($insertStaff) {
                $kdUser = 'STF-' . $insertStaff->staff_id;
                $hashedPassword = Hash::make($request->password);

                $account = [
                    'kd_user' => $kdUser,
                    'email' => $request->email,
                    'password' => $hashedPassword,
                    'image' => 'college_student.png',
                    'is_staff' => true,
                ];

                $insertAccount = User::create($account);

                if ($insertAccount) {
                    $updateStaff = Staff::where('staff_id', $insertStaff->staff_id)
                        ->update([
                            'user_id' => $insertAccount->id
                        ]);

                    if ($updateStaff) {
                        DB::commit();

                        return $this->successfulResponseJSONV2('Akun staff berhasil ditambahkan');
                    }
                }
            }

            DB::rollBack();

            return $this->failedResponseJSON('Akun staff gagal ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function getAllStaff(Request $request) {
        try {
            $job = $request->query('job');

            if ($job) {
                $jobName = strtolower($job);
                $allStaff = AllStaffView::where($jobName, true)
                    ->orderBy('staff_id', 'DESC')
                    ->get();
            } else {
                $allStaff = AllStaffView::orderBy('staff_id', 'DESC')->get();
            }

            if ($allStaff->count() > 0) {
                foreach ($allStaff as $index => $item) {
                    $image = config('app.url') . 'storage/users/images/' . $item['image'];
                    $item['image'] = $image;
                    $allStaff[$index] = $item;
                }
            }

            return $this->successfulResponseJSON([
                'total_staff' => $allStaff->count(),
                'list_staff' => $allStaff,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
