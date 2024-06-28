<?php

namespace App\Imports;

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
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        if ($row['qtd'] > 1) {
            $rm = $row['rm'];
            for ($i = 0; $i < $row['qtd']; $i++) {

                $materialCheck = Material::where('registration', $rm)->first();

                if (!$materialCheck) {
                    $newMaterial = Material::create([
                        'registration' => $rm,
                        'secondary_code' => $row['siadi'],
                        'serial_number' => $row['serial'],
                        'description' => $row['descricao'],
                        'observations' => $row['obs'],
                        'value' => str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $row['valor']))),
                        'status' => strtolower($row['status']) == 'ativo' ? 'Ativo' : 'Baixa',
                        'year' => $row['ano'],
                        'created_at' => new DateTime('now'),
                        'group_id' => Group::where('name', $row['grupo'])->first()->id ?? null,
                        'department_id' => Department::where('name', $row['setor'])->first()->id ?? null,
                        'user_id' => Auth::user()->id,
                        'write_off_date_at' => strtolower($row['status']) == 'ativo' ? null : date('Y-m-d H:i:s'),
                    ]);

                    $newMaterial->save();
                }

                $rm++;
            }
        } else {
            $materialCheck = Material::where('registration', $row['rm'])->first();

            if (!$materialCheck) {
                $newMaterial = Material::create([
                    'registration' => $row['rm'],
                    'secondary_code' => $row['siadi'],
                    'serial_number' => $row['serial'],
                    'description' => $row['descricao'],
                    'observations' => $row['observacoes'],
                    'value' => str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $row['valor']))),
                    'status' => strtolower($row['status']) == 'ativo' ? 'Ativo' : 'Baixa',
                    'year' => $row['ano'],
                    'created_at' => new DateTime('now'),
                    'group_id' => Group::where('name', $row['grupo'])->first()->id ?? null,
                    'department_id' => Department::where('name', $row['setor'])->first()->id ?? null,
                    'user_id' => Auth::user()->id,
                ]);

                $newMaterial->save();
            }
        }
    }

    public function rules(): array
    {
        return [
            'rm' => "required|numeric|between:1,18446744073709551615|unique:materials,registration",
            'siadi' => 'nullable|max:191',
            'serial' => 'nullable|max:191',
            'descricao'  => 'nullable|max:400000000',
            'descricao' => 'nullable|max:400000000',
            'valor' => 'required|numeric|between:0,999999999.999',
            'ano' => 'required|date_format:Y',
            'qtd' => 'nullable|numeric|min:1',
        ];
    }
}
