<?php 
use Carbon\Carbon; 
?>
<table class="table table-striped table-bordered zero-configuration" id="tblList" >
    <thead>
        <tr>
            <th>Nro</th>
            <th class="text-center">Fecha de ruta</th>
            <th class="text-center">Disponibilidades</th>
            <th class="text-center">Filtro de técnicos</th>
            <th class="text-center" title="Cantidad de servicios">Filtro de servicios</th>
            <th class="text-center">F. Creación</th>
            <th class="text-center">Creado por</th>
            <th class="text-center"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($automations as $automation)
            <tr class="pointer">
                <td class="text-center font-weight-bolder" data-click="openForm" data-id="{{$automation->id}}">{{$automation->id}}</td>
                <td class="text-center font-weight-bolder" data-click="openForm" data-id="{{$automation->id}}">
                    {{Carbon::parse($automation->date)->format('d/m/Y')}}
                </td>
                <td>
                    @if($automation->availabilities)
                        @foreach (\App\Availability::find($automation->availabilities) as $availability)
                            <span class="badge badge-info text-bold-900">{{$availability->name}}</span><br>
                        @endforeach
                    @endif
                </td>
                <td>
                    @if($automation->services)
                        @foreach (\App\User::find($automation->users) as $user)
                            <span class="badge badge-success text-bold-900">{{$user->name}}</span><br>
                        @endforeach
                    @endif
                </td>
                <td>
                    @if($automation->services)
                        @foreach (\App\Service::find($automation->services) as $service)
                            <span class="badge badge-success text-bold-900">{{$service->name}}</span><br>
                        @endforeach
                    @endif
                </td>
                <td> {{Carbon::parse($automation->created_at)->format('d/m/Y')}}</td>
                <td  style="width:250px;" data-click="openForm" data-id="{{$automation->id}}">

                    <div class="col-12" style="display: flex;">
                        <div style="display: inline-block;">
                            <img class="avatar xxs mr-1" src="{{url($automation->user->avatar ?? '')}}" onerror="this.src='{{asset('images/default/user.jpg')}}'" />
                        </div>
                        <div style="display: inline-flex; vertical-align: middle;">
                            <span style="position: relative;  {{$automation->user->deleted_at ? 'text-decoration: line-through;' : ''}}">{{mb_strtoupper($automation->user->name)  }}</span>
                        </div>
                    </div>

                </td>
                
                
                <td class="text-center" >
                    <div class="text-center">
                        <a href="#" data-click="openFormDelete" data-id="{{$automation->id}}" class="text-danger"><i class="las la-trash la-2x"></i></a>
                        <a href="{{route('automation.edit',[$automation->id])}}" class="text-success"><i class="las la-folder-open la-2x"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        
    </tbody>
</table>
