<?php

namespace App\Transformers;

class OrganizationAddressTransformer extends BaseTransformer
{
    /**
     * Transformer for Organization address.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The Organization address.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item, $method = 'index') : array
    {
        return [
            'id'                    => $item->id,
            'organization_id'       => $item->organization_id,
            'customer_address_type' => $item->customerAddressType ? (array) $this->relationTransformer($item->organizationAddressType, new CustomerAddressTypeTransformer) : [],
            'country'               => $item->country ? (array) $this->relationTransformer($item->country, new CountryTransformer) : [],
            'address'               => $item->address,
        ];
    }
}
