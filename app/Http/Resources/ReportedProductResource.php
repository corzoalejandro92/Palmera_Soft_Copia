<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class ReportedProductResource extends Resource
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
            "controlId"             => $this->control_id,
            "productId"             => $this->product_id, 
            "rsa"                   => $this->rsa,
            "plot"                  => $this->plot,
            "expirationDate"        => $this->expiration_date,
            "quantity"              => $this->quantity,
            "dose"                  => $this->dose,
            "prepared"              => $this->prepared,
        ];
    }
}
