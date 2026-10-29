@include('layouts.loading.full')
@if($service) 
<div class="modal-header">
    <h5 class="modal-title">ELIMINAR PARÁMETRO</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="frmDeleteTechnical" class="steps-validation">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}

        <p style="font-size: 1.4rem;">¿Realmente desea eliminar el parámetro para el servicio <b><?= $service->name ?></b> ?</p>

        <input type="hidden" value="<?= $id ?? ''; ?>" name="id" />
        <div id="formDeleteContent">
            @include('layouts.loading.full')
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round btnsave" data-click="deleteTechnical">Eliminar</button>
</div>
@endif