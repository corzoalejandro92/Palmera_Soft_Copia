@include('layouts.loading.full') 
@if($parameter)
<div class="modal-header">
    <h4 class="modal-title">Eliminar Parámetro</h4>
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
            <p style="font-size: 1.4rem;">¿Realmente deseas quitar el parámetro: <br><b><?= $parameter->item ?></b>?</p>
        </div>
        <div class=" text-left">
            <p style="font-size: 1.4rem;">Ten presente que si eliminas este item, lo harás tambien para las otras sedes que se encuentran relacionadas y podrías afectar su producción, si lo que deseas hacer es no usar este item o personalizar uno para tu sede, te recomendamos que canceles esta acción  desde el botón <b>cerrar</b> y quites tu sede del campo sedes y posterior a ello actualices este servicio.</p>
        </div>
        <form id="frmDeleteParameter">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $parameter->id ?>" />
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round" data-click="deleteParameter">Eliminar</button>
</div>
@endif
