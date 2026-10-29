<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class InventoryResource extends Resource
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
            "productId"         => $this->product->id,
            "productName"       => $this->product->name,
            "unitName"          => explode(" ",$this->product->unit->name)[0],
            "stock"             => $this->stock ?? 0,
            "plots"             => PlotResource::collection($this->detailInventories),

        ];
    }
}
