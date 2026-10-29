<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
?>

    <table class="table table-striped table-bordered zero-configuration" id="tblList" >
        <thead>
            <tr>
                <th>Fecha</th>
                <th class="text-center">Actividad</th>
                <th class="text-center">Cliente</th>
                <th class="text-center">Técnico</th>
                <th class="text-center">Hora inicio/final</th>
                <th class="text-center">Duración</th>
                <th class="text-center">Tiempo laborado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $index => $scheduling)
            
            @php 
                $scheduling_trackable = $scheduling->groupBy('trackable_id');
                $dates_number=Array();
            @endphp
                @foreach($scheduling_trackable as $i => $schedulingg)

                    @php
                        $schedule = $schedulingg->first()->trackable;
                        $sameActivity =  true;
                        $diff_minutes = 0;
                        $date_start=null;
                    
                        $date  =  Carbon::parse($schedulingg->first()->date);
                        $dates_number[str_replace("-","",Carbon::parse($date->format('Y-m-d'))->toDateString())]=1;
                        
                        
                        if(!isset($add_date)) $working_minutes =  0;//Carbon::parse($date->format('Y-m-d'));
                        $start =  $date->copy()->format('d');
                        $title_date =  Str::ucfirst($date->copy()->monthName). " ". $start ." de ".$date->format('Y') . "<br>" .$date->format('h:i A');
                    @endphp
                    <tr  class="pointer" >
                        <td class="text-center font-weight-bolder" style="width:10%" >
                            {!! $title_date !!}
                        </td>
                        <td style="width:15%" >

                            @if($schedule)
                            <div class="row">
                                <div class="col-12" style="display: flex;">
                                    <div style="display: inline-block;">
                                        <i class="icon-opacity success font-large-1 mr-1 {{ $schedule->service->avatar?? 'default'}}"></i>
                                    </div>
                                    <div style="display: inline-block; vertical-align: middle;">
                                        <span><b>{{isset($schedule->service->name) ? $schedule->service->name . ' '.$schedule->num :'Sin servicio' }}</b></span>
                                        @if($schedule->technicalReports->count() > 0)
                                            <div class="cursor-pointer" data-click="openReportsModal" data-id="{{$schedule->id}}" >
                                                <span  class="badge badge-pill badge-glow badge-info">{{$schedule->technicalReports->count()}}</span> Reporte nuevo
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @else
                                <b>{{$schedulingg->first()->name}}</b>
                            @endif


                        </td>
                        <td  style="width:20%;" >
                            @if($schedule)
                                {{$schedule->crm->name ?? ''}} ({{$schedule->office->name ?? ''}})<br>
                            @else
                                <i>Sin Cliente</i>
                            @endif
                        </td>
                        <td  style="width:10%;" >
                            @if($schedulingg->first()->user)
                                {{$schedulingg->first()->user->name ?? ''}}<br>
                            @else
                                <i>Cualquier técnico</i>
                            @endif
                        </td>
                        <td  style="width:15%;" >
                            @forelse ($schedulingg->where('user_tracking_type', 3) as $i => $scheduleHours3 )
                                    @php
                                        $date_start  =  Carbon::parse($scheduleHours3->date);
                                        $start_date_start =  $date_start->copy()->format('d');
                                        $title_date_date_start =  Str::ucfirst($date_start->copy()->monthName). " ". $start_date_start ." de ".$date_start->format('Y') ." a ". $date_start->format('h:i A');
                                    @endphp
                                    <strong>Hora Inicio: {{$title_date_date_start}}</strong>
                            @empty
                                @php
                                    $diff_minutes=$schedulingg->first()->trackable->duration/60 ?? 0;
                                @endphp
                                <strong class="text-danger">Tiempo estimado por cronograma - Técnico NO inicio el servicio</strong>
                            @endforelse
                            @forelse ($schedulingg->where('user_tracking_type', 4) as $i => $scheduleHours4 )
                                    @php
                                        $date_end  =  Carbon::parse($scheduleHours4->date);
                                        $start_date_end =  $date_end->copy()->format('d');
                                        $title_date_date_end =  Str::ucfirst($date_end->copy()->monthName). " ". $start_date_end ." de ".$date_end->format('Y') ." a ". $date_end->format('h:i A');
                                        if(isset($date_start)) $diff_minutes=$date_end->diffInMinutes($date_start);

                                    @endphp
                                    <hr><strong>Hora final: {{$title_date_date_end}}</strong>
                            @empty
                                    @php
                                        $diff_minutes=$schedulingg->first()->trackable->duration/60 ?? 0;
                                    @endphp
                                    <hr><strong class="text-danger">Tiempo estimado por cronograma - Técnico NO termino el servicio</strong>
                            @endforelse
                        </td>
                        @php
                            $add_date=1;
                            $working_minutes += $diff_minutes;
                            $working_days = count($dates_number);
                            $working_minutes_text = $schedulingg->first()->timeText($working_minutes);

                            $ordinary_minutes=$working_days*8*60;
                            $extra_minutes=$working_minutes-$ordinary_minutes;
                            $extra_time = ($extra_minutes > 0) ? '<strong class="font-weight-bold">'.$schedulingg->first()->timeText($extra_minutes).'</strong>' : '<span class="text-muted">* No Registra</span>';
                        @endphp
                        <td style="width:15%;" class="text-center">
                            <strong>{{$diff_minutes}} minutos</strong>
                        </td>
                        <td style="width:15%;" class="text-center">
                            <strong>{{$working_minutes_text}}</strong>
                        </td>
                    </tr>
                @endforeach
            
            <tr class="text-white " style="background-color: #35977a">
                <th >
                    <strong>
                        <br>
                        Resultados
                        @if($schedulingg->first()->user)
                            <hr>{{$schedulingg->first()->user->name ?? ''}}<br>
                        @endif
                    </strong>
                </th>
                <th ><br><br>Dias laborados => {{$working_days}}</th>
                <th >Tiempo ordinario <br> {{$schedulingg->first()->timeText($ordinary_minutes)}}  <hr> Tiempo laborado <br> {{$schedulingg->first()->timeText($working_minutes)}}</th>
                <th class="text-center">
                    @if($schedulingg->first()->user)
                        <br><br>
                        {{$schedulingg->first()->user->name ?? ''}}<br>
                    @endif
                </th>
                <th><br>Tiempo de diferencia <hr>{{$schedulingg->first()->timeText($extra_minutes)}}</th>
                <th class="text-center">
                    @if($schedulingg->first()->user)
                        <br><br>{{$schedulingg->first()->user->name ?? ''}}
                    @endif
                </th>
                <th class="text-center"><br>Tiempo extra <hr> {!!$extra_time!!}</th>
            </tr>
            @php unset($add_date); @endphp
            @endforeach
            
        </tbody>
    </table>
