<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <!-- contenido del modal -->
    <form id="generalOrderPurchase">
        <section class="card">
            {{ method_field( isset($generalOrderPurchase->id)?'PUT':'POST') }}
            {{ csrf_field() }}
            <input type="hidden" value="<?= $generalOrderPurchase->id ?? ''; ?>" name="id" />
            <input type="hidden" name="enabled" value="1" />
            <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
            @foreach($orderPurchases as $indx => $orderPurchase)
                    @php
                    $key = uniqid();
                    @endphp
                    <input type="hidden" name="order[<?=$key?>][orderPurchase_id]" value="{{$orderPurchase->id ?? ''}}">
                @endforeach

            <div id="invoice-template" class="card-body">
                <!-- Invoice Company Details -->
                <div id="invoice-company-details" class="row">
                    <div class="col-md-6 col-sm-12 text-left text-md-left">
                        <img src="<?=url("template/app-assets/images/logo/logo.png")?>" alt="company logo"
                            class="mb-2" width="150">
                        <ul class="px-0 list-unstyled">
                            <li class="text-bold-700">Palmera Junior, Control De Plagas</li>
                            <li>B. La Esmeralda, Bucaramanga,</li>
                            <li>Calle 60 # 16-35</li>
                        </ul>

                    </div>
                    <div class="col-md-6 col-sm-12 text-center text-md-right">
                        <h2>ORDEN DE COMPRA</h2>
                        <div class="form-group m-form__group row">
                            <label class="col-6 col-form-label">No.</label>
                            <div class="col-4">
                                @if(isset($generalOrderPurchase->number_order_purchase))
                                <input type="text" class="form-control" name="number_order_purchase" readonly
                                    value="{{$generalOrderPurchase->number_order_purchase}}">
                                @else
                                <input type="text" name="number_order_purchase" value="{{$numberOrder}}"
                                    class="form-control" readonly>
                                @endif
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-4 col-form-label">Fecha.</label>
                            <div class="col-7">
                                @if(isset($generalOrderPurchase->data_order))
                                <input type="date" class="form-control" name="date_order" readonly
                                    value="{{$generalOrderPurchase->data_order}}">
                                @else
                                <input class="form-control datepicker"
                                    value="{{ Carbon::parse($generalOrderPurchase->date_order ?? '')->format("Y-m-d")}}"
                                    name="date_order" type="date" readonly>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
                <!--/ Invoice Company Details -->

                <!-- Invoice Customer Details -->
                <div id="invoice-customer-details" class="row pt-2">
                    <div class="col-md-6 col-sm-12">
                        <p class="text-muted">(7) 676 00 50</p>
                        <p class="text-muted">bucaramanga@palmerajunior.com</p>
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
                            @if(isset($detailorders))
                            @foreach($detailorders as $indx => $detailorder)
                            @php
                            $key = uniqid();
                            @endphp
                            @include('inventory.forms.frames.orderPurchase_new_row',compact(['key','detailorder']))
                            @endforeach
                            @else
                            @php $key = uniqid(); $detailorder; @endphp
                            @include('inventory.forms.frames.orderPurchase_new_row',compact(['key','detailorder']))
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
                        placeholder="Ingrese las observaciones de la orden de compra general"></textarea>
                </div>


            </div>
        </section>
    </form>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveGeneralOrderPuschase">{{(isset($generalOrderPurchase->id)?'Editar':'Guardar')}}</button>
</div>