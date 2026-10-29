<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class ConsumptionResource extends Resource
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
            "userId"                => $this->user_id,
            "crmScheduleId"         => $this->crm_schedule_id,
            "foxconDocumentId"      => $this->foxcon_document_id,
            "quantity"              => $this->quantity,
            "plotId"                => $this->inventoryDetail ? $this->inventoryDetail->id : null,
            "inventoryId"           => $this->inventoryDetail ? $this->inventoryDetail->inventory_id : null,
            "createdAt"             => $this->created_at,
        ];
    }
}
