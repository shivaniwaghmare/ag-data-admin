<?php

namespace App\Http\Resources\Commodity\Commodities\Table;

use App\Models\Commodity\Commodities;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Commodity\Commodities\Table\CommoditiesTableResource;

class CommoditiesTableCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'columns' => Commodities::getTableFields(),
            'data' => $this->collection->map(function ($data) {
                return new CommoditiesTableResource($data);
            })->all(),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
