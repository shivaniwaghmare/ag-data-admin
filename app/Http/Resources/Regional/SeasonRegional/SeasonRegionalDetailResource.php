<?php

namespace App\Http\Resources\Regional\SeasonRegional;

use Illuminate\Http\Resources\Json\JsonResource;

class SeasonRegionalDetailResource extends JsonResource
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
            'StateCode' => $this->StateCode,
            'SeasonID' => $this->SeasonID,
            'SeasonStartWeek' => $this->SeasonStartWeek,
            'SeasonEndWeek' => $this->SeasonEndWeek,
        ];
    }
}
