<table class="table stripe tbl-documents">
    <thead>
        <tr class="th_title">
            <th>Jornada</th>
            <th>Inicio</th>
            <th>Fin</th> 
            <th>Horas Extras</th>
            <th style="width: 10px;"></th>
        </tr>
    <tbody>
        @if(isset($availability) && $availability->workingHours)
            @foreach($availability->workingHours as $index => $workingHour)
                @php 
                    $key = uniqid(); 
                @endphp
                @include('availability.forms.frames.working_hours_new_row',compact(['key','workingHour']))
            @endforeach
        @else
            @php $key = uniqid(); $workingHour= null; @endphp
            @include('availability.forms.frames.working_hours_new_row',compact(['key','workingHour']))
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addRow">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>
              

