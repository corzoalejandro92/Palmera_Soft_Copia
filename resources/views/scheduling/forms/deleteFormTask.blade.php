@include('layouts.loading.full') 
@if($task)
<div class="modal-header">
    <h4 class="modal-title">Eliminar Tarea</h4>
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
            <p style="font-size: 1.4rem;">¿Realmente desea quitar la tarea: <br><b><?= $task->description ?></b> ?</p>
        </div>
        <form id="frmDeleteDocument">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $task->id ?>" />
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round" data-click="deleteDocument">Eliminar</button>
</div>
@endif
