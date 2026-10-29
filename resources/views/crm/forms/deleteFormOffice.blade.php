@include('layouts.loading.full')
<div class="modal-header">
    <h5 class="modal-title">ELIMINAR SUCURSAL</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="frmDeleteOffice" class="steps-validation">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}

        <p style="font-size: 1.4rem;">¿Realmente desea eliminar la sucursal <b><?= $office->name ?></b> ?</p>

        <input type="hidden" value="<?= $office->id ?? ''; ?>" name="id" />
        <div id="formDeleteContent">
            @include('layouts.loading.full')
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round btnsave" data-click="deleteOffice">Eliminar</button>
</div>