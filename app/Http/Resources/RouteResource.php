<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RouteResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            "date"          => $this->date,
            "createdAt"     => $this->created_at,
            "customers"     => CrmResource::collection($this->customers),
            'statusId'      => $this->status_id,
            'urlFile'      => $this->url_file,
        ]; 
    }
}
