
@include('layouts.loading.full') 
@if($orderPurchase)
<div class="modal-header">
    <h4 class="modal-title">ELIMINAR DETALLE DE ORDEN DE COMPRA</h4>
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
            <p style="font-size: 1.4rem;">¿Realmente desea eliminar el producto <b>{{$orderPurchase->product->name }}</b> ?</p>
        </div>
        <form id="frmDelete">
            {{ method_field('DELETE') }} 
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$orderPurchase->id}}" />
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round" data-click="deleteDetailOrderPurchase">Eliminar</button>
</div>
@endif
