<?php

namespace App\Imports;

ini_set('max_execution_time', 300);

use App\Models\Group;
use App\Models\Material;
use App\Models\Views\Department;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MaterialsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $group = Group::where('code', $row['cod_grupo'])->first();

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $department = Department::where('id', $row['id_setor'])->first();
        } else {
            $department = Department::where(function ($query) use ($row) {
                $query->where('id', $row['id_setor']);
                $query->whereIn('id', Auth::user()->departments->pluck('id')->toArray());
            })->first();
        }

        return new Material([
            'registration' => preg_replace('/\D/', '', $row['rm']),
            'secondary_code' => preg_replace('/\D/', '', $row['siads']),
            'description' => $row['descricao'],
            'observations' => $row['obs'],
            'value' => str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $row['valor']))),
            'status' => 'Ativo',
            'year' => $row['ano'] ?? date('Y'),
            'created_at' => new DateTime('now'),
            'group_id' => $group->id ?? null,
            'department_id' => $department->id ?? null,
            'user_id' => Auth::user()->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'rm' => 'nullable|numeric|between:1,18446744073709551615|unique:materials,registration,NULL,id,deleted_at,NULL',
            'siads' => 'nullable|numeric|between:1,18446744073709551615|unique:materials,secondary_code,NULL,id,deleted_at,NULL',
            'descricao' => 'nullable|max:400000000',
            'valor' => 'required|numeric|between:0,999999999.999',
            'ano' => 'nullable|date_format:Y',
        ];
    }
}
