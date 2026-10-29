<div class="modal-header">
    <h5 class="modal-title">ADJUNTAR ARCHIVO</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="documentDiagnosis" action="quotes/save-quote-file" enctype="multipart/form-data">
        {{ method_field( isset($diagnosi->id)?'POST':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $diagnosi->id ?? ''; ?>" name="id" />
        <input type="hidden" value="<?= $quote ?? ''; ?>" name="quote_id" />
        <input type="hidden" value="<?= $fileType->id ?? ''; ?>" name="type_id" />
        
        <div class="row">
            <div class="col-md-12">
                <div class="dropzone dropzone-area">
                    <div class="col-12 col-lg-3 mb-1">
                        <div class="content-diagnosis">
                            <div data-upload="file" class="pointer text-center">
                                <img class="avatar md pointer"src="<?= asset('images/default/document.png'); ?>" />
                                <div class="mt-1">
                                    <div class="custom-file" style="right: 75px;">
                                        <div class="custom-file">
                                            <input type="file" name="document" class="custom-file-input" id="inputGroupFile01">
                                            <label class="custom-file-label" for="inputGroupFile01">Seleccione un archivo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cancelar</button>
    <button type="button" class="btn btn-success round" data-click="saveDocumentDiagnosis">Guardar</button>
</div>