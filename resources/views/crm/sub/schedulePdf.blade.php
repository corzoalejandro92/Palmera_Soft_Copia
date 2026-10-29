<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\General\Contrast;
?>

<!DOCTYPE html>
<html>
    <head>  
        <title>Cronograma de actividades {{$crm->name}} {{$office->name}}</title>
        <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap.lite.css")?>">
        <style>
            *{
                font-size: 13px;
            }
            .page_break { page-break-before: always; }
            #tableContent tr td{
                background: #f6f6f6;
                z-index: 1;
            }
            table{font-size: 13px!important;}
            html, body{background: #fff!important;}
            th{font-size: 13px!important;}
            td, th {padding: 0.3rem 1rem!important;}
            h1, h2, h3, h4, h5, h6 {
                font-weight: bolder;
                font-family: sans-serif;
                
            }
            h2, .discount-value{font-family: sans-serif; color:#ff0808;}
            
            .avatars {
                border-radius: 50%;
                object-fit: cover;
                width: 85px;
                height: 85px;
            }
            table, th, tr, tbody, td {
                border: 0.5px solid #adadad!important;
                border-collapse: collapse!important;
                border-spacing: 0!important; 
            }

            .events-content{
                page-break-inside: avoid; 
            }

            .events-content .table th,
            .events-content .table td {
                padding: 0.2rem 0.1rem;
                font-size: 11px!important;
            }

            #footer { 
                position: fixed;
                left: 0px;
                bottom: 10px;
                right: 0px;
                height: 10px;
                text-align: right;
                color: #adadad;
                font-size: 11px;
                z-index: -1;
                }

                #footer .page:after { 
                /*content: counter(page, upper-roman); */
                }

                .text-contrast {
                    color: #fff;
                    mix-blend-mode: exclusion;
                    font-weight: 500;
                }
        </style>
    </head>

    <body>


        <div class="col-md-10 offset-md-1" id="step4table">
            <table class="table table-padded table-bordered table-xl mb-0 responsive" id="recent-project-table" style="margin-top: 3px;">
                <tbody>
                    <tr>
                        <th class="nopadding-td" style="width: 20%;  vertical-align: middle;" align="center">
                            <img src="<?=asset("template/app-assets/images/logo/logo.png")?>"
                            alt="cmpany logo" class="mb-2" width="120" >
                        </th>
    
                        <th class="nopadding-td" style="text-align: center; vertical-align: middle;">
                            <h4>CRONOGRAMA DE ACTIVIDADES</h4>                            
                        </th>
                        <th class="nopadding-td" style="width: 20%; text-align: center; vertical-align: middle;">
                            F7-PPR0101
                        </th>
                    </tr>
                </tbody>
            </table>
            @php 
                $_startDate  =  Carbon::parse($startDate); 
                $startMonthName =  $_startDate->monthName;
                $startDate = $_startDate->format('d') . ' de '. $startMonthName. ' de '. $_startDate->format('Y');

                $_endDate  =  Carbon::parse($endDate); 
                $endMonthName =  $_endDate->monthName;
                $endDate = $_endDate->format('d') . ' de '. $endMonthName. ' de '. $_endDate->format('Y');
                $count = 0;
            @endphp
            <br>
            <img src="{{asset($crm->avatar)}}" class="avatars" style="top:13px; right:-0px;"/>
            <ul class="px-0 list-unstyled" style="margin-top: -75px;margin-right: 60px;margin-left: 68px;">
                <li  style="padding:0px;padding;margin:0px;height: 18px;"><b>Empresa:</b> {{$crm->name}}</li>
                <li  style="padding:0px;padding;margin:0px;height: 18px;"><b>Dirección:</b>  {{$office->name . ' - '. $office->full_address}}</li>
                <li  style="padding:0px;padding;margin:0px;height: 18px;"> <b>Contacto:</b>  {{$office->contacts->implode('name', ', ')}}</li>
                <li  style="padding:0px;padding;margin:0px;height: 18px;"><b>Periodo a programado:</b> Programación desde {{$startDate}} hasta {{$endDate}}</li>
            </ul>
            <table class="table table-striped" id="tableContent">
                <thead style="background: #ddd">
                    <tr>
                        <th>Fecha</th>
                        <th>Control</th>
                        <th>Áreas</th>
                        <th>Producto</th>
                        <th>Dosis</th>
                        <th>Aplicación</th>
                    </tr>
                </thead>
                <tbody>
                    @php $trueData =  []; @endphp
                    @foreach ($schedules as $index => $service)
                     @php $controls  = $service->controls->sortByDesc('infestation.monthly')->all(); @endphp
                        @foreach ($controls as $control )
                            
                            @php
                                if($service->service->allow_control_group){
                                    $date  =  Carbon::parse($service->date); 
                                    $start =  $date->copy()->format('d');
                                    $control->title_date =  Str::ucfirst($date->copy()->monthName)." de ".$date->format('Y');
                                    $control->title_date=  Str::ucfirst($date->copy()->monthName)." de ".$date->format('Y') . "<b style='display:none;'>{$service->start_day}{$service->num}</b>";
                                }else{
                                    $date  =  Carbon::parse($service->date); 
                                    $start =  $date->copy()->startOfWeek()->format('d');
                                    $end   =  $date->copy()->endOfWeek()->format('d');
                                    $control->title_date=  Str::ucfirst($date->copy()->startOfWeek()->monthName). " ". $start ." <br>a " .Str::ucfirst($date->copy()->endOfWeek()->monthName). " ". $end;
                                    
                                }

                                if (!isset($trueData[$control->title_date])) {
                                    $trueData[$control->title_date] = [];
                                }
                                $trueData[$control->title_date][] = $control;
                            @endphp


                    
                        @endforeach
                    @endforeach

                    @php $trueData = collect($trueData); @endphp
                    @foreach ($trueData as $title => $data)
                         @php $count = 0; @endphp
                         @if($visual_type)
                            <tr>
                            <td rowspan="{{count($data)}}">{!! $title !!}</td>
                        @endif
                            @foreach (collect($data)->sortByDesc('infestation.monthly')->all() as $index => $control)
                               
                            
                                @if(!$visual_type)
                                    <tr>
                                    <td rowspan="{{count($data)}}" style=" {{$count > 0 ? 'display:none;':''}}">{!! $title !!}</td>
                                @endif
                                <td style="border: {{$control->infestation->monthly ? '2px solid #029944!important;height:auto; border-left:1px solid #029944!important;': 'initial;display: table-cell;'}} border-collapse: collapse!important;">{{$control->infestation->name}}</td>
                                <td>{{$control->area->name}}</td>
                                <td style="{{(isset($control->product) && isset($control->product)  ? 'background-color:'.$control->product->color.';': '')}}"><span style="color:{{ Contrast::generate((isset($control->product) && isset($control->product) ? $control->product->color : '#fff'))}}">{{$control->product->name ?? ''}}</span></td>
                                <td>{{isset($control->dose) ? $control->dose->text   : 'NO' }}</td>
                                <td>{{isset($control->application) ? $control->application->name : 'NO' }}</td>
                            </tr>
                            @php $count ++; @endphp
                            @endforeach
                           
                    @endforeach


                    
                </tbody>
            </table>
            <hr>
            <p>NOTA: ESTE CRONOGRAMA ESTA SUJETO A CAMBIOS DE ULTIMO MOMENTO</p>
            @if($userFirm != null)
                <p>Atentamente</p>
            @endif
            @php 
                //obtener el usuario que generó el ultimo evento de la lista de schedules
                $user = $userFirm;

            @endphp
            @if($userFirm != null)
            <img src="{{$user->signature}}" style="width:200px;" />
            <p>{{$user->name}}<br>{{$user->position}}</p>
            @endif
            

        </div>
        <div id="footer">
      
            <p class="page">Palmera Junior S.A.S. &nbsp;&nbsp;&nbsp;|
                &nbsp;&nbsp;&nbsp; Generado <?= date('Y-m-d') ?> &nbsp;&nbsp;&nbsp; |
                &nbsp;&nbsp;&nbsp; Por {{Auth::User()->name}} &nbsp;&nbsp;&nbsp; |
          </div>
        
    </body>

</html>

<script src="<?=url("template/app-assets/vendors/js/vendors.min.js")?>"> type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {	
               
                window.print();
            });

        </script>
