<?php

namespace App\Http\Resources\Commodity\Commodities\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class CommoditiesListResource extends JsonResource
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
            'Name' => $this->Name ?? '',
            'Description' => $this->Description ?? '',
            'ScientificName' => $this->ScientificName ?? '',
            'CommodityGroupID' => $this->CommodityGroupID ?? '',
            'CommodityGroupIndex' => $this->CommodityGroupIndex ?? '',
            'Logo' => $this->Logo ?? '',
            'Status' => $this->Status ?? ''
        ];
    }
}
