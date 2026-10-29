<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h5 class="modal-title"> {{$warehouse->name }} > Consumo de inventario</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="transferInventory">
        {{ method_field( isset($transferInventory->id)?'PUT':'POST') }}
        {{ csrf_field() }}
      
        <input type="hidden" value="{{$warehouse->warehousable_id ?? ''}}" id="warehousable_id">
        <input type="hidden" value="{{$warehouse->warehousable_type ?? ''}}" id="warehousable_type">
        <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
        <div class="container">
            <div class="row">
                <div class="form-group col-12 col-sm-12 col-md-10 col-lg-2 col-xl-2">
                    <h5>Fecha de transf.</h5>
                    <div class="input-group">
              
                        <input class="form-control datepicker" value="{{ Carbon::now()->format("Y-m-d") }}"
                            name="date_transfer" type="date">
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-10 col-lg-2 col-xl-2">
                    <h5>Bodega:</h5>
                    <input type="hidden" value="{{$warehouse->id ?? ''}}" name="warehouse_id" id="warehouse_id">
                    <input type="hidden" value="3" id="docId">
                   
                    <input type="text" class="form-control" readonly value="{{$warehouse->name ?? ''}}">
                </div>

                @if(\App\Integration::find(1)->enabled && 
                    (in_array(
                        \App\Headquarter::find(
                            session('headquarter_id')
                        )->id , \App\Integration::find(1)->headquarters
                    ) ) ||
                    ( (
                        $warehouse->id == 1 &&
                        in_array( 99999999 , \App\Integration::find(1)->headquarters)
                    ))
                )
                <div class=" mb-1 col-4 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                    <h5 for="price">¿Consumos por facturación?</h5>
    
                    <div class="row icheck_minimal skin">
                        <div class="mechanismRadio" style="left: 13px!important;     display: contents;">
                            <div class="skin-states">
                                <fieldset>
                                        <input type="radio" class="confirmationTransfer" value="0" id="input-radio-6" name="facturable" checked>
                                        <label for="input-radio-6">No (Reportar a foxcon)</label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="mechanismRadio" style="left: 15px!important;display: contents;">
                            <fieldset>
                                    <input type="radio" class="confirmationTransfer" value="1" id="input-radio-5"  name="facturable">
                                    <label for="input-radio-5">Si (No reportar a foxcon)</label>
                            </fieldset>
                        </div>  
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-10 col-lg-2 col-xl-2 foxcon-doc">
                    <h5>Doc. Foxcon</h5>
                    <select class="select2 form-control type_document" data-docId="3" data-change="hiden-error" name="document_type_id">
                        <option value="">--seleccionar--</option>
                        {{-- @if (isset($warehouseParent->configurationFoxcon) && $warehouseParent->configurationFoxcon->count() > 0)
                            <option value="{{$warehouseParent->configurationFoxcon->last()->id}}" selected="">{{$warehouseParent->configurationFoxcon->last()->nomenclature}}</option>
                        @endif --}}
                    </select>
                </div>
                <div class=" mb-1 col-4 col-sm-12 col-md-2 col-lg-2 col-xl-2 foxcon-doc">
                    <h5 for="price">Cod. Documento</h5>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">#
                            </div>
                        </div>
                        <input type="text" class="form-control" style="text-transform: uppercase;" autocomplete="off" value="{{ $transferInventory->foxconDocument->numero ?? ''}}"
                            name="code_foxcon" placeholder="Codigo documento foxcon" data-keyup="hiden-error" readonly>
                    </div>
                </div>
                @endif
                
            </div>
            
            </div>
            <hr>
            <div>
                <table class="table stripe tbl-documents">
                    <thead>
                        <tr class="th_title">
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Cantidad consumida:</th>
                            {{-- <th>Alquiler</th>
                            <th>¿Facturable?</th> --}}
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
                            @include('inventory.forms.frames.consumptionForm_new_row',compact(['key','transferInventory']))
                        @endforeach
                        @else
                        @php $key = uniqid(); $order = false; $transferInventory = null; @endphp
                            @include('inventory.forms.frames.consumptionForm_new_row',compact(['key','transferInventory']))
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" class="add_row_th" style="width: 10px;">
                                <a class="btn btn-outline-teal" data-click="addRowConsumptionInventory">
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
                    placeholder="Ingrese las observaciones del consumo"><?=$transferInventory->observation ?? '' ?></textarea>
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveConsumptionInventory">{{isset($transferInventory->id)?'Actualizar':'Guardar'}}</button>
</div>