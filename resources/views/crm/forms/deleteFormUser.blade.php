@include('layouts.loading.full')
<div class="modal-header">
    <h5 class="modal-title">ELIMINAR ASIGNACIÓN</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="frmDeleteUser" class="steps-validation">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}

        <p style="font-size: 1.4rem;">¿Realmente desea eliminar la asignación de este cliente a  <b><?= $user->name ?></b> ?</p>

        <input type="hidden" value="<?= $user->id ?? ''; ?>" name="user_id" />
        <input type="hidden" value="<?= $id ?>" name="id" />
        <input type="hidden" name="office_id" id="office_id_frmDeleteUser" />
        <div id="formDeleteContent">
            @include('layouts.loading.full')
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round btnsave" data-click="deleteUser">Eliminar</button>
</div>