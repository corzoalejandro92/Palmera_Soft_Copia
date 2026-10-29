@if($scheduling)
<div class="modal-header">
    <h4 class="modal-title">{{ $scheduling->name}} > Cambiar estado</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="statusScheduling">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$scheduling->id}}" />
        <div class="card" >
                <div class="card-content">
                    <div class="card-body">
                        {{-- <h4 class="card-title">Fecha programada</h4>
                        <input class="form-control" type="date" disabled  value="{{\Carbon\Carbon::parse($scheduling->start)->format('Y-m-d')}}"><br>
                        <h4 class="card-title">Hora programada</h4>
                        <input class="form-control" type="time" disabled value="{{\Carbon\Carbon::parse($scheduling->start)->format('H:i')}}"><br>
                        <h4 class="card-title">Cantidad de técnicos</h4>
                        <input class="form-control" type="number" disabled value="{{$scheduling->users->count() ?? '1'}}"><br>
                        <h4 class="card-title">Técnico Asignado</h4>
                        <select class="form-control select2" name="user[]" multiple>
                            @if($scheduling->users)
                                @foreach($scheduling->users as $technical)
                                <option value="{{$technical->id }}" selected>{{$technical->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <br><br><br> --}}
                    
                        <h4 class="card-title">Estado actual</h4>
                        @if($scheduling->completed == 0)
                            <p><span class="badge" style="background-color: #795548"><b>En ruta</b> </span></p>
                        @elseif($scheduling->completed == 1)
                            <p><span class="badge" style="background-color:green"><b>Completado</b> </span></p>
                        @elseif($scheduling->completed == 2)
                            <p><span class="badge" style="background-color:red"><b>Cancelado</b> </span></p>
                        @elseif($scheduling->completed == 3)
                        <p><span class="badge" style="background-color:#FF9800"><b>No realizado</b> </span></p>
                        @endif
                        <h4 class="card-title">Estados disponibles</h4>
                        <p>Seleccione un estado <code> de esta lista</code>. Los estados se condicionan según tu rol en el sistema.</p>
                        <div class="row skin skin-square">
                            <div class="col-md-6 col-sm-12">
                                <fieldset>
                                    <input type="radio" name="completed" value="0" id="input-radio-0" {{ $scheduling->completed == 0 ? 'checked':'' }}>
                                    <label for="input-radio-0">En ruta</label>
                                </fieldset>
                                <fieldset>
                                    <input type="radio" name="completed" value="1" id="input-radio-1" {{ $scheduling->completed == 1 ? 'checked':'' }}>
                                    <label for="input-radio-1">Completado</label>
                                </fieldset>
                                <fieldset>
                                    <input type="radio" name="completed" value="2" id="input-radio-2" {{ $scheduling->completed == 2 ? 'checked':'' }}>
                                    <label for="input-radio-2">Cancelado</label>
                                </fieldset>
                                <fieldset>
                                    <input type="radio" name="completed" value="3" id="input-radio-3" {{ $scheduling->completed == 3 ? 'checked':'' }}>
                                    <label for="input-radio-3">No realizado</label>
                                </fieldset>
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
    <button type="button" class="btn btn-success round" data-click="updateStatusScheduling">Cambiar Estado</button>
</div>
@endif
