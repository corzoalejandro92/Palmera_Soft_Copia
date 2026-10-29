<?php use Carbon\Carbon; ?>
 <!-- BEGIN: Content-->
 <style>
    #timeline.timeline-left .timeline-item {
        padding-right: 40px;
    }
    #timeline .timeline-group a.btn {
        left: -20px !important;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title">{{$ticket->subject}} # {{$ticket->id}}</h4>
    <button type="button" data-click="categoryFilter" data-solved="{{ null }}" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
    <div class="content-body">
        <section id="timeline" class="timeline-left timeline-wrapper">
            <ul class="timeline">
                <li class="timeline-line"></li>
                
            </ul>
            <ul class="timeline">
                <li class="timeline-line"></li>
                <li class="timeline-group">
                    <a href="#" class="btn btn-primary round">
                        <i class="ft-calendar"></i> {{$ticket->created_at->format('d-m-Y H:i:s')}} H</a>
                </li>
                        <li class="timeline-item">
                            <div class="timeline-badge">
                            </div>
                            <div class="timeline-card card border-grey border-lighten-2" style="border: solid;">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{$ticket->subject}}
                                    </h4>
                                </div>
                                <div class="card-content">
                                    <div class="col-12">
                                        <p class="card-subtitle text-muted mb-0">
                                            <span class="font-medium-2"><span style="color: #35977a;">Mensaje:</span>&nbsp;{{$ticket->message}}</span>
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <p class="card-subtitle text-muted mb-0">
                                            <span class="font-medium-2"><span style="color: #35977a;">Categoria:</span>&nbsp;{{$ticket->category->name}}</span>
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <p class="card-subtitle text-muted mb-0">
                                        <span class="font-medium-2"><span style="color: #35977a;">Prioridad:<span class="badge badge-{{$ticket->priority->class}}">{{$ticket->priority->full_name}}</span></span>
                                        </p>
                                    </div>
                                    @can('ticket.changed.priority')
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="priority_id" class="form-group">Prioridad</label><br>
                                                @foreach ($priorities as $priority)
                                                    @php
                                                        $checked=$priority->id == $ticket->priority_id ? 'checked' : '';
                                                    @endphp
                                                    <div class="custom-control  custom-control-inline">
                                                        <input {{$checked}} form="answerTicket" type="radio" id="reviewed{{$priority->id}}" data-keyup="hiden-error" name="priority_id" value="{{$priority->id}}" class="custom-control-input">
                                                        <label class="custom-control-label" for="reviewed{{$priority->id}}">{{$priority->name}}</label>
                                                    </div>

                                                @endforeach
                                            </div>
                                        </div>
                                    @endcan
                                    @if ($ticket->file)
                                    @php
                                        $nameFiles=explode('#',$ticket->file);
                                        $nameFile=$nameFiles[1];
                                    @endphp
                                        <div class="col-12">
                                            <p class="card-subtitle text-muted mb-0">
                                            <span class="font-medium-2"><span style="color: #35977a;">Archivo:  <a  href="{{route('crms.dowloadFileTicket',["path" => $ticket->file ])}}" target="_blank"><i class="la la-eye"></i> {{$nameFile ?? 'desconocido'}}</a></span>
                                            </p>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <p class="card-subtitle text-muted mb-0">
                                            @if (filled($ticket->read_by_id))
                                                <p class="info">Leido por<a href="#"> {{optional($ticket->read)->name}} </a> {{ optional($ticket->read_at)->diffForHumans() }} <i class="fa fa-comments"></i> <a href="#">{{$ticket->count_answers ?? 0}} Respuestas</a></p>
                                            @else
                                                <p class="info">Sin leer</p>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="media-body text-right">
                                    <ul class="list-unstyled users-list">
                                        <li>
                                            <div style="position: relative;right: 40px;">
                                                <img class="avatar avatar-sm pull-up" src="<?= url($ticket->registered->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                                                &nbsp;<?= $ticket->registered->name ?? '' ?>
                                            </div>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </li>
                        @php
                            $answers=$ticket->answers;
                        @endphp
                        @foreach($answers as $answer)
                        <li class="timeline-group">
                            <a href="#" class="btn btn-primary round">
                                <i class="ft-calendar"></i> {{$answer->created_at->format('d-m-Y H:i:s')}} H</a>
                        </li>
                                <li class="timeline-item">
                                    <div class="timeline-badge">
                                    </div>
                                    <div class="timeline-card card border-grey border-lighten-2" style="border: solid;">
                                        <div class="card-content">
                                            <div class="col-12">
                                                <p class="card-subtitle text-muted mb-0">
                                                    <span class="font-medium-2"><span style="color: #35977a;">Mensaje:</span>&nbsp;{{$answer->message}}</span>
                                                </p>
                                            </div>
                                            @if ($answer->file)
                                            @php
                                                $nameFiles=explode('#',$answer->file);
                                                $nameFile=$nameFiles[1];
                                            @endphp
                                                <div class="col-12">
                                                    <p class="card-subtitle text-muted mb-0">
                                                    <span class="font-medium-2"><span style="color: #35977a;">Archivo:  <a href="{{route('crms.dowloadFileTicket',["path" => $answer->file ])}}" target="_blank"><i class="la la-eye"></i> {{$nameFile ?? 'desconocido'}}</a></span>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="media-body text-right">
                                            <ul class="list-unstyled users-list">
                                                <li>
                                                    <div style="position: relative;right: 40px;">
                                                        <img class="avatar avatar-sm pull-up" src="<?= url($answer->registered->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                                                        &nbsp;<?= $answer->registered->name ?? '' ?>
                                                    </div>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                    @endforeach
            </ul>
        </section>
    </div>
    <!-- END: Content-->
    @can('ticket.answer')
        <div class="modal-body">
            <div class="mt-2">
                <form id="answerTicket" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('POST')}}
                    <input type="hidden" name="id" value="{{$ticket->id}}">
                    <div class="form-group">
                        <label for="message" class="form-group">Mensaje<span class="text-danger">*</span></label>
                        <textarea data-keyup="hiden-error" name="message" class="form-control" placeholder="Mensaje" style="height: 120px;"></textarea>
                    </div>
                    <input class="form-group" type="file" name="file">
                    
                </form>
            </div>
        </div>
    @endcan
<div class="modal-footer">
    @can('ticket.solved')
        <div class="custom-control  custom-control-inline" style="left: 33px;position: absolute;">
            <input {{ $ticket->solved == 1 ? 'checked' : '' }} type="checkbox" data-id="{{$ticket->id}}" id="solved" data-click="solved" class="custom-control-input">
            <label class="custom-control-label" for="solved">Solucionado?</label>
        </div>
    @endcan
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal"  data-click="categoryFilter" data-solved="{{ null }}">Cerrar</button>
    @can('ticket.answer')
        <button type="button" class="btn btn-success round" data-click="answer_ticket">Responder</button>
    @endcan
</div>