<?php

namespace App\Http\Resources\Agriculture\PlantPart\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class PlantPartListResource extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
             'Name' => $this->Name,
        ];
    }
}
