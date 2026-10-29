<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class InfestationGradeResource extends Resource
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
            "crmId"                 => $this->crm_id,
            "officeId"              => $this->office_id,
            "crmScheduleId"         => $this->crm_schedule_id,
            "crmScheduleControlId"  => $this->crm_schedule_control_id,
            "infestationId"         => $this->control_infestation_id,
            "value"                 => $this->value,
            "userId"                => $this->user_id,
            "headquarterId"         => $this->headquarter_id
        ];
    }
}
