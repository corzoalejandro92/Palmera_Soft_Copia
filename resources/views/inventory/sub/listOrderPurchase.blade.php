<?php use Carbon\Carbon; ?>
<table class="table text-inputs-searching table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th></th>
            <th class="text-center"># Orden</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Bodega</th>
            <th class="text-center">Producto & Cantidad </th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($orderPurchases as $orderPurchase)

        <tr>
            <td>
                <div class="row icheck_minimal skin" style="margin-left: auto;margin-right: auto;">
                    <div>
                        <fieldset>
                           <input type="checkbox" name="received" value="{{ $orderPurchase->id}}">
                        </fieldset>
                    </div>
                </div>
            </td>
            <td class="text-center">
                {{ $orderPurchase->number_order_purchase}}
            </td>
            <td class="text-center">
                {{ Carbon::parse($orderPurchase->date_order)->format("Y-m-d")}}
            </td>
            <td class="text-center">
                {{ $orderPurchase->warehouse->name}}
            </td>
            <td>
                @foreach($orderPurchase->detailOrderPurchases as $detailOrderPurchase)
                <span class="badge badge-info m-3px">{{$detailOrderPurchase->product->name}}:
                    {{$detailOrderPurchase->quantity}}</span>
                @endforeach
            </td>
            <td class="text-center">
                <a href="#"><i class="icon-pencil mx-3" data-click="openFormOrderPurchase"
                        data-id="<?= $orderPurchase->id ?>"></i></a>
                <a href="#"><i class="icon-close mx-1" data-click="openFormDeleteOrderPurchase"
                        data-id="<?= $orderPurchase->id ?>"></i></a>
            </td>
        </tr>

        @endforeach

    </tbody>
</table>