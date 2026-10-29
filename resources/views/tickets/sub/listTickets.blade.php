

<div style="min-height: 405px;max-height: 605px; overflow: auto; margin-bottom: 40px">

<ul class="list-group fa-padding">
    @forelse ($officeTicket as $officeTicke)
        <li class="list-group-item" @can('ticket.view') data-click="openlistTicketShow" data-id="{{$officeTicke->id}}" @endcan>
            <div class="media">
                <i class="fa fa-cog pull-left"></i>
                <div class="media-body">
                    <strong>{{$officeTicke->subject}}</strong>
                    @if ($officeTicke->solved != 1  )
                        <span class="badge badge-{{$officeTicke->priority->class}}">{{$officeTicke->priority->full_name}}</span>
                    @endif 
                    @if ($officeTicke->solved == 1  )
                        <span class="badge badge-success">SOLUCIONADO</span>
                    @endif
                    <span class="number pull-right"># {{ $officeTicke->id}}</span>
                        <p class="info">Registrado por<a href="#"> {{$officeTicke->registered->name}} </a> {{ $officeTicke->created_at->diffForHumans() }}</p>
                    @if (filled($officeTicke->category_id))
                        <p class="info">Categoría: <a href="#">{{$officeTicke->category->name}}</a></p>
                    @endif
                    @if (filled($officeTicke->read_by_id))
                        <p class="info">Abierto por<a href="#"> {{optional($officeTicke->read)->name}} </a> {{ optional($officeTicke->read_at)->diffForHumans() }} <i class="fa fa-comments"></i> <a href="#">{{$officeTicke->count_answers ?? 0}} Respuestas</a></p>
                    @else
                        <p class="info">Sin leer</p>
                    @endif
                    
                </div>
            </div>
        </li>
    @empty
        <li class="list-group-item" >
            <div class="media">
                <i class="fa fa-cog pull-left"></i>
                <div class="media-body">
                    <span>No hay Registros</span>
                </div>
            </div>
        </li>
    @endforelse
</ul>
</div>                        