
<h4 class="card-title">Listado de precios</h4>

<table class="table stripe tbl-coverages">
    <thead>
        <tr class="th_title">
            <th>Sedes</th>
            <th>Cobertura</th>
            <th>Visitas</th>
            <th>Precio</th> 
            <th style="width: 10px;"></th>
        </tr>
    <tbody>
         @if(isset($service) && $service->detailServices)
            @foreach($service->detailServices as $indx => $detailService)
                 @php 
                    $key = uniqid(); 
                 @endphp
                 @include('service.forms.frames.coverage_new_row',compact(['key','detailService']))
            @endforeach
        @else
            @php $key = uniqid(); $detailService=null; @endphp
            @include('service.forms.frames.coverage_new_row',compact(['key','detailService']))
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addCoverageRow">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="4"></th>
        </tr>
    </tfoot>
</table>
              

