<?php

namespace App\Http\Resources\Agrochemicals\AgroCommoditychemical\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class AgroCommoditychemicalTableResource extends JsonResource
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
             'CommodityName' => $this->Commodity->Name ?? '',
             'AgrochemicalName' => $this->Agrochemical->Name ?? '',
            'AgrochemicalTypeName' => $this->AgrochemicalType->Name ?? '',
            'Status' => $this->Status,
            'CIBRCApproved' => $this->CIBRCApproved,
            'WaitingPeriod' => $this->WaitingPeriod,
            'ErrorMessage' => $this->ErrorMessage,
        ];
    }
}
