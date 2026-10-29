<h6><i class="step-icon ft-activity"></i>Servicios</h6>
<fieldset class="fieldspace">

    <table class="table stripe tbl-services">
        <thead>
        <tr class="th_title">
            <th>Servicio</th>
            <th>Cobertura</th>
            <th>Cant.</th>
            <th>Precio</th>
            <th>Visitas</th>
            <th>Periodicidad</th>
            <th style="width: 10px;"></th>
        </tr>
        <tbody>
        @if(isset($quote->services))
            @foreach($quote->services as $service)
                @php
                    $key = uniqid();
                    $quoteId = $quote->id;
                @endphp
                @include('quote.forms.frames.service_new_row',compact(['key','service','quoteId']))
            @endforeach
        @else
            @php $key = uniqid(); $service= null; $quoteId = null; @endphp
            @include('quote.forms.frames.service_new_row',compact(['key','service','quoteId']))
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addServiceRow">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="6"></th>
        </tr>
        </tfoot>
    </table>
</fieldset>