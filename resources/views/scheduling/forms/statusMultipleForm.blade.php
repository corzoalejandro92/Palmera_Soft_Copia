
<div class="modal-header">
    <h4 class="modal-title"> Cambiar estados masivamente</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="status">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        @foreach ($ids as $id)
            <input type="hidden" name="id[]" value="{{$id}}" />
        @endforeach
        
        <div class="card" style="height: auto;">
                <div class="card-content">
                    <div class="card-body">
                            <h4 class="card-title">Fecha programada</h4>
                            <input class="form-control" type="date" name="date"  value=""><br>
                            <h4 class="card-title">Hora programada</h4>
                            <input class="form-control" type="time" name="time"  value=""><br>
                            <h4 class="card-title">Cantidad de técnicos</h4>
                                <input class="form-control" type="number" name="technical_quantity"  value="{{$schedule->technical_quantity ?? '1'}}"><br>
                                <h4 class="card-title">Técnico Asignado</h4>
                                <select class="form-control select2" name="user[]" multiple>
    
                                </select><br><br>
                        <h4 class="card-title">Estados disponibles</h4>
                        <p>Seleccione un estado <code> de esta lista</code>. Los estados se condicionan según tu rol en el sistema.</p>
                        <div class="row skin skin-square">
                            <div class="col-md-6 col-sm-12">
                                @foreach($statuses as $status)
                                    <fieldset>
                                        <input type="radio" name="status_id" value="{{ $status->id }}" id="input-radio-{{ $status->id }}">
                                        <label for="input-radio-{{ $status->id }}">{{ $status->name }}</label>
                                    </fieldset>
                                @endforeach
                            </div>
                            
                            <div class="col-sm-12">
                                <fieldset class="colors clear">
                                    <strong>Importante:</strong><br>
                                    <p>
                                        Recuerda que la fecha que definas aquí, será la que el módulo de 
                                        automatización tomará para calcular las rutas de los técnicos.
                                    </p>
                                </fieldset>
                            </div>
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

