<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MaterialBatchRequest extends FormRequest
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
            'description'  => 'nullable|max:400000000',
            'observations' => 'nullable|max:400000000',
            'value' => 'required|numeric|between:0,999999999.999',
            'group_id' => 'required|exists:groups,id',
            'department_id'  => 'required|exists:departments,id',
            'status' => 'required|in:Ativo,Baixa',
            'year' => 'nullable|date_format:Y',
        ];
    }

    public function messages()
    {
        return [
            'value.between' => 'O valor unitário deve ser entre 0 e 999.999.999,999.',
        ];
    }
}
