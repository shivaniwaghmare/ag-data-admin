<?php

namespace App\Http\Resources\Yields\Health\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthTableResource extends JsonResource
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
            'CommodityName' => $this->Commodity->Name ?? '',
            'PhenophaseName' => $this->Phenophase->Name ?? '',
            'HealthParameterName' => $this->HealthParameter->Name ?? '',
            'Specification' => $this->Specification,
            'Status' => $this->Status,
        ];
    }
}
