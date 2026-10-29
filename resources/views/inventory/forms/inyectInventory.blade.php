<?php use Carbon\Carbon; ?>
<div class="modal-header">
    @if( $warehouse->parent)
        <h5 class="modal-title">B.Nacional > {{ $warehouse->parent()->first()->name}} > {{ $warehouse->name}} > Entrada Mercancia</h5>
    @else
    <h5 class="modal-title">{{ $warehouse->name}} > Entrada Mercancia</h5>
    @endif


    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="inyect">
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="{{$warehouse->warehousable_type ?? ''}}" id="warehousable_type">
        <input type="hidden" value="{{$warehouse->id ?? ''}}" name="warehouse_id" id="warehouse_id">
        <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
        <input type="hidden" value="<?= $purchase->id ?? ''; ?>" name="id" />
        <input type="hidden" name="enabled" value="1" />
        <input type="hidden" value="4" id="docId">
        <div class="container">
            <div class="row">
                <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <h5>Fecha de inición</h5>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-calendar"></span>
                            </span>
                        </div>
                        <input class="form-control datepicker" value="{{ Carbon::parse($purchase->date_shopping ?? '')->format("Y-m-d")}}"
                            name="date_shopping" type="date">
                    </div>
                </div>

                
                @if(\App\Integration::find(1)->enabled && 
in_array(
	\App\Headquarter::find(
		session('headquarter_id')
	)->id , \App\Integration::find(1)->headquarters
))
                    <div class="form-group mb-1 col-12  col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <h5>Documento Foxcon</h5>
                        <select class="select2 form-control type_document" data-docId="2" data-change="hiden-error" name="document_type_id">
                            <option value="">--seleccionar--</option>
                            @if (isset($transferInventory->documentType->id))
                                <option value="{{$transferInventory->documentType->id}}" selected="">{{$transferInventory->documentType->nomenclature}}</option>
                            @endif
                        </select>
                    </div>
                @endif

                @if(\App\Integration::find(1)->enabled && 
in_array(
	\App\Headquarter::find(
		session('headquarter_id')
	)->id , \App\Integration::find(1)->headquarters
))
                    <div class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <h5 for="price">Cod. Documento</h5>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">#
                                </div>
                            </div>
                            <input type="text" class="form-control" style="text-transform: uppercase;" autocomplete="off" value="{{ $purchase->code_foxcon ?? ''}}"
                                name="code_foxcon" placeholder="Codigo documento foxcon" data-keyup="hiden-error" readonly>
                        </div>
                    </div>
                @else
                    <div class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <h5 for="price">Cod. Foxcon</h5>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">#
                                </div>
                            </div>
                            <input type="text" class="form-control" style="text-transform: uppercase;" autocomplete="off" value="{{ $purchase->code_foxcon ?? ''}}"
                                name="code_foxcon" placeholder="Codigo foxcon" data-keyup="hiden-error">
                        </div>
                    </div>
                @endif
                <hr>
            </div>
            <hr>
            <div>
                <table class="table stripe tbl-documents">
                    <thead>
                        <tr class="th_title">
                            <th>Producto</th>
                            <th>RSA</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Fecha de vencimiento</th>
                            <th style="width: 10px;"></th>
                        </tr>
                    <tbody>
                            
                            @php $key = uniqid(); $purchase; @endphp
                            @include('inventory.forms.frames.purchase_new_row',compact(['key','purchase']))

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" class="add_row_th" style="width: 10px;">
                                <a class="btn btn-outline-teal" data-click="addRowShopping">
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
            placeholder="Ingrese las observaciones necesarias"><?=$purchase->observation ?? '' ?></textarea>
            </div>

        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveInyect">Guardar</button>
</div>