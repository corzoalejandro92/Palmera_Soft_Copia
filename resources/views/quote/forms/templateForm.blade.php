<div class="modal-header">
    <h4 class="modal-title">Formato de cotización</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="templateForm">
        {{ method_field(isset($template->id) && $template->id ? 'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" name="id" value="<?= $template->id ?? '' ?>" />
        <div class="row">
            <div class="col-md-12">
                <hr><br>
            </div>
            <div class="col-md-6">
                <div class="form-group m-form__group row">
                    <label class="col-3 col-form-label text-right">Nombre Formato</label>
                    <div class="col-9">
                    <input class="form-control" data-keyup="hiden-error" type="text" name="name" value="{{$template->name ?? ''}}" placeholder="Nombre para identificar este formato">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label text-right">Sedes</label>
                    <div class="col-10">
                       <select class="form-control" name="headquarters[]" multiple placeholder="Seleccione una opción">
                            @if(isset($template->headquarters))
                                @foreach($template->headquarters as $headquarter)
                                    <option value="{{$headquarter->id}}" selected>{{$headquarter->name}}</option>
                                @endforeach
                            @else
                                <option value="{{session('headquarter_id')}}" selected>{{session('headquarter_name')}}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <h4 class="card-title">Bloques Disponibles</h4>
                            <div class="row">
                                @foreach($blocks as $block)
                                    <div class="col-12 block-template" data-click="setBlock" data-toggle="tooltip" data-id={{$block->id}} data-placement="top" title="{{$block->description}}">
                                        {{$block->name}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card" align="center">
                    <div class="card-content">
                        <div class="card-body">
                            <h4 class="card-title">Diseño de la plantilla</h4>
                            <textarea class="display-hidden" id="new_page">
                                <div class='cke-page'><div class="cke-content" style="font-size:16px;font-family:Arial,Helvetica,sans-serif"><p style="font-size:16px;font-family:Arial,Helvetica,sans-serif">Nueva hoja</p></div></div> 
                            </textarea>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">

                                        <textarea data-keyup="hiden-error" id="ckeditor"  class="ckeditor" name="body">
                                            
                                            @if(isset($template->content))
                                                {!!$template->content!!}
                                            @else
                                            <div class="cke-page">
                                                <div class="cke-content" style="font-size:14px;font-family:Arial,Helvetica,sans-serif">
                                                    <p style="font-size:14px;font-family:Arial,Helvetica,sans-serif"> Redacte su texto aqui </p>
                                                </div>
                                            </div>
                                            @endif
                                        </textarea>
                                    </div> 
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <a class="btn btn-info" href="#" onclick="CKEDITOR.instances.ckeditor.insertHtml($('#new_page').val());">Nueva página</a>
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
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveTemplate">Guardar</button>
</div>
