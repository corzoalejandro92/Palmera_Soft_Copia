<input type="hidden" value="{{ $schedule->id ?? '' }}" name="id" />
        <div class="m-2">
            <section  style="    margin-bottom: -7px;
            padding: 24px 70px;
            border: 7px dotted #e6e6e6;" id="section{{$schedule->id}}{{$schedule->service->id}}">
                <div class="row">
                    <div class="col-12" >
                        {{-- <h4 class="opaque"><img class="avatar xxs" src=" {{url($control->infestation->avatar ?? '')}}" onerror="this.src='{{url("images/default/infestation.png")}}'" /> <span>&nbsp; Configuración para {{ $control->infestation->name }}</span></h4><br> --}}
                    </div>
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
                        <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="date" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($schedule->datefc) ?  $schedule->datefc->format('d F, Y') : '')}}">
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
                    @else
                        @php $key = uniqid(); $control= null; $scheduleId = $schedule->id; $serviceId = $schedule->service->id; @endphp
                        @include('crm.forms.frames.supply_edit_new_row',compact(['key','control','serviceId','scheduleId']))
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
                    <textarea class="form-control" data-keyup="hiden-error" name="observation" placeholder="Obervaciones o notas que deba conocer programación y/o el técnico" rows="4">{{$schedule->observation ?? ''}}</textarea>
                </div>
            </div>
            </section>
        </div>