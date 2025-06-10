<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'tasca_id' => [
                'required',
                'exists:tascas,id'
            ],
            'manager_id' => [
                'nullable',
                'exists:managers,id'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('validation.attributes.name'),
            'email' => __('validation.attributes.email'),
            'tasca_id' => __('validation.attributes.tasca'),
            'manager_id' => __('validation.attributes.manager')
        ];
    }

//    public function messages(): array
//    {
//        return [
//            'email.required' => 'El correo electrónico es obligatorio',
//            'email.email' => 'El correo electrónico debe ser válido',
//            'email.unique' => 'Este correo electrónico ya está registrado',
//            'name.required' => 'El nombre es obligatorio',
//            'tasca_id.required' => 'La tasca es obligatoria',
//            'tasca_id.exists' => 'La tasca seleccionada no existe',
//            'manager_id.exists' => 'El manager seleccionado no existe'
//        ];
//    }
}
