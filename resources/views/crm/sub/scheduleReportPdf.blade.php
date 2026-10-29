<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\General\Contrast;
use App\TechnicalReportType;
use Illuminate\Database\Eloquent\Relations\MorphTo;
setlocale(LC_ALL, 'es_ES'); 
$background = public_path("/images/default/report_service_background.png");
$log = public_path("/images/default/new_log_palmera_junior.png");
$slogn = public_path("/images/default/slogn.png");

?>

<?php
    set_time_limit(20);
    //$background = "/images/default/report_service_background.png";
?>

<!DOCTYPE html>
<html>
    <head>  
        <title>Reporte de servicio {{$schedule->crm->name}} {{$schedule->office->name}} - {{Carbon::now()->format('d-m-Y H:i')}}</title>
        <link rel="stylesheet" type="text/css" href="<?=public_path("/template/app-assets/css/bootstrap.lite.css")?>">
        <style>
            *{
                font-size: 13px;
            }

            html, body{background: #fff!important; font-family: sans-serif!important}
            .page_break { page-break-before: always; }
            .page_break_inside { page-break-after: auto; }
            #tableContent tr td{
                background: #f6f6f6;
                z-index: 1;
            }
            table{font-size: 13px!important;}
            /* body{
                margin-top: 140px;
            } */

            html{
                background-image: url({{$background}})!important;
                background-repeat: no-repeat!important;
                background-size: cover!important;
            }
            
            th{font-size: 13px!important;}
            td, th {padding: 0.1rem 0.6rem!important;}
            h1, h2, h6, h4, h6, h6 {
                font-weight: bolder;
                font-family: sans-serif;
                
            }
            h2, .discount-value{font-family: sans-serif; color:#ff0808;}
            
            .avatars {
                border-radius: 50%;
                object-fit: cover;
                width: 50px;
                height: 50px;
            }
            #header {  
                background-image: url({{$background}})!important;
                background-repeat: no-repeat;
                position: absolute;
                top: -30px;
                left: -50px;
                width: 100%;
                height: 200%;
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
                padding: 2px 2px !important;
                font-size: 11px!important;
            }
            #content{
               z-index: 1000000000;
            }
            #footer { 
                position: fixed;
                left: 0px;
                bottom: 35px;
                right: 0px;
                height: 10px;
                text-align: center;
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
                .avatar {
                    border-radius: 50%;
                    object-fit: cover;
                    width: 30px;
                    height: 30px;
                    cursor: pointer !important;
                }
                .avatar-infestation{
                    height: 15px;
                    width: 15px;
                }
                .badge-dark{
                    padding:20px;
                    margin:auto;
                    background-color:#464855;
                    color:#fff;
                    align-items: center;
                    me
                }
        </style>
    </head>

    <body>
        <div id="header">
                <div style="background-color: #fff; width:200px;height:140px;position: absolute;left:48px;top:8px"></div>
                <img style="width:150px;position: absolute;left:80px;top:30px" src="{{$log}}" alt="logo">
                <img style="width:200px;position: absolute;left:280px;top:50px" src="{{$slogn}}" alt="slogan">
        </div>

        <div id="content" style="margin-top: 120px;">
            <div class="col-md-10 offset-md-1" id="step4table">
                <table class="table table-padded table-bordered table-xl mb-0 responsive" id="recent-project-table" style="margin-top: 3px;">
                    <tbody>
                        <tr>
                            <th class="nopadding-td" style="width: 30%;  vertical-align: middle; text-align: center;" align="center">
                                <span>{{$schedule->date}}</span>
                            </th>
        
                            <th class="nopadding-td" style="text-align: center; vertical-align: middle;">
                                <b>Reporte de servicio</b>                            
                            </th>
                            <th class="nopadding-td" style="width: 30%; text-align: center; vertical-align: middle;">
                                <span style="color:red; font-size: 14px!important; font-weight: bold">{{$schedule->id}}</span>
                            </th>
                        </tr>
                        
                    </tbody>
                </table> 

                <table width="100%"> 
                    <tbody>
                        <tr >
                           
                            {{-- <td class="nopadding-td" style="width: 15%;  vertical-align: middle; text-align: center;" align="center">
                                <span style="background-color: yellow">HH:MM</span>
                            </td> --}}
                            <td class="nopadding-td" style="width: 35%;  vertical-align: middle; text-align: center;" align="center">
                                <span>Fecha inicio: {{$schedule->scheduling && $schedule->scheduling->start_management ? Carbon::parse($schedule->scheduling->start_management)->translatedformat('F d \d\e Y \a \l\a\s h:i a') : 'Sin reporte'}}</span>
                            </td>
                            <td class="nopadding-td" style="width: 35%;  vertical-align: middle; text-align: center;" align="center">
                                <span>Fecha fin: {{$schedule->scheduling && $schedule->scheduling->end_management ? Carbon::parse($schedule->scheduling->end_management)->translatedformat('F d \d\e Y \a \l\a\s h:i a'): 'Sin reporte'}}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br>
                {{-- <img src="{{public_path('/public'.$schedule->crm->avatar)}}" class="avatars" style="top:13px; right:-0px;"/> --}}
                <ul class="px-0 list-unstyled" style="margin-right: 10px;margin-left: 10px;">
                    <li  style="padding:0px;padding;margin:0px;height: 18px;"><b>Empresa:</b> {{$schedule->crm->name}}</li>
                    <li  style="padding:0px;padding;margin:0px;height: 18px;"><b>Dirección:</b>  {{$schedule->office->name . ' - '. $schedule->office->full_address}}</li>
                </ul>

                <br>
                <div><b style="color:#222">ACTIVIDAD REALIZADA: </b><span>{{$schedule->service->name}}</span></div>
                <hr>
               
                <br>

                @php 
                $controls  = $schedule->controls; 
                $count = 0;
            @endphp
            <div><h6>CONTROLES Y PRODUCTOS APLICADOS</h6></div>
            @if(count($controls)>0)

            <table class="table table-striped" id="tableContent">
                <thead style="background: #ddd">
                    <tr>
                        <th>Control</th>
                        <th>Área</th>
                        <th>Producto</th>
                        <th>Método</th>
                        <th>Dosis</th>
                        <th>Cantidad</th>
                        <th>Lote</th>
                        <th>RSA</th>
                        <th>F. Vto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedule->controls as $control)

                    @php 
                        $reportedProducts= $control->reportedProducts;
           
                    @endphp

                        @if(count($reportedProducts)>0)
                            @foreach ($reportedProducts as $reportedProduct)
                            @php
                                $date_expiration  =  isset($reportedProduct->expiration_date) ? Carbon::parse($reportedProduct->expiration_date)->format('d-m-Y') : null; 
                                $doseUnit = $control->dose->text;
                                $pattern = '/(\d|\w)\/(\d|\w)/';


                                if (preg_match($pattern, $doseUnit)) {
                                    $firstPart = explode(' ', $doseUnit)[1];
                                    $quantityUnit = explode('/', $firstPart)[1];
                                } else {
                                    $quantityUnit = $control->product->unit->name;
                                }
     
                            @endphp
                            <tr>
                                <td>{{$control->infestation->name ?? ''}}</td>
                                <td>{{$control->area->name ?? ''}}</td>
                                <td>{{$control->product->name ?? ''}} {{$reportedProduct  && $reportedProduct->product ? ' - '. $reportedProduct->product->name : ''}}</td>
                                <td>{{$control->application->name ?? ''}}</td>
                                @if(preg_match($pattern, $doseUnit))<td>{{$reportedProduct->dose  ?? '' }} {{$firstPart}}</td>@endif
                                @if(!preg_match($pattern, $doseUnit))<td style="color:#ccc;">{{'N/A'}}</td>@endif
                                @if(preg_match($pattern, $doseUnit))<td>{{$reportedProduct->quantity  ?? '' }}{{$quantityUnit}}</td>@endif
                                @if(!preg_match($pattern, $doseUnit))<td>{{$reportedProduct->consumption  ?? '' }}{{$quantityUnit}}</td>@endif
                                <td>{{$reportedProduct->plot  ?? '' }}</td>
                                <td>{{$reportedProduct->rsa  ?? '' }}</td>
                                <td>{{$date_expiration ?? ''}}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>{{$control->infestation->name ?? ''}}</td>
                                <td>{{$control->area->name ?? ''}}</td>
                                <td>{{$control->product->name ?? ''}}</td>
                                <td>{{$control->application->name ?? ''}}</td>
                                
                                <td style="color:#ccc;">{{'N/A'}}</td>
                                <td style="color:#ccc;">{{'N/A'}}</td>
                                <td style="color:#ccc;">{{'N/A'}}</td>
                                <td style="color:#ccc;">{{'N/A'}}</td>
                                <td style="color:#ccc;">{{'N/A'}}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @else
                 <table style="width: 100%;border:0px !important">
                    <th style="border:0px !important" class="badge-dark">Sin reporte</th>
                </table>
            @endif
            <br>

            @php 
                $ig  = $schedule->infestationGrades; 
                $count = 0;
            @endphp
            <div><h6>GRADO DE INFESTACIÓN</h6></div>
            @if($schedule->infestationGrades->count() >0)

                <table class="table table-striped" id="tableContent">
                    <thead style="background: #ddd">
                        <tr>
                            <th>Plaga</th>
                            <th>Nivel</th>
                        </tr>
                    </thead>
                    <tbody>
                        

                        @foreach ( $schedule->infestationGrades as $infestationGrade)
        
                        
                             
                                    <tr>
                    
                                        <td>{{$infestationGrade->pest->name}}</td>
                                        <td>{{$infestationGrade->grade}}</td>
                                    </tr>
                                @endforeach
                    </tbody>
                </table>
            @else
                <table style="width: 100%;border:0px !important">
                    <th style="border:0px !important" class="badge-dark">Sin reporte</th>
                </table>
            @endif
            <br>

            @php 
                $technicalReportsData =   \App\MechanismReport::with(['extend.extendible' => function (MorphTo $morphTo) {
                        $morphTo->morphWith([Convection::class,])->with('category');
                    },'type'])->where('scheduling_id', ($schedule->scheduling && $schedule->scheduling->id ? $schedule->scheduling->id : 0))->whereHas('extend', function ($ext) {
                        $ext->where('extendible_type', 'App\Convection');
                    })
                ->get()
                ->groupBy('type.name');

                $technicalReportsData = $technicalReportsData;
                //->where('datedate',$schedule->date);
            @endphp
            @if(!is_null($technicalReportsData) && $technicalReportsData->count() > 0)
            <div><h6>REPORTE DE MECANISMOS</h6></div>
    
                @php
                    $contt=1;
                @endphp
                <div>
                    
                    <ol>
                    @foreach ($technicalReportsData as $category => $technicalReportGroup)
                        {{-- @if($report->id != 11 || ($technicalReport->observation != null && $report->id === 11)) --}}
                            {{-- @if($contt === 1)
                                
                                <br>
                                @php $contt=0; @endphp
                            @endif  --}}
                            
                                <li>
                                <hr>
                                <p style="margin-left: 20px;margin-bottom: -20px !important;" class="page_break_inside">
                                    <h5><b>{{mb_strtoupper($category)}}</b></h5>
                                    <h5>Estaciones:  
                                        @foreach ($technicalReportGroup as $technicalReport)

                                            @if($technicalReport->type->id != 11 || ($technicalReport->observation != null && $technicalReport->type->id === 11))
                                                #{{$technicalReport->extend->num}}({{$technicalReport->extend->extendible->slug}}),&nbsp;

                                                {{-- <p style="margin-left: 15px;">
                                                    <span style="color:#222"> - {{Str::upper($technicalReport->type->name)}}</span>

                                                        @if($technicalReport->type->id === 9)
                                                        <span>, plaga: {{$technicalReport->pest->name ?? ''}}</span>
                                                    @endif

                                                    @if($technicalReport->type->pest_required === 1)
                                                        <span>, cantidad: {{$technicalReport->quantity}}</span>
                                                    @endif
                                                    @if($technicalReport->observation)
                                                    <br>
                                                    <span style="color:#222">Observacion: </span><span>{{$technicalReport->observation}}</span></Span><br>
                                                    @else
                                                    <br>
                                                    @endif
                                                </p> --}}
                                            @endif
                                        @endforeach
                                    </h5>
                                    
                                </p>
                                <hr>
                            </li>
                            
                            
                        {{-- @endif --}}
                        
                    @endforeach
                    <ol>
                </div>
            @endif


            @if($schedule->scheduling && $schedule->scheduling->observations->count() > 0)
                <div><h6>OBSERVACIONES/HALLAZGOS</h6></div>
                <table class="table table-striped" id="tableContent">
                    <thead style="background: #ddd">
                        <tr>
                            <th>Nota</th>
                            <th>Evidencia Fotográfica</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedule->scheduling->observations as $observation)

                    

                        
                                <tr>
                                    <td>{{$observation->note}}</td>
                                    <td>
                                        <div>
                                           @if ($observation->file_path != null  && $observation->file_path != '')
                                            <br>
                                            <img src="{{storage_path('app/public/'.$observation->file_path)}}" class="avatars"/>
                                            <br>
                                           @endif
                                        </div>
                                </td>                              
                                </tr>
                        
                    
                        @endforeach
                    </tbody>
                </table>
    
            @endif

            <hr>
                    <p><b>Nota:</b> En caso de desinsectación se puede volver a ingresar al área después de <b>120</b> minutos</p><br>
                    @php
                        $customer_signature= $schedule->scheduling && $schedule->scheduling->signature != null ? $schedule->scheduling->signature->file_path : null;
                        $customer_name= $schedule->scheduling && $schedule->scheduling->signature != null ? $schedule->scheduling->signature->name : null;
                        $customer_cc=   $schedule->scheduling && $schedule->scheduling->signature != null ? $schedule->scheduling->signature->document : null;
                        $user_name= $schedule->scheduling ? $schedule->scheduling->users->first()->name : '';
                        $user_signature= $schedule->scheduling  ? $schedule->scheduling->users->first()->signature : null  ;
                    @endphp
                    <table style="width: 100%;border:0px!important">
                        <tbody style="border:0px !important">
                        <tr style="border:0px !important">
                            <td style="width: 50%;border:0px !important">
                                @if(isset($user_signature ))
                                <center>
                                <img style="height: 100px;
                                position: relative;
                                bottom: 10px;" src="{{public_path('storage/'.$user_signature)}}">
                               
                                </center> @endif 
                            </td>
                            <td style="width: 50%;border:0px !important">
                                @if(isset($customer_signature))
                                <center><img style="height: 100px;position: relative;" src="{{public_path('storage/'.$customer_signature)}}">.</center>
                                @endif
                                
                            </span>
                            </td>
                        </tr>
                        <tr style="border:0px !important">
                            <td style="width: 50%;border:0px !important;">
                                <center>
                                    <span>
                                        REALIZÓ:
                                        @if(isset($user_name))
                                            <strong style="margin-left:100px">{{$user_name}}</strong>
                                        @endif
                                    </span>
                                </center>
                            </td>
                            
                   
                            <td style="width: 50%;border:0px !important;">
                                <center>
                                    <span> RECIBIO Y C.C 
                                   
                                        @if(isset($customer_name))
                                            <strong style="margin-left:100px">
                                                {{$customer_name ?? ''}} {{$customer_cc > 0 ? 'C.C.  '.number_format($customer_cc) : ''}}
                                            </strong>
                                        @endif
                                    </span>
                                </center>

                            </td>
                           
                            
                        </tr>
                        </tbody>
                    </table>
            </div>
        </div>
            
           

           
        <div id="footer">
            @php 
                $headquarter=$schedule->scheduling ? $schedule->scheduling->headquarter: null;
            @endphp
            <span class="page">Palmera Junior S.A.S. |
                &nbsp;&nbsp;&nbsp; Generado <?= $schedule->date ?>
                <br>
                @if(!is_null($headquarter))
                <strong>Sede:</strong>{{$headquarter->name}} <strong>Dirección:</strong>{{$headquarter->address}}-{{$headquarter->cities->name}} / <strong>PBX:</strong> (7) {{$headquarter->phone}} - <strong>Cel.</strong> {{$headquarter->cell_phone}}
                <br>
                <strong>E-mail: </strong>{{$headquarter->email}} @endif / www.palmerajunior.com
            </span>
        
        </div>
        </body>

</html>

<script src="<?="/template/app-assets/vendors/js/vendors.min.js"?>"> type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {	
               
                //window.print();
            });

        </script>
