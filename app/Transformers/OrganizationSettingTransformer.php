<?php

namespace App\Transformers;

class OrganizationSettingTransformer extends BaseTransformer
{
    /**
     * Transformer for OrganizationSettings.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The OrganizationSettings.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item, $method = 'index') : array
    {
        return [
            'sourceable_type' => $item->sourceable_type,
            'sourceable_id'   => $item->sourceable_id,
            'key'             => $item->key,
            'value'           => $item->value,
            'created_at'      => $item->created_at,
        ];
    }
}
