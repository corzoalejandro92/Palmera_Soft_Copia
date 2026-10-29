<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class PlotResource extends Resource
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
            "plot"              => $this->plot ?? 'N/A',
            "rsa"               => $this->code ?? ($this->rsa ?? ($this->foxcon?? 'N/A')),
            "expirationDate"    => $this->date_expiration ? Carbon::parse($this->date_expiration)->format('Y-m-d') : null,
            "stock"             => $this->stock ?? ($this->transfer_quantity  ?? 0),
        ];
    }
}
