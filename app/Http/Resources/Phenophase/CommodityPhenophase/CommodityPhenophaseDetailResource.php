<?php

namespace App\Http\Resources\Phenophase\CommodityPhenophase;

use Illuminate\Http\Resources\Json\JsonResource;

class CommodityPhenophaseDetailResource extends JsonResource
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
            'CommodityID' => $this->CommodityID,
            'PhenophaseID' => $this->PhenophaseID
        ];
    }
}
