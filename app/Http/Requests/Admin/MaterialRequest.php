<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'registration'  => preg_replace('/\D/', '', $this->registration),
            'secondary_code'  => preg_replace('/\D/', '', $this->secondary_code),
            'value'  => str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $this->value))),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'registration' => "nullable|numeric|between:1,18446744073709551615|unique:materials,registration,$this->id,id,deleted_at,NULL",
            'secondary_code' => "nullable|numeric|between:1,18446744073709551615|unique:materials,secondary_code,$this->id,id,deleted_at,NULL",
            'serial_number' => 'nullable|max:191',
            'description'  => 'nullable|max:400000000',
            'observations' => 'nullable|max:400000000',
            'value' => 'required|numeric|between:0,999999999.999',
            'group_id' => 'required|exists:groups,id',
            'department_id'  => 'required|exists:departments,id',
            'status' => 'required|in:Ativo,Baixa',
            'year' => 'nullable|date_format:Y',
            'quantity' => 'nullable|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'value.between' => 'O valor unitário deve ser entre 0 e 999.999.999,999.',
        ];
    }
}
