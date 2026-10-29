@include('layouts.loading.full') 
<div class="modal-header">
    <h4 class="modal-title">{{$title}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mt-2">
        <form id="frm">
            {{ method_field( isset($documentType->id)?'PUT':'POST') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $documentType->id ?? '' ?>" />
            <div class="form-group col-12">
                <h5>Tipo de Documento</h5>
                <input type="text" class="form-control" autocomplete="nope" name="name" value="{{ $documentType->name ?? ''}}"
                    placeholder="Nombre" data-keyup="hiden-error">
            </div>
            <div class="form-group col-12">
                <h5>Modulo al que aplica</h5>
                <select class="form-control documentType" name="slug" data-change="hiden-error">
                    <option value="">--seleccionar--</option>
                    <option value="user" {{ isset($documentType->slug) &&  $documentType->slug == 'user' ? 'selected' : ''}} >Módulo Usuarios</option>
                    <option value="crm" {{ isset($documentType->slug) &&  $documentType->slug == 'crm' ? 'selected' : ''}}>Módulo CRM</option>
                    <option value="product" {{ isset($documentType->slug) && $documentType->slug == 'product' ? 'selected' : ''}}>Módulo Productos</option>
                    <option value="vehicle" {{ isset($documentType->slug) &&  $documentType->slug == 'vehicle' ? 'selected' : ''}}>Módulo Vehículos</option>
                    <option value="plan" {{ isset($documentType->slug) &&  $documentType->slug == 'plan' ? 'selected' : ''}}>Módulo Planos</option>
                </select>
            </div>
            
            @if(isset($documentType->id))
                <div class="col-12">
                    <div class="form-group">
                        <div class="col-12" >
                            <div class="row skin skin-square">
                                <div class="col-1">
                                    <input type="checkbox" name="enabled" value="1" id="input-checkbox-1" {{ isset( $documentType->enabled) ? ($documentType->enabled == "1" ? 'checked': ''):''}}>
                                </div>
                                <div class="col-11">
                                    <label for="input-checkbox-1"> Habilitado</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="save">Guardar</button>
</div>