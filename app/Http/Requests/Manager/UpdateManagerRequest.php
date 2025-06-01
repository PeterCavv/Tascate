<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateManagerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('manager')->user_id, 'id')
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'tasca_id' => [
                'required',
                'exists:tascas,id'
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
            'tasca_id.required' => 'La tasca es obligatoria',
            'tasca_id.exists' => 'La Tasca seleccionada no existe'
        ];
    }
}
