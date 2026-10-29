<div class="modal-header">
    <h5 class="modal-title">Enviar cotización</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="sendEmail">
        {{ method_field( isset($email->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $email->id ?? ''; ?>" name="id" />
        <input type="hidden" name="enabled" value="1" />
        <div class="col-xl-12 col-lg-12">
            <div class="form-group">
                <select class="select2-size-lg form-control" id="lisContact" multiple="multiple">
  
                </select> 
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cancelar</button>
    <button type="button" class="btn btn-success round" data-click="savePlan">Enviar</button>
</div>