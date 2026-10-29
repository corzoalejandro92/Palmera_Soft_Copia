<?php use Carbon\Carbon; ?>
<table class="table text-inputs-searching table-striped table-bordered zero-configuration">
    <thead>
        <tr>
            <th class="text-center"># Orden</th>
            <th class="text-center">Fecha</th>
            <th class="text-center nofilter">Producto & Cantidad </th>
            <th class="text-center nofilter">Opciones</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th class="text-center"># Orden</th>
            <th class="text-center">Fecha</th>
            <th class="text-center nofilter">Producto & Cantidad </th>
            <th class="text-center nofilter">Opciones</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach ($generalOrderPurchases as $generalOrderPurchase)
        <tr>
            <td class="text-center">
                {{$generalOrderPurchase->number_order_purchase}}
            </td>
            <td class="text-center">
                {{ Carbon::parse($generalOrderPurchase->date_order)->format("Y-m-d")}}
            </td>
            <td>
                @foreach($generalOrderPurchase->detailGeneralOrderPurchases as $detailGeneralOrderPurchase)
                <span class="badge badge-info m-3px">{{$detailGeneralOrderPurchase->product->name}}:
                    {{$detailGeneralOrderPurchase->quantity}}</span>
                @endforeach
            </td>
            <td class="text-center">
                <a href="#"><i class="icon-close mx-1" data-click="openFormDeleteGeneralOrderPurchase" data-id="<?= $generalOrderPurchase->id?>"></i></a>
            </td>
        </tr>

        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center"># Orden</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Producto & Cantidad </th>
            <th class="text-center nofilter">Opciones</th>
        </tr>
    </tfoot>
</table>