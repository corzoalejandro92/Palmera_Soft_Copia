<div class="modal-header">
    <h4 class="modal-title">{{$title}} = {{$fileExtend->extendible->name ?? ''}} > N: {{$fileExtend->num ?? ''}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mt-2">
        <form id="frm-renumber">
            {{ csrf_field() }}
            <input type="hidden" value="{{$title}}" name="title">
            @if($title == 'Escalar')
                <div class="form-group col-12">
                    <h5>Tamaño del ícono</h5>
                    <div class="slidecontainerE">
                        <input name="size" type="range" min="1" max="100" value="{{$fileExtend->size}}" class="slider" id="range">
                        <p style="margin-top: 10px;"><span id="size">{{$fileExtend->size}}</span> pixeles</p>
                    </div>
                </div>
            @endif
            @if($title == 'Renumerar(Manual)')
                <div class="form-group col-12">
                    <input type="hidden" name="file_id" value="{{$fileExtend->file_id}}">
                    <input type="hidden" name="category_id" value="{{$fileExtend->extendible->category->id ?? ''}}">
                    <h5>Numero<span class="text-danger">*</span></h5>
                    <input type="text" class="form-control" autocomplete="nope" name="number" value="{{ $fileExtend->num ?? ''}}"
                        placeholder="Numero..." data-keyup="hiden-error">
                </div>
            @endif
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-id="{{$fileExtend->id}}" data-click="saveRenumber">Guardar</button>
</div>