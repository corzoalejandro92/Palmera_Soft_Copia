<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class InventoryPendingResource extends Resource
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
            "observation"       => $this->observation,
            "date"              => $this->date_transfer,
            "products"          => PlotPendingResource::collection($this->detailTranferInventory),
        ];
    }
}
