<?php

namespace App\Http\Resources\Stress\StressStage;

use Illuminate\Http\Resources\Json\JsonResource;

class StressStageDetailResource extends JsonResource
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
            'StressID' => $this->StressID,
            'StageID' => $this->StageID,
        ];
    }
}
