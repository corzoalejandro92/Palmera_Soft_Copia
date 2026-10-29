<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h5 class="modal-title">CONFIRMACION DE TRANSFERENCIA DE INVENTARIO</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="confirmationTranferInventory">
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="" name="id" />
        <input type="hidden" name="enabled" value="1" />
        <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
        <input type="hidden" name="warehouse_exit" value="{{ $transferInventory->warehouse_id}}">
        <input type="hidden" name="warehouse_arrival" value="{{ $transferInventory->warehouse_transfer}}">
        <input type="hidden" name="transferInventory" value="{{ $transferInventory->id}}">
        <input type="hidden" name="type_document_id" value="{{ $transferInventory->documentType->id ?? ''}}">
        <input type="hidden" name="user_id" value="{{ $transferInventory->user_id ?? ''}}">
        <input type="hidden" name="file_extend_id" value="{{ $transferInventory->file_extend_id ?? ''}}">
        <div>
            <div class="row">
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <h5>Fecha de transferencia</h5>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-calendar"></span>
                            </span>
                        </div>
                        <input class="form-control text-center" readonly
                            value="{{ Carbon::parse($transferInventory->date_transfer)->format("Y-m-d")}}"
                            name="data_transfer" type="date">
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-10 col-lg-3 col-xl-3">
                    <h5>Bodega de salida</h5>
                    <div class="input-group">
                        <input class="form-control text-center" readonly
                            value="{{ $transferInventory->warehouse->name ?? ''}}"
                            type="text">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-chevrons-right"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-10 col-lg-3 col-xl-3">
                    <h5>Bodega de llegada</h5>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-check-square"></span>
                            </span>
                        </div>
                        <input class="form-control text-center" readonly
                            value="{{ $transferInventory->warehouseTransfer->name ?? ''}}"
                             type="text">
                    </div>
                </div>
                @if(\App\Integration::find(1)->enabled && 
in_array(
	\App\Headquarter::find(
		session('headquarter_id')
	)->id , \App\Integration::find(1)->headquarters
))
                <div class="col-12 col-sm-12 col-md-10 col-lg-3 col-xl-3">
                    <h5>Documento Foxcon</h5>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-check-square"></span>
                            </span>
                        </div>
                        <input class="form-control text-center" readonly
                            value="{{ $transferInventory->documentType->nomenclature ?? ''}}"
                             type="text">
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
                        <th></th>
                        <th>Producto</th>
                        <th>Foxcon</th>
                        <th>Lote</th>
                        <th>Fecha. Vcto</th>
                        <th>Cant. Enviada</th>
                        {{-- <th>Aprobado</th> --}}
                        <th>Cant. Recibida</th>
                        <!-- <th style="width: 10px;"></th> -->
                    </tr>
                </thead>
                <tbody>
                    @if(isset($transferInventory) && $transferInventory->detailTranferInventory)
                    @foreach($transferInventory->detailTranferInventory as $indx => $tranferInventory)
                    @php
                    $key = uniqid();
                    $order = true;
                    @endphp
                    @include('inventory.forms.frames.confirmationTransfer_new_row',compact(['key','transferInventory']))
                    @endforeach
                    @else
                    @php $key = uniqid();$order = false; $transferInventory; @endphp
                    @include('inventory.forms.frames.confirmationTransfer_new_row',compact(['key','transferInventory']))
                    @endif
                </tbody>
                <!-- <tfoot>
                    <tr>
                        <th colspan="1" class="add_row_th" style="width: 10px;">
                            <a class="btn btn-outline-teal" data-click="addRowConfirmationTransfer">
                                <i class="la la-plus-circle"></i>
                            </a>
                            @include('layouts/loading/min')
                        </th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot> -->
              
            </table>
        </div>


        <hr>
        <div class="form-group col-12 col-sm-12 col-md-10 col-lg-12 col-xl-12">
            <h5>Observaciones</h5>
            <textarea type="text" name="observation" class="form-control"
                placeholder="Ingrese las observaciones de la confirmacion de transferencia"></textarea>
        </div>
        <hr>
        @if(isset($transferInventory->observation))
        <hr>
            <div class="form-group col-12 col-sm-12 col-md-10 col-lg-12 col-xl-12">
                <h5>Observaciones: <strong>{{$transferInventory->observation ?? ''}} </strong> </h5>
            </div>
        <hr>
        @endif
        @if(isset($transferInventory->user))
            <div class="form-group col-12 col-sm-12 col-md-10 col-lg-12 col-xl-12">
                <h5>Transferencia realizada por: <strong>{{$transferInventory->user->name ?? ''}} </strong> el <strong>{{$transferInventory->created_at->format('d/m/Y h:i a')}}</strong></h5>
            </div>
        @endif
        <div class="form-group col-12 col-sm-12 col-md-10 col-lg-12 col-xl-12">
            <div class="alert alert-danger"><strong>*Nota:</strong> Señor usuario, debe aprobar la transferencia, <strong>si y solo si</strong>, esta recibiendo los productos y las cantidades indicadas;
            de lo contrario, se debe comunicar a la persona que realizo la transferencia, y pedirle el favor que ingrese la informacion <strong>CORRECTA</strong>; ya que una vez aprobada no la podra editar</div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveConfirmationTransferInventory">Confirmar</button>
</div>