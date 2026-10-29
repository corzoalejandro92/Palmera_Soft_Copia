<div class="modal-header">
    <h5 class="modal-title"><b>{{isset($peakPlate->id) ? 'EDITAR' : 'AGREGAR'}} PICO Y PLACA</b></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="form-peak-plate">
        <!-- valores ocultos del formulario -->
        {{ method_field(isset($peakPlate->id)?'PUT':'POST') }}  
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{isset($peakPlate->id) ? $peakPlate->id : '' }}">
            <div class="form-group row">
                <div class="col-6">
                    <label class="col-form-label">Departamento<span class="text-danger">*</span></label>
                    <select class="form-control select2" data-change="hiden-error" id="state_id" name='states_id'  data-placeholder="Departamento" autocomplete="off">
                        <option value="">Departamento</option>
                        <?php if (isset($peakPlate->city)): ?>
                            <option value="<?= $peakPlate->city->state->id; ?>" selected=""><?= $peakPlate->city->state->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label class="col-form-label">Ciudad<span class="text-danger">*</span></label>
                    <select class="form-control select2" data-change="hiden-error" id="city_id" name='city_id'  data-placeholder="Ciudad" autocomplete="off">
                        <option value="">Ciudad</option>
                        
                        <?php
                        if (!empty($peakPlate->city)): ?>
                            <option value="<?= $peakPlate->city->id; ?>" selected=""><?= $peakPlate->city->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        @if(isset($peakPlate->id))
            <div class="form-group row">
                <label class="col-2 col-form-label">Fecha</label>
                <div class="col-10">
                    <input class="form-control" data-keyup="hiden-error" disabled name="date" type="date" value="{{\Jenssegers\Date\Date::parse($peakPlate->date)->format('Y-m-d')}}" title="Fecha" autocomplete="off">
                </div>
            </div>
        @else
            <div class="form-group row">
                <label class="col-2 col-form-label">Dias</label>
                <div class="col-10">
                    <select class="form-control select2" data-change="hiden-error" id="days_week" name='days_week[]'  data-placeholder="dias de la semana" autocomplete="off">
                        <option value="" disabled >Seleccione</option>
                        
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 col-form-label">Rango Fecha<span class="text-danger">*</span></label>
                <div class="col-5">
                    <input class="form-control" data-keyup="hiden-error" name="start_date" id="start_date" type="date" value="" title="Fecha inicial" autocomplete="off">
                </div>
                <div class="col-5">
                    <input class="form-control" data-keyup="hiden-error" name="end_date" id="end_date" type="date" value="" title="Fecha final" autocomplete="off">
                </div>
            </div>
            <input type="hidden" name="verify_days_week" id="verify_days_week" value="0">
        @endif
        <div class="form-group row">
            <label class="col-2 col-form-label">Rango Hora<span class="text-danger">*</span></label>
            <div class="col-5">
                <input class="form-control" data-keyup="hiden-error" name="start_time" type="time" value="{{$peakPlate->start_time ?? ''}}" title="Hora inicial" autocomplete="off">
            </div>
            <div class="col-5">
                <input class="form-control" data-keyup="hiden-error" name="end_time" type="time" value="{{$peakPlate->end_time ?? ''}}" title="Hora final" autocomplete="off">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-2 col-form-label">Tipo de vehículo<span class="text-danger">*</span></label>
            <div class="col-10">
                <select class="form-control select2" data-change="hiden-error" id="vehicletype"
                    name="vehicle_type_id" data-keyup="hiden-error">
                    <option value="" disabled>--seleccionar--</option>
                        @if (isset($peakPlate->vehicleType))
                            <option value="{{$peakPlate->vehicleType->id}}" selected>
                            {{$peakPlate->vehicleType->name}}</option>
                        @endif
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-2 col-form-label">Numeros<span class="text-danger">*</span></label>
            <div class="col-10">
                <select class="form-control select2" data-select="{{isset($peakPlate->digits) ? json_encode($peakPlate->digits) : ''}}" data-change="hiden-error" id="digits" name='digits[]' autocomplete="off">
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Regla<span class="text-danger">*</span></label>
            <div class="col-10">
                <select class="form-control select2" data-change="hiden-error" id="rule_id" name='rule_id' autocomplete="off">
                    @if (isset($peakPlate->rule))
                            <option value="{{$peakPlate->rule->id}}" selected>
                            {{$peakPlate->rule->name}}</option>
                    @endif
                </select>
            </div>
        </div>
        <button type="submit" hidden></button>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($peakPlate->id))
        @can('headquarters.edit')
            <button type="button" class="btn btn-primary round" data-click="save">Editar</button>
        @endcan
    @else
        @can('headquarters.create')
            <button type="button" class="btn btn-success round" data-click="save">Agregar</button>
        @endcan
    @endif
</div>