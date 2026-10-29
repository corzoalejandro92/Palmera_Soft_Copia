
@include('layouts.loading.full') 
<div class="modal-header">
    <h4 class="modal-title">ELIMINAR CONFIRMACION DE TRANSFERENCIA</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mt-2">
        <div class="float-left">
            <i style="font-size: 4.4rem;" class="la la-trash"></i>
        </div>
        <div class=" text-center">
            <p style="font-size: 1.4rem;">¿Realmente desea eliminar y devolver la transferencia <b>{{$confirmationTransfers->id}}</b> ?</p>
        </div>
        <form id="frmDelete">
            {{ method_field('DELETE') }} 
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $confirmationTransfers->id ?>" />
            <input type="hidden" name="headquarter_id" value="<?= $confirmationTransfers->headquarter_id ?>" />
            <input type="hidden" name="warehouse_id" value="<?= $confirmationTransfers->warehouse_id ?>" />
            @foreach($confirmationTransfers->detailTranferInventory as $confirmationTransfer)
            <input type="hidden" name="product_id" value="<?= $confirmationTransfer->product_id ?>" />
            <input type="hidden" name="plot" value="<?= $confirmationTransfer->plot ?>" />
            <input type="hidden" name="transfer_quantity" value="<?= $confirmationTransfer->transfer_quantity ?>" />

            @endforeach
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round" data-click="deleteConfirmationTransferInventory">Eliminar</button>
</div>
