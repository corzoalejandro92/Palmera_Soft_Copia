<?php 
use Carbon\Carbon; 
?>
<div style="position:relative; padding:10px"></div>
<table class="table table-striped table-bordered zero-configuration" >
    <thead>
        <tr>
            <th class="text-center">Modulo</th>
            <th class="text-center" title="Id del modelo">Id</th>
            <th class="text-center">Acción</th>
            <th class="text-center">Titulo</th>
            <th class="text-center">Sede</th>
            <th class="text-center">F. Creación</th>
            <th class="text-center">Realizado por</th>
        </tr>
    </thead>
    <tbody>
        @foreach($audits as $audit)
            
            <tr class="pointer">
                <td class="text-center"  data-id="{{$audit->id}}">
                    {{$audit->model_name}}
                </td>
                <td class="text-center"  data-id="{{$audit->id}}">
                    @can('audit.view')
                            <a href="#" data-click="openFormView" data-id="<?= $audit->id ?>">
                                {{$audit->auditable_id}}
                            </a>
                    @endcan
                    @cannot('audit.view')
                        {{$audit->auditable_id}}
                    @endcannot
                </td>
                <td class="text-center"  data-id="{{$audit->id}}">
                   {{ $audit->eventt}}
                </td>
                <td class="text-center"  data-id="{{$audit->id}}">
                   {{ isset($audit->title) ? $audit->title :''}}
                </td>
                <td class="text-center"  data-id="{{$audit->id}}">
                    {{$audit->headquarter->name}}
                </td>
                <td class="text-center"  data-id="{{$audit->id}}">
                    {{Carbon::parse($audit->created_at)->format('d/m/Y H:i:s A')}}
                </td>
                <td class="text-center"  data-id="{{$audit->id}}">
                    {{$audit->user->name}}
                </td>
            </tr>
        @endforeach
        
    </tbody>
</table>
<span align="left" style="margin-top: -58px;">Mostrando del {{$audits->firstItem()}}  al {{$audits->lastItem()}} de {{$audits->total()}} resultados</span>
<div align="right" style="margin-top: -40px;">
    {!!$audits->render()!!}
</div>

