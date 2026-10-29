<hr>
<h4 class="card-title">Sucursales</h4>
<div>
    <fieldset class="fieldspace">
        <div class="repeater-default">
            <div data-repeater-list="officeCustomer">
                <div data-repeater-item>
                    <input type="hidden" name="id" value="{{ $office->id ?? '' }}" />
                    <input type="hidden" name="enabled" value="1" />
                    <div class="row">

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <h5>Ciudad</h5>
                            <select class="select2 form-control" name="city_name" data-change="hiden-error">
                                @if(isset($office->city))
                                    <option value="{{$office->city->id}}">{{$office->city->name}}</option>
                                @endif
                            </select>
                            {{-- <input type="text" class="form-control" value="{{!empty($office->cities) ? $office->cities->name : ''}}"  readonly /> --}}
                        </div> 
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <h5>Dirección Física</h5>
                             <input  class="form-control" {{-- class="mapControls form-control searchMapInput"--}} type="text" name="address" placeholder="Buscar dirección" data-keyup="hiden-error"  autocomplete="nope" value="{{$office->address ?? ''}}">
                        </div>
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <h5>Observaciones</h5>
                            <input type="text" class="form-control" name="observation" data-keyup="hiden-error" value="{{ $office->observation ?? '' }}"
                                placeholder="Piso, Apto, Oficina">
                        </div>

                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <h5>Definir ubucación en el mapa</h5>
                            <a class="btn btn-info" type="button" data-click="openMap" href="#">Abrir mapa</a>
                            <span class="badge badge-success font-medium-1 display-hidden openMapSuccess"><i class="las la-check-circle"></i> Ubiación definida correctamente</span>
                            <div class="display-hidden">
                                <input type="text" name="lat" value="{{$office->lat ?? ''}}" />
                                <input type="text" name="lng" value="{{$office->lng ?? ''}}" />
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <h5>Nombre de esta sucursal</h5>
                            <input type="text" class="form-control" autocomplete="nope" name="name" value="{{ $office->name ?? '' }}"
                                 data-keyup="hiden-error">
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group">
                                <h5>Sede Palmera</h5>
                                    <input type="hidden" name="headquarter_id" value="{{isset($office->headquarter) ? $office->headquarter->id  : session('headquarter_id')}}">
                                    <input type="text" class="form-control" readonly value="{{isset($office->headquarter) ? $office->headquarter->name  : session('headquarter_name')}}">
                            </div>
                        </div>
                        <?php if(isset($office) && !$office->id){ ?>
                        <div class="form-group col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 text-center mt-1">
                            <button type="button" class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                data-repeater-delete>
                                <i class="ft-x"></i>
                            </button>
                        </div>
                        <?php } ?>
                    </div>
                    <?php if(isset($office) && !$office->id){ ?>
                        <hr style="border-top: 1px dashed #a4aab6;">
                    <?php } ?>
                </div>

            </div>
            <?php if(isset($office) && !$office->id){ ?>
            <div class="form-group overflow-hidden">
                <div class="col-12">
                    <a data-repeater-create class="btn btn-sm btn-round btn-fab btn-success text-white">
                        <i class="ft-plus"></i>
                    </a>
                </div>
            </div>
            <?php } ?>
        </div>
    </fieldset>
</div>
