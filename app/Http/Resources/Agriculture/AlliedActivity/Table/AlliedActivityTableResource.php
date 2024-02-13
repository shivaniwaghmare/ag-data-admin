<?php

namespace App\Http\Resources\Agriculture\AlliedActivity\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class AlliedActivityTableResource extends JsonResource
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
            'Name' => $this->Name,
            'Status' => $this->Status
        ];
    }
}
