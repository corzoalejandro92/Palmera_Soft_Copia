<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FileResource extends Resource
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
            "type"              => $this->fileType->name,
            "name"              => $this->fileType->name,
            "url"               => $this->url,
            "mechanisms"        => $this->extends ? ExtendResource::collection($this->extends) : null,

        ];
    }
}
