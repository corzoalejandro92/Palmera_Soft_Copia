@include('layouts.loading.full') 
@if($service)
<div class="modal-header">
    <h4 class="modal-title">Eliminar Sevicio</h4>
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
            <p style="font-size: 1.4rem;">¿Realmente desea quitar el servicio: <br><b>{{$service->service->name}} ({{$service->coverage}})</b> ?</p>
        </div>
        <form id="frmDeleteService">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $service->id ?>" />
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round" data-click="deleteSupply">Eliminar</button>
</div>
@endif
