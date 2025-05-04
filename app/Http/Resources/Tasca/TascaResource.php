<?php

namespace App\Http\Resources\Tasca;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TascaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $request->has('basic') ?
            $this->basicFormat() : $this->detailedFormat();
    }

    /**
     * Transform the resource into a detailed array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function detailedFormat()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'data' => [
                'opening_time' => $this->opening_time,
                'closing_time' => $this->closing_time,
                'capacity' => $this->capacity,
                'menu' => $this->menu,
                'reservation' => $this->reservation,
                'reservation_price' => !$this->reservation ? 0 : $this->reservation_price,
            ],
        ];
    }

    /**
     * Transform the resource into a basic array.
     *
     * @return array
     */
    private function basicFormat()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'telephone' => $this->telephone,
        ];
    }
}
