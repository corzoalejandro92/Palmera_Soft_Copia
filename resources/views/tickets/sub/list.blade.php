<?php 
use Carbon\Carbon; 
?>
<div style="position:relative; padding:10px"></div>
<table class="table table-striped table-bordered " >
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Cliente</th>
            <th class="text-center">Sucursal</th>
            <th class="text-center">Prioridad</th>
            <th class="text-center">Solucionado</th>
            <th class="text-center">Leido</th>
            <th class="text-center">N Respuestas</th>
            <th class="text-center">Categoria</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Realizado por</th>
            {{-- 
            <th class="text-center">Fecha Realizacion</th>
            <th class="text-center">Sede</th>
            <th class="text-center">F. Creación</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
            
            <tr class="pointer" style="cursor: pointer;" data-click="openlistTicketShow" data-id="{{$ticket->id}}">
                <td class="text-center"  >
                    {{ $ticket->id}}
                </td>
                <td class="text-center"  >
                    {{ $ticket->office->crm->name}}
                </td>
                <td class="text-center"  >
                    {{ $ticket->office->name}}
                </td>
                <td class="text-center"  >
                    <span class="badge badge-{{$ticket->priority->class}}">{{ $ticket->priority->name}}</span>
                </td>
                <td class="text-center"  >
                    @if ($ticket->solved == 1)
                        <span class="badge badge-success">Si</span>
                    @else
                        <span class="badge badge-danger">No</span>
                    @endif
                </td>
                <td class="text-center"  >
                    @if ($ticket->read_by_id != null)
                        <span class="badge badge-success">Si</span>
                    @else
                        <span class="badge badge-danger">No</span>
                    @endif
                </td>
                <td class="text-center"  >
                    {{ $ticket->count_answers ?? 0}}
                </td>
                <td class="text-center"  >
                    {{$ticket->category->name}}
                </td>
                <td class="text-center"  >
                    {{Carbon::parse($ticket->created_at)->format('d/m/Y H:i:s A')}}
                </td>
                <td class="text-center"  >
                    {{$ticket->registered->name}}
                </td>
            </tr>
        @endforeach
        
    </tbody>
</table>
<span align="left" style="margin-top: -58px;">Mostrando del {{$tickets->firstItem()}}  al {{$tickets->lastItem()}} de {{$tickets->total()}} resultados</span>
<div align="right" style="margin-top: -40px;">
    {!!$tickets->render()!!}
</div>

