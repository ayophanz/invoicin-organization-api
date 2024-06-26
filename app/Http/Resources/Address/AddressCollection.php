<?php

namespace App\Http\Resources\Address;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function ($address) {
            return (new AddressResource($address));
        });

        return parent::toArray($request);
    }
}