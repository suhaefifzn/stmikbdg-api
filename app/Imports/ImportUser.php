<?php

namespace App\Imports;

use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class ImportUser implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = [
            'kd_user' => self::setKdUser(
                $row['kd_user'], self::toBoolean($row['is_dosen']), self::toBoolean($row['is_admin'])
            ),
            'is_dosen' => self::toBoolean($row['is_dosen']),
            'is_admin' => self::toBoolean($row['is_admin']),
            'is_mhs' => self::toBoolean($row['is_mhs']),
            'is_dev' => self::toBoolean($row['is_dev']),
            'is_doswal' => self::toBoolean($row['is_doswal']),
            'is_prodi' => self::toBoolean($row['is_prodi']),
            'email' => $row['email'],
            'password' => self::setPasswordUser($row['password']),
            'image' => 'college_student.png', // default sementara
        ];

        return User::create($user);
    }

    public function rules(): array {
        return [
            '*.kd_user' => 'required|unique:users,kd_user',
            '*.is_dosen' => 'nullable|boolean',
            '*.is_admin' => 'nullable|boolean',
            '*.is_mhs' => 'nullable|boolean',
            '*.is_dev' => 'nullable|boolean',
            '*.is_doswal' => 'nullable|boolean',
            '*.is_prodi' => 'nullable|boolean',
            '*.email' => 'required|email|unique:users',
            '*.password' => 'required|string|min:8|max:64|regex:/^\S*$/u'
        ];
    }

    private function setKdUser($kdUser, $isDosen, $isAdmin) {
        $tempKdUser = $isDosen ? 'DSN-' . $kdUser : 'MHS-' . $kdUser;

        if ($isAdmin and !$isDosen) {
            $tempKdUser = 'ADM-' . $kdUser;
        }

        return $tempKdUser;
    }

    private function setPasswordUser($password) {
        return Hash::make($password);
    }

    private function toBoolean($variable) {
        return filter_var($variable, FILTER_VALIDATE_BOOLEAN);
    }
}
