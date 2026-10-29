<?php use Carbon\Carbon; 
    $disabledd= optional(optional(optional($transferInventory)->first())->foxconDocument)->sync == 1 ? 'disabled' : '';
?>
<div class="modal-header">
    <h5 class="modal-title">Obsequio > {{ optional(optional(optional($transferInventory)->first())->warehouse)->name ?? $warehouse->name ?? '' }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="transferInventory">
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="{{$warehouse->id ?? ''}}" name="warehouse_id" id="warehouse_id">
        <input type="hidden" value="5" id="docId">

        <input type="hidden" value="{{ isset($transferInventory->id) ? $transferInventory->id : '' }}" name="id" />
        <div class="container">
            <div class="row">
            @if(\App\Integration::find(1)->enabled && 
in_array(
	\App\Headquarter::find(
		session('headquarter_id')
	)->id , \App\Integration::find(1)->headquarters
))
            <div class="mb-1 col-5  col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <h5>Documento Foxcon</h5>
                <select class="select2 form-control type_document" {{$disabledd}} data-change="hiden-error" name="document_type_id">
                    <option value="">--seleccionar--</option>
                    @if (isset($transferInventory->first()->typeDocumentFoxcon->id))
                        <option value="{{$transferInventory->first()->typeDocumentFoxcon->id}}" selected="">{{$transferInventory->first()->typeDocumentFoxcon->nomenclature}}</option>
                    @endif
                </select>
            </div>
            <div class=" mb-1 col-4 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <h5 for="price">Cod. Documento</h5>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">#
                        </div>
                    </div>
                    <input type="text" class="form-control" style="text-transform: uppercase;" autocomplete="off" value="{{ $transferInventory->first()->foxconDocument->numero ?? ''}}"
                        name="code_foxcon" placeholder="Codigo documento foxcon" data-keyup="hiden-error" readonly>
                </div>
            </div>
            @endif
        </div>
            <hr>
            <div>
                <table class="table stripe tbl-documents">
                    <thead>
                        <tr class="th_title">
                            <th style="width: 5%;">Id</th>
                            <th style="width: 30%;">Producto</th>
                            <th style="width: 12%;">Lote</th>
                            <th style="width: 20%;">Cant. a Obsequiar</th>
                            <th style="width: 30%;">Stock</th>
                            <th style="width: 10px;"></th>
                        </tr>
                    </thead>
                    <tbody class="tranferInventory">
                       
                        @if(isset($transferInventory))
                        @foreach($transferInventory as $indx => $detailTranferInventory)
                        @php
                        $key = uniqid();
                        $disabled= optional(optional($detailTranferInventory)->foxconDocument)->sync == 1 ? 'disabled' : '';
                        @endphp
                            @include('inventory.forms.frames.giftForm_new_row',compact(['key','transferInventory']))
                        @endforeach
                        @else
                        @php $key = uniqid(); $transferInventory;  @endphp
                            @include('inventory.forms.frames.giftForm_new_row',compact(['key','transferInventory']))
                        @endif
                    </tbody>

                    @if(isset($transferInventory) && count($transferInventory) == 0)
                    <tfoot>
                        <tr>
                            <th class="add_row_th" style="width: 10px;">
                                <a class="btn btn-outline-teal {{$disabledd}}" data-click="addRowGiftInventory">
                                    <i class="la la-plus-circle"></i>
                                </a>
                                @include('layouts/loading/min')
                            </th>
                            
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            <hr>
            <div class="form-group col-12 col-sm-12 col-md-10 col-lg-12 col-xl-12">
                <h5>Observaciones</h5>
                <textarea type="text" name="observation" class="form-control"
                    placeholder="Ingrese las observaciones del obsequio" {{$disabledd}}>{{optional(optional($transferInventory)->first())->observation ?? '' }}</textarea>
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round " {{$disabledd}} data-click="saveGiftInventory">{{isset($transferInventory->id)?'Actualizar':'Guardar'}}</button>
</div>