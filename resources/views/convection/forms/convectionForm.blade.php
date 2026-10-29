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
            {{ method_field( isset($convection->id)?'PUT':'POST') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $convection->id ?? '' ?>" />

             <!-- formulario dinamico para empaquetar productos -->
             <div align="center">
                @include('layouts/loading/min')
            </div>
            <div >
                @include('convection.forms.productsForm')
            </div>  
            <br>

            <div class="row">
                
                
                <div class="col-12 col-lg-12">
                  
                    <div class="form-group m-form__group row colordiv">
                        <label class="col-3 col-form-label">Color</label>
                        <div class="col-9">
                            <input class="form-control colorpicker" id="color" name="color" value="{{ $convection->color ?? '#000000' }}">
                        </div>
                    </div>
                   
                    <div class="form-group m-form__group row">
                        <label class="col-3 col-form-label">Nombre</label>
                        <div class="col-9">
                            <input class="form-control" data-keyup="hiden-error" type="text" name="name" value="<?= $convection->name ?? ''; ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" >
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-3 col-form-label">Siglas</label>
                        <div class="col-9">
                            <input class="form-control" data-keyup="hiden-error" type="text" name="slug" value="<?= $convection->slug ?? ''; ?>"  placeholder="" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-3 col-form-label">Categoria</label>
                        <div class="col-9">
                            <select class="form-control" id="category_convention" name="category_convention" required data-change="hiden-error">
                                <option value="">--Seleccionar--</option>
                                @if(isset($convection->category_conventions_id))
                                    <option value="{{ $convection->category_conventions_id }}" selected>{{ $convection->category->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    @if(isset($convection->id))
                        <div class="form-group m-form__group row">
                            <div class="col-12">
                                <div class="row skin skin-square">
                                    <div class="col-1">
                                        <input type="checkbox" name="enabled" value="1" id="input-checkbox-1" {{ isset( $convection->enabled) ? ($convection->enabled == "1" ? 'checked': ''):''}}>
                                    </div>
                                    <div class="col-11">
                                        <label for="input-checkbox-1"> Habilitada</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                
                </div>
            </div>

              

        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="save">Guardar</button>
</div>