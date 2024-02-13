<?php

namespace App\Http\Resources\Test\Example\Table;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class ExampleTableResource extends JsonResource
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
            'AboutMe' => Str::limit($this->AboutMe, $limit = 50, $end = "..."),
            'ProfilePhoto' => Str::replace('images/', 'thumbnails/', $this->ProfilePhoto),
            'Gender' => $this->Gender,
            'Status' => $this->Status
        ];
    }
}

