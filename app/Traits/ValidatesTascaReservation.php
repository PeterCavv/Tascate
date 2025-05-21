<?php

namespace App\Traits;

use App\Models\Tasca;

trait ValidatesTascaReservation
{
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $tasca = Tasca::find($this->input('tasca_id'));

            if ($tasca && $this->input('people') > $tasca->capacity) {
                $validator->errors()->add('people', 'La cantidad de personas excede la capacidad de la tasca.');
            }

            if ($this->input('reservation_price') &&
                $tasca &&
                $tasca->reservation_price != $this->input('reservation_price'))
            {
                $validator->errors()->add('reservation_price', 'El precio de la reserva no coincide con el precio propuesto por la tasca.');
            }
        });
    }
}
