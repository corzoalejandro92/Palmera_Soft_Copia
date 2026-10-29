<div class="modal-header">
    <h5 class="modal-title">{{(isset($vehicle->id)?'EDITAR VEHICULO':'CREAR VEHICULO')}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">

    <form id="vehicle" action="{{isset($vehicle->id)?'vehicles/update':'vehicles/save'}}" enctype="multipart/form-data">
        {{ method_field( isset($vehicle->id)?'POST':'POST') }}
        {{ csrf_field() }}
        <div class="container">
            <div class="form-body row">
                <input type="hidden" value="<?= $vehicle->id ?? ''; ?>" name="id" />
                <div class="form-group col-12 col-sm-12 col-md-4 col-lg-5 col-xl-6">
                    <label for="complaintinput1">Placa</label>
                    <input type="text" class="form-control" data-keyup="hiden-error"
                        placeholder="Ingrese el numero de placa del vehículo" name="plaque"
                        value="{{ $vehicle->plaque ?? ''}}">
                    {!! $errors->first('name_plate', '<span class=error>:message</span>' ) !!}
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label for="complaintinput1">Tipo de vehículo</label>
                        <select class="form-control select2" data-change="hiden-error" id="vehicletype"
                            name="vehicle_type_id" data-keyup="hiden-error">
                            <option value="">--seleccionar--</option>
                            <?php if (isset($vehicle->vehicleTypes)): ?>
                            <option value="<?= $vehicle->vehicleTypes->id; ?>" selected="">
                                <?= $vehicle->vehicleTypes->name; ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label for="complaintinput1">Sede</label>
                        <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
                        <input type="text" class="form-control" readonly value="{{session('headquarter_name')}}">
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="timesheetinput3">Fecha de caducidad de los documentos:</label>
                </div>


                <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label>SOAT</label>
                    <div class="position-relative has-icon-left">
                        <input type="date" class="form-control" name="date_soat" value="{{ $vehicle->date_soat ?? ''}}"  data-keyup="hiden-error">
                        {!! $errors->first('date_soat', '<span class=error>:message</span>' ) !!}
                    </div>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label for="timesheetinput3">Técno mecánica</label>
                    <div class="position-relative has-icon-left">
                        <input type="date" id="timesheetinput3" class="form-control" name="date_mechanics"
                            value="{{ $vehicle->date_mechanics ?? ''}}" data-keyup="hiden-error">
                        {!! $errors->first('date_mechanics', '<span class=error>:message</span>' ) !!}
                    </div>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label for="timesheetinput3">Seguro</label>
                    <div class="position-relative has-icon-left">
                        <input type="date" id="timesheetinput3" class="form-control" name="date_secure"
                            value="{{ $vehicle->date_secure ?? ''}}" data-keyup="hiden-error">
                    </div>
                </div>

            </div>
            <hr>

            <div>
                <h4 class="card-title">Documentos del vehículo</h4>
                <table class="table stripe tbl-documents">
                    <thead>
                        <tr class="th_title">
                            <th>Archivo</th>
                            <th>Tipo</th>
                            <th>Observación</th>
                            <th style="width: 10px;"></th>
                        </tr>
                    <tbody>
                        @if(isset($vehicle) && $vehicle->files)
                        @foreach($vehicle->files as $indx => $document)
                        @php
                        $key = uniqid();
                        @endphp
                        @include('vehicle.forms.frames.document_new_row',compact(['key','document','filetypes']))
                        @endforeach
                        @else
                        @php $key = uniqid(); $document; @endphp
                        @include('vehicle.forms.frames.document_new_row',compact(['key','document','filetypes']))
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" class="add_row_th" style="width: 10px;">
                                <a class="btn btn-outline-teal" data-click="addRow">
                                    <i class="la la-plus-circle"></i>
                                </a>
                                @include('layouts/loading/min')
                            </th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($vehicle->id))
        @can('vehicles.edit')
           <button type="button" class="btn btn-primary round" data-click="save">Editar</button>
        @endcan
    @else
        @can('vehicles.create')
           <button type="button" class="btn btn-success round" data-click="save">Crear</button>
        @endcan
    @endif
</div>