<?php

namespace App\Http\Resources\Agriculture\MeteorologicalWeek;

use Illuminate\Http\Resources\Json\JsonResource;

class MeteorologicalWeekDetailResource extends JsonResource
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
            'WeekNo' => $this->WeekNo,
            'StartDay' => $this->StartDay,
            'StartMonth' => $this->StartMonth,
            'EndDay' => $this->EndDay,
            'EndMonth' => $this->EndMonth,
        ];
    }
}
