<?php

namespace App\Http\Requests\Reservation;

use App\Models\Tasca;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreReservationRequest extends FormRequest
{
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

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $tasca = Tasca::find($this->input('tasca_id'));

            if ($tasca && $this->input('people') > $tasca->capacity) {
                $validator->errors()->add('people', 'La cantidad de personas excede la capacidad de la tasca.');
            }

            if($tasca->reservation_price != $this->input('reservation_price')) {
                $validator->errors()->add('reservation_price',
                    'El precio de la reserva no coincide con el precio propuesto por la tasca.');
            }
        });
    }

    public function authorize(): bool
    {
        return true;
    }
}
