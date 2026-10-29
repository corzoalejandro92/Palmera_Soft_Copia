<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\Resource;

class CrmResource extends Resource
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
            "id"                => $this->resource['id'],
            "name"              => $this->resource['name'],
            "schedulingsCount"  => $this->resource['schedulings_count'],
            "schedulings"       => SchedulingResource::collection($this->resource['schedulings'])
        ];
    }
}
