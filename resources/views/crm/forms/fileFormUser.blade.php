
<div class="modal-header">
    <h5 class="modal-title">DOCUMENTOS DE {{mb_strtoupper($user->name)}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="documents">
        @if(isset($user) && $user->files->count() > 0)
            @include('crm.forms.frames.documentsFrame')
        @else
            <div class="alert alert-light text-center" role="alert" >
                <b>Vaya! Al parecer no hay documentos para mostrar aquí.</b>
            </div>
        @endif
    </div>   
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
</div>