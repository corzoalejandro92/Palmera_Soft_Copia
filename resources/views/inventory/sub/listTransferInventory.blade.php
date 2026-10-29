<?php use Carbon\Carbon; ?>
<div class="row card-title">
    <div class="col-md-6">
        <h4 class="card-title top-5">Transferencias de inventario de <b>{{$warehouse->name}}</b></h4>
    </div>
    <div class="col-md-6" align="right">

            @can('inventory.transfer')
                <button class="btn btn-outline-teal" data-warehouse="{{$warehouse->id}}" data-click="moveInventoryForm" data-id>
                    <i class="ft-plus"></i>
                    Crear Transferencia
                </button>
            @endcan
  
    </div>
</div>
<table class="responsive table datatables" style="width: 100%">
    <thead>
        <tr>
            <th>F. transf</th>
            <th> Documento</th>
            <th >Bodega salida</th>
            <th>Bodega llegada</th>
            <th>Producto</th>
            <th>Lote</th>
            <th>Cant.</th>
            <th style="width: 100px;">...</th>
        </tr>
    </thead>
    <tbody>

    @foreach ($inventoryTransfers as $inventoryTransfer)
        @php $count = 0; $colrow = $inventoryTransfer->detailTranferInventory->count(); @endphp
        @php
        $disabled= optional(optional($inventoryTransfer)->foxconDocument)->sync == 1 ? 'disabled' : '';
        $icons= optional(optional($inventoryTransfer)->foxconDocument)->sync == 1 ? 'refresh' : 'clock-o';
        $class= optional(optional($inventoryTransfer)->foxconDocument)->sync == 1 ? 'success' : 'warning';
        $title = optional(optional($inventoryTransfer)->foxconDocument)->sync == 1 ? 'Sincronizado' : 'Por sincronizar';
        $anulado = optional((optional($inventoryTransfer)->foxconDocument))->anulado  ? true :false;
    @endphp
        <tr>
            <td class="text-center" style="border-bottom: 2px solid #8bc34a;" rowspan="{{$colrow}}">
                @if(\App\Integration::find(1)->enabled && 
                in_array(
                    \App\Headquarter::find(
                        session('headquarter_id')
                    )->id , \App\Integration::find(1)->headquarters
                )) 
                    <i title="{{$title}}" style="color:#fff;font-size:14px;"  class="la la-{{$icons}} {{$class}}"></i>
                @endif
                {{ Carbon::parse($inventoryTransfer->date_transfer)->format("Y-m-d")}}</td>
                <td style="border-bottom: 2px solid #8bc34a;width: 200px;" rowspan="{{$colrow}}"> {{optional($inventoryTransfer->documentType)->nomenclature}} {{$inventoryTransfer->pre_document_number}}</td>
            <td style="border-bottom: 2px solid #8bc34a;width: 200px;" rowspan="{{$colrow}}">{{$inventoryTransfer->warehouse->name}}</td>
            <td style="border-bottom: 2px solid #8bc34a;width: 200px; {{ $inventoryTransfer->warehouseTransfer->deleted_at ? 'text-decoration: line-through;': '' }}" rowspan="{{$colrow}}">{{$inventoryTransfer->warehouseTransfer->name}}</td>
            
        
                @foreach($inventoryTransfer->detailTranferInventory as $countsub => $detailTranfer)
                    @if($count > 0) <tr> @endif 
                    <td @if(($countsub+1) == count($inventoryTransfer->detailTranferInventory)) style="border-bottom: 2px solid #8bc34a;" @endif>@if($detailTranfer->facturable) <span class="badge badge-danger" title="Facturable">$$</span> @endif{{$detailTranfer->product->name}}</td>
                    <td @if(($countsub+1) == count($inventoryTransfer->detailTranferInventory)) style="border-bottom: 2px solid #8bc34a;" @endif>{{$detailTranfer->plot}}</td>
                    <td @if(($countsub+1) == count($inventoryTransfer->detailTranferInventory)) style="border-bottom: 2px solid #8bc34a;" @endif>{{$detailTranfer->transfer_quantity}}</td>
                @if($count == 0) 
                    <td rowspan="{{$colrow}}" style="border-bottom: 2px solid #8bc34a;">
                        @if($inventoryTransfer->enabled === 1)
                            <div class="text-center">
                                <a href="#"><i class="icon-pencil" data-click="moveInventoryForm" data-id="<?= $inventoryTransfer->id ?>" data-warehouse="{{$inventoryTransfer->warehouse_id}}" ></i></a>
                                <a href="#"><i class="icon-close" data-click="openFormDeleteTransferInventory" data-id="<?= $inventoryTransfer->id ?>"></i></a>
                            </div>
                        @else
                            @if($anulado)
                                <span class="badge badge-danger m-3px " style="font-weight:900">Anulado</span>
                            @else
                                <span class="badge badge-success m-3px " style="font-weight:900">Aprobada</span>
                            @endif
                            @if(\App\Integration::find(1)->enabled && 
                                in_array(
                                    \App\Headquarter::find(
                                        session('headquarter_id')
                                    )->id , \App\Integration::find(1)->headquarters
                                )
                            )
                                <span>
                                    <b>{{ optional($inventoryTransfer->foxconDocument)->documento . ' '.  optional($inventoryTransfer->foxconDocument)->numero }}</b>
                                    <b>{{ optional($inventoryTransfer->eppFoxconDocument)->documento . ' '.  optional($inventoryTransfer->eppFoxconDocument)->numero }}</b>
                                </span>
                            @endif
                        @endif
                    </td>
                @else
                @endif 
                 @php $count++; @endphp 
            @endforeach
            
        </tr>
        @endforeach
    </tbody>

</table>