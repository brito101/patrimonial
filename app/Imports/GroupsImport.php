<?php

namespace App\Imports;

use App\Models\Group;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GroupsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $groupCheck = Group::where('name', $row['nome'])->where('code', $row['codigo'])->first();

        if (!$groupCheck) {
            $newGroup = Group::create([
                'code'      => $row['codigo'],
                'name'     => $row['nome'],
                'created_at' => new DateTime('now'),
                'user_id' => Auth::user()->id,
            ]);

            $newGroup->save();
        }
    }

    public function rules(): array
    {
        return [
            'codigo' => 'nullable|max:191',
            'nome' => 'required|max:191'
        ];
    }
}
