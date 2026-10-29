@if($quote)
<div class="modal-header">
    <h4 class="modal-title">{{ $quote->crm->name }} > Cotización {{ $quote->id }} > Cambiar estado</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="status">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <input type="hidden" name="id" value="<?= $quote->id ?>" />
        <div class="card" >
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title">Estados disponibles</h4>
                        <p>Seleccione un estado <code> de esta lista</code>. Los estados se condicionan según tu rol en el sistema.</p>
                        <div class="row skin skin-square">
                            <div class="col-md-6 col-sm-12">
                                @foreach($statuses as $status)
                                    <fieldset>
                                        <input type="radio" name="status_id" value="{{ $status->id }}" id="input-radio-{{ $status->id }}" {{ $status->id == $quote->status_id ? 'checked':'' }}>
                                        <label for="input-radio-{{ $status->id }}">{{ $status->name }}</label>
                                    </fieldset>
                                @endforeach
                            </div>
                            
                            <div class="col-sm-12">
                                <fieldset class="colors clear">
                                    <strong>Importante:</strong><br>
                                    <p>Esta acción generará un registro en el seguimiento del cliente de manera automática.</p>
                                </fieldset>
                            </div>

                        </div>
                        <div class="col-sm-12" id="showaprovedCombo" style="{{$quote->status_id != 2 ? 'display: none;' : ''}}">
                            <hr>
                            <fieldset class="colors clear">
                                <strong>Servicios aprobados:</strong><br><br>
                                <div class="row skin skin-flat">
                                    <div class="col-md-12 col-sm-12">
                                        @foreach ($quote->services as $service)
                                            <fieldset>
                                                <input type="checkbox" checked id="service-{{$service->id}}" name="aproved_id[]" value="{{ $service->id }}">
                                                <label for="service-{{$service->id}}">{{$service->service->name}}</label>
                                            </fieldset>
                                        @endforeach 
                                    </div>
                                </div>
                            </fieldset>
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
