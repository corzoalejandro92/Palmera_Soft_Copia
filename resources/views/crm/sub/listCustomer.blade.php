<h4>Informacion básica</h4>
<br>
<table class="table table-padded table-xl mb-0" id="recent-project-table" id="tblList">
    <tbody>
        <tr>
            <td class="text-truncate align-middle">
                <span><b> Nombre comercial</b></span>
            </td>
            <td class="text-truncate">
            <td class="text-truncate pb-0">
            <span>{{ $crm->commercial_name ?? ''}}</span>
            </td>
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b> NIT / CC</b></span>
            </td>
            <td class="text-truncate">
            <td class="text-truncate pb-0">
            <span>{{ $crm->document ?? ''}}</span>
            </td>
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Cliente Nacional</b></span>
            </td>
            <td class="text-truncate">
            <td class="text-truncate pb-0">
            <span>{{ $crm->customer_national ? 'Si': 'No' }}</span>
            </td>
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b> Tipo de Persona</b></span>
            </td>
            <td class="text-truncate">
            <td class="text-truncate pb-0">
                <span>{{ $crm->type_customer ? 'Juridica': 'Natural' }}</span>
            </td>
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Medio de Contacto</b></span>
            </td>
            <td class="text-truncate">
            <td class="text-truncate pb-0">
                <span>{{ $crm->meanContact ? $crm->meanContact->name : 'No registra'}}</span>
            </td>
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Cliente por Subcontratación</b></span>
            </td>
            <td class="text-truncate">
            <td class="text-truncate pb-0">
                <span>{{ $crm->customer_subcontract ? 'Si': 'No'  }}</span>
            </td>
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Comercial asignado</b></span>
            </td>
            <td class="text-truncate">
            <td class="text-truncate pb-0">
                <span>{{ $crm->user ? $crm->user->name : 'Sin Asignar'  }}</span>
            </td>
        </tr>
        <tr>
            <td class="text-truncate align-middle">
                <span><b>Correo para facturación electronica</b></span>
            </td>
            <td class="text-truncate">
            <td class="text-truncate pb-0">
                <span>{{ $crm->ei_email ?? ''  }}</span>
            </td>
        </tr>
    </tbody>
</table>
<br><br><br>

<h4>Configuración de facturación</h4>
@can('crms.info.admin')
    <div style="z-index:1000!important;">
        <a href="{{asset('images/default/formato_descarga_reporte_ruta.pdf')}}" class="btn btn-outline-teal" data-click="openFormConfigBilling"
            data-id="<?= $crm->id ?>">
            <i class="ft-plus"></i>
            Editar Configuracion
        </a>
    </div>
@endif
<br>
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