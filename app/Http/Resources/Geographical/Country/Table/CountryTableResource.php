<?php

namespace App\Http\Resources\Geographical\Country\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryTableResource extends JsonResource
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
            'ID' => $this->ID,
            'CountryCode' => $this->CountryCode,
            'Name' => $this->Name,
            'Status' => $this->Status,
        ];
    }
}
