
<div class="modal-header">
    <h5 class="modal-title">ENVIAR CRONOGRAMA</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    
    <section id="basic">
        <div class="card">
            <div class="card-content collapse show">
                <div class="card-body">
                    <form action="#" id="sendSchedule">
                        {{ method_field('POST')}}
                        {{ csrf_field() }}
                        
                        
                        <input type="hidden" name="start_date_submit" value="{{$startDate}}">
                        <input type="hidden" name="end_date_submit" value="{{$endDate}}">
                        <input type="hidden" name="office_id" value="{{$office->id}}">
                        <input type="hidden" name="crm_id" value="{{$crm->id}}">

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
                            <label class="col-md-1">BCC:</label>
                            <div class="col-md-11">
                                <div class="form-control tags" data-tags-input-name="bcc"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-1">Asunto:</label>
                            <div class="col-md-11">
                            <input class="form-control text-bold-700" type="text" value="Cronograma de servicio Palmera Junior desde {{$startDateFormat}} a {{$endDateFormat}}" name="subjet"/>
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
                                            {{$greeting}} {{$contact->name}},<br><br>

                                            Esperamos que se encuentre muy bien.<br><br>

                                            Nos permitimos compartir con usted el cronograma de servicios que será prestado desde el {{$startDateFormat}} hasta el {{$endDateFormat}}, 
                                            acorde a la contratación que {{$crm->name}} ha adquirido con nosotros. <br><br>

                                            Tambien queremos informarle que este cronograma está sujeto a cambios de último momento y que la fecha y hora de los servicios serán confirmados por medio de llamada de nuestro equipo de programación.<br><br>

                                            Quedamos muy atentos a cualquier duda o inquietud que pueda tener al respecto.
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
        <button type="button" class="btn btn-success round" data-click="send-schedule" >Enviar</button>
        @endcan
</div>