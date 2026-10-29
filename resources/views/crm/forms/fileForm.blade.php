<hr>
<input type="hidden" name="pruebasss" value="something">
<h4 class="card-title">Documentos del cliente {{ isset($seatype) ? '': '(Solo sucursal principal)'}}</h4>

<table class="table stripe tbl-documents">
    <thead>
        <tr class="th_title">
            <!-- @if(isset($seatype))
                <th class="text-center">Sucursal</th>
            @endif -->
            <th class="text-center">Archivo</th>
            <th class="text-center">Tipo</th>
            <th class="text-center">Observación</th>
            <th style="width: 10px;"></th>
        </tr>
    <tbody>
        @if(isset($crm) && $crm->files)
            @foreach($crm->files as $indx => $cdocument)
                @php
                $key = uniqid(); 
                @endphp
                @include('crm.forms.frames.document_new_row',compact(['key','cdocument','filetypes']))
            @endforeach
        @else
            {{-- @php $key = uniqid(); $cdocument; @endphp
            @include('crm.forms.frames.document_new_row',compact(['key','cdocument', 'filetypes'])) --}}
        @endif

        @if(isset($offices))
            @foreach($offices as $office)
                @foreach($office->files as $cdocument)
                    @php
                    $key = uniqid(); 
                    @endphp
                    @include('crm.forms.frames.document_new_row',compact(['key','cdocument','filetypes']))
                @endforeach
            @endforeach
        @else
            {{-- @php $key = uniqid(); $cdocument; @endphp
            @include('crm.forms.frames.document_new_row',compact(['key','cdocument', 'filetypes'])) --}}
        @endif

    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addRow" data-typeseat="{{isset($seatype)}}">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="{{isset($seatype) ? 4: 3}}"></th>
        </tr>
    </tfoot>
</table>