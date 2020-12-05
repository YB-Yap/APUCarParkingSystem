<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'parking_zone' => $this->parking_zone,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'duration' => $this->duration,
            'fee' => $this->fee,
            'is_car_park_full' => $this->is_car_park_full,
        ];
    }
}
