<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class SignatureResource extends Resource
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
            "id"                    => $this->id,
            "scheduling_id"         => $this->scheduling_id,
            "userId"                => $this->user_id,
            "name"                  => $this->name ?? '',
            "document"              => $this->document ?? '',
            "urlFile"               => Storage::disk('public')->url($this->file_path)
        ];
    }
}
