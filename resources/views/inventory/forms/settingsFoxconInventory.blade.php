<div class="modal-header">
    <h5 class="modal-title">{{$warehouse->name}} => Configuracion de Foxcon</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">

    <form id="settingFoxconInventory">
        {{ method_field( isset($warehouse->configurationFoxcon->first()->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="{{$warehouse->id ?? ''}}" name="warehouse_id">
        <div class="row">
            <div class="container">
                <table class="table stripe tbl-documents">
                    <thead>
                        <tr class="th_title">
                            <th>Tipo documento</th>
                            <th>Nomenclatura</th>
                            <th>Consecutivo</th>
                            <th style="width: 10px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(isset($warehouse->configurationFoxcon->first()->id))
                        @foreach($warehouse->configurationFoxcon as $configurationwarehouseFoxcon)
                            @php $key = uniqid(); @endphp
                            @include('inventory.forms.frames.settingsFoxcon_new_row',compact(['key','configurationwarehouseFoxcon']))
                        @endforeach
                    @else
                        @php $key = uniqid(); @endphp
                        @include('inventory.forms.frames.settingsFoxcon_new_row',compact(['key']))
                    @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" class="add_row_th" style="width: 10px;">
                                <a class="btn btn-outline-teal" data-click="addRowFoxconSetting">
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
    <button type="button" class="btn btn-success round" data-click="saveFoxconSetting">Guardar</button>
</div>