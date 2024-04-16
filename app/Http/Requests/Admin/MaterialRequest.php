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
            'registration' => 'required|max:191',
            'secondary_code' => 'nullable|max:191',
            'serial_number' => 'nullable|max:191',
            'description'  => 'nullable|max:400000000',
            'observations' => 'nullable|max:400000000',
            'value' => 'required|numeric|between:0,999999999.999',
            'group_id' => 'required|exists:groups,id',
            'department_id'  => 'nullable|exists:departments,id',
            'status' => 'required|in:Ativo,Baixa',
        ];
    }

    public function messages()
    {
        return [
            'value.between' => 'O valor unitário deve ser entre 0 e 999.999.999,999.',
        ];
    }
}
