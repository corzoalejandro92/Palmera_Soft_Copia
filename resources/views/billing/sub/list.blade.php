<?php use Carbon\Carbon; ?>
<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Nro.</th>
            <th>Cliente</th>
            <th>Servicio</th>
            <th>Total</th>
            <!-- <th>Total</th> -->
            <th>Estado</th>
            <th class="nofilter">Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($billings as $billing)
        <tr>
            <td style="width: 11%;" class="text-center">{{ Carbon::parse($billing->date_billing)->format("Y-m-d")}}</td>
            <td class="text-center">{{$billing->number_billing}}</td>
            <td class="text-center">{{$billing->crm->name}}</td>
            <td>
                @foreach($billing->detailBillings as $detailBilling)
                <span class="badge badge-info m-3px">{{$detailBilling->name}}:
                    {{$detailBilling->quantity}} \
                    Precio:<b>$<?= number_format($detailBilling->valor,0,'.',',') ?></b></span>
                @endforeach
            </td>
            <td class="text-center"><b>$<?= number_format($billing->total,0,'.',',') ?></b></td>
            <td><select class="select2 form-control" id="stateBilling" data-id="{{ $billing->id}}" data-change="saveStateBilling">
                    <option value="">-Seleccionar-</option>
                    <?php if (isset($billing->billingState)): ?>
                    <option value="<?= $billing->billingState->id;?>"  selected><?= $billing->billingState->name; ?></option>
                    <?php endif; ?>
                </select>
            </td>
            <td style="width: 12%;">
                <div class="text-center">
                    <a href="#" data-click="openForm" data-id="<?= $billing->id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><i
                            class="icon-pencil mx-3"></i><a>

                    <a href="{{route('billings.format-pdf',[$billing->id])}}" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver Proforma"><i
                            class="icon-cloud-download mx-3"></i></a>

                    <a href="#" data-toggle="tooltip" data-placement="top" title="Enviar Proforma"><i
                            class="icon-paper-plane mx-3"></i></a>

                    <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Eliminar"><i
                            class="icon-close mx-1"></i></a> -->

                </div>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>