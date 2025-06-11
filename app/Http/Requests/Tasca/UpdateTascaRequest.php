<?php

namespace App\Http\Requests\Tasca;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTascaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'telephone' => 'required|digits:9',
            'reservation' => 'required|boolean',
            'reservation_price' => 'required_if:reservation,true|numeric|min:0',
            'menu' => 'nullable|string',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'capacity' => 'required|integer|min:1',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('validation.attributes.name'),
            'address' => __('validation.attributes.address'),
            'telephone' => __('validation.attributes.telephone'),
            'reservation' => __('validation.attributes.reservation'),
            'reservation_price' => __('validation.attributes.reservation_price'),
            'menu' => __('validation.attributes.menu'),
            'opening_time' => __('validation.attributes.opening_time'),
            'closing_time' => __('validation.attributes.closing_time'),
            'capacity' => __('validation.attributes.capacity'),
            'picture' => __('validation.attributes.picture'),
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
        ];
    }
}
