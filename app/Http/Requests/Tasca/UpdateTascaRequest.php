<?php

namespace App\Http\Requests\Tasca;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTascaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|digits:9',
            'reservation' => 'required|boolean',
            'menu' => 'nullable|string',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'capacity' => 'required|integer|min:1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'phone.digits' => 'El teléfono debe tener exactamente 9 dígitos.',
            'closing_time.after' => 'La hora de cierre debe ser posterior a la de apertura.',
        ];
    }
}
