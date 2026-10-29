@include('layouts.loading.full') 
@if($coverage)
<div class="modal-header">
    <h4 class="modal-title">Eliminar Cobertura</h4>
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
            <p style="font-size: 1.4rem;">¿Realmente desea quitar la cobertura: <br><b><?= $coverage->zone ?></b>?</p>
        </div>
            <p style="font-size: 1.4rem;">Ten presente que si eliminas esta cobertura, lo harás tambien para las otras sedes que se encuentran relacionadas y podrías afectar su producción, si lo que deseas hacer es no usar esta cobertura o personalizar una para tu sede, te recomendamos que canceles esta acción  desde el botón <b>cerrar</b> y quites tu sede del campo sedes y posterior a ello actualices este servicio.</p>
        </div>
        <form id="frmDeleteCoverage">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $coverage->id ?>" />
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round" data-click="deleteCoverage">Eliminar</button>
</div>
@endif
