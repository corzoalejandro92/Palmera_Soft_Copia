<table class="table table-padded table-xl mb-0" id="recent-project-table" id="tblList">
    @foreach ($setting as $settings)
    <tbody>

        <tr>
            <td class="text-truncate align-middle">
                <span><b>Facturas</b></span>
            </td>
            <td data-click="openForm" data-id="<?= $settings->id ?>" class="text-truncate pb-0">
                <span>{{$settings->invoice}}</span>
            </td>
            <td class="text-truncate">
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Cotizaciones</b></span>
            </td>
            <td data-click="openForm" data-id="<?= $settings->id ?>" class="text-truncate pb-0">
                <span>{{$settings->quote}}</span>
            </td>
            <td class="text-truncate">
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Comprobantes de ingreso</b></span>
            </td>
            <td data-click="openForm" data-id="<?= $settings->id ?>" class="text-truncate pb-0">
                <span>{{$settings->proof_income}}</span>
            </td>
            <td class="text-truncate">
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Comprobantes de egreso</b></span>
            </td>
            <td data-click="openForm" data-id="<?= $settings->id ?>" class="text-truncate pb-0">
                <span>{{$settings->proof_discharge}}</span>
            </td>
            <td class="text-truncate">
        </tr>

        @endforeach
    </tbody>
</table>