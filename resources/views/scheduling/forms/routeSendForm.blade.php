<?php  
    use Jenssegers\Date\Date;
    use Illuminate\Support\Str;
?>

<div class="modal-header">
    <h5 class="modal-title">ENVIAR / REENVIAR RUTA DEL TÉCNICO</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">

    <section id="basic">
        <div class="card">
            <div class="card-content collapse show">
                <div class="card-body">
                    <form action="#" id="sendRoute">
                        {{ method_field('POST')}}
                        {{ csrf_field() }}
                        
                        <input type="hidden" name="date" value="{{$route->date}}">
                        <input type="hidden" name="route_id" value="{{$route->id}}">

                        <div class="form-group row">
                            <label class="col-md-1">Destinatario:</label>
                            <div class="col-md-11">
                                <div class="form-control tags" data-tags-input-name="destinatary"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-1">CC:</label>
                            <div class="col-md-11">
                                <div class="form-control tags" data-tags-input-name="cc"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-1">BCC</label>
                            <div class="col-md-11">
                                <div class="form-control tags" data-tags-input-name="bcc"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-1">Asunto</label>
                            <div class="col-md-11">
                            <input class="form-control text-bold-700" type="text" value="Reporte de ruta {{Date::parse($route->date)->format('l')}} ({{Carbon\Carbon::parse($route->date)->format('d/m/Y')}}) - {{$route->user->name}}" name="subject"/>
                            </div>
                        </div>

                            <div class="col-md-12">
                                <hr>
                            </div>
                            

                            <div class="col-12">
                                <label>Cuerpo del correo.</label>
                                <div class="form-group">
                                    <textarea name="body" id="ckeditor" cols="30" rows="15" class="ckeditor">
                                        <p>
                                            {{$greeting}} {{explode(' ', $route->user->name)[0]}},<br><br>

                                            Esperamos que te encuentres muy bien.<br><br>

                                            Nos permitimos compartir tu ruta de prestación de servicio para el dia {{Date::parse($route->date)->format('l')}} ({{Carbon\Carbon::parse($route->date)->format('d/m/Y')}}). Debes asegurarte que cuentas con el inventario necesario para ejecutar esta ruta, de ser necesario debes abastecerte de la bodega de la sede mínimo un día antes de la fecha de este servicio.<br><br>

                                            Quedamos muy atentos a cualquier duda o inquietud que puedas tener al respecto.<br><br>

                                            Cordialmente,<br><br>

                                            <b>{{auth()->user()->name}}</b><br>
                                            {{auth()->user()->position}}<br>
                                            {{auth()->user()->email}}<br>
                                            {{auth()->user()->phone ? 'Cel: '.auth()->user()->phone :''}}<br>
                                        </p>
                                    </textarea>
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
        <button type="button" class="btn btn-success round" data-click="send-route" >Enviar</button>
        @endcan
</div>