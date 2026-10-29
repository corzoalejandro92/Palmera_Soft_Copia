<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h5 class="modal-title">{{ $deleted ? 'ELIMINAR' : 'EDITAR' }}  INVENTARIO</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="editInventory">
        {{ method_field( isset($editInventory->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
        <input type="hidden" value="<?= $editInventory->id ?? ''; ?>" name="id" />
        <input type="hidden" name="enabled" value="1" />
        <div class="container">
            <div class="row">
                <div class="form-group col-12 col-sm-12 col-md-10 col-lg-3 col-xl-3">
                    <h5>Fecha</h5>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-calendar"></span>
                            </span>
                        </div>
                        <input class="form-control datepicker text-center"
                            name="date_shopping" value="{{ Carbon::parse($editInventory->fecha ?? '')->format("Y-m-d")}}" type="date" readonly>
                    </div>
                </div>

                <div class="form-group mb-1 col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                    <h5 for="price">Stock</h5>
                    <div class="input-group mb-2">
                    <div class="input-group-prepend">
                            <div class="input-group-text">#
                            </div>
                        </div>
                        <input type="text" class="form-control text-center" style="text-transform: uppercase;" autocomplete="off" value="{{ $editInventory->stock ?? ''}}"
                            name="code_foxcon" placeholder="Codigo foxcon" data-keyup="hiden-error" readonly>
                    </div>
                </div>

                <div class="form-group mb-1 col-12 col-sm-12 col-md-6 col-lg-7 col-xl-7">
                    <h5 for="price">Producto</h5>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control text-center" autocomplete="off" value="{{ $editInventory->detailInventory->product->name ?? ''}}"
                            name="code_foxcon" placeholder="Codigo foxcon" data-keyup="hiden-error" readonly>
                    </div>
                </div>

                <hr>
            </div>
            <hr>
            <div>
                <table class="table stripe tbl-documents">
                    <thead>
                        <tr class="th_title">
                            <th>Codigo</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Fecha de vencimiento</th>
                            <!-- <th style="width: 10px;"></th> -->
                        </tr>
                    <tbody>
                            @if(isset($editInventory) && $editInventory->detailInventories)
                            @foreach($editInventory->detailInventories as $indx => $detailInventory)
                                 @php 
                                    $key = uniqid(); 
                                 @endphp
                                 @include('inventory.forms.frames.inventoryDetail_new_row',compact(['key','editInventory','deleted']))
                            @endforeach
                        @else
                            @php $key = uniqid(); $purchase; @endphp
                            @include('inventory.forms.frames.inventoryDetail_new_row',compact(['key','editInventory','deleted']))
                        @endif
                    </tbody>
                    </thead>
                </table>
            </div>


            <hr>
            <div class="form-group col-12 col-sm-12 col-md-10 col-lg-12 col-xl-12">
                <h5>Observaciones</h5>
                <textarea type="text" name="observation" class="form-control"
            placeholder="Ingrese las observaciones del ajuste manual"></textarea>
            </div>

        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if($deleted)
        <button type="button" class="btn btn-success round" data-id="{{$editInventory->id}}" data-click="deleteInventory" data-warehouse="{{$editInventory->warehouse_id}}">Eliminar</button>
    @else
        <button type="button" class="btn btn-success round" data-click="saveEditInventory">{{isset($editInventory->id)?'Actualizar':'Guardar'}}</button>
    @endif
</div>