@php use Carbon\Carbon; @endphp
<table class="table table-striped table-bordered zero-configuration">
    <thead>
        <tr>
          <th>#</th>
          <th>Fecha</th>
          <th>Servicio</th>
          <th>Controles</th>
          <th>Observaciones</th>
          <th>Técnico</th>
          <th>Reporte</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportServices as $reportService)
            <tr class="pointer">
              <!-- id -->
              <td class="text-center font-weight-bolder text danger"  data-id="{{$reportService->id}}">
                  {{$reportService->id}}
              </td>

              <!-- fecha -->
              <td class="text-center font-weight-bolder"  data-id="{{$reportService->id}}">
                  {{Carbon::parse($reportService->date)->format('d/m/Y')}}
              </td>

              <!-- Servicio-->
              <td class="text-center"  data-id="{{$reportService->id}}">
                <a href="{{route('crm.gotoSchedule',[$reportService->crm_id, $reportService->office_id])}}?date={{$reportService->date}}#tabSchedule" target="_blank">
                {{$reportService->service->name}}
                </a>
              </td>

               <!-- Controles-->
               <td class="text-center"  data-id="{{$reportService->id}}">
                    @foreach ($reportService->controls as  $control)
                    - {{$control->infestation ? $control->infestation->name: ''}} <br>                    
                    @endforeach
               </td>

               @php 
               $endReportType = $reportService->userTrackings
                                ->where('user_tracking_type',4)
                                ->where('date', '>=', Carbon::parse($reportService->date)->startOfDay()->format('Y-m-d H:i:s'))
                                ->where('date', '<=', Carbon::parse($reportService->date)->endOfDay()->format('Y-m-d H:i:s'))
                                ->first();
               $userReport = $endReportType ? $endReportType->user: ($reportService->technicals ? $reportService->technicals->first() : null);
              @endphp
               
               <!-- Observacione técnico -->
               <td class="text-center" style="width:300px;"  data-id="{{$reportService->id}}">
                 {{$endReportType ? $endReportType->observation: ''}}
                </td>

               <!-- Técnico-->
               <td style="width:250px;"  data-id="{{$reportService->id}}">
                    <div class="col-12" style="display: flex;">
                        <div style="display: inline-block;">
                            <img class="avatar xxs mr-1" src="{{url($userReport->avatar ?? '')}}" onerror="this.src='{{asset('images/default/user.jpg')}}'" />
                        </div>
                        <div style="display: inline-flex; vertical-align: middle;">
                            <span style="position: relative;">{{mb_strtoupper($userReport ? $userReport->name:  '')  }}</span>
                        </div>
                    </div>
                </td>

                <!-- botón de descargar -->
                <td style="width:200px;">
                    @if(Auth::user()->roles->pluck('slug')->contains('root'))
                        <center>
                            <a class="btn btn-info btn-min-width mr-1 mb-1" href="{{route('crmschedule.report',[$reportService->id,"force"=>1])}}" target="_blank"><i class="las la-key"></i> Corregir</a>
                        </center>
                        @endif
                        <center>
                            <a class="btn btn-success" href="{{route('crmschedule.report',[$reportService->id])}}" target="_blank"><i class="la la-download"></i> Abrir</a>
                        </center>
                    
                </td>
            </tr>
        @endforeach
        
    </tbody>
</table>
<span align="left" style="margin-top: -58px;">Mostrando del {{$reportServices->firstItem()}}  al {{$reportServices->lastItem()}} de {{$reportServices->total()}} resultados</span>
<div align="right" style="margin-top: -40px;">
    {!!$reportServices->render()!!}
</div>