<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class ObservationResource extends Resource
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
            "note"              => $this->note ?? '',
            "userId"            => $this->user_id,
            "urlFile"           => Storage::disk('public')->url($this->file_path)
        ];
    }
}
