<?php

namespace App\Http\Requests\Reservation;

use App\Traits\ValidatesTascaReservation;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    use ValidatesTascaReservation;

    public function rules(): array
    {
        return [
            'tasca_id' => 'required|exists:tascas,id',
            'reservation_price' => 'required|numeric|min:0',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'people' => 'required|integer|min:1',
            'observations' => 'nullable|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
