<?php use Carbon\Carbon; ?>
<div class="row card-title">
    <div class="col-md-6">
        <h4 class="card-title top-5"> Obsequios de {{$warehouse->name ?? ''}}  <i style="cursor: pointer" title="Recargar"  data-click="LisGiftInventory" data-warehouse="{{$warehouse->id ?? ''}}" class="la la-refresh"></i></h4>
    </div>
    <div class="col-md-6" align="right">
        @can('inventory.transfer')
            <button class="btn btn-outline-teal" data-warehouse="{{$warehouse->id ?? ''}}" data-click="giftInventoryForm" data-id>
                <i class="ft-plus"></i>
                Nuevo Obsequio
            </button>
        @endcan
    </div>
</div>
<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th >Id</th>
            <th >Producto</th>
            <th >Lote</th>
            <th >Cantidad</th>
            @if(\App\Integration::find(1)->enabled && 
                in_array(
                    \App\Headquarter::find(
                        session('headquarter_id')
                    )->id , \App\Integration::find(1)->headquarters
                ))
                <th >Documento Foxcon</th>
            @endif
            <th ></th>
        </tr>
    </thead>
    <tbody>
        @if(isset($transferInventory))
            @foreach($transferInventory as $indx => $detailTranferInventory)
            @php
                $disabled= optional(optional($detailTranferInventory)->foxconDocument)->sync == 1 ? 'disabled' : '';
                $icons= optional(optional($detailTranferInventory)->foxconDocument)->sync == 1 ? 'refresh' : 'clock-o';
                $class= optional(optional($detailTranferInventory)->foxconDocument)->sync == 1 ? 'success' : 'warning';
                $title = optional(optional($detailTranferInventory)->foxconDocument)->sync == 1 ? 'Sincronizado' : 'Por sincronizar';
            @endphp

                <tr data-id="{{$detailTranferInventory->id ?? ''}}">
                    <td >{{ $detailTranferInventory->id ?? '' }} 
                        <i title="{{$title}}" style="color:#fff;font-size:14px;"  class="la la-{{$icons}} {{$class}}"></i>
                </td>
                    <td >
                    {{$detailTranferInventory->product->name ?? ''}}
                    </td>
                    <td >
                        {{$detailTranferInventory->plot ?? ''}}
                    </td>
                    <td  >
                        {{$detailTranferInventory->quantity  ?? ''}} - {{$detailTranferInventory->product->units->name ?? ''}}
                    </td>
                    @if(\App\Integration::find(1)->enabled && 
in_array(
	\App\Headquarter::find(
		session('headquarter_id')
	)->id , \App\Integration::find(1)->headquarters
))
                    <td >
                        {{ optional($detailTranferInventory->typeDocumentFoxcon)->nomenclature  ?? ''}} {{ $detailTranferInventory->foxconDocument->numero}}
                    </td>
                    @endif
                    <td class="remove_row_td" >
                        <a class="btn btn-sm btn-outline-danger  {{$disabled}}" data-warehouse="{{$detailTranferInventory->warehouse_id ?? ''}}" data-click="removeRowGift" data-id="{{$detailTranferInventory->id ?? ''}}">
                            <i class="la la-trash-o"></i>
                        </a>
                        <a class="btn btn-sm btn-outline-teal mr-1"  data-warehouse="{{$detailTranferInventory->warehouse_id ?? ''}}" data-click="giftInventoryForm" data-id="{{$detailTranferInventory->id ?? ''}}">
                            <i class="la la-edit"></i>
                        </a>
                    </td>
                    
                </tr>
            @endforeach
        @endif
    </tbody>

</table>