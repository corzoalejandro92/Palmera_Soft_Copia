<div class="modal-header">
    <h4 class="modal-title">Asignar codigo Foxcon: {{$warehouse->name ?? ''}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mt-2">
        <form id="frmCodeFoxcon">
           <div class="col-12 ">
                <h5>Codigo Foxcon</h5>
                <input type="text" name="code_foxcon" autocomplete="off" class="form-control" value="{{$warehouse->code_foxcon ?? ''}}">
            </div>
            {{ method_field('PUT') }} 
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $warehouse->id }}" />
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveCodeFoxcon">Guardar</button>
</div>
