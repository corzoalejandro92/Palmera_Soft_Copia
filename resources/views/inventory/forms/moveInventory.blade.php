<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h5 class="modal-title">B.Nacional > {{$warehouse->name }} > Traslado de inventario</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="transferInventory">
        {{ method_field( isset($transferInventory->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $transferInventory->id ?? ''; ?>" name="id" />
        <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
        <div class="container">
            <div class="row">
                <div class="form-group col-12 col-sm-12 col-md-10 col-lg-2 col-xl-2">
                    <h5>Fecha de transf.</h5>
                    <div class="input-group">
              
                        <input class="form-control datepicker" value="{{ Carbon::parse($transferInventory->date_transfer ?? '')->format("Y-m-d")}}"
                            name="date_transfer" type="date">
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-10 col-lg-2 col-xl-2">
                    <h5>Bodega de salida:</h5>
                    <input type="hidden" value="{{$warehouse->id ?? ''}}" name="warehouse_id" id="warehouse_id">
                    <input type="hidden" value="2" id="docId">
                    <input type="hidden" value="{{$warehouse->warehousable_id ?? ''}}" id="warehousable_id">
                    <input type="hidden" value="{{$warehouse->warehousable_type ?? ''}}" id="warehousable_type">
                    <input type="text" class="form-control" readonly value="{{$warehouse->name ?? ''}}">
                </div>


                <div class="col-12 col-sm-12 col-md-10 col-lg-2 col-xl-2">
                    <h5>Bodega a transferir</h5>
                    <select class="select2 form-control warehouse_transfer" data-change="hiden-error" name="warehouse_transfer">
                        <option value="">--seleccionar--</option>
                    <?php if (isset($transferInventory->warehouse_transfer)): ?>
                        <option value="<?= $transferInventory->warehouseTransfer->id ; ?>" selected=""><?= $transferInventory->warehouseTransfer->name; ?></option>
                    <?php endif; ?>
                    </select>
                </div>
                @if(

                    (
                        \App\Integration::find(1)->enabled && 
                        in_array(
                            \App\Headquarter::find(
                                session('headquarter_id')
                            )->id , \App\Integration::find(1)->headquarters
                        ) ||

                        (
                            $warehouse->id == 1 && 
                            \App\Integration::find(1)->enabled && 
                            in_array('99999999', \App\Integration::find(1)->headquarters
                            )

                        )
                    )
                )
                <div class="col-12 col-sm-12 col-md-10 col-lg-2 col-xl-2">
                    <h5>Doc. Foxcon</h5>
                    <select class="select2 form-control type_document" data-docId="2" data-change="hiden-error" name="document_type_id">
                        <option value="">--seleccionar--</option>
                        @if (isset($transferInventory->documentType->id))
                            <option value="{{$transferInventory->documentType->id}}" selected="">{{$transferInventory->documentType->nomenclature}}</option>
                        @endif
                    </select>
                </div>
                <div class=" mb-1 col-4 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                    <h5 for="price">Cod. Documento</h5>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">#
                            </div>
                        </div>
                        <input type="text" class="form-control" style="text-transform: uppercase;" autocomplete="off" value="{{ $transferInventory->pre_document_number ?? ''}}"
                            name="code_foxcon" placeholder="Codigo documento foxcon" data-keyup="hiden-error" readonly>
                    </div>
                </div>
                @endif

                <div class="col-12 col-sm-12 col-md-10 col-lg-2 col-xl-2">
                    <h5>Orden de compra</h5>
                    <select class="select2 form-control" id="order_purchase"  data-change="hiden-error" name="order_id">
                        <option value="">--seleccionar--</option>
                        <?php if (isset($transferInventory->order_id)): ?>
                            <option value="<?= $transferInventory->orderPurchases->id ; ?>" selected=""><?= $transferInventory->orderPurchases->number_order_purchase; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <hr>
            <div>
                <table class="table stripe tbl-documents">
                    <thead>
                        <tr class="th_title">
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Cant. a transferir:</th>
                            <th>Alquiler</th>
                            <th>¿Facturable?</th>
                            <!-- <th>Foxcon</th> -->
                            <!-- <th>En stock:</th> -->
                            <th style="width: 10px;"></th>
                        </tr>
                    </thead>
                    <tbody class="tranferInventory">
                        @if(isset($transferInventory) && $transferInventory->detailTranferInventory)
                        @foreach($transferInventory->detailTranferInventory as $indx => $detailTranferInventory)
                        @php
                        $key = uniqid();
                        $order = false;
                        @endphp
                            @include('inventory.forms.frames.moveForm_new_row',compact(['key','transferInventory']))
                        @endforeach
                        @else
                        @php $key = uniqid(); $order = false; $transferInventory; @endphp
                            @include('inventory.forms.frames.moveForm_new_row',compact(['key','transferInventory']))
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" class="add_row_th" style="width: 10px;">
                                <a class="btn btn-outline-teal" data-click="addRowTranferInventory">
                                    <i class="la la-plus-circle"></i>
                                </a>
                                @include('layouts/loading/min')
                            </th>
                            <th colspan="1"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
            <div class="form-group col-12 col-sm-12 col-md-10 col-lg-12 col-xl-12">
                <h5>Observaciones</h5>
                <textarea type="text" name="observation" class="form-control"
                    placeholder="Ingrese las observaciones de la transferencia"><?=$transferInventory->observation ?? '' ?></textarea>
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveTransferInventory">{{isset($transferInventory->id)?'Actualizar':'Guardar'}}</button>
</div>