<div class="modal fade" id="mdlFormShowFullScreen" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-layout" role="document">
        @include('layouts.loading.full')
        <div class="modal-content" style="min-height: 380px">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form id="customerFull" class="steps-validation">
                    {{ method_field( isset($crms->id)?'POST':'POST') }}
                    {{ csrf_field() }}
                   
                    <input type="hidden" value="<?= $crms->id ?? ''; ?>" name="crm_id" />
                    <input type="hidden" value="<?= $office->id ?? ''; ?>" name="office_id" />
                    <div id="formContent">
                            @include('layouts.loading.full')
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success round btnsave" data-click="">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlFormShowFullScreen2" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-layout" role="document">
        @include('layouts.loading.full')
        <div class="modal-content" style="min-height: 380px">
        </div>
    </div>
</div>