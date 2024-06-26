<?php

namespace App\Http\Resources\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'type'           => $this->addressType()->select('id', 'name')->first(),
            'country'        => $this->country,
            'state_province' => $this->state_province,
            'city'           => $this->city,
            'zipcode'        => $this->zipcode,
            'address'        => $this->address
        ];
    }
}
