<?php use Carbon\Carbon; ?>

<table class="table text-inputs-searching table-bordered zero-configuration" style="width:100%!important;">
<thead>
    <tr>
        <th class="nofilter">Nro de<br> Contrato</th>
        <th>Fecha Inicio<br>Del Contrato</th>
        <th>Fecha Final<br>Del Contrato</th>
        <th class="nofilter">Nro de<br> Cotización</th>
        <th class="nofilter">Opciones</th>

    </tr>
</thead>
<tfoot>
    <tr>
        <th>Nro de<br> Contrato</th>
        <th>Fecha Inicio<br>Del Contrato</th>
        <th>Fecha Final<br>Del Contrato</th>
        <th class="nofilter">Nro de<br> Cotización</th>
        <th class="nofilter">Opciones</th>

    </tr>
</tfoot>
<tbody>
    @foreach($contracts as $contract)
    <tr>
            <th scope="row">{{ $contract->number }}</th>
            <td>{{ Carbon::parse($contract->start_date)->format('d/m/Y') }}</td>
            <td>{{ Carbon::parse($contract->end_date)->format('d/m/Y') }}</td>
            <td>{{ $contract->quote_id }}</td>
            <td>
                <div class="text-center">
                    <a href="#"><i class="icon-pencil mx-3"></i></a>
                    <i class="icon-close mx-1"></i>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>
</table>