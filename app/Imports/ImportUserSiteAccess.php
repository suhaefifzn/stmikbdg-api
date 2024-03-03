<?php

namespace App\Imports;

use App\Models\Users\UserSite;
use App\Models\Users\UserView;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportUserSiteAccess implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $kdUser = $row['kd_user'];
        $user = UserView::where('kd_user', 'like', '%' . $kdUser .'%')->first();

        if ($user) {
            $userSiteAccess = [
                'user_id' => $user['id'],
                'site_id' => $row['site_id']
            ];

            return UserSite::create($userSiteAccess);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Id user tidak ditemukan',
        ]);
    }

    public function rules(): array {
        return [
            '*.kd_user' => 'required',
            '*.site_id' => 'required|exists:sites,id',
        ];
    }
}
