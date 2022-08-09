<?php

namespace App\Transformers;

class OrganizationTransformer extends BaseTransformer
{
    /**
     * Transformer for Organization.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The Organization.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item, $method = 'index') : array
    {
        return [
            'uuid'                   => $item->uuid,
            'name'                   => $item->name,
            'email'                  => $item->email,
            'organization_addresses' => $item->organizationAddresses ? (array) $this->relationTransformer($item->organizationAddresses, new OrganizationAddressTransformer) : [],
        ];
    }
}
