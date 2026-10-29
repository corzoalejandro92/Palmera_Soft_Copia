<div class="modal-header">
    <h5 class="modal-title">{{ isset($infestation->id)?'EDITAR' : 'CREAR' }} CONTROL</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="infestations"  enctype="multipart/form-data" action="{{isset($infestation->id) ? 'infestations/update':'infestations/store'}}" >
        <!-- valores ocultos del formulario -->
        {{ method_field( isset($infestation->id)?'POST' : 'POST') }}  
        {{ csrf_field() }}
        <input type="hidden" value="<?= $infestation->id ??'' ?>" name="id" />
    

        <div class="col-12 col-lg-12 mb-1">
            <div>
                <input type="file" data-input="fileImage" accept="image/*" onchange="loadImage(this)"
                    class="d-none" />
                <div data-upload="file" data-form="infestations" class="pointer text-center">
                    <img class="avatar md pointer"
                        src="<?= url($infestation->full_avatar)?>" />

                    @canAny(['infestations.create', 'infestations.edit'])
                        <input type="hidden" value="<?= $infestation->avatar ?? 'images/default/control.png'; ?>"
                            name="avatar" />
                        <div class="mt-1">
                            <button type="button" class="btn btn-outline-success btn-sm round">Cargar
                                imagen
                            </button>
                        </div>
                    @endcanAny
                    
                </div>
            </div>
        </div>
        <hr>
            <div class="form-group row">
                <label class="col-4 col-form-label">Nombre</label>
                <div class="col-12">
                    <input class="form-control" data-keyup="hiden-error" value="<?= $infestation->name??'' ?>" name="name" type="text" value="" placeholder="Nombre del control" autocomplete="off">
                </div>
            </div>

            <div class="form-group row">
                <label class="radio-inline col-12">
                    <input type="checkbox" name="monthly" {{ isset( $infestation->monthly) ? ($infestation->monthly == "1" ? 'checked': ''):''}}> Control Mensual
                  </label>
            </div>

            <div class="form-group row">
                <label class="col-4 col-form-label">Descripción</label>
                <div class="col-12">
                    <textarea class="form-control" data-keyup="hiden-error" name="description" type="text" placeholder="Información adicional que verán los clientes/técnicos" autocomplete="off">{{ $infestation->description??''}}</textarea>
                </div>
            </div>

            {{-- <div class="form-group row">
                <label class="col-4 col-form-label">Servicios relacionados</label>
                <div class="col-12">
                    <select class="form-control" name="services[]" multiple>
                        <option>Seleccione uno o mas servicios</option>
                        @if(isset($infestation->services))
                            @foreach ($infestation->services as $service)
                                <option value="{{$service->id}}" selected>{{$service->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div> --}}

        <hr>
        <div>
            <h5 class="card-title">TIPOS DE PLAGAS</h5>
            <table class="table stripe tbl-documents">
                <thead>
                    <tr class="th_title">
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th style="width: 10px;"></th>
                    </tr>
                <tbody>
                    @if(isset($infestation) && $infestation->plagas)
                    @foreach($infestation->plagas as $index => $plaga)
                            @php 
                            $key = uniqid(); 
                            @endphp
                            @include('infestation.forms.frames.document_new_row',compact(['key','plaga']))
                    @endforeach
                @else
                    @php $key = uniqid(); $plaga = null; @endphp
                    @include('infestation.forms.frames.document_new_row',compact(['key','plaga']))
                @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1" class="add_row_th" style="width: 10px;">
                            @canAny(['infestations.create', 'infestations.edit'])
                                <a class="btn btn-outline-teal" data-click="addRow">
                                    <i class="la la-plus-circle"></i>
                                </a>
                            @endCanAny
                            @include('layouts/loading/min')
                        </th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @canAny(['infestations.create', 'infestations.edit'])
    <button type="button" class="btn btn-success round" data-click="save">Guardar</button>
    @endcanAny
</div>