<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('employee')->user_id, 'id')
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'name.required' => 'El nombre es obligatorio',
            'manager_id.exists' => 'El manager seleccionado no existe',
            'tasca_id.required' => 'La tasca es obligatoria',
            'tasca_id.exists' => 'La tasca seleccionada no existe'
        ];
    }
}
