<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Collection;

abstract class BaseTransformer
{
    /**
     * Transformer for the models.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The model to be transformed.
     *
     * @return string[] The valid output, displayed in the API.
     */
    abstract public function transform($item): array;

    /**
     * Transformer for a collection of models.
     *
     * @param string[] $items The collection of items.
     *
     * @return string[] The valid output, display in the API.
     */
    public function transformCollection(array $items): array
    {
        return array_map([$this, 'transform'], $items);
    }

    /**
     * Transformer for a collection of models.
     *
     * @param string[] $items The collection of items.
     *
     * @return string[] The valid output, display in the API.
     */
    public function transformArray(Collection $items): array
    {
        return $items->transform(function ($item, $key) {
            return $item;
        })->all();
    }

    /**
     * Transfomer for the relations.
     *
     * @param  \Illuminate\Database\Eloquent\Model $relation The $item->relation() from a transformer.
     *
     * @return string[] The valid output, displayed in the item from the source transformer.
     */
    public function relationTransformer($relation, $transformerClass): array
    {
        try {

            if ($relation->count() === 0) {
                return [];
            }

            if ($relation instanceof \Illuminate\Database\Eloquent\Collection) {
                return $transformerClass->transformCollection(
                    $relation->all()
                );
            }

            return $transformerClass->transform($relation);

        } catch (\Exception $ex) {
            return [];
        }

    }

    /**
     * Transfomer for the optional attribute.
     *
     * @param  \Illuminate\Database\Eloquent\Model $relation The $item->relation() from a transformer.
     *
     * @return string The valid output, displayed in the item from the source transformer.
     */
    public function optionalRelationAttribute($relation, $attribute)
    {
        if (!is_null($relation) && isset($relation->{$attribute})) {
            return $relation->{$attribute};
        }

        return '';
    }

    /**
     * Transfomer for the optional attribute.
     *
     * @param  \Illuminate\Database\Eloquent\Model $relation The $item->relation() from a transformer.
     *
     * @return string The valid output, displayed in the item from the source transformer.
     */
    public function optionalModelAndRelationAttribute($model, $relation, $attribute): string
    {
        // if($model && $model->{$relation}) {
        //     return $model->{$relation}->{$attribute};
        // }

        return '';
    }
}
