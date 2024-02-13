<?php

namespace App\Http\Resources\Yields\Health\Lists;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Yields\Health\HealthDetailResource;

class HealthListCollection extends ResourceCollection
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
            'data' => $this->collection->map(function ($data) {
                return new HealthDetailResource($data);
            })->all()
        ];
    }
}
