<?php  
    use Jenssegers\Date\Date;
    use Illuminate\Support\Str;
?>

<div class="modal-header">
    <h5 class="modal-title">Realizar modificaciones</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">

    <section id="basic">
        <div class="card">
            <div class="card-content collapse show">
                <div class="card-body">
                    <form action="#" id="editRoute">
                        {{ method_field('PUT')}}
                        {{ csrf_field() }}
                        
                       
                        <input type="hidden" name="id" value="{{$route->id}}">

                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">Fecha:</label>
                                <input class="form-control" type="date" name="date" value="{{\Carbon\Carbon::parse($route->date)->format('Y-m-d')}}">
                            </div>
           
                            <div class="col-3">
                                <label class="col-form-label">Estado:</label>
                                <select class="form-control" name="status">
                                    <option value="{{$route->status->id}}">{{$route->status->name}}</option>
                                </select>
                            </div>

                            <div class="col-3">
                                <label class="col-form-label">Técnico:</label>
                                <input class="form-control" type="text" value="{{$route->user->name}}" disabled readonly>
                            </div>

                            <div class="col-12">
                                <label class="col-form-label">Observaciones:</label>
                                <textarea class="form-control" autozise name="observation">{{preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $route->observation)}}</textarea>
                            </div>

                        </div>


                            <div class="col-md-12">
                                <hr>
                            </div>
                            

                            <div class="col-12">
                                <label>Eventos de la ruta.</label>
                                <button class="btn btn-primary" data-click="openEventForm" data-route="{{$route->id}}" data-user="{{$route->user_id}}" style="float: right;position: absolute;right: 15px;">Añdir evento</button>
                                <button class="btn btn-success btn-edit" data-click="openEventForm" data-route="{{$route->id}}" data-user="{{$route->user_id}}" style="float: right;position: absolute;right: 15px; display:none;">Editar evento</button>
                                <br><br>
                                <div class="form-group">
                                    <div id="visualization"></div>
                                </div> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
        @can('crms.create')
        <button type="button" class="btn btn-success round" data-click="edit-route" data-id="{{$route->id}}" >Guardar</button>
        @endcan
</div>