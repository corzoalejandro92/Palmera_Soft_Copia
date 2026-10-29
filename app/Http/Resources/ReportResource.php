<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class ReportResource extends Resource
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
            "userId"            => $this->user_id,
            "userName"          => $this->user->name,
            "reportTypeId"      => $this->technical_report_type_id,
            "observation"       => $this->observation,
            "urlFile"           => Storage::disk('public')->url($this->file_path),
            "quantity"          => $this->quantity,
            'createdAt'         => $this->created_at,
            'schedulingId'      => $this->scheduling_id,
        ];
    }
}
