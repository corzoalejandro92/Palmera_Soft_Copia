<?php 
    use Carbon\Carbon; 
?>
<div class="modal-header">
    <h4 class="modal-title">Ver Auditoria ( {{$audit->model_name}} - {{$audit->auditable_id}} )</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mt-2">
        <form id="frmDelete">
            <div class="form-group col-12">
                <h5>Usuario</h5>
                <input type="text" class="form-control"  disabled value="{{$audit->user->name}}" data-keyup="hiden-error">
            </div>
            <div class="form-group col-12">
                <h5>Acción</h5>
                <input type="text" class="form-control"  disabled value="{{$audit->eventt}}" data-keyup="hiden-error">
            </div>
            <div class="form-group col-12">
                <h5>Fecha</h5>
                <input type="text" class="form-control"  disabled value="{{Carbon::parse($audit->created_at)->format('d/m/Y H:i:s A')}}" data-keyup="hiden-error">
            </div>
            <div class="form-group col-12">
                <h5>Url</h5>
                <input type="text" class="form-control"  disabled value="{{ $audit->url ?? ''}}" data-keyup="hiden-error">
            </div>
            <div class="form-group col-12">
                <h5>IP</h5>
                <input type="text" class="form-control"  disabled value="{{ $audit->ip_address ?? ''}}" data-keyup="hiden-error">
            </div>
            <div class="form-group col-12">
                <h5>Navegador / Equipo</h5>
                <textarea type="text" class="form-control"  disabled data-keyup="hiden-error">{{ $audit->user_agent ?? ''}}</textarea>
            </div>
            <div class="form-group col-12">
                <h5>Titulo</h5>
                <textarea type="text" class="form-control"  disabled data-keyup="hiden-error">{{ $audit->title ?? ''}}</textarea>
            </div>
            <div class="form-group col-12">
                <h5>Valores Anteriores</h5>
                <textarea type="text" class="form-control"  disabled  data-keyup="hiden-error">{{ $audit->old_values ?? ''}}</textarea>
            </div>
            <div class="form-group col-12">
                <h5>Valores Nuevos</h5>
                <textarea type="text" class="form-control"  disabled  data-keyup="hiden-error">{{ $audit->new_values ?? ''}}</textarea>
            </div>
        </form>
        <form id="frmRestore">
            {{ method_field('POST') }}
            {{ csrf_field() }}
            <input type="hidden" name="id_audit" value="<?= $audit->id ?>" />
            <input type="hidden" name="id_model" value="<?= $audit->auditable_id ?>" />
            <input type="hidden" name="model" value="<?= $audit->auditable_type ?>" />
        </form>
</div>
<div class="modal-footer">
    <div class="col-6 text-left">
        @if($audit->event == 'updated' || $audit->event == 'deleted')
            <button type="button" class="btn btn-success round text-left" data-click="restore">Restaurar</button>
        @endif
    </div>
    <div class="col-6 text-right">
        <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    </div>
</div>