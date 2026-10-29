<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h5 class="modal-title text-uppercase">Editar control del cronograma</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    @php 
        $hasRouteToday = $schedule->schedulings()->whereBetween('start', [Carbon::now()->startOfDay()->format('Y-m-d H:i:s'),Carbon::now()->endOfDay()->format('Y-m-d H:i:s')])->first();
    @endphp
    @if($hasRouteToday && $schedule->status->id == 2 )
        <div class="alert alert-warning" role="alert">
            <b>No puedes cambiar el estado de este servicio.</b> Este servicio se encuentra vinculado a una ruta en curso y su estado es "En Ruta", no es posible cambiar su estado en este momento.
        </div>
    @endif
    @php
       $schd = $schedule->schedulings()
       ->whereHas('routes',function($r) use($schedule){
        $r->where('date', $schedule->datec->format('Y-m-d'));
       })->first();

    @endphp
    @if($schd && !$hasRouteToday )
        <div class="alert alert-warning" role="alert">
            <b>¡Este servicio pertenece a una ruta!</b>
        </div>
    @endif
    <form id="control">
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="{{ $crm->id ?? '' }}" name="crm_id" />
        <input type="hidden" value="{{ $schedule->id ?? '' }}" name="id" />
        <div class="m-2">
            <section  style="    margin-bottom: -7px;
            padding: 24px 70px;
            border: 7px dotted #e6e6e6;" id="section{{$schedule->id}}{{$schedule->service->id}}">
                <div class="row">
                    <div class="col-12" >
                         <h4 class="opaque"><i class="font-medium-2 {{$schedule->service->avatar }} event-name-i" style="display: inline-flex;position: relative;top: 12px;font-size: 33px!important;"></i><span>&nbsp; Configuración para servicio {{$schedule->service->name }}</span></h4><br>
                    </div>
    
                            <div class="col-2">
                                    <label class="col-form-label">Hora Fija</label>
                                    <input type="time"  class="form-control"  name="start_hour" value="{{$schedule->start_hour ?? ''}}" >
                                </div>
                            <div class="col-2">
                                <label class="col-form-label">Estado</label>
                                @if($hasRouteToday && isset($schedule->schedulings) && $schedule->schedulings->count() > 0 && $schedule->status->id == 2 )
                                <br><p>En ruta</p>   
                                
                                @else
                                <select class="form-control select2" data-change="hiden-error" name="init_status" data-service-model="{{ $schedule->service_id}}" data-service = "{{$schedule->id }}" data-placeholder="Cobertura" autocomplete="off">
                                    @if (isset($schedule->init_status))
                                        <option value="<?= $schedule->init_status->id; ?>" selected ><?= $schedule->init_status->name; ?></option>
                                    @elseif(isset($schedule->status))
                                    <option value="<?= $schedule->status->id; ?>" selected ><?= $schedule->status->name; ?></option>
                                    @endif
                                </select>
                                @endif
                            </div>
                            <div class="col-2">
                                <label class="col-form-label">Cantidad de técnicos</label>
                                <input type="number" class="form-control" data-change="hiden-error"  name="technical_quantity"  value="{{$schedule->technical_quantity ?? ''}}">
                            </div>
                            <div class="col-4">
                                <label class="col-form-label">Técnicos asignados</label>
                                <select class="form-control select2" data-change="hiden-error" name="user[]" data-service-model="{{ $schedule->service_id}}" data-service = "{{$schedule->id }}" data-placeholder="Cobertura" autocomplete="off" multiple>
                                    @if (isset($schedule->technicals))
                                    @foreach ($schedule->technicals as $technical)
                                    <option value="<?= $technical->id; ?>" selected ><?= $technical->name; ?></option>
                                    @endforeach
                                        
                                    @endif
                                </select>
                            </div>
  
                    <div class="col-12"><hr></div>
                    
                    <div class="col-4">
                        <label class="col-form-label">Duración</label>
                           <div class="row">
                               <div class="col-md-4">
                                     <input type="number" data-keyup="hiden-error" data-change="hiden-error" class=" form-control" id="time{{$schedule->id}}" name='time_value' value="{{ $schedule->time_value ?? 0 }}" >
                                </div>
                                <div class="col-md-8">
                                     <select class="form-control select2" data-change="hiden-error" name="time_unit_id" data-placeholder="Cliente" autocomplete="off">
                                         @foreach($timeUnits as $timeUnit)
                                            <option value="{{ $timeUnit->id }}" {{ $timeUnit->id == $schedule->time_unit_id  ?  'selected':'' }}>{{ $timeUnit->name }}</option>
                                         @endforeach
                                     </select>
                                </div>
                           </div>
                    </div>
                    <div class="col-3">
                        <label class="col-form-label">Posible fecha de ejecución</label>
                        <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="date" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($schedule->datec) ?  $schedule->datec->format('d F, Y') : '')}}">
                    </div>
                    <div class="col-12" >
                        <br>
                    </div>
                </div>
                
                
                <table class="table stripe tbl-supplies" id="table{{$schedule->service->id}}">
                    <thead>
                    <tr class="th_title">
                        <th>Control</th>
                        <th>Área</th>
                        <th>Producto</th>
                        <th>Dosis</th>
                        <th class="text-center" style="width: 19%;">Aplicación</th>
                        <th style="width: 10px;"></th>
                    </tr>
                    <tbody>
                    @if(isset($schedule->controls) && count($schedule->controls) > 0)
                        @foreach($schedule->controls as $control)
                        
                            @php
                                $key = uniqid();
                                $serviceId = $schedule->service->id;
                                $scheduleId = $schedule->id;
                            @endphp
                            @php 
                            
                            @endphp
                            @include('crm.forms.frames.supply_edit_new_row',compact(['key','control','serviceId','scheduleId']))
                        @endforeach
                    {{-- @else
                        @php $key = uniqid(); $control= null; $scheduleId = $schedule->id; $serviceId = $schedule->service->id; @endphp
                        @include('crm.forms.frames.supply_edit_new_row',compact(['key','control','serviceId','scheduleId'])) --}}
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="1" class="add_row_th" style="width: 10px;">
                            <a class="btn btn-outline-teal" data-click="addSupplyRowFormControl" data-infestation="{{$schedule->service->id}}" data-control='{{$schedule->id}}'>
                                <i class="la la-plus-circle"></i>
                            </a>
                            @include('layouts/loading/min')
                        </th>
                        <th colspan="5"></th>
                    </tr>
                    </tfoot>
                </table>
                <br><br>
            <div class="form-group row ">
                <div class="col-12">
                    <label class="col-form-label">Observaciones para el servicio "<?= $schedule->service->name ?>"</label>
                    <textarea class="form-control" readonly data-keyup="hiden-error" name="observation" placeholder="Obervaciones o notas que deba conocer programación y/o el técnico" rows="4">{{$schedule->observation ?? ''}}</textarea>
                </div>
            </div>
            <div class="form-group row ">
                <div class="col-12">
                    <label class="col-form-label">Notas del programador</label>
                    @if($schedule->status->id == 3 || $schedule->status->id == 7)
                        <textarea class="form-control" readonly data-keyup="hiden-error" name="note_programmer" placeholder="Espacio para que el pramador registre notas especiales" rows="4">{{$schedule->note_programmer ?? ''}}</textarea>
                    @else
                        <textarea class="form-control"  data-keyup="hiden-error" name="note_programmer" placeholder="Espacio para que el pramador registre notas especiales" rows="4">{{$schedule->note_programmer ?? ''}}</textarea>
                    @endif
                </div>
            </div>
            </section>
        </div>

    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round"
        data-click="saveControlForm">Guardar</button>
</div>

<style>
    .select2-selection__choice {
        width: auto!important
    }
</style>