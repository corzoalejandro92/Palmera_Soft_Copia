
<h4 class="card-title">Parametros adicionales</h4>

<table class="table stripe tbl-parameters">
    <thead>
        <tr class="th_title_second">
            <th>Sedes</th>
            <th>Item</th>
            <th>Precio unitario</th> 
            <th>Observación</th> 
            <th style="width: 10px;"></th>
        </tr>
    <tbody>
         @if(isset($service) && $service->parameters)
            @foreach($service->parameters as $indx => $parameterService)
                 @php 
                    $key = uniqid(); 
                 @endphp
                 @include('service.forms.frames.parameter_new_row',compact(['key','parameterService']))
            @endforeach
        @else
            @php $key = uniqid(); $parameterService=null; @endphp
            @include('service.forms.frames.parameter_new_row',compact(['key','parameterService']))
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addParameterRow">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="4"></th>
        </tr>
    </tfoot>
</table>
              

