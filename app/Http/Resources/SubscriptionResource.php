<?php

/*
| Description: Used for formatting array of data to array of user defined object
*/

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'valid_at' => $this->valid_at,
            'valid_till' => $this->valid_till,
            'is_active' => $this->is_active,
            'is_expired' => $this->is_expired,
        ];
    }
}
