

        <div id="collapse3" class="card-accordion">
            <div class="card collapse-icon accordion-icon-rotate">
                <div class="card">

                    @foreach($users as $user)
                    @if($user->schedulings->count(0) > 0)
                        <div class="card-header" id="heading{{$user->id}}">
                            <h2 class="mb-0">
                                <a class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$user->id}}" aria-expanded="true" aria-controls="collapse{{$user->id}}">
                                    {{$user->name}}
                                </a>
                            </h2>
                        </div>

                        <div id="collapse{{$user->id}}" class="collapse" aria-labelledby="heading{{$user->id}}">
                            <div class="container-fluid blue-bg">
                                <div class="container">
                                <h4 class="pb-3 pt-2">Resumen de ruta para el día {{$dateFront}}</h4>
                                        <div>
                                            @foreach($user->schedulings as $scheduling)
                                                <div id="pending" class="event animated fadeInUp p">
                                                    <div class="listcontainer"">
                                                        <h5> {{$scheduling->name}}
                                                            <span> desde 
                                                                <span class="event-time">{{$scheduling->startFront->format('h:i A')}}</span> hasta
                                                                <span class="event-time">{{$scheduling->endFront->format('h:i A')}}</span>
                                                            </span>
                                                        </h5>
                                        
                                                        <span class="text-wrap">
                                                            <i name="custom-customer" class="route-icon"></i>
                                                        <span class="route-text"> {{isset($scheduling->crm) ? $scheduling->crm->name : 'Cliente sin especificar'}}</span><br>
                                                        <span class="route-text"> {{isset($scheduling->office) ? $scheduling->office->name : 'Sucursal sin especificar'}}</span><br>
                                                    </span><br>
        
                                                    <span style="color:#677d94;font-size: 1rem;">{{$scheduling->crm_address ?? 'Sin especificar'}}</span><br><br>

                                                    <span class="route-text"><b>Observaciones:</b> <textarea class="textarea-invisible">{!!$scheduling->description ?? 'Sin especificar'!!}</textarea></span><br>
                                                        </span><br>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
 






