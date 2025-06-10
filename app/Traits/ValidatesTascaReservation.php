<?php

namespace App\Traits;

use App\Models\Tasca;
use Carbon\Carbon;

trait ValidatesTascaReservation
{
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $tasca = Tasca::find($this->input('tasca_id'));

            // Check if the tasca exists and people is greater than the tasca's capacity
            if ($tasca && $this->input('people') > $tasca->capacity) {
                $validator->errors()->add('people', 'La cantidad de personas excede la capacidad de la tasca.');
            }

            //Check if the reservation price is set and if it matches the tasca's reservation price
            if ($this->input('reservation_price') &&
                $tasca &&
                $tasca->reservation_price != $this->input('reservation_price'))
            {
                $validator->errors()->add('reservation_price', 'El precio de la reserva no coincide con el precio propuesto por la tasca.');
            }

            // Check if the user has a reservation in the same tasca within 10 hours
            $user = $this->user();
            $date = $this->input('reservation_date');
            $time = $this->input('reservation_time');

            if ($user && $tasca && $date && $time) {
                $reservationDatetime = Carbon::createFromFormat('Y-m-d H:i', "$date $time");

                $conflictingReservation = $user->customer->reservations()
                    ->where('tasca_id', $tasca->id)
                    ->where('reservation_date', $date)
                    ->where(function ($query) use ($reservationDatetime) {
                        $query->whereRaw('ABS(TIMESTAMPDIFF(HOUR, CONCAT(reservation_date, " ", reservation_time), ?)) < 10', [$reservationDatetime]);
                    });

                if ($this->route('reservation')) {
                    $conflictingReservation->where('id', '!=', $this->route('reservation')->id);
                }

                if ($conflictingReservation->exists()) {
                    $validator->errors()->add('reservation_time', 'Ya tienes una reserva en esta tasca con menos de 10 horas de diferencia.');
                }
            }
        });
    }
}
