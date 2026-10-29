
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="dataTables_length" id="tblList_length">
            <label style="display: inline-flex;">Mostrar 
                <select style="position: relative;top: -10px;margin-left: 5px;margin-right: 5px;" name="tblList_length" aria-controls="tblList" class="form-control form-control-sm">
                    <option value="10" {{$officeTicket->perpage() == 10 ? 'selected' : ''}} >10</option>
                    <option value="25" {{$officeTicket->perpage() == 25 ? 'selected' : ''}} >25</option>
                    <option value="50" {{$officeTicket->perpage() == 50 ? 'selected' : ''}} >50</option>
                    <option value="100" {{$officeTicket->perpage() == 100 ? 'selected' : ''}} >100</option>
                </select> 
                registros
            </label>
        </div>
    </div>
    <div class="col-sm-12 col-md-6" align="right">
            <div id="tblList_filter" class="dataTables_filter" style="display: inline-flex;">
                <label style="display: inline-flex;">Buscar:  
                    <input style="position: relative;top: -10px;margin-left: 5px;" type="search" name="search" id="search" class="form-control form-control-sm" placeholder="" aria-controls="tblList" data-value="{{$term ?? ''}}">
                </label>
            </div>
        
    </div>
    
</div>
<div style="min-height: 405px;max-height: 605px; overflow: auto; margin-bottom: 40px">

<ul class="list-group fa-padding">
    @forelse ($officeTicket as $officeTicke)
        <li class="list-group-item" data-click="openlistTicketShow" data-id="{{$officeTicke->id}}">
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
                        <p class="info">Leido por<a href="#"> {{optional($officeTicke->read)->name}} </a> {{ optional($officeTicke->read_at)->diffForHumans() }} <i class="fa fa-comments"></i> <a href="#">{{$officeTicke->count_answers ?? 0}} Respuestas</a></p>
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
<span align="left" style="margin-top: -58px;">Mostrando del {{$officeTicket->firstItem()}}  al {{$officeTicket->lastItem()}} de {{$officeTicket->total()}} resultados</span>
<div align="right" style="margin-top: -40px;">
    {!!$officeTicket->render()!!}
</div>
                        