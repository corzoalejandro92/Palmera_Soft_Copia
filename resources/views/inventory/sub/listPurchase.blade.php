<?php use Carbon\Carbon; ?>
<div class="row card-title">
    <div class="col-md-6">
        <h4 class="card-title top-5">Compras</h4>
    </div>
    <div class="col-md-6" align="right">
        @can('inventory.create')
            <button class="btn btn-outline-teal" data-warehouse="{{$warehouse->id ?? ''}}" data-click="shoppingInventoryForm" data-id>
                <i class="ft-plus"></i>
                Crear compra
            </button>
        @endcan
    </div>
</div>
<table class="table table-striped table-bordered zero-configuration" style="width: 98%" id="tblList">
    <thead>
        <tr>
            <th>F. de compra</th>
            <th>Foxcom</th>
            <th>F. vencimiento</th>
            <th>Lote</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        
        @foreach ($purchases as $purchase)
        @php $count = 0; $colrow = $purchase->detailPurchases->count(); @endphp
        @if($purchase->detailPurchases->count() > 0)
        <tr>
            <td class="text-center" rowspan="{{$colrow}}">{{ Carbon::parse($purchase->date_shopping)->format("Y-m-d")}}</td>
            <td rowspan="{{$colrow}}">{{$purchase->code_foxcon}}</td>
        
                @foreach($purchase->detailPurchases as $detailPurchase)
                    @if($count > 0) <tr> @endif 
                    <td>{{Carbon::parse($detailPurchase->date_expiration)->format("Y-m-d")}}</td>
                    <td>{{$detailPurchase->plot}}</td>
                    <td>{{$detailPurchase->product->name}}</td>
                    <td>{{$detailPurchase->quantity}}</td>
                
                @if($count == 0) 
                    <td rowspan="{{$colrow}}">
                        <div class="text-center">
                        <a href="#"><i class="icon-pencil mx-3" data-click="shoppingInventoryForm" data-id="<?= $purchase->id ?>" data-warehouse="{{$purchase->warehouse_id}}"></i></a>
                            <a href="#"><i class="icon-close mx-1" data-click="openFormDeletePurchase"data-id="<?= $purchase->id ?>"></i></a>
                        </div>
                    </td>
                @else
                @endif 
                 @php $count++; @endphp 
            @endforeach
            
        </tr>
        @endif
        @endforeach
    </tbody>

</table>