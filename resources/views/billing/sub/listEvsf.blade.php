

@foreach ($crms as $crm)
    <div class="list-group-item flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between bg-blue-grey bg-lighten-3 p-1">
            <a href="{{route('crms.show',[$crm->id])}}?date={{$startDate}}#tabSchedule" target="_blank" >
                <h5 class="text-bold-600">{{$crm->document}} - {{$crm->name}}  {{$crm->commercial_name ? ' / '. $crm->commercial_name : ''}}</h5>
            </a>
            
        </div>
        @if($crm->offices)
            <table class="table">
                <thead>
                    <tr>
                        <th>Sucursal</th>
                        <th>Fecha</th>
                        <th>Nota de facturación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($crm->offices as $office)
                        @forelse ($office->externalNotes as $en)
                            <tr>
                                <td style="width:30%"><a href="{{route('crm.gotoSchedule',[$crm->id,$office->id])}}?date={{$en->date}}#tabSchedule" target="_blank">{{$office->name}}</a></td>
                                <td style="width:10%">{{$en->date}}</td>
                                <td  style="width:60%" class="bg-warning bg-lighten-3 blue-grey darken-4">Fac:{{$en->number ?? 0}}; Valor:{{$en->value_format}}; Nuevo:{{$en->new_service == 1 ? 'Si' : 'No'}}; {{$en->observation}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td style="width:30%"><a href="{{route('crm.gotoSchedule',[$crm->id,$office->id])}}?date={{$startDate}}#tabSchedule" target="_blank">{{$office->name}}</a></td>
                                <td style="width:10%"></td>
                                <td  style="width:60%" class="bg-danger bg-lighten-3"></td>
                            </tr>
                        @endforelse
                    
                    @endforeach
                    
                </tbody>
            </table>
        @endif
        </div>
@endforeach