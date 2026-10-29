@if($schedule)
<div class="modal-header">
    <h4 class="modal-title">{{ $schedule->crm->name }} > Control {{ $schedule->service->name }} > Cambiar estado</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="status">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$schedule->id}}" />
        <div class="card" >
                <div class="card-content">
                    <div class="card-body">
                        @if($schedule->status && !$restrict)
                            @if(in_array($schedule->status->slug, ['init','programed','cancelled','service-fail']))
                                <h4 class="card-title">Fecha programada</h4>
                                <input class="form-control" type="date" name="date"  value="{{$schedule->date}}"><br>
                                <h4 class="card-title">Hora programada</h4>
                                <input class="form-control" type="time" name="start_hour"  value="{{$schedule->start_hour}}"><br>
                                <h4 class="card-title">Cantidad de técnicos</h4>
                                <input class="form-control" type="number" name="technical_quantity"  value="{{$schedule->technical_quantity ?? '1'}}"><br>
                                <h4 class="card-title">Técnico Asignado</h4>
                                <select class="form-control select2" name="user[]" multiple>
                                    @if($schedule->technicals)
                                        @foreach($schedule->technicals as $technical)
                                        <option value="{{$technical->id }}" selected>{{$technical->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <br><br><br>
                            @endif
                                @if(in_array($schedule->status->slug, ['route','service-success','invoiced']) && $schedule->scheduling)
                                    <h4 class="card-title">Fecha programada</h4>
                                    <input class="form-control" type="text" disabled value="{{\Carbon\Carbon::parse($schedule->scheduling->start)->format('d/m/Y')}}"><br>
                                    <h4 class="card-title">Hora programada</h4>
                                    <input class="form-control" type="text" disabled  value="{{\Carbon\Carbon::parse($schedule->scheduling->start)->format('h:i a')}}"><br>
                                    <h4 class="card-title">Cantidad de técnicos</h4>
                                    <input class="form-control"  type="text" disabled  value="{{$schedule->scheduling->users->count() ?? '1'}}"><br>
                                    <h4 class="card-title">Técnico Asignado</h4>
                                    <input class="form-control"  type="text" disabled  value="{{$schedule->scheduling->users->implode('name', ', ')}}"><br>
                                    <br><br><br>
                            @endif
                        @else
                            @if(!$restrict)
                                 <p class="text-danger"> Los campos de fecha y hora no pueden ser cargados por que hay un problema con el estado de este servicio</p>
                            @endif
                        @endif
                        <h4 class="card-title">Estado actual</h4>
                        @if($schedule->status)
                            <p><span class="badge" style="background-color: {{$schedule->status->color}}"><b>{{$schedule->status->name}}</b> </span></p>
                        @else
                        <p class="text-danger"> Este servicio no tiene un estado establecido, por favor seleccione uno se la siguiente lista:</p>
                        @endif
                        <h4 class="card-title">Estados disponibles</h4>
                        <p>Seleccione un estado <code> de esta lista</code>. Los estados se condicionan según tu rol en el sistema.</p>
                        <div class="row skin skin-square">
                            <div class="col-md-6 col-sm-12">
                                @foreach($statuses as $status)
                                    <fieldset>
                                        <input type="radio" name="status_id" value="{{ $status->id }}" id="input-radio-{{ $status->id }}" {{ $status->id == $schedule->status_id ? 'checked':'' }}>
                                        <label for="input-radio-{{ $status->id }}">{{ $status->name }}</label>
                                    </fieldset>
                                @endforeach
                            </div>
                            
                            @if(!$restrict)
                            <div class="col-sm-12">
                                <fieldset class="colors clear">
                                    <strong>Importante:</strong><br>
                                    <p>
                                        Recuerda que la fecha que definas aquí, será la que el módulo de 
                                        automatización tomará para calcular las rutas de los técnicos.
                                    </p>
                                </fieldset>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="updateStatus">Cambiar Estado</button>
</div>
@endif
