<?php
use App\InventoryDetail;
?>
<div class="row card-title">
    <div class="col-md-6">
        <h4 class="card-title top-5"> Inventario de {{$warehouse->name ?? ''}} <i style="cursor: pointer" title="Recargar"  data-click="LisInventory" data-warehouse="{{$warehouse->id ?? ''}}" class="la la-refresh"></i></h4>
    </div>
    @if(\App\Integration::find(1)->enabled && 
        (in_array(
            \App\Headquarter::find(
                session('headquarter_id')
            )->id , \App\Integration::find(1)->headquarters
            )
        ) 
        
        || 

        (
            $warehouse->id == 1 &&
            in_array( 99999999 , \App\Integration::find(1)->headquarters)
        )






    )
        <div class="col-md-6" align="right">
            <button class="btn btn-danger" data-click="makeConsumtion" data-warehouse="{{$warehouse->id}}"> - Realizar consumo manual</button>
         </div>
    @endif

    
</div>
<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th class="text-center">Producto</th>
            <th class="text-center nofilter">Stock:</th>
            <th class="text-center nofilter">Lote y Cantidad:</th>
            @can('inventory.edit')
                @if($warehouse->warehousable_type != "App\SharedMechasnims")
                    <th class="text-center nofilter" style="width: 45px;">...</th>
                @endif
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach ($inventories as $r => $inventory)
        <tr class="pointer">
            <td>
                <div class="col-12" style="display: flex;">

                    <div style="display: inline-block;">
                        @if($warehouse->warehousable_type == "App\SharedMechasnims")
                        <img class="avatar xxs mr-1" src="{{url($inventory->first()->product->avatar ?? '')}}"
                        />    
                    
                        @else
                            <img class="avatar xxs mr-1" src="{{url($inventory->product->avatar ?? '')}}"
                             />
                        @endif
                    </div>

                    <div style="display: inline-flex; vertical-align: middle;">
                        <span style="position: relative;font-size:13px; {{ (isset($inventory->product) && $inventory->product->deleted_at == null) || (!isset($inventory->product) && $inventory->first()->product->deleted_at == null) ? '':'text-decoration: line-through;' }}">
                            @if($warehouse->warehousable_type == "App\SharedMechasnims")
                                {{$inventory->first()->product->name}}
                            @else
                                {{$inventory->product->name}}
                            @endif
                            <br>
                            <small><span class="la la-qr-alt text-bold-400"></span>
                                @if($warehouse->warehousable_type == "App\SharedMechasnims")
                                    {{$inventory->first()->product->code}} - {{ optional($inventory->first()->product->categorys)->name }}
                                @else
                                {{$inventory->product->code}} - {{ optional($inventory->product->categorys)->name }}
                                @endif
                            </small>
                        </span>
                        
                    </div>
                </div>
            </td>
            <td class="text-center">
                @if($warehouse->warehousable_type == "App\SharedMechasnims")
                    {{$inventory->pluck('stock')->sum()}} {{$inventory->first()->product->unit->name}}
                @else
                    {{$inventory->stock}} {{$inventory->product->unit->name}}
                @endif
            </td>
            <td>
                @if($warehouse->warehousable_type == "App\SharedMechasnims")
                    @foreach ($inventory->first()->detailInventories as $detail)
                    <span class="badge badge-info m-3px font-medium-1">Lote {{$detail->plot}}:
                        {{$detail->stock}} {{$inventory->first()->product->unit->name}}
                        
                    </span>
                    @endforeach
                @else
                    @foreach ($inventory->detailInventories as $detail)
                    @if ($detail->foxconDocument)
                    <span class="badge badge-success @if($detail->facturable) badge-danger @endif m-3px font-medium-1" @if($detail->facturable) title="Facturable: Este lote no puede ser consumido, es únicamente para facturación." style="opacity: 0.3;  cursor: not-allowed;" @endif >
                        {{$detail->facturable ? '$$': ''}} Lote {{$detail->plot}}:
                      {{$detail->stock}} {{explode(' ',$inventory->product->unit->name)[0]}}
                      <small style="color:#ccc">{{ $detail->foxconDocument->documento  }} {{$detail->foxconDocument->numero  }}</small>
                    </span><br>
                    @else
                    <span class="badge badge-info @if($detail->facturable) badge-danger @endif m-3px font-medium-1" @if($detail->facturable) title="Facturable: Este lote no puede ser consumido, es únicamente para facturación." style="opacity: 0.3;  cursor: not-allowed;" @endif >
                        {{$detail->facturable ? '$$': ''}} Lote {{$detail->plot}}:
                      {{$detail->stock}} {{explode(' ',$inventory->product->unit->name)[0]}}
                    </span><br>
                    @endif

                    @endforeach
                @endif

            </td>
            @can('inventory.edit')
                @if($warehouse->warehousable_type != "App\SharedMechasnims")
                    <td>
                        <div class="text-center">                 
                            <a href="#"><i class="icon-pencil" data-click="openFormEditInventory" data-id="{{ $inventory->id }}" data-warehouse="{{ $inventory->warehouse_id }}"></i></a>
                            <i class="icon-close" data-click="openFormEditInventory" data-id="{{ $inventory->id }}" data-deleted="{{true}}" data-warehouse="{{ $inventory->warehouse_id }}"></i>
                        </div>
                    </td>
                @endif
            @endcan

        </tr>
        @endforeach

    </tbody>
</table>