<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h5 class="modal-title">{{(isset($orderPurchase->id)?'EDITAR ORDEN DE COMPRA':'CREAR ORDEN DE COMPRA')}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="orderPurchase">
        {{ method_field( isset($orderPurchase->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $orderPurchase->id ?? ''; ?>" name="id" />
        <input type="hidden" name="enabled" value="1" />
        <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
        <div class="container">

            <div class="row">
                <div class="form-group m-form__group row">
                    <label class="col-6 col-form-label">ORDEN DE COMPRA No.</label>
                    <div class="col-4">
                        @if(isset($orderPurchase->number_order_purchase))
                        <input type="text" class="form-control" name="number_order_purchase"  value="{{$orderPurchase->number_order_purchase}}">
                        @else
                        <input type="text" name="number_order_purchase" value="{{$numberOrder}}" class="form-control" >
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12 col-sm-12 col-md-10 col-lg-5 col-xl-5">
                    <h5>Fecha</h5>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-calendar"></span>
                            </span>
                        </div>
                        <input class="form-control datepicker"
                            value="{{ Carbon::parse($orderPurchase->date_order ?? '')->format("Y-m-d")}}"
                            name="date_order" type="date">
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-10 col-lg-4 col-xl-4">
                    <h5>Bodega</h5>
                    <select class="select2 form-control warehouse" data-change="hiden-error"
                        name="warehouse_id">
                        <option value="">--seleccionar--</option>
                        <?php if (isset($orderPurchase->warehouse)): ?>
                        <option value="<?= $orderPurchase->warehouse->id; ?>" selected="">
                            <?= $orderPurchase->warehouse->name; ?>
                        </option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <table class="table stripe tbl-documents">
                <thead>
                    <tr class="th_title">
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th style="width: 10px;"></th>
                    </tr>
                <tbody>
                    @if(isset($orderPurchase) && $orderPurchase->detailOrderPurchases)
                    @foreach($orderPurchase->detailOrderPurchases as $indx => $detailorder)
                    @php
                    $key = uniqid();
                    @endphp
                    @include('inventory.forms.frames.orderPurchase_new_row',compact(['key','orderPurchase']))
                    @endforeach
                    @else
                    @php $key = uniqid(); $orderPurchase; @endphp
                    @include('inventory.forms.frames.orderPurchase_new_row',compact(['key','orderPurchase']))
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1" class="add_row_th" style="width: 10px;">
                            <a class="btn btn-outline-teal" data-click="addRowOrderPurchase">
                                <i class="la la-plus-circle"></i>
                            </a>
                            @include('layouts/loading/min')
                        </th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
                </thead>
            </table>
        </div>


        <hr>
        <div class="form-group col-12 col-sm-12 col-md-10 col-lg-12 col-xl-12">
            <h5>Observaciones</h5>
            <textarea type="text" name="observation" class="form-control"
                placeholder="Ingrese las observaciones de la orden de compra"><?=$orderPurchase->observation ?? '' ?></textarea>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round"data-click="saveOrderPurchase">{{isset($orderPurchase->id)?'Actualizar':'Guardar'}}</button>
</div>