<?php

namespace App\Http\Resources\Test\Example\List;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class ExampleListResource extends JsonResource
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
            'Name' => $this->Name,
            'AboutMe' => Str::limit($this->AboutMe, $limit = 50, $end = "..."),
            'ProfilePhoto' => $this->ProfilePhoto,
            'Gender' => $this->Gender
        ];
    }
}
