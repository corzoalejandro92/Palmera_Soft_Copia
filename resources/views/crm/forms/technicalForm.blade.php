<hr>
<h4 class="card-title">Técnicos X Servicio</h4>
<input type="hidden" name="office_id" value="{{$office ?? ''}}">
<table class="table stripe tbl-technical">
    <thead>
        <tr class="th_title">
            <th class="text-center">Servicio</th>
            <th class="text-center">Hora Fija</th>
            <th class="text-center">Estado inicial</th>
            <th class="text-center">Cantidad</th>
            <th class="text-center">Técnico(s)</th>
            <th class="text-center">Inicio validez</th>
            <th class="text-center">Fin validez</th>
            <th style="width: 10px;"></th>
        </tr>
    <tbody>
        @if(isset($crm) && $crm->services)
            @foreach($crm->services as $indx => $technical)
                @php
                $key = uniqid();
                @endphp
                @include('crm.forms.frames.technical_new_row',compact(['key','technical']))
            @endforeach
        @else
            {{-- @php $key = uniqid(); $technical; @endphp
            @include('crm.forms.frames.technical_new_row',compact(['key'])) --}}
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addRowTechnical">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>