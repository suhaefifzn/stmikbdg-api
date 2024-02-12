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
        return new User([
            'kd_user' => self::setKdUser($row['kd_user'], self::toBoolean($row['is_dosen'])),
            'is_dosen' => self::toBoolean($row['is_dosen']),
            'is_admin' => self::toBoolean($row['is_admin']),
            'email' => $row['email'],
            'password' => self::setPasswordUser($row['password']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function rules(): array {
        return [
            '*.kd_user' => 'required|unique:users,kd_user',
            '*.is_dosen' => 'required|boolean',
            '*.is_admin' => 'nullable|boolean',
            '*.email' => 'required|email:dns|unique:users,email',
            '*.password' => 'required|string|min:8|max:64|regex:/^\S*$/u'
        ];
    }

    private function setKdUser($kdUser, $isDosen) {
        return $isDosen ? 'DSN-' . $kdUser : 'MHS-' . $kdUser;
    }

    private function setPasswordUser($password) {
        return Hash::make($password);
    }

    private function toBoolean($variable) {
        return filter_var($variable, FILTER_VALIDATE_BOOLEAN);
    }
}
