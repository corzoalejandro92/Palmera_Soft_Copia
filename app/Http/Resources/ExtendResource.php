<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ExtendResource extends Resource
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
            "id"                => $this->id,
            "name"              => $this->extendible->name,
            "slug"              => $this->extendible->slug,
            "num"               => $this->num,
            "reports"           => $this->lastReports ? ReportResource::collection($this->lastReports) : null,
        ];
    }
}
