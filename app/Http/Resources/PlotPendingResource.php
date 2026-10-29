<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class PlotPendingResource extends Resource
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
            "productName"       => $this->product->name,
            "unitName"          => explode(" ",$this->product->unit->name)[0],
            "plot"              => $this->plot ?? 'N/A',
            "stock"             => $this->stock ?? ($this->transfer_quantity  ?? 0),
        ];
    }
}
