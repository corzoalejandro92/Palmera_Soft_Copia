<div class="modal-header">
    <h5 class="modal-title">B.Nacional > {{$warehouse->name}} >Configuracion de inventario</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">

    <form id="settingInventory">
        {{ method_field( isset($settingProductWarehouse->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="{{$warehouse->id ?? ''}}" name="warehouse_id">
        <input type="hidden" value="<?= $settingProductWarehouse->id ?? ''; ?>" name="id" />
        <input type="hidden" name="enabled" value="1" />
        <div class="row">
            <div class="container">
                <table class="table stripe tbl-documents">
                    <thead>
                        <tr class="th_title">
                            <th>Producto</th>
                            <th></th>
                            <th>Minimo</th>
                            <th></th>
                            <th></th>
                            <th>Maximo</th>
                            <th></th>
                            <th style="width: 10px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(isset($settingProductWarehouse))
                    @php
                    $key = uniqid();
                    @endphp
                    @include('inventory.forms.frames.settings_new_row',compact(['key','settingProductWarehouse']))
                    @else
                    @php $key = uniqid(); @endphp
                    @include('inventory.forms.frames.settings_new_row',compact(['key','settingProductWarehouse']))
                    @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" class="add_row_th" style="width: 10px;">
                                <a class="btn btn-outline-teal" data-click="addRowSetting">
                                    <i class="la la-plus-circle"></i>
                                </a>
                                @include('layouts/loading/min')
                            </th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveProductWarehouse">Guardar</button>
</div>