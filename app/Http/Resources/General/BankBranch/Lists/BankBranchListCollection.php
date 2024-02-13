<?php

namespace App\Http\Resources\General\BankBranch\Lists;

use App\Http\Resources\Common\Option\OptionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BankBranchListCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return new OptionResource($data);
            })->all()
        ];
    }
}
