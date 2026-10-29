
<div class="modal-header">
    <h5 class="modal-title">{{isset($qr->id) ? 'EDITAR LOTE QR':'GENERAR LOTE QR'}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="qr" action="{{isset($qr->id)?'qr/update':'qr/store'}}">
        {{ method_field( isset($qr->id)?'PUT':'POST') }} 
        {{ csrf_field() }}
        <input type="hidden" value="<?= $qr->id ?? ''; ?>" name="id" />
        <div class="row">
            
            <div class="col-12 col-lg-5 col-md-5">
                <div>
                    <img src="{{asset('images/default/qr.png')}}" style="width: 100%;" >
                </div>
            </div>
            <div class="col-12 col-lg-7 co-md-7 mt-2">
                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Sede<span class="text-danger">*</span></label>
                    <div class="col-10">
                        @if(Auth::user()->roles->pluck('slug')->contains('root'))
                            <select class="form-control select2" data-change="hiden-error" id="headquarter_id" name="headquarter_id" data-placeholder="Seleccionar una sede">
                                <option value="">Seleccionar Sede</option>
                                @if (!empty($qr->headquarter))
                                    <option value="<?= $qr->headquarter->id; ?>" selected=""><?= $qr->headquarter->name; ?></option>
                                @else
                                    <option value="<?= session('headquarter_id'); ?>" selected=""><?= session('headquarter_name'); ?></option>
                                @endif
                            </select>
                        @else
                            <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
                            <input type="text" class="form-control" readonly value="{{session('headquarter_name')}}">
                        @endif
                    </div>
                    
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Cant<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="number" class="form-control" name="quantity" min="1" max="300" value="{{$qr->lot ?? 1}}" placeholder="Cantidad" />
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Papel<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <select class="form-control select2" data-change="hiden-error" id="paper_size_id" name="paper_size_id" data-placeholder="Seleccionar una tipo de papel">
                            <option value="">Seleccionar tipo de papel</option>
                            @if (!empty($qr->paperSize))
                                <option value="<?= $qr->paperSize->id; ?>" selected=""><?= $qr->paperSize->name; ?></option>
                            @else
                            <option value="{{ $paperSize->where('slug','letter')->first()->id }}" selected="">{{$paperSize->where('slug','letter')->first()->name}}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Uso<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <select class="form-control select2" data-change="hiden-error" id="use" name="use" data-placeholder="Seleccionar una opcion">
                            <option selected disabled value="">Seleccionar</option>
                            <option value="1">Técnico</option>
                            <option value="2">Administrativo</option>
                        </select>
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label"></label>
                    <div class="col-10">
                        <div class="row skin skin-square">
                            <div class="col-md-12 col-sm-12">
                                <fieldset>
                                    <input type="radio" name="orientation" checked>
                                    <label for="input-radio-11">Vertical</label>
                                </fieldset>
                                <fieldset>
                                    <input type="radio" name="orientation">
                                    <label for="input-radio-12">Horizontal</label>
                                </fieldset>
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
    @if (isset($qr->id))
        @can('qr.edit')
            <button type="button" class="btn btn-primary round" data-click="save">Editar</button>
        @endcan
        @else
        @can('qrs.create')
            <button type="button" class="btn btn-success round" data-click="save">Generar</button>
        @endcan
    @endif
</div>
 
