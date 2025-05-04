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
            'menu' => 'sometimes|string|max:255',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'capacity' => 'required|integer|min:1',
            'reservation' => 'sometimes|boolean',
            'reservation_price' => 'sometimes|numeric|min:0',
            'telephone' => 'sometimes|string|max:9|nullable',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
