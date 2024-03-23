<?php

namespace App\Transformers;

class CountryTransformer extends BaseTransformer
{
    /**
     * Transformer for Country.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The Country.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item, $method = 'index') : array
    {
        return [
            'id'   => (int) $item->id,
            'code' => $item->code,
            'name' => $item->name,
        ];
    }
}
