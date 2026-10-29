<?php use Carbon\Carbon; ?>
<div class="row card-title">
    <div class="col-md-6">
        <h4 class="card-title top-5">Configuracion de stock por producto</h4>
    </div>
    <div class="col-md-6" align="right">
        <button class="btn btn-outline-teal" data-warehouse="{{$warehouse->id ?? ''}}" data-click="settingForm" data-id>
            <i class="ft-plus"></i>
            Crear Configuracion
        </button>
    </div>
</div>
<table class="table text-inputs-searching table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th class="text-center">Bodega</th>
            <th class="text-center">Producto</th>
            <th class="text-center">Mínimo</th>
            <th class="text-center">Máximo</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($settingProduct as $setting)
      
        <tr>
            <td class="text-center">{{$setting->warehouse->name}}</td>
            <td class="text-center">{{$setting->product->name}}</td>
            <td class="text-center">{{$setting->minimum}}</td>
            <td class="text-center">{{$setting->maximum}}</td>
            <td>
                <div class="text-center">
                    <a href="#"><i class="icon-pencil mx-3" data-click="settingForm" data-id="<?= $setting->id ?>" data-warehouse="{{$setting->warehouse_id}}" ></i></a>
                    <a href="#"><i class="icon-close mx-1" data-click="openFormDeleteSettings" data-id="<?= $setting->id ?>"></i></a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
    {{-- <tfoot>
        <tr>
            <th>Fecha de compra</th>
            <th>Lote</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Bodega</th>
            <th>Sede</th>
            <th>Fecha de vencimiento</th>
        </tr>
    </tfoot> --}}
</table>