<?php use Carbon\Carbon; ?>

<table class="table table-striped table-bordered zero-configuration" style="width: 98%" id="tblList">

    <thead>
        <tr>
            <th>#. Transferencia</th>
            <th>F. de transferencia</th>
            <th>Bodega de salida:</th>
            <th>Producto, Cantidad, Lote</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($confirmationTransfers as $confirmationTransfer)
        <tr>
            <td class="text-center">{{$confirmationTransfer->id}}</td>
            <td class="text-center">{{ Carbon::parse($confirmationTransfer->date_transfer)->format("Y-m-d")}}</td>
            <td class="text-center">{{ $confirmationTransfer->warehouse->name}}</td>
            <td>
                @foreach($confirmationTransfer->detailTranferInventory as $detailTranfer)
                <span class="badge badge-info m-3px " style="font-weight:900">{{$detailTranfer->product->name}}:
                {{$detailTranfer->transfer_quantity}} {{$detailTranfer->product->unit->name}} Lote: {{$detailTranfer->plot}}</span>
                @endforeach
            </td>
            <td>
                <div class="text-center">
                    <a href="#"><i class="icon-eye mx-1" style="font-size: 1.7rem;" data-click="confirmationTransferForm" data-id="<?= $confirmationTransfer->id ?>"></i></a>
                    <a href="#"><i class="icon-close mx-1" data-click="openFormDeleteConfirmationTransferInventory" data-id="<?= $confirmationTransfer->id ?>"></i></a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>