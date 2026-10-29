
<div class="modal-header">
    <h5 class="modal-title">ENVIAR COTIZACIÓN</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    
    <section id="basic">
        <div class="card">
            <div class="card-content collapse show">
                <div class="card-body">
                    <form action="#" id="sendQuote">
                        {{ method_field('POST')}}
                        {{ csrf_field() }}
                        
                        
                        <input type="hidden" name="quote_id" value="{{$quote->id}}">
                        <input type="hidden" name="consecutive" value="{{$quote->consecutive}}">

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
                            <input class="form-control text-bold-700" type="text" value="Propuesta de cotización #{{$quote->consecutive}}" name="subjet"/>
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

                                            Nos permitimos compartir con usted la propuesta de cotización
                                            que ha sido tratada con nosotros, Favor revisarla y posteriormente confirmarnos su respectiva aprobación 
                                            para proceder con la programación de los servicios a prestar.<br><br>

                                            Por favor dar respusta de aprobación al correo <a href="mailto:{{ $quote->commercial->email }}?subject=Respuesta%de%20propuesta%20de%20cotización%20#{{$quote->consecutive}}">{{ $quote->commercial->email }}</a><br><br>

                                            Quedamos muy atentos a cualquier duda o inquietud que pueda tener al respecto.<br><br>

                                            Cordialmente,<br><br>

                                            <b>{{$quote->commercial->name}}</b><br>
                                            {{$quote->commercial->position}}<br>
                                            {{$quote->commercial->email}}<br>
                                            {{$quote->commercial->phone ? 'Cel: '.$quote->commercial->phone :''}}<br>
                                        </p>
                                    </textarea>
                                </div> 
                            </div>
                            <div>
                                <label>Adjunto:</label>
                                @if(!$quote->file)
                                    <div class="alert alert-warning">
                                        <b>Podrías tener errores al envíar</b> No has adjuntado ningun archivo a esta cotización
                                    </div>
                                @else
                                <div class="documents">
                                <div class="row">
                            
                                        <div class="col-4">
                                            <a class="filemanager" href="{{route('crms.showfile',['id'=>$quote->file->id])}}" target="_blank">
                                                <p class="type">{{$quote->file->name}}</p>
                                                <span class="icon file f-{{$quote->file->extension}}">{{$quote->file->extension}}</span>
                                                <span class="name">{{$quote->file->name}}</span>
                                                <span class="size">{{$quote->file->size_text}}</span>
                                            </a>
                                        </div>

                                </div>
                            </div>
                                   
                                @endif
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
        <button type="button" class="btn btn-success round" data-click="send-quote" >Enviar</button>
        @endcan
</div>