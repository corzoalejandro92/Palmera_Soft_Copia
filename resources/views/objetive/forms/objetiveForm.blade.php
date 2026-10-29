@include('layouts.loading.full') 
<div class="modal-header">
    <h4 class="modal-title">{{$title}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div id="errorsObjetive"></div>
    <div class="mt-2">
        <form id="frm">
            {{ method_field( isset($objetive->id)?'PUT':'POST') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $objetive->id ?? '' ?>" />
            <input type="hidden" name="code" value="<?= $objetive->code ?? '' ?>" />
            
            <div class="form-group col-12">
                <h6>Valor <span class="text-danger">*</span></h6>
                <input type="text" class="form-control" autocomplete="nope" data-change="hiden-error" name="value" value="{{ $objetive->value ?? ''}}"
                    placeholder="Valor" data-keyup="hiden-error">
            </div>
    
            <div class="form-group col-12">
                <h6>Año <span class="text-danger">*</span></h6>
                <input type="text" class="form-control" autocomplete="nope" data-change="hiden-error" name="year" value="{{ $objetive->year ?? ''}}"
                    placeholder="Año" data-keyup="hiden-error">
            </div>
            <div class="col-12">
                <div class="form-group">
                    <h6>Tipo de meta</h6>
                    <select class="select2 form-control" id="type" name="type">
                        <option value="">--Seleccionar--</option>
                        @foreach ($type as $typ)
                            @php
                                $check=isset($objetive->type_objetive_id) ? ($objetive->type_objetive_id == $typ->id ? "selected" : "") : "";
                            @endphp
                            <option {{ $check }} value="{{$typ->id}}">{{$typ->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div> 

        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="save">Guardar</button>
</div>