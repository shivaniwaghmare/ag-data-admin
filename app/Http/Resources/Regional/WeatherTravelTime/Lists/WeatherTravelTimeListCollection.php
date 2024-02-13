<?php

namespace App\Http\Resources\Regional\WeatherTravelTime\Lists;

use App\Http\Resources\Common\Option\OptionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WeatherTravelTimeListCollection extends ResourceCollection
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
                return new OptionResource($data);
            })->all()
        ];
    }
}
