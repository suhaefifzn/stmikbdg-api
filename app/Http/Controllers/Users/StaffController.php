<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

// ? Models - Tables
use App\Models\Users\Staff;
use App\Models\Users\User;

// ? Models - Views
use App\Models\Users\AllStaffView;
use App\Models\Users\Site;
use App\Models\Users\UserSite;
use App\Models\Users\UserSitesView;
use App\Models\Users\UserView;

class StaffController extends Controller
{
    public function addStaff(Request $request) {
        try {
            $request->validate([
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|max:64|regex:/^\S*$/u',
                'nama' => 'required|string',
                'no_hp' => 'required|string',
                'is_marketing' => 'nullable|boolean',
                'is_akademik' => 'nullable|boolean',
                'is_secretary' => 'nullable|boolean'
            ]);

            DB::beginTransaction();

            // insert ke table staff dulu
            $insertStaff = Staff::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'is_marketing' => $request->is_marketing ?? false,
                'is_akademik' => $request->is_akademik ?? false,
                'is_secretary' => $request->is_secretary ?? false,
            ]);

            if ($insertStaff) {
                $kdUser = 'STF-' . $insertStaff->staff_id;
                $hashedPassword = Hash::make($request->password);

                $account = [
                    'kd_user' => $kdUser,
                    'email' => $request->email,
                    'password' => $hashedPassword,
                    'image' => config('app.url') . 'storage/users/images/college_student.png', // default awal,
                    'is_staff' => true,
                ];

                $insertAccount = User::create($account);

                $karyawanSites = Site::where('is_staff', true)
                    ->select('id')
                    ->get();

                $siteAccess = array_map(function ($item) use  ($insertAccount) {
                    $item['user_id'] = $insertAccount->id;
                    $item['site_id'] = $item['id'];

                    unset($item['id']);

                    return $item;
                }, $karyawanSites->toArray());

                $insertSiteAccess = UserSite::insert($siteAccess);

                if ($insertAccount and $insertSiteAccess) {
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

            return $this->successfulResponseJSON([
                'total_staff' => $allStaff->count(),
                'list_staff' => $allStaff,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getAccount(Request $request) {
        try {
            $staffId = $request->query('staff_id');
            $staff = AllStaffView::where('staff_id', (int) $staffId)->first();

            if ($staff) {
                $account = UserView::where('id', $staff['user_id'])->first();

                $filteredAccount = collect($account)->filter(function ($item) {
                    if ($item) {
                        return $item;
                    }
                });

                $filteredUser = collect($staff)->filter(function ($item) {
                    if ($item) {
                        return $item;
                    }
                });

                $siteAccess = UserSitesView::where('user_id', $account['id'])->get();

                $user = [
                    'account' => $filteredAccount,
                    'profile' => $filteredUser,
                    'site_access' => $siteAccess
                ];

                return $this->successfulResponseJSON([
                    'user' => $user
                ]);
            }

            return $this->failedResponseJSON('Staff tidak ditemukan', 404);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function update(Request $request) {
        try {
            $staffId = $request->staff_id;
            $staff = AllStaffView::where('staff_id', (int) $staffId)->first();

            if ($staff) {
                $request->validate([
                    'staff_id' => 'required|integer',
                    'nama' => 'required|string',
                    'no_hp' => 'required|string',
                    'is_marketing' => 'nullable|boolean',
                    'is_akademik' => 'nullable|boolean',
                    'is_secretary' => 'nullable|boolean',
                    'email' => [
                        'required',
                        'email',
                        Rule::unique('users')->ignore($staff['user_id']),
                    ],
                ]);

                DB::beginTransaction();

                // update ke table staff dulu
                $updateStaff = Staff::where('staff_id', (int) $staffId)
                    ->update([
                        'nama' => $request->nama,
                        'email' => $request->email,
                        'no_hp' => $request->no_hp,
                        'is_marketing' => $request->is_marketing ?? false,
                        'is_akademik' => $request->is_akademik ?? false,
                        'is_secretary' => $request->is_secretary ?? false,
                    ]);

                if ($updateStaff) {
                    $updateEmail = User::where('id', $staff['user_id'])
                        ->update([
                            'email' => $request->email
                        ]);

                    if ($updateEmail) {
                        DB::commit();

                        return $this->successfulResponseJSONV2('Staff berhasil diubah');
                    }
                }

                DB::rollBack();

                return $this->failedResponseJSON('Staff gagal diubah');
            }

            return $this->failedResponseJSON('Staff tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }

    public function delete(Request $request) {
        try {
            $staffId = $request->staff_id;
            $staff = AllStaffView::where('staff_id', (int) $staffId)->first();

            if ($staff) {
                DB::beginTransaction();

                $deleteStaff = Staff::where('staff_id', (int) $request->staff_id)->delete();
                $deleteAccount = User::where('id', $staff['user_id'])->delete();

                if ($deleteStaff and $deleteAccount) {
                    DB::commit();

                    return $this->successfulResponseJSONV2('Staff berhasil dihapus');
                }

                DB::rollBack();

                return $this->failedResponseJSON('Staff gagal dihapus');
            }

            return $this->failedResponseJSON('Staff tidak ditemukan', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorHandler::handle($e);
        }
    }
}
