<?php
use Jenssegers\Date\Date;
use Illuminate\Support\Str;
?>

@php
$background = public_path("/images/default/report_service_background.png");
@endphp



<html>
    <head>  
        <title>{{$subject}}</title>
        <link rel="stylesheet" type="text/css" href="<?=public_path("/template/app-assets/css/bootstrap.lite.css")?>">
        <style>
            *{
                font-size: 13px;
                color: #000000;
            }
            .page_break { page-break-before: always; }
            table{font-size: 12px!important;}
            html, body{background: #fff!important;}
            th{font-size: 12px!important;}
            td, th {padding: 1px 2px!important;}
            h1, h2, h3, h4, h5, h6 {
                font-weight: bolder;
                font-family: sans-serif;
                
            }
            h2, .discount-value{font-family: sans-serif; color:#ff0808;}
            
            .avatars {border-radius: 50%;object-fit: cover;width: 40px;height: 40px;}
            table, th, tr, tbody, td {
                border: 0.5px solid #d1d1d1!important;
                border-collapse: collapse!important;
                border-spacing: 0!important; 
            }

            .textarea-invisible {
                width: 100%;
                border: none;
                background-color: transparent;
                color: #6b6f80;
            }

            .events-content, .table-striped{
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
                bottom: -0px; 
                right: 0px; 
                height: 10px; 
                text-align: right; 
                color: #adadad;
                font-size: 11px;
                }

                #footer .page:after { 
                content: counter(page, upper-roman); 
                }

        </style>
    </head>

    <body>

  <div id="footer">
      
    <p class="page">Palmera Junior S.A.S. &nbsp;&nbsp;&nbsp;|
        &nbsp;&nbsp;&nbsp; Generado <?= date('Y-m-d') ?> &nbsp;&nbsp;&nbsp; |
        &nbsp;&nbsp;&nbsp; Por {{Auth::User() ? Auth::User()->name :''}} &nbsp;&nbsp;&nbsp; |
        &nbsp;&nbsp;&nbsp;Página &nbsp; </p>
  </div>
        <div class="col-md-10 offset-md-1" id="step4table">
            <table class="table table-padded table-bordered table-xl mb-0 responsive" id="recent-project-table" style="margin-top: 3px;">
                <tbody>
                    <tr>
                        <th class="nopadding-td" style="width: 20%;  vertical-align: middle;" align="center">
                            <img src="<?=public_path("/template/app-assets/images/logo/logo.png")?>"
                            alt="cmpany logo" class="mb-2" width="90" >
                        </th>
    
                        <th class="nopadding-td" style="text-align: center; vertical-align: middle;">
                            <h4>REPORTE DE RUTA</h4>                            
                        </th>
                        <th class="nopadding-td" style="width: 20%; text-align: center; vertical-align: middle;">
                            {{Carbon\Carbon::parse($route->date)->format('Y-m-d')}}<br>
                            {{Date::parse($route->date)->format('l')}}
                        </th>
                    </tr>
                    <tr>
                        <td class="nopadding-td" style="vertical-align: middle;" align="center" colspan="3">
                            <label><b class="text-bold-700">TECNICO:</b>  {{strtoupper($route->user->name)}}</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="nopadding-td" style="vertical-align: middle;" align="center" colspan="3">
                            <label><b class="text-bold-700">OBSERVACIONES GENERALES:</b>  <span>{{preg_replace('/\<br(\s*)?\/?\>/i', ' | ',strtoupper($route->observation ?? 'Ninguna'))}}</span></label>
                        </td>
                    </tr>
                </tbody>
            </table>
            
         

            @php $count = 0; 
  
            @endphp
            
                @foreach ($route->schedulings as $scheduling)
                
                @php 
                $countService = 0;
                    try {
                        $products_count = isset($scheduling->crmSchedule) && isset($scheduling->crmSchedule->controls) ? $scheduling->crmSchedule->controls->count(): 1;
                    } catch (\Exce  $th) {
                        $products_count = 1;
                    }
                    
                
                @endphp
                    <div class="events-content">
                        <hr style="border-color: rgb(10, 140, 10);">
                        <h5 style="display:flex;color: rgb(10, 140, 10);">
                            Hora: {{Carbon\Carbon::parse($scheduling->start)->format('h:i A')}} A {{Carbon\Carbon::parse($scheduling->end)->format('h:i A')}}
                        </h5>
                        
                            <table class="table table-padded  table-xl mb-0 responsive" id="recent-project-table" style="margin-top: 3px;">
                                <tbody>
                                    @if(!$scheduling->crmSchedule)
                                        <tr>
                                            <td class="nopadding-td" style="vertical-align: middle;" align="center" colspan="3">
                                                <label><b class="text-bold-700">ACTIVIDAD:</b>  {{strtoupper($scheduling->name ?? 'Sin especificar')}}</label><br>
                                                
                                            </td>
                                        </tr>
                                    @endif
                                    @if($scheduling->crm_id)
                                        <tr>
                                            <td class="nopadding-td" style="vertical-align: middle;" align="left" colspan="3">
                                                <label style="display:block;"><b class="text-bold-700">CLIENTE:</b>  {{strtoupper($scheduling->crm->name ?? 'Sin especificar')}} {{strtoupper($scheduling->crm->commercial_name ? ' / '. $scheduling->crm->commercial_name : '')}}</label>
                                                <label style="display:block;"><b class="text-bold-700">SUCURSAL:</b>  {{strtoupper($scheduling->office ? $scheduling->office->name: 'Sin especificar')}}</label>
                                                <label style="display:block;"><b class="text-bold-700">CONTACTO:</b>  {{strtoupper($scheduling->contact ? $scheduling->contact->name  :'Sin especificar')}}, tel/cel: {{$scheduling->contact->phone ?? 'Sin especificar'}}</label>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($scheduling->crm_address)
                                        <tr>
                                            <td class="nopadding-td" style="vertical-align: middle;" align="center" colspan="3">
                                                <label><b class="text-bold-700">DIRECCIÓN:</b>  {{strtoupper($scheduling->crm_address ?? 'Sin especificar')}}, {{strtoupper($scheduling->office->observation)}}</label>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($scheduling->duration_text)
                                        <tr>
                                            <td class="nopadding-td" style="vertical-align: middle;" align="center" colspan="3">
                                                <label><b class="text-bold-700">DURACIÓN:</b>  {{strtoupper($scheduling->duration_text ?? 'Sin especificar')}}</label>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="nopadding-td" style="vertical-align: middle;" align="center" colspan="3">
                                            <label><b class="text-bold-700">OBSERVACIONES:</b>   {!! ($scheduling->description ?? '') !!}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @if($scheduling->crmSchedule && $scheduling->crmSchedule->controls->count() > 0)    
                                <table class="table table-striped">
                                    <thead style="background: #ddd;">
                                        <tr>
                                            <th>Servicio</th>
                                            <th>Control</th>
                                            <th>Área</th>
                                            <th>Producto</th>
                                            <th>Dosis</th>
                                            <th>Aplicación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($scheduling->crmSchedule)
                                            @foreach ( $scheduling->crmSchedule->controls as $control)
                                                @php $count = 0;  @endphp
                                            
                                                    <tr>
                                                        @if($countService == 0)
                                                            <td rowspan="{{$products_count}}" class="{{$countService > 0 ? 'display-hidden' : ''}}">
                                                                {{$scheduling->crmSchedule->service->name}}
                                                            </td>
                                                        @endif
                                                        @if($count == 0)
                                                            <td>
                                                                {{$control->infestation ? $control->infestation->name : ''}}
                                                            </td>
                                                            <td>{{$control->area ? $control->area->name : ''}}</td>
                                                        @endif
                                                        <td rowspan="1">{{$control->product ? $control->product->name : ''}}</td>
                                                        <td rowspan="1">{{isset($control->dose) ? $control->dose->text  : '' }}</td>
                                                        <td rowspan="1">{{isset($control->application) ? $control->application->name : '' }}</td>

                                                    </tr>
                                                    @php $count++; $countService ++; @endphp
                                                
                                            @endforeach
                                        @endif
                                            <tr style="{{$scheduling->users->count() > 1 ? 'background: #ebeb99;' : ''}}">
                                                <td colspan="6"> <b>Técnico(s): </b> {{$scheduling->users->implode('name',', ')}}</td>
                                            </tr>

                                    </tbody>
                                </table>
                            @elseif($scheduling->crmSchedule && $scheduling->crmSchedule->controls->count() == 0)

                            <table class="table table-striped">
                                <thead style="background: #ddd;">
                                    <tr>
                                        <th>Servicio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    <tr>
                                        <td>
                                            {{$scheduling->crmSchedule->service->name}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                
                            @endif
                    </div>

                @endforeach
                    <br><br><br>
                <div align="center" margin-top="20px; page-break-inside: avoid;">  
                      <img src="{{public_path("/images/default/end_route_stamp.png")}}">
                </div>
       
        </div>
        
    </body>
</html>