<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $userCheck = User::where('email', $row['e_mail'])->first();

        if (!$userCheck) {
            $newUser = User::create([
                'name'      => $row['nome'],
                'email'     => $row['e_mail'],
                'password'  => bcrypt($row['e_mail']),
                'created_at' => new DateTime('now'),
                'department_id' => Department::where('name', $row['setor'])->first()->id ?? null,
            ]);

            $newUser->save();
            $newUser->syncRoles('Usuário');
        }
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|min:2|max:100',
            'e_mail' => 'required|min:8|max:100|email|unique:users,email,NULL,id,deleted_at,NULL',
        ];
    }
}
