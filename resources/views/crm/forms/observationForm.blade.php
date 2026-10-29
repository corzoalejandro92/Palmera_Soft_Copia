
<div class="modal-header">
    <h5 class="modal-title">OBSERVACIONES INTERNAS DEL SERVICIO </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="observations">
        {{ method_field('POST') }} 
        {{ csrf_field() }}
        <input type="hidden" value="<?= $crmSchedule->id ?? ''; ?>" name="id" />
        <table class="table stripe tbl-technical">
            <thead>
                <tr >
                    <th class="text-center th_title" colspan="4" >NOTA INTERNA</th>
                    <th class="text-center th_subtitle" colspan="2" >FEEDBACK</th>
                    <th class="text-center " colspan="1" >OPCIONES</th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th class="text-center th_title">Usuario</th>
                    <th class="text-center th_title">Observación</th>
                    <th class="text-center th_title">Visibilidad</th>
                    <th class="text-center th_title">Fecha</th>
                    <th class="text-center th_subtitle">Feedback</th>
                   
                    <th class="text-center th_subtitle">Fecha</th>
                    <th style="width: 10px;"></th>
                </tr>
            </thead>
            <tbody>
                @if(isset($crmSchedule) && $crmSchedule->observations)
                    @foreach($crmSchedule->observations as $indx => $observation)
                        @php
                        $key = uniqid();
                        @endphp
                        @include('crm.forms.frames.observation_new_row',compact(['key','observation']))
                    @endforeach
                @else
                    {{-- @php $key = uniqid(); $technical; @endphp
                    @include('crm.forms.frames.technical_new_row',compact(['key'])) --}}
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="1" class="add_row_th" style="width: 10px;">
                        <a class="btn btn-outline-teal" data-click="addRowObservation">
                            <i class="la la-plus-circle"></i>
                        </a>
                        @include('layouts/loading/min')
                    </th>
                    <th colspan="7"></th>
                </tr>
            </tfoot>
        </table>
    </form>
</div>

<div class="modal-footer">
    
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveObservations">Guardar</button>
</div>
 
