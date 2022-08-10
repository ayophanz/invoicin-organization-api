<?php

namespace App\Transformers;

class OrganizationAddressTypeTransformer extends BaseTransformer
{
    /**
     * Transformer for Organization address type.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The Organization address type.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item, $method = 'index') : array
    {
        return [
            'id'   => $item->id,
            'name' => $item->name,
        ];
    }
}
