<table class="table table-padded table-xl mb-0" id="recent-project-table" id="tblList">
    <tbody>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Periodicidad</b></span>
            </td>
            <td class="text-truncate">{{ $crm->periodicity }}</td>
            <td class="text-truncate pb-0">
                <span></span>
            </td>
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Ciclo de facturacion</b></span>
            </td>
            <td class="text-truncate">{{ $crm->billing_cycle }}</td>
            <td class="text-truncate pb-0">
                <span></span>
            </td>
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Plazo de facturacion</b></span>
            </td>
            <td class="text-truncate">{{ $crm->pay_method ? 'Mes anticipado': 'Mes vencido' }}</td>
            <td class="text-truncate pb-0">
                <span></span>
            </td>
        </tr>
    </tbody>
</table>