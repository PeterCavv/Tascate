<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('employee'));
    }

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
            ],
            'manager_id' => [
                'exists:managers,id'
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
            'manager_id.exists' => 'El manager seleccionado no existe'
        ];
    }
}
