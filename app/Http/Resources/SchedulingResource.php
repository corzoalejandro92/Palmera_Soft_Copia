<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SchedulingResource extends Resource
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
            "crmScheduleId"     => $this->crm_schedule_id,
            "name"              => $this->name,
            "description"       => $this->description,
            "address"           => $this->crm_address,
            "latitude"          => $this->crm_lat,
            "longitude"         => $this->crm_lng,
            "start"             => $this->start,
            "end"               => $this->end,
            "startManagement"   => $this->start_management,
            "endManagement"     => $this->end_management,    
            "completed"         => $this->completed,
            "office"            => $this->crmSchedule ?  new OfficeResource($this->crmSchedule->office) : null,
            "service"           => $this->crmSchedule && $this->crmSchedule->service ? new ServiceResource($this->crmSchedule->service) : null,
            "controls"          => $this->crmSchedule  && $this->crmSchedule->controls ? ControlResource::collection($this->crmSchedule->controls) : null,
            "infestationGrades" => $this->crmSchedule && $this->crmSchedule->infestationGrades ? InfestationGradeResource::collection( $this->crmSchedule->infestationGrades) : null,
            "consumptions"      => $this->crmSchedule && $this->crmSchedule->consumptions ? ConsumptionResource::collection($this->crmSchedule->consumptions) : [],
            "reportedProducts"  => $this->crmSchedule && $this->crmSchedule->reportedProducts ? ReportedProductResource::collection($this->crmSchedule->reportedProducts) : [],
            "observations"      => ObservationResource::collection($this->observations),
            "signature"         => $this->signature ? new SignatureResource($this->signature) : null,
        ];
    }
}
