<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tasca_id' => 'required|integer|exists:tascas,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'reservation_price' => 'required|numeric|min:0',
            'reservation_date' => 'required|date|date_format:d-m-Y|after_or_equal:today',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
