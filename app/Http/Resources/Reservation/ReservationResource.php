<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $request->has('basic') ?
            $this->basicFormat() :
            $this->detailedFormat();
    }

    /**
     * Format the basic response.
     *
     * @return array
     */
    private function basicFormat(): array
    {
        return [
            'id' => $this->id,
            'tasca' => $this->tasca->name,
            'customer' => $this->customer->user->name,
        ];
    }

    /**
     * Format the detailed response.
     *
     * @return array
     */
    private function detailedFormat()
    {
        return [
            'customer' => [
                'id' => $this->customer?->id,
                'name' => $this->customer?->user?->name,
                'email' => $this->customer?->user?->email,
            ],
            'reservation' => [
                'id' => $this->id,
                'tasca' => $this->tasca?->name,
                'reservation_price' => $this->reservation_price,
                'reservation_date' => $this->reservation_date,
            ],
        ];
    }
}
