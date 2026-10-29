<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\General\Contrast;
use App\TechnicalReport;
use App\CrmScheduleControl;
use App\CrmArea;
use App\General\Menu;
setlocale(LC_ALL, 'es_ES'); 
$log = public_path("/images/default/new_log_palmera_junior.png");
$slogn = public_path("/images/default/slogn.png");
set_time_limit(40000000);
$background = url("/images/default/image_back8.png");
$background_footer = url("/images/default/footer_image_back8.png");
$background_head = url("/images/default/head_image_back8.png");
$background_new_log = url("/images/default/new_log_palmera_junior.png");
?>

<!DOCTYPE html>
<html>
    <head> 
        <meta charset="UTF-8">
        <title>Reporte consolidado</title>{{-- {{$schedule->crm->name}} {{$schedule->office->name}} - {{Carbon::now()->format('d-m-Y H:i')}}</title> --}}
        <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap.lite.css")?>">
        <link rel="stylesheet" type="text/css" href="{{url("assets/charts4php/lib/js/chartphp.css")}}">
        <style>
           
            main {
                /* margin-top:    3cm;
                margin-bottom: 2cm; */
                margin-left:   2.5cm;
                margin-right:  2.5cm;
                font-size: 22px;
                font-family: Arial;
                background-color: #fff!important;
            }
            body {
                width:100%;
                margin-left:   0cm;
                margin-right:  0cm;
                background-color: #fff;
            }
            .justify{
                text-align: justify;
                /* font-size: 18px!important; */
            }
            
            div.header > #image { 
                width:28cm;
            }
            div.footer > #image_footer { 
                width:100%;
            }
            div.footer > .page{
                position: relative;
                top:-3cm;
                left:2cm;
            }
            div.header > #new_log { 
                width:300px;
                height:200px;
                position:absolute;
                top:-0.3cm;
                left:0.7cm;
                background-color:#fff ;
                padding:0.5cm;

            }
            div.header { 
                display: block; text-align: center; position: top; width: 100% }
            div.footer {
                 text-align: left; position: relative; width: 100%;
                bottom: -90px; 
                left:0;
                
                } 
            @media print {
                body {
                    padding-bottom: 1cm;
                    color: #000;
                    background-color: rgb(255, 255, 255);
                }
                page-break-after:always;
                @page {
                    size: auto;/* es el valor por defecto */
                    margin: 20%;
                    padding-bottom: 5cm;
                    }

            }
                        body > * {
                color:#000;
            }
            

            /* html, body{background: #fff!important; font-family: sans-serif!important} */
            .page_break { page-break-before: always; }
            #tableContent tr td{
                background: #f6f6f6;
                z-index: 1;
            }
            
            th{/* font-size: 13px!important; */
                text-align: center;
            }
            td, th {padding: 0.3rem 1rem!important;text-align: center
            }
            h1, h2, h4, h4,h5, h4, h4 {
                font-weight: bolder;
                font-family: Arial, Helvetica, sans-serif;
                color:#000;
            }
            h2, .discount-value{font-family: sans-serif; color:#ff0808;}
            
            table, th, tr, tbody, td {
                border: 0.5px solid #000!important;
                border-collapse: collapse!important;
                border-spacing: 0!important; 
            }

            .events-content{
                page-break-inside: avoid; 
            }

            .events-content .table th,
            .events-content .table td {
                padding: 2px 2px !important;
                /* font-size: 11px!important; */
            }
            #content{
               z-index: 1000000000;
            }
                #footer .page:after { 
                content: counter(page, upper-roman);
                }

                .text-contrast {
                    color: #fff;
                    mix-blend-mode: exclusion;
                    font-weight: 500;
                }

                .badge-dark{
                    padding:20px;
                    margin:auto;
                    background-color:#464855;
                    color:#fff;
                    align-items: center;
                }
                
        </style>
           
           <script src="{{url("assets/charts4php/lib/js/jquery.min.js")}}"> </script>
           <script src="{{url("assets/charts4php/lib/js/plotly-latest.min.js")}}"> </script>
           
</head>
<?php
include_once("assets/charts4php/lib/inc/chartphp_dist.php");

?>
    <body>
       
        <div class="header">
           <img id="image" src="{{$background_head}}" />
           <img id="new_log" src="{{$background_new_log}}" />
       </div>
        <main> 
             <!-- Custom HTML header -->
            <!-- El contenido de tu PDF aquí -->
            <center>
            <h4 style="font-style: bold">INFORME MANEJO INTEGRADO DE PLAGAS<br>
                {{Str::upper(optional($schedules->first())->crm->name ?? '')}}<br>
                {{Str::upper(optional($schedules->first())->office->name ?? '')}}<br>
                {{Str::upper(\Jenssegers\Date\Date::parse($start_date)->format('F'))}}  {{Carbon::parse($start_date)->format('Y')}}</h4>
                <br><br>
            
            </center>
            @if($mechanisms_non_toxic_controls->count() > 0 && $mechanisms_baits->count() == 0)
                <b>Introducción:</b>
                <br>
                <br><br>
                <p class="justify">
                A continuación, nos permitimos presentar informe de resultados y tendencias de control de plagas en las Instalaciones de <strong>{{Str::upper(optional($schedules->first())->crm->name ?? '')}}</strong><br><br>


                Para efectos de interpretación de resultados es importante tener en cuenta que los grados de infestación son tomados con base en el nivel de rastros, capturas y/o avistamientos de dicha plaga, por lo que se asume que a mayor capturas, mayor grado de infestación se obtuvo en el periodo analizado, esto debido a la imposibilidad de realizar conteos físicos de roedores en las instalaciones y a la inexistencia de métodos o técnicas precisas que permitan medir con exactitud el grado de infestación o abundancia de roedores en un área determinada.
                <br><br>

                <b>Índice de Abundancia:</b> El objetivo del muestreo de plagas es proporcionar estimaciones confiables sobre la abundancia de su población y conocer sus patrones de organización espacial, buscando un justo equilibrio entre la exactitud y el esfuerzo que se hizo para capturarlas. Esta información es de suma importancia para determinar las probables causas que generan la abundancia y está dada por índices. Un índice es una medida que se relaciona de alguna forma con la abundancia real de una población. Para este caso, la abundancia se estima mediante el método directo (éxito de capturas), el cual se basa en la ponderación del número de individuos capturados mediante la utilización de un sistema de monitores durante un tiempo controlado. 
                <br><br>

                Los índices de abundancia de roedores e insectos para <strong>{{Str::upper(optional($schedules->first())->crm->name ?? '')}}</strong> se podrán encontrar en el transcurso del presente informe.
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>

                </p>
            @endif
            @if(($mechanisms_non_toxic_controls->count() > 0 || $mechanisms_non_toxic_controls->count() == 0 ) && $mechanisms_baits->count() > 0)
                <b>Introducción:</b>
                <br><br>
                <p class="justify">
                A continuación, nos permitimos presentar informe de resultados y tendencias de control de plagas en las Instalaciones de <strong>{{Str::upper(optional($schedules->first())->crm->name ?? '')}}</strong><br><br>


                Para efectos de interpretación de resultados es importante tener en cuenta que los grados de infestación son tomados con base en el nivel de consumos, rastros, capturas y/o avistamientos de dicha plaga, por lo que se asume que a mayor consumo y/o capturas, mayor grado de infestación se obtuvo en el periodo analizado, esto debido a la imposibilidad de realizar conteos físicos de roedores en las instalaciones y a la inexistencia de métodos o técnicas precisas que permitan medir con exactitud el grado de infestación o abundancia de roedores en un área determinada.
                <br><br>
                Para el caso de los cebos desaparecidos es importante reconocer que aquel que ha sido reportado de esta forma, ha sido un cebo que no se ha encontrado en la estación de cebado y del cual no existen evidencias claras de los factores que pudieron ocasionar dicha perdida. Algunas de las causas más comunes para la pérdida de cebos son: arrastre por aguas de escorrentía, consumo total por parte de animales silvestres no plaga, retiros por parte del personal, entre otros.
                <br><br>

                <b>Índice de Abundancia:</b> El objetivo del muestreo de plagas es proporcionar estimaciones confiables sobre la abundancia de su población y conocer sus patrones de organización espacial, buscando un justo equilibrio entre la exactitud y el esfuerzo que se hizo para capturarlas. Esta información es de suma importancia para determinar las probables causas que generan la abundancia y está dada por índices. Un índice es una medida que se relaciona de alguna forma con la abundancia real de una población. Para este caso, la abundancia se estima mediante el método directo (éxito de capturas), el cual se basa en la ponderación del número de individuos capturados mediante la utilización de un sistema de monitores durante un tiempo controlado. 
                <br><br>

                Los índices de abundancia de roedores e insectos para <strong>{{Str::upper(optional($schedules->first())->crm->name ?? '')}}</strong> se podrán encontrar en el transcurso del presente informe.
                <br>
                <br>

                </p>
            @endif

    
            <div class="col-md-10 offset-md-1" >
            
            @php
                $months=$schedules->groupBy('month_text');
                $baits_consume=filled($schedules->where('service_id',5)) ? $schedules->where('service_id',5)->first()->controls->first() : null;
                $bait_placed=filled($baits_consume) ? $baits_consume->product->name." ".$baits_consume->dose->start_range." ".$baits_consume->dose->unit->name : null;
                $table_counter=1;
                $baits_stations = $mechanisms_baits->count();
                $mechanisms_non_toxic_controls_count = $mechanisms_non_toxic_controls->count();
                $end_dates=$end_date;
                $year = substr($end_dates,0,4); 
                $year_month = substr($end_dates,5,2)-1; 
                $months_year=Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                $cont_tecnicals_count_six_num=0;
                $visit_color_array=Array();
                $avg_bait_consumption_array=Array();
                $name_avg_array=Array();
                $avg_bait_consumption_array_six=Array();
                $name_avg_array_six=Array();
                $visit_color_array_six=Array();
                $avgNine=null;
                $percentage=null;
                $visits=Array();
                $weeks=Array();
                $weeks_values=Array();
                $headquarter=Array();
                $avg=Array();
                $visit_avgs=Array();
                $visit_days=Array();
                $visit_color=Array();
                $data=Array();
                $ids_schedules=Array();
                $avg_C=Array();
                $visit_avgs_five=Array();
                $visit_days_five=Array();
                $visit_color_five=Array();
                $avg_bait_consumption_array_xxx=Array();
                $value=collect();
            @endphp
            {{-- <span>{{$schedules->each->technicalReports}}</span><br> --}}
            {{-- <span>{{$schedules->groupBy('date')}}</span><br> --}}
            @foreach ($months as $month => $value)
                
            @endforeach
                @php
                    $services_count=$value->where('service_id',5)->count();
                    $tecnical_sum=0;
                    $ids_schedules=$value->where('service_id',5)->pluck('id');
                @endphp
                @if($mechanisms_baits->count() > 0 )        
                        <h4>Tabla {{$table_counter++}}. Cebos Consumidos Mensual</h4>
                        <table style="width: 100%;border:1px #000 solid !important">
                            <tbody >
                                <tr>
                                    <th rowspan="2">Mes</th>
                                    <th colspan="{{$services_count}}">Visita</th>
                                    <th rowspan="2">Promedio</th>
                                    <th rowspan="2">%</th>
                                </tr>
                                <tr>
                                    @for($cont=0;$cont<$services_count;$cont++)
                                        <th>{{$cont+1}}</th>
                                    @endfor
                                </tr>
                                <tr>
                                    
                                    <td>{{$month}}</td>
                                    @foreach($value->where('service_id',5) as $values_id)
                                        @php
                                            $tecnicals=TechnicalReport::where('technical_report_type_id',7)->where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                                            $sum_values=$tecnicals->pluck('quantity')->sum();
                                            $tecnical_sum+=$sum_values;
                                            $avg=$baits_stations > 0 ? round((($tecnical_sum/$cont)/$baits_stations)*100,2) : 0;
                                        @endphp
                                        <td><center>{{$sum_values}}</center></td>
                                    @endforeach
                                    <td><center>{{ round($tecnical_sum/$cont,2)}}</center></td>
                                    <td><center>{{$avg}} %</center></td>
                                </tr>
                            </tbody>
                        </table>
                    <br>
                    
                    <p>
                        <strong>NOTA:</strong> El porcentaje del mes de se basa en {{$baits_stations}} estaciones de cebado. El límite crítico y el rango de control están basados en un análisis de capacidad de carga y presión externa relacionado en el Diagnóstico Inicial.
                    </p>
                    <p>
                        <b style="color:red">-------</b> Límite crítico (30%): Limite en el cual de tomarse y documentarse una acción correctiva.<br>
                        <b style="color:orange">-------</b> Rango de Control (10% – 20%): Rango en el cual debe tomarse y documentarse una acción preventiva en el momento que la línea de tendencia suba a dicho nivel.<br><br>
                        <br>
                        
                        <strong>Formulas Tabla y Grafica</strong><br><br>
                        <center>

                            @php
                                foreach ($months_year as $key => $month){
                                    $monthsYear[]=array($month."-".$year,$key === $year_month ? $avg : 0);
                                    $monthsYear2[]=array($month."-".$year,30);
                                    $monthsYear3[]=array($month."-".$year,20);
                                    $months_value[]= $key === $year_month ? $avg : 0;

                                }
                                $drawLineOne = array(
                                    $monthsYear,
                                    $monthsYear2,
                                    $monthsYear3
                                ); 
                                $p1 = new chartphp();

                                $p1->data=$drawLineOne;
                                $p1->chart_type = "line";

                                // Common Options
                                $p1->title = "Consumo de cebos mensual";
                                $p1->xlabel = "Meses";
                                $p1->ylabel = "Porcentajes";
                                $p1->show_xticks = true;
                                $p1->show_yticks = true;
                                $p1->show_point_label = true;
                                $p1->theme = "dark";
                                //$p1->bgcolor = "#000";
                                $p1->color = "teal,red,orange";
                                $p1->series_label = array("Consumo de cebo","Límite crítico","Rango de Control");
                                $drawOne = $p1->render('c1');
                            @endphp
                            <input type="hidden" id="month_year" value="{{json_encode($monthsYear)}}">
                            <input type="hidden" id="month_value" value="{{json_encode($months_value)}}">
                            
                            <center>
                                <div>
                                    <?php echo $drawOne ?>
                                </div>
                            </center>
                            
                        </center>
                        <b>Promedio = </b>Total de cebos consumidos / Número de visitas efectuadas en el mes.<br>
                       
                        <b>% de cebos consumidos mensual =</b> (Promedio / N° de cebos totales instalados) * 100.
                    </p><br>
                    
                    <h4>Tabla {{$table_counter++}}. Consumo de cebos por visita</h4>
                        <table style="width: 100%;border:1px #000 solid !important">
                            <tbody >
                                <tr>
                                    <th >VISITAS</th>
                                    <th >No. DE CEBOS</th>
                                    <th >PORCENTAJE</th>
                                </tr>
                                    @php 
                                        $count_visit=1;
                                    @endphp
                                @foreach($value->where('service_id',5) as $values_id)
                                        @php
                                            $tecnicals=TechnicalReport::where('technical_report_type_id',7)->where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                                            $sum_values=$tecnicals->pluck('quantity')->sum();
                                            $tecnical_sum+=$sum_values;
                                            $avg_cont=$baits_stations > 0 ? round(($sum_values/$baits_stations)*100,2) : 0;
                                            $visit_day=Jenssegers\Date\Date::parse($values_id->date)->format('F d Y');
                                            $visit_avgs[]=$avg_cont;
                                            $visit_days[]=$visit_day;
                                            $visit_color[]=Menu::randomColor();
                                            $data[]=Array($visit_day,$avg_cont);
                                        @endphp
                                        <tr>
                                            
                                            <td><center>VISITA {{$count_visit++}} {{$visit_day}}</center></td>
                                            <td><center>{{$sum_values}}/{{$baits_stations}}</center></td>
                                            <td><center>{{$avg_cont}}%</center></td>

                                        </tr>
                                        
                    
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <br>
                        <input type="hidden" id="visit_avgs" value="{{json_encode($visit_avgs)}}">
                        <input type="hidden" id="visit_days" value="{{json_encode($visit_days)}}">
                        <input type="hidden" id="visit_color" value="{{json_encode($visit_color)}}">
                        @php
                            $drawTwo = array(
                                    $data
                                ); 
                                $p2 = new chartphp();

                                $p2->data=$drawTwo;
                                $p2->chart_type = "bar";
                                // Common Options
                                $p2->title = "Consumo de cebos por visita";
                                $p2->xlabel = "Visitas";
                                $p2->ylabel = "Porcentaje";
                                $p2->show_xticks = true;
                                $p2->show_yticks = true;
                                $p2->show_point_label = true;
                                $p2->theme = "dark";
                                $p2->color = "#A8383B";
                                //$p2->bgcolor = "#000";
                                $drawTwoD = $p2->render('c2');
                            @endphp
                            
                            <center>
                                <div>
                                    <?php echo $drawTwoD ?>
                                </div>
                            </center>
                            <br>
                            <br>
                        <h4>Formula Tabla y Grafica</h4>

                        <p><b>Porcentaje de Consumo por visita =</b> (N° de cebos consumidos por cada visita / Número total de estaciones instaladas) * 100</p><br>
                       
                        <h4>Tabla {{$table_counter++}}. Consumo de Cebo por mecanismo</h4>
                        <table style="width: 100%;border:1px #000 solid !important">
                            <tbody >
                                <tr>
                                    <th >VISITAS</th>
                                    @foreach($value->where('service_id',5) as $values_id)
                                        @php
                                            $tecnicals=TechnicalReport::where('technical_report_type_id',7)->where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                                            $num_day=Jenssegers\Date\Date::parse($values_id->date)->format('F d Y');
                                            $num_days_2[]=$num_day;
                                            $num_days_3[]=$values_id->date;
                                        @endphp
                                        <th >{{$num_day}}</th>
                                    @endforeach
                                    <th >PORCENTAJE</th>
                                </tr>
                                        @php 
                                            $count_C=1;
                                            $tecnicals=TechnicalReport::where('technical_report_type_id',7)->whereIn('crm_schedule_id',$ids_schedules)->orderBy('id','asc')->get();
                                            $group_fileExtends=$tecnicals->unique('file_extend_id');
                                        @endphp
                                            @foreach ($group_fileExtends as $index => $group_fileExtendd)
                                            @php
                                                $name_avg="C".$group_fileExtendd->extend->num;
                                                $name_avg_array[]=$name_avg;
                                                $cont_C=0;
                                                $visit_color_array[]=Menu::randomColor();
                                            @endphp
                                            <tr>
                                                <td><center>C{{$group_fileExtendd->extend->num}}</center></td>
                                                    
                                                @foreach ($num_days_2 as $keyy => $num_day2)
                                                        @php
                                                            $character='--';
                                                            $tecnicals_count2=TechnicalReport::where('quantity','>',0)->where('file_extend_id',$group_fileExtendd->file_extend_id)->whereDate('created_at',$num_days_3[$keyy])->count();
                                                            if($tecnicals_count2 > 0 ){
                                                                $cont_C++;
                                                                $character='X';
                                                            }
                                                            $avg_bait_consumption=round(($cont_C/count($num_days_2))*100,2);
                                                        @endphp
                                                        <td><center>{{$character}}</center></td>
                                                    @endforeach
                                                    @php
                                                        $avg_bait_consumption_array[]=$avg_bait_consumption;
                                                    @endphp
                                                    <td><center>{{$avg_bait_consumption}}%</center></td>
                                                @endforeach
                                            </tr>
                            </tbody>
                        </table>
                        <input type="hidden" id="avg_bait_consumption_array" value="{{json_encode($avg_bait_consumption_array)}}">
                        <input type="hidden" id="name_avg_array" value="{{json_encode($name_avg_array)}}">
                        <input type="hidden" id="visit_color_array" value="{{json_encode($visit_color_array)}}">
                        
                        <br>
                        <br>
                        @php
                        $dataThree=Array();
                            foreach ($name_avg_array as $key => $name){
                                $dataThree[]=Array($name,$avg_bait_consumption_array[$key]);
                            }
                            $drawThree = array(
                                    $dataThree
                                ); 
                                $p3 = new chartphp();

                                $p3->data=$drawThree;
                                $p3->chart_type = "bar";
                                // Common Options
                                $p3->title = "Consumo de cebos por mecanismo";
                                $p3->xlabel = "Mecanismo";
                                $p3->ylabel = "Porcentaje";
                                $p3->show_xticks = true;
                                $p3->show_yticks = true;
                                $p3->show_point_label = true;
                                $p3->theme = "dark";
                                //$p3->bgcolor = "#000";
                                $p3->color = "blue";
                                $drawThreeD = $p3->render('c3');
                            @endphp
                            
                            <center>
                                <div>
                                    <?php echo $drawThreeD ?>
                                </div>
                            </center>
                            <br>
                            <br>
                        <h4>Formula tabla y Grafica 3:</h4><br>

                        <p><b>Porcentaje de consumo</b>= (Número de ocasiones con evidencia de consumo / Número de visitas efectuadas de control) * 100</p>
                        @foreach ($months as $month => $value)
                        @php
                            $services_count=$value->where('service_id',5)->count();
                            $tecnical_sum=0;
                            $ids_schedules=$value->where('service_id',5)->where('status_id',3)->pluck('id');
                        @endphp
                        <h4>Tabla {{$table_counter++}}. Cebos Desaparecidos Mensual</h4>
                        <table style="width: 100%;border:1px #000 solid !important">
                            <tbody >
                                <tr>
                                    <th rowspan="2">Mes</th>
                                    <th colspan="{{$services_count}}">Visita</th>
                                    <th rowspan="2">Promedio</th>
                                    <th rowspan="2">%</th>
                                </tr>
                                <tr>
                                    @for($cont=0;$cont<$services_count;$cont++)
                                        <th>{{$cont+1}}</th>
                                    @endfor
                                </tr>
                                <tr>
                                    
                                    <td>{{$month}}</td>
                                    @foreach($value->where('service_id',5) as $values_id)
                                        @php
                                            $tecnicals=TechnicalReport::where('technical_report_type_id',8)->where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                                            $sum_values=$tecnicals->pluck('quantity')->sum();
                                            $tecnical_sum+=$sum_values;
                                            $avg_C=$baits_stations > 0 ? round((($tecnical_sum/$cont)/$baits_stations)*100,2) : 0;
                                            
                                        @endphp
                                    <td><center>{{$sum_values}}</center></td>
                                    @endforeach
                                    <td><center>{{ round($tecnical_sum/$cont,2)}}</center></td>
                                    <td><center>{{$avg_C}} %</center></td>
                                </tr>
                            </tbody>
                        </table>
                    @endforeach
                    @php
                    foreach ($months_year as $key => $month2){
                        $monthsYear_C[]=$month2."-".$year;
                        $months_value_C[]= $key === $year_month ? $avg_C : 0;
                    }
                    @endphp
                    <br>
                    <br>
                    <input type="hidden" id="month_year_C" value="{{json_encode($monthsYear_C)}}">
                    <input type="hidden" id="month_value_C" value="{{json_encode($months_value_C)}}">
                    @php
                        $dataFour=Array();
                        $dataFourOne=Array();
                            foreach ($monthsYear_C as $key => $name){
                                $dataFour[]=Array($name,$months_value_C[$key]);
                                $dataFourOne[]=Array($name,30);
                                $dataFourTwo[]=Array($name,20);
                            }
                            $drawFour = array(
                                    $dataFour,
                                    $dataFourOne,
                                    $dataFourTwo,
                                ); 
                                $p4 = new chartphp();

                                $p4->data=$drawFour;
                                $p4->chart_type = "line";
                                // Common Options
                                $p4->title = "Cebos desaparecidos por visita";
                                $p4->xlabel = "Visitas";
                                $p4->ylabel = "Porcentaje";
                                $p4->show_xticks = true;
                                $p4->show_yticks = true;
                                $p4->show_point_label = true;
                                $p4->theme = "dark";
                                //$p4->bgcolor = "#000";
                                $p4->series_label = array("Cebos desaparecidos","Límite crítico","Rango de Control");
                                $p4->color = "teal,red,orange";
                                $drawThreeD = $p4->render('c4');
                            @endphp
                            
                            <center>
                                <div>
                                    <?php echo $drawThreeD ?>
                                </div>
                            </center>
                            <br>
                            <br>
                    <br>
                    <p>
                        <strong>NOTA:</strong> El porcentaje del mes de se basa en {{$baits_stations}} estaciones de cebado. El límite crítico y el rango de control están basados en un análisis de capacidad de carga y presión externa relacionado en el Diagnóstico Inicial.
                    </p>
                    <p>
                        <b style="color:red">-------</b> Límite crítico (30%): Limite en el cual de tomarse y documentarse una acción correctiva.<br>
                        <b style="color:orange">-------</b> Rango de Control (10% – 20%): Rango en el cual debe tomarse y documentarse una acción preventiva en el momento que la línea de tendencia suba a dicho nivel.<br><br>

                        <strong>Formulas Tabla y Grafica 4:</strong><br><br>
                        <b>Promedio = </b>Total de cebos desaparecidos / Número de visitas efectuadas en el mes.<br>
                        <b>% de cebos desaparecidos mensual =</b> (Promedio / N° de cebos totales instalados) * 100
                    </p><br>
                    
                        <h4>Tabla {{$table_counter++}}. Consumo de cebos por visita</h4>
                        <table style="width: 100%;border:1px #000 solid !important">
                            <tbody >
                                <tr>
                                    <th >VISITAS</th>
                                    <th >No. DE CEBOS</th>
                                    <th >PORCENTAJE</th>
                                </tr>
                                    @php 
                                        $count_visit=1;
                                    @endphp
                                @foreach($value->where('service_id',5) as $values_id)
                                        @php
                                            $tecnicals=TechnicalReport::where('technical_report_type_id',8)->where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                                            $sum_values=$tecnicals->pluck('quantity')->sum();
                                            $tecnical_sum+=$sum_values;
                                            $avg_cont=$baits_stations > 0 ? round(($sum_values/$baits_stations)*100,2) : 0;
                                            $visit_day=Jenssegers\Date\Date::parse($values_id->date)->format('F d Y');
                                            $visit_avgs_five[]=$avg_cont;
                                            $visit_days_five[]=$visit_day;
                                            $visit_color_five[]=Menu::randomColor();
                                        @endphp
                                        <tr>
                                            
                                            <td><center>VISITA {{$count_visit++}} {{$visit_day}}</center></td>
                                            <td><center>{{$sum_values}}/{{$baits_stations}}</center></td>
                                            <td><center>{{$avg_cont}}%</center></td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <br>
                        <input type="hidden" id="visit_avgs_five" value="{{json_encode($visit_avgs_five)}}">
                        <input type="hidden" id="visit_days_five" value="{{json_encode($visit_days_five)}}">
                        <input type="hidden" id="visit_color_five" value="{{json_encode($visit_color_five)}}">
                        @php
                        $dataFive=Array();
                            foreach ($visit_days_five as $key => $name){
                                $dataFive[]=Array($name,$visit_avgs_five[$key]);
                            }
                            $drawFive = array(
                                    $dataFive,
                                ); 
                                $p5 = new chartphp();

                                $p5->data=$drawFive;
                                $p5->chart_type = "bar";
                                // Common Options
                                $p5->title = "Cebos desaparecidos";
                                $p5->xlabel = "Visitas";
                                $p5->ylabel = "Porcentaje";
                                $p5->show_xticks = true;
                                $p5->show_yticks = true;
                                $p5->show_point_label = true;
                                $p5->theme = "dark";
                                //$p5->bgcolor = "#000";
                                $p5->color = "green";
                                $drawFiveD = $p5->render('c5');
                            @endphp
                            
                            <center>
                                <div>
                                    <?php echo $drawFiveD ?>
                                </div>
                            </center>
                            <br>
                            <br>
                        
                        <h4>Formula Tabla y Grafica 5:</h4>

                        <p><b>% Desaparecidos por visita =</b> (N° de cebos desaparecidos por cada visita / Número total de estaciones instaladas) * 100</p><br>
                        <h5>PRODUCTO APLICADO</h5>

                        <p>{{$bait_placed}} de cebado en {{$baits_stations}} rataurantes</p>
                        <br>
                @endif
                @if($mechanisms_non_toxic_controls->count() > 0)
                    <center><h4>DETERMINACIÓN DEL INDICE DE ABUNDANCIA POR TRAMPEO</h4></center>
                    <h4>Tabla {{$table_counter++}}. Consumo Por Cebo Mensual</h4>
                    <table style="width: 100%;border:1px #000 solid !important">
                        <tbody >
                            <tr>
                                <th>TRAMPAS/<br>FECHAS</th>
                                @foreach($mechanisms_non_toxic_controls as $traps)
                                    <th >{{$traps->extend->num ?? ''}}</th>
                                    @php
                                        $visit_color_array_six[] = Menu::randomColor();
                                        $name_avg_array_six[$traps->extend->id]=$traps->extend->num ?? '';
                                        $avg_bait_consumption_array_six=Array();
                                    @endphp
                                @endforeach
                            </tr>
                            @foreach($value->where('service_id',5) as $values_id)
                                    @php 
                                        $tecnicals_six=TechnicalReport::where('technical_report_type_id',9)->where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                                        $num_day_six=Jenssegers\Date\Date::parse($values_id->date)->format('F d Y');
                                    @endphp
                                        <tr>
                                            <td ><center>{{$num_day_six}}</center></td>
                                            @foreach($mechanisms_non_toxic_controls as $traps)
                                                @php
                                                    $tecnicals_count_six=TechnicalReport::where('file_extend_id',$traps->extend->id)->whereDate('created_at',$values_id->date)->get();
                                                    $tecnicals_count_six_num=$tecnicals_count_six->pluck('quantity')->sum();
                                                    //if(array_key_exists($traps->extend->id, array $avg_bait_consumption_array_six)){
                                                        $avg_bait_consumption_array_six[$traps->extend->id][]=$tecnicals_count_six_num;
                                                    //}
                                                    $cont_tecnicals_count_six_num+=$tecnicals_count_six_num;
                                                    //$avg_bait_consumption_array_six[$traps->extend->id]=$tecnicals_count_six_num;
                                                @endphp
                                                <th>{{$tecnicals_count_six_num}}</th>
                                            @endforeach
                                        </tr>
                                        
                            @endforeach
                            @php
                                foreach($avg_bait_consumption_array_six as $i => $avg_bait_consumption_array_sixx){
                                        $avg_bait_consumption_array_xxx[$i]=array_sum($avg_bait_consumption_array_sixx);
                                    }
                            @endphp
                        </tbody>
                    </table>
                    <br>
                    <br>
                    @php
                    $dataSix=Array();
                    $validated=Array();
                    $name_avg_array_sixx=$name_avg_array_six;
                        if(count($avg_bait_consumption_array_xxx) > 0)    {
                            foreach ($name_avg_array_six as $key => $name){
                                $val=0;
                                foreach ($name_avg_array_sixx as $i => $vall){
                                    if($name==$vall){
                                        $val+=$avg_bait_consumption_array_xxx[$i];
                                    }
                                    
                                }
                                $dataSix[$name]=Array($name,$val);
                            }
                        }
                        $drawSix = array(
                                $dataSix
                            ); 
                            $p6 = new chartphp();

                            $p6->data=$drawSix;
                            $p6->chart_type = "bar";
                            // Common Options
                            $p6->title = "Cebos desaparecidos";
                            $p6->xlabel = "Visitas";
                            $p6->ylabel = "Porcentaje";
                            $p6->show_xticks = true;
                            $p6->show_yticks = true;
                            $p6->show_point_label = true;
                            $p6->theme = "dark";
                            //$p6->bgcolor = "#000";
                            $p6->color = "blue";
                            $drawSixD = $p6->render('c6');
                        @endphp
                        
                        <center>
                            <div>
                                <?php echo $drawSixD ?>
                            </div>
                        </center>
                        <br>
                        <br>
                    <p><b>Grafica 6.</b> Captura de roedores en trampas</p>
                    <p>Atrayente usado en las trampas: No aplica.</p>
                    <h5>Tabla 7. Referencia para grados de infestación de roedores.</h5>
                    <table  style="width: 100%;border:1px #000 solid !important">
                        <thead style="background-color: #000; color:#fff">
                            <th rowspan="2">PLAGAS</th>
                            <th rowspan="2">Índices de<br> Infestación</th>
                            <th rowspan="2">VALOR<br>(Resultado del IA)</th>
                            <th rowspan="2">INDICACIÓN</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="5">Roedor</td>
                            </tr>
                            <tr>
                                <td>0</td>
                                <td>0</td>
                                <td>Nulo</td>
                            </tr>
                            <tr>
                                <td>0.1 - 1</td>
                                <td>1</td>
                                <td>Bajo</td>
                            </tr>
                            <tr>
                                <td>1.1 - 2</td>
                                <td>2</td>
                                <td>Medio</td>
                            </tr>
                            <tr>
                                <td>> 2</td>
                                <td>3</td>
                                <td>Alto</td>
                            </tr>
                        </tbody>
                    </table>
                    <p><b>Índice de abundancia (IA):</b> (Número de animales capturados / Esfuerzo de captura) * 100.
                        Donde el esfuerzo de captura = Número de trampas colocadas x número de días que funcionaron.</p>
                    <br>
                    @php
                        $abundance_index=$mechanisms_non_toxic_controls_count > 0 ? round($cont_tecnicals_count_six_num/($mechanisms_non_toxic_controls_count*30)*100,2) : 0;
                        $abundance_index_array[]=0;
                        $abundance_index_array[]=$abundance_index;
                        $abundance_index_array[]=0;
                        $abundance_index_color[]=0;
                        $abundance_index_color[]=Menu::randomColor();
                        $abundance_index_color[]=0;
                        $month_Y[] ="Q";
                        $month_Y[] =$month;
                        $month_Y[] ="K";
                    @endphp
                    <center>
                        <p>I . A = (Número individuos capturados / Esfuerzo de Captura )∗ 100 <br>
                        Donde Esfuerzo de Captura = N ° Trampas ∗ Dias de funcionamiento <br>
                        I.A = {{$cont_tecnicals_count_six_num}}/({{$mechanisms_non_toxic_controls_count}} * 30) x 100 <br>
                        IA = {{$abundance_index}}</p>
                    </center>
                    <p><b>NOTA:</b> En el índice de abundancia se tienen en cuenta las capturas realizadas en las jaulas para rata.</p>
                    <input type="hidden" id="abundance_index_seven" value="{{json_encode($abundance_index_array ?? 0)}}">
                    <input type="hidden" id="abundance_index_month_seven" value="{{json_encode($month_Y)}}">
                    <input type="hidden" id="abundance_index_color_seven" value="{{json_encode($abundance_index_color)}}">
                    @php
                    $dataSeven=Array();
                    $dataSevenOne=Array();
                        foreach ($month_Y as $key => $name){
                            $dataSeven[]=Array($name,$abundance_index_array[$key]);
                            $dataSevenOne[]=Array($name,3);
                        }
                        $drawSeven = array(
                                $dataSeven,
                                $dataSevenOne
                            ); 
                            $p7 = new chartphp();

                            $p7->data=$drawSeven;
                            $p7->chart_type = "line";
                            // Common Options
                            $p7->title = "Cebos desaparecidos";
                            $p7->xlabel = "Meses";
                            $p7->ylabel = "Nivel de infestacion";
                            $p7->show_xticks = true;
                            $p7->show_yticks = true;
                            $p7->show_point_label = true;
                            $p7->theme = "dark";
                            //$p7->bgcolor = "#000";
                            $p7->series_label = array("Indice de abundancia","Limite Critico");
                            $p7->color = "teal,red";
                            $drawSevenD = $p7->render('c7');
                        @endphp
                        
                        <center>
                            <div>
                                <?php echo $drawSevenD ?>
                            </div>
                        </center>
                        <br>
                        <br>
                    <p><strong style="color:red">-----</strong> Límite Crítico para roedores: 2.0</p>
                    <p style="background-color:yellow;color:red"><b>NOTAS DE ROEDORES RETIRADOS:</b> (En caso de que se retiren roedores muertos por consumos de cebo o capturados en jaulas que no tienen numeración, o en mecanismos provisionales).</p>
                        <ul>
                            <li>El día 14 de enero se retiran 4 roedores (Rattus rattus), muertos por consumo de cebo.</li>
                        </ul>

                    <h4>OBSERVACIONES Y RECOMENDACIONES PARA EL CONTROL DE ROEDORES:</h4>
                    @php
                        $technicals_observations=TechnicalReport::whereIn('technical_report_type_id',[7,8])->whereIn('crm_schedule_id',$ids_schedules)->orderBy('id','asc')->get();
                        
                    @endphp
                    <ul>
                        @foreach ($technicals_observations as $technicals_observation)
                            @if($technicals_observation->observation)
                                <li>{{$technicals_observation->observation." - ".$technicals_observation->created_at}}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif
                @if($fly_control_lamps->count() > 0 || $pennants->count() > 0 )        
                    <center>
                        <h4>CONTROL DE INSECTOS RASTREROS Y VOLADORES</h4>
                    </center>
                    <h4><b>Tabla 1. Referencia para grados de infestación de insectos en áreas Internas y externas</b></h4>
                    <table  style="width: 100%;border:1px #000 solid !important">
                        <thead style="background-color: #000; color:#fff">
                            <th rowspan="2">PLAGAS</th>
                            <th rowspan="2">Índices de<br> Infestación</th>
                            <th rowspan="2">VALOR<br>(Resultado del IA)</th>
                            <th rowspan="2">INDICACIÓN</th>
                        </thead>
                        <tbody>
                            <tr>
                                <th style="background-color: #000; color:#fff" colspan="4">INTERNAS</th>
                            </tr>
                            <tr>
                                <td rowspan="5">Mosca</td>
                            </tr>
                            <tr>
                                <td>0</td>
                                <td>0</td>
                                <td>Nulo</td>
                            </tr>
                            <tr>
                                <td>0.1 - 1</td>
                                <td>1</td>
                                <td>Bajo</td>
                            </tr>
                            <tr>
                                <td>1.1 - 2</td>
                                <td>2</td>
                                <td>Medio</td>
                            </tr>
                            <tr>
                                <td>> 2</td>
                                <td>3</td>
                                <td>Alto</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table  style="width: 100%;border:1px #000 solid !important">
                        <thead style="background-color: #000; color:#fff">
                            <th rowspan="2">PLAGAS</th>
                            <th rowspan="2">Índices de<br> Infestación</th>
                            <th rowspan="2">VALOR<br>(Resultado del IA)</th>
                            <th rowspan="2">INDICACIÓN</th>
                        </thead>
                        <tbody>
                            <tr>
                                <th style="background-color: #000; color:#fff" colspan="4">EXTERNAS</th>
                            </tr>
                            <tr>
                                <td rowspan="5">Mosca</td>
                            </tr>
                            <tr>
                                <td>0</td>
                                <td>0</td>
                                <td>Nulo</td>
                            </tr>
                            <tr>
                                <td>0.1 - 2</td>
                                <td>1</td>
                                <td>Bajo</td>
                            </tr>
                            <tr>
                                <td>2.1 - 5</td>
                                <td>2</td>
                                <td>Medio</td>
                            </tr>
                            <tr>
                                <td>> 5</td>
                                <td>3</td>
                                <td>Alto</td>
                            </tr>
                        </tbody>
                    </table>
                    <h4>Fórmula para índices de infestación de Insectos rastreros y voladores:</h4>
                    <span>I . I = (Número individuos capturados /(N ° dispositivos ∗ Área de atrapamiento) ∗ Días de funcionamiento) ∗ 100</span>
                    <p><b>Nota:</b> Para la determinación del I.I en zonas donde se presentan dispositivos con diferentes áreas de captura, se calcula un I.I por cada grupo de dispositivos y se procede a calcular el promedio entre ellos.</p>
                    
                    
                    <h4>Grado de Infestación de Mosca domestica (Musca domestica)</h4>
                    <table style="width: 100%;border:1px #000 solid !important">
                        <tbody >
                            <tr>
                                <th><b>Unidad de Monitoreo</b></th>
                                <th><b>Superficie de Captura (cm2)</b></th>
                                <th><b>Unidades capturadas</b></th>
                                <th><b>Periodo funcionamiento (días)</b></th>
                                <th><b>I.I</b></th>
                                <th><b>Zona</b></th>

                            </tr>
                            @if($fly_control_lamps->count() > 0)
                                <tr>
                                    <th colspan="6">Internas</th>
                                </tr>
                                @php 
                                    $sum_ii_e=Array();
                                    $sum_ii_i=Array();
                                    $avg_i=0;
                                    $avg_e=0;
                                    $fly_controls_ids=$value->where('service_id',8)->pluck('id')->toArray();
                                    $tecnicals=TechnicalReport::whereDate('created_at','>=',$start_date)->WhereDate('created_at','<=',$end_date)->whereIn('crm_schedule_id',$fly_controls_ids)->orderBy('id','asc')->get();
                                    $file_extend_ids=$tecnicals->unique('file_extend_id')->pluck('file_extend_id');
                                @endphp
                                @foreach ($file_extend_ids as $file_extend_id)
                                    @php
                                        $tecnicals_reports=TechnicalReport::whereDate('created_at','>=',$start_date)->WhereDate('created_at','<=',$end_date)->where('file_extend_id',$file_extend_id)->orderBy('id','desc')->get();
                                        $sum_quantity_I=$tecnicals_reports->pluck('quantity')->sum();
                                        $capture_surface=$tecnicals_reports->first()->extend->extendible->products->first()->capture_surface;
                                        $ii=round(($sum_quantity_I/($capture_surface*30))*100,4);

                                    @endphp
                                        @if($tecnicals_reports->first()->extend->extendible->category->slug === 'fly-control-lamps')
                                            <tr>
                                                <td>{{$tecnicals_reports->first()->extend->extendible->name}} # {{$tecnicals_reports->first()->extend->num}}</td>
                                                <td>{{$capture_surface}}</td>
                                                <td>{{$sum_quantity_I}}</td>
                                                <td>30</td>
                                                <td>{{$ii}}</td>
                                                <td>{{$tecnicals_reports->first()->crmSchedule->controls->first()->area->name ?? ""}}</td>
                                            </tr>
                                            @php
                                                $sum_ii_i[]=$ii;
                                                $avg_i=round(array_sum($sum_ii_i)/count($sum_ii_i),4);
                                            @endphp
                                        @endif
                                @endforeach
                                <tr>
                                    <td >Indice general de infestación</td>
                                    <td colspan="3"></td>
                                    <td>{{$avg_i}}</td>
                                    @php
                                        $avg_i_arr[]=0;
                                        $avg_i_arr[]=$avg_i;
                                        $avg_i_arr[]=0;

                                        $label_i_arr[]="A";
                                        $label_i_arr[]="INTERIOR";
                                        $label_i_arr[]="D";
                                    @endphp
                                    <input type="hidden" id="label_i_musca" value="{{json_encode($label_i_arr)}}">
                                    <input type="hidden" id="values_i_musca" value="{{json_encode($avg_i_arr)}}">
                    

                                </tr>
                            @endif
                            @if($pennants->count() > 0)

                                <tr>
                                    <th colspan="6">Externas</th>
                                </tr>
                                @php 
                                    $fly_controls_ids=$value->where('service_id',8)->pluck('id')->toArray();
                                    $tecnicals=TechnicalReport::whereIn('crm_schedule_id',$fly_controls_ids)->orderBy('id','asc')->get();
                                    $file_extend_ids=$tecnicals->unique('file_extend_id')->pluck('file_extend_id');
                                @endphp
                                @foreach ($file_extend_ids as $file_extend_id)
                                    @php
                                        $tecnicals_reports=TechnicalReport::where('file_extend_id',$file_extend_id)->orderBy('id','desc')->get();
                                        $sum_quantity_E=$tecnicals_reports->pluck('quantity')->sum();
                                        $capture_surface=$tecnicals_reports->first()->extend->extendible->products->first()->capture_surface;
                                        $ii=round(($sum_quantity_E/($capture_surface*30))*100,4);

                                    @endphp
                                        @if($tecnicals_reports->first()->extend->extendible->category->slug === 'pennants')
                                            <tr>
                                                <td>{{$tecnicals_reports->first()->extend->extendible->name}} # {{$tecnicals_reports->first()->extend->num}}</td>
                                                <td>{{$capture_surface}}</td>
                                                <td>{{$sum_quantity_E}}</td>
                                                <td>30</td>
                                                <td>{{$ii}}</td>
                                                <td>{{$tecnicals_reports->first()->crmSchedule->controls->first()->area->name ?? ""}}</td>
                                            </tr>
                                            @php
                                                $sum_ii_e[]=$ii;
                                                $avg_e=round(array_sum($sum_ii_e)/count($sum_ii_e),4);
                                            @endphp
                                        @endif
                                @endforeach
                                <tr>
                                    <td >Indice general de infestación</td>
                                    <td colspan="3"></td>
                                    <td>{{$avg_e}}</td>
                                    @php
                                        $avg_e_arr[]=0;
                                        $avg_e_arr[]=$avg_e;
                                        $avg_e_arr[]=0;

                                        $label_e_arr[]="A";
                                        $label_e_arr[]="EXTERIOR";
                                        $label_e_arr[]="D";
                                    @endphp
                                    <input type="hidden" id="label_e_musca" value="{{json_encode($label_e_arr)}}">
                                    <input type="hidden" id="values_e_musca" value="{{json_encode($avg_e_arr)}}">
                    

                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <br>
                    <br>
                    @if($fly_control_lamps->count() > 0)

                        @php
                        $dataEigth=Array();
                        $dataEigthOne=Array();
                        foreach ($label_i_arr as $key => $name){
                            $dataEigth[]=Array($name,$avg_i_arr[$key]);
                            $dataEigthOne[]=Array($name,3);
                        }
                        $drawEigth = array(
                                $dataEigth,
                                $dataEigthOne
                            ); 
                            $p8 = new chartphp();

                            $p8->data=$drawEigth;
                            $p8->chart_type = "line";
                            // Common Options
                            $p8->title = "Indice de abundancia";
                            $p8->xlabel = "Interior";
                            $p8->ylabel = "Indice de abundancia";
                            $p8->show_xticks = true;
                            $p8->show_yticks = true;
                            $p8->show_point_label = true;
                            $p8->theme = "dark";
                            //$p8->bgcolor = "#000";
                            $p8->series_label = array("Indice de abundancia","Limite Critico");
                            $p8->color = "teal,red";
                            $drawEigthD = $p8->render('c8');
                        @endphp
                        
                        <center>
                            <div>
                                <?php echo $drawEigthD ?>
                            </div>
                        </center>
                        <br>
                        <br>
                    @endif
                    @if($pennants->count() > 0)
                    
                        @php
                            $dataNine=Array();
                            $dataNineOne=Array();
                            foreach ($label_e_arr as $key => $name){
                                $dataNine[]=Array($name,$avg_e_arr[$key]);
                                $dataNineOne[]=Array($name,7);
                            }
                            $drawNine = array(
                                    $dataNine,
                                    $dataNineOne
                                ); 
                                $p9 = new chartphp();

                                $p9->data=$drawNine;
                                $p9->chart_type = "line";
                                // Common Options
                                $p9->title = "Indice de abundancia";
                                $p9->xlabel = "Exterior";
                                $p9->ylabel = "Indice de abundancia";
                                $p9->show_xticks = true;
                                $p9->show_yticks = true;
                                $p9->show_point_label = true;
                                $p9->theme = "dark";
                                //$p9->bgcolor = "#000";
                                $p9->series_label = array("Indice de abundancia","Limite Critico");
                                $p9->color = "teal,red";
                                $drawNineD = $p9->render('c9');
                        @endphp
                        
                        <center>
                            <div>
                                <?php echo $drawNineD ?>
                            </div>
                        </center>
                        <br>
                        <br>
                        <p>---- Límite Crítico para interiores: 7.0</p>
                    @endif
                    @if($pennants->count() > 0 || $fly_control_lamps->count() > 0)

                        <br>
                        @php 
                            $fly_controls_ids=$value->where('service_id',8)->pluck('id')->toArray();
                            $tecnicals=TechnicalReport::whereIn('crm_schedule_id',$fly_controls_ids)->orderBy('id','asc')->get();
                            $tecnicals_group=$tecnicals->groupBy('crmSchedule.control.area.name');
                            $area_label=Array();
                            $area_values=Array();
                        @endphp
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <th>Areas</th>
                                    <th>Promedio</th>
                                </tr>
                                @foreach($tecnicals_group as $area => $tecnical_report)
                                    @php
                                        $avgg_sum=Array();
                                    @endphp
                                    <tr>
                                        <th ">{{$area}}</th>
                                        @foreach($tecnical_report as $report)
                                            @php
                                                $avgg=(($report->quantity)/($report->extend->extendible->products->first()->capture_surface*30))*100;
                                                $avgg_sum[]=$avgg;
                                            @endphp
                                        @endforeach
                                        @php
                                        if(count($avgg_sum) > 0){
                                            $avg_area=round(array_sum($avgg_sum)/count($avgg_sum),4);
                                            $area_label[]=$area;
                                            $area_values[]=$avg_area;
                                        }
                                        @endphp
                                        <td>{{$avg_area ?? 0}}</td>
                                    </tr>
                                @endforeach
                                <tr></tr>
                            </tbody>
                        </table>
                        <br>
                        <br>
                        <input type="hidden" id="area_label" value="{{json_encode($area_label)}}">
                        <input type="hidden" id="area_values" value="{{json_encode($area_values)}}">
                        <br>
                        @php
                        $dataTen=Array();
                        foreach ($area_label as $key => $name){
                            $dataTen[]=Array($name,$area_values[$key]);
                        }
                        $drawTen = array(
                                $dataTen,
                            ); 
                            $p10 = new chartphp();

                            $p10->data=$drawTen;
                            $p10->chart_type = "bar";
                            // Common Options
                            $p10->title = "Indice de abundancia por areas";
                            $p10->xlabel = "Areas";
                            $p10->ylabel = "Indice de abundancia";
                            $p10->show_xticks = true;
                            $p10->show_yticks = true;
                            $p10->show_point_label = true;
                            $p10->theme = "dark";
                            //$p10->bgcolor = "#000";
                            $p10->color = "teal";
                            $drawTenD = $p10->render('c10');
                        @endphp
                        
                        <center>
                            <div>
                                <?php echo $drawTenD ?>
                            </div>
                        </center>
                        <br>
                        <br>
                        <br>
                        
                        <h4>OBSERVACIONES Y RECOMENDACIONES PARA EL CONTROL DE INSECTOS RASTREROS Y VOLADORES</h4>
                        @foreach($value->where('service_id',8)->sortBy('id') as $values_id)
                            @php
                                $tecnicalsTen=TechnicalReport::where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                            @endphp
                            <ul>
                                @foreach ($tecnicalsTen as $i => $ten)
                                    @if(filled($ten->observation))
                                        <li>{{$ten->observation}} ({{$ten->created_at}})</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endforeach
                @endif
                
            @endif
            @php
                $disinceptation_ids=$value->whereIn('service_id',[1,2])->pluck('id');
                $disinceptation_count=$value->whereIn('service_id',[1,2])->count();
                $disinceptation_groups=$value->whereIn('service_id',[1,2])->groupBy('control.area.id');
            @endphp
            @if($pennants->count() == 0 && $fly_control_lamps->count() == 0 && $disinceptation_count > 0)
                    <center>
                        <h4>CONTROL DE INSECTOS RASTREROS Y VOLADORES</h4>
                    </center>
                    <table  style="width: 100%;border:1px #000 solid !important">
                        <thead >
                            <th rowspan="2">GRADO DE
                                INFESTACIÓN</th>
                            <th rowspan="2">VALOR</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>NULO</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>BAJO</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>MEDIO</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>ALTO</td>
                                <td>3</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    @foreach($value->whereIn('service_id',[1,2]) as $disinceptation)
                        <span>Tabla Grado de Infestación de insectos, ({{$disinceptation->created_at}})</span>
                        <table  style="width: 100%;border:1px #000 solid !important">
                            <thead >
                                <th rowspan="2">AREA</th>
                                <th rowspan="2">Cucarachas</th>
                                <th rowspan="2">Mosca</th>
                                <th rowspan="2">Zancudo</th>
                                <th rowspan="2">Hormiga</th>
                            </thead>
                            <tbody>
                                    @foreach($disinceptation_groups as $area => $disinceptation_area)
                                        <tr>
                                            @php
                                                $area_name=CrmArea::find($area);
                                            @endphp
                                            <td>{{$area_name->name ?? 'SN'}}</td>
                                            @php
                                                $schedules_control=CrmScheduleControl::with('infestationGrade')->where('crm_area_id',$area)->where('crm_schedule_id',$disinceptation->id)->get();
                                                $cockroaches=collect();
                                                $fly=collect();
                                                $mosquito=collect();
                                                $ant=collect();
                                                foreach ($schedules_control as $key => $schedule_control) {
                                                    $cockroaches->push($schedule_control->infestationGrade->where('infestation_id',2)->max('value'));
                                                    $fly->push($schedule_control->infestationGrade->where('infestation_id',3)->max('value'));
                                                    $mosquito->push($schedule_control->infestationGrade->where('infestation_id',4)->max('value'));
                                                    $ant->push($schedule_control->infestationGrade->where('infestation_id',5)->max('value'));

                                                }
                                            
                                            @endphp
                                            <td>{{ ($cockroaches->max() != null) ? $cockroaches->max() : ' - '}} </td>
                                            <td>{{ ($fly->max() != null) ? $fly->max() : ' - '}} </td>
                                            <td>{{ ($mosquito->max() != null) ? $mosquito->max() : ' - '}} </td>
                                            <td>{{ ($ant->max() != null) ? $ant->max() : ' - '}} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>
                        <br>
                    @endforeach
                    @php
                    $reports_confirmaded=false;
                            $reports=TechnicalReport::whereIn('crm_schedule_id',$disinceptation_ids)->get();
                            foreach ($reports as $report){
                                if($report->observation != null && $report->observation != ''){
                                    $reports_confirmaded=true;
                                }
                            }
                    @endphp

                    @if($reports_confirmaded)
                        <h4>OBSERVACIONES Y RECOMENDACIONES PARA EL CONTROL DE INSECTOS RASTREROS Y VOLADORES</h4>
                            @foreach($value->whereIn('service_id',[1,2])->sortBy('id') as $values_id)
                                @php
                                    $tecnicalsTen=TechnicalReport::where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                                @endphp
                                <ul>
                                    @foreach ($tecnicalsTen as $i => $ten)
                                        @if(filled($ten->observation))
                                            <li>{{$ten->observation}} ({{$ten->created_at}})</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endforeach
                        
                        <br>
                        <br>
                    @endif
                @endif
                <br>
                <br>
                @php
                    $services_count=$value->where('service_id',9)->count();
                    $tecnical_sum=0;
                    $tecnical_sumNine=0;
                    $ids_schedules=$value->where('service_id',9)->pluck('id');
                @endphp
                @if($services_count > 0)
                    <center><h4>CONTROL DE PALOMAS</h4></center>
                    <p>Para efectos de interpretación de resultados es importante tener en cuenta que los grados de presencia son tomados con base en la capacidad de cada jaula reloj que es de 35 palomas para garantizar condiciones cómodas. (Pueden que ingresen más por lo tanto se puede salir del % tomado)</p>
                    
                    
                    <h4>Tabla de retiro de huevos en el mes</h4>
                    <table style="width: 100%;border:1px #000 solid !important">
                        <tbody >
                            <tr>
                                <th rowspan="2">Mes</th>
                                <th colspan="{{$services_count}}">SEMANA DE VISITAS</th>
                                <th rowspan="2">TOTAL DE HUEVOS RETIRADOS/MES</th>
                            </tr>
                            <tr>
                                @for($cont=0;$cont<$services_count;$cont++)
                                    <th>{{$cont+1}}</th>
                                @endfor
                            </tr>
                            <tr>
                                
                                <td>{{$month}}</td>
                                @foreach($value->where('service_id',9)->sortBy('id') as $values_id)
                                    @php
                                        $tecnicals=TechnicalReport::where('technical_report_type_id',12)->where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                                        $sum_values=$tecnicals->pluck('quantity')->sum();
                                        $tecnical_sum+=$sum_values;
                                        $avg=$tecnical_sum;
                                    @endphp
                                    <td><center>{{$sum_values}}</center></td>
                                @endforeach
                                <td><center>{{ $avg }}</center></td>
                            </tr>
                        </tbody>
                    </table>
                    <strong>Formulas Tabla y Grafica:</strong><br><br>
                    <center>
                        
                        @php
                            foreach ($months_year as $key => $monthE){
                                $monthsYearEight[]=$monthE."-".$year;
                                $months_valueEight[] = $key === $year_month ? $avg : 0;

                            }
                        @endphp
                        <input type="hidden" id="month_year_eight" value="{{json_encode($monthsYearEight)}}">
                        <input type="hidden" id="month_value_eight" value="{{json_encode($months_valueEight)}}">
                        
                    </center>
                    @php
                        $dataEleven=Array();
                        foreach ($monthsYearEight as $key => $name){
                            $dataEleven[]=Array($name,$months_valueEight[$key]);
                        }
                        $drawEleven = array(
                                $dataEleven,
                            ); 
                            $p11 = new chartphp();

                            $p11->data=$drawEleven;
                            $p11->chart_type = "bar";
                            // Common Options
                            $p11->title = "Retiro de huevos mensual";
                            $p11->xlabel = "Huevos";
                            $p11->ylabel = "Cantidad";
                            $p11->show_xticks = true;
                            $p11->show_yticks = true;
                            $p11->show_point_label = true;
                            $p11->theme = "dark";
                            //$p11->bgcolor = "#000";
                            $p11->color = "blue";
                            $drawElevenD = $p11->render('c11');
                        @endphp
                        
                        <center>
                            <div>
                                <?php echo $drawElevenD ?>
                            </div>
                        </center>
                        <br>
                        <br>
                        <br>
                    <h4>Tabla de retiro de palomas en Jaulas Reloj por mes</h4>
                        <table style="width: 100%;border:1px #000 solid !important">
                            <tbody >
                                <tr>
                                    <th rowspan="2">Mes</th>
                                    <th colspan="{{$services_count}}">SEMANA DE VISITAS</th>
                                    <th rowspan="2">PROMEDIO POR SEMANA</th>
                                    <th rowspan="2">%</th>
                                </tr>
                                <tr>
                                    @for($cont=0;$cont<$services_count;$cont++)
                                        <th>{{$cont+1}}</th>
                                    @endfor
                                </tr>
                                <tr>
                                    
                                    <td>{{$month}}</td>
                                    @foreach($value->where('service_id',9)->sortBy('id') as $values_id)
                                        @php
                                            $tecnicalsNine=TechnicalReport::where('technical_report_type_id',13)->where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                                            $sum_valuesNine=$tecnicalsNine->pluck('quantity')->sum();
                                            $visits[]=$sum_valuesNine;
                                            $tecnical_sumNine+=$sum_valuesNine;
                                            $avgNine=$tecnical_sumNine/$services_count;
                                            try{
                                                $percentage=round(($avgNine/(35*$clock_cages ))*100,2);
                                            }catch(\Exception $e){
                                                $percentage=0;
                                            }
                                        @endphp
                                        <td><center>{{$sum_valuesNine}}</center></td>
                                    @endforeach
                                    <td><center>{{ $avgNine }}</center></td>
                                    <td><center>{{ $percentage }}</center></td>
                                </tr>
                            </tbody>
                        </table>
                    <strong>Formulas Tabla y Grafica:</strong><br><br>
                    <center>
                        @php
                            foreach ($months_year as $key => $monthE){
                                $monthsYearNine[]=$monthE."-".$year;
                                $months_valueNine[] = $key === $year_month ? $percentage : 0;

                            }

                            foreach ($visits as $index => $visit){
                                $weeks[]="Semana ".($index+1);
                                $weeks_values[]=$visit;
                            }
                        @endphp
                        <input type="hidden" id="month_year_nine" value="{{json_encode($monthsYearNine)}}">
                        <input type="hidden" id="month_value_nine" value="{{json_encode($months_valueNine)}}">
                        @php
                        $dataTwelve=Array();
                        $dataTwelveOne=Array();
                        $dataTwelveTwo=Array();
                        foreach ($monthsYearNine as $key => $name){
                            $dataTwelve[]=Array($name,$months_valueNine[$key]);
                            $dataTwelveOne[]=Array($name,30);
                            $dataTwelveTwo[]=Array($name,20);
                            $dataTwelveThree[]=Array($name,10);
                        }
                        $drawTwelve = array(
                                $dataTwelve,
                                $dataTwelveOne,
                                $dataTwelveTwo,
                                $dataTwelveThree,
                            ); 
                            $p12 = new chartphp();

                            $p12->data=$drawTwelve;
                            $p12->chart_type = "line";
                            // Common Options
                            $p12->title = "Retiro de palomas mensual";
                            $p12->xlabel = "Meses";
                            $p12->ylabel = "Porcentajes";
                            $p12->show_xticks = true;
                            $p12->show_yticks = true;
                            $p12->show_point_label = true;
                            $p12->theme = "dark";
                            //$p12->bgcolor = "#000";
                            $p12->series_label = array("Retiro de palomas","Limite critico","Rango de control","Rango de control");
                            $p12->color = "teal,red,orange,gray";
                            $drawTwelveD = $p12->render('c12');
                        @endphp
                        
                        <center>
                            <div>
                                <?php echo $drawTwelveD ?>
                            </div>
                        </center>
                        <br>
                        <br>
                        <br>
                        
                        <input type="hidden" id="weeks" value="{{json_encode($weeks)}}">
                        <input type="hidden" id="weeks_values" value="{{json_encode($weeks_values)}}">
                        
                    </center>
                    <h4>Fórmula para capacidad de atrapamiento de palomas en jaula/s reloj:</h4>

                    C.A= (Promedio mensual del numero de individuos capturados / (Capacidad de atrapamiento de dispositivos * Numero de dispositivos)) * 100
                    <p><b>Nota:</b> la capacidad de atrapamiento de palomas en condiciones cómodas para cada jaula reloj es de 35 palomas.</p>
                    @php
                        $dataThirteen=Array();
                        foreach ($weeks as $key => $name){
                            $dataThirteen[]=Array($name,$weeks_values[$key]);
                        }
                        $drawThirteen = array(
                                $dataThirteen,
                            ); 
                            $p13 = new chartphp();

                            $p13->data=$drawThirteen;
                            $p13->chart_type = "bar";
                            // Common Options
                            $p13->title = "Retiro de palomas por semana";
                            $p13->xlabel = "Semanas";
                            $p13->ylabel = "Cantidad";
                            $p13->show_xticks = true;
                            $p13->show_yticks = true;
                            $p13->show_point_label = true;
                            $p13->theme = "dark";
                            //$p13->bgcolor = "#000";
                            $p13->color = "green";
                            $drawThirteen = $p13->render('c13');
                        @endphp
                        
                        <center>
                            <div>
                                <?php echo $drawThirteen ?>
                            </div>
                        </center>
                        <br>
                        <br>
                        <br>
                    <h4>RECOMENDACIONES Y OBSERVACIONES PARA EL CONTROL DE AVES</h4>
                    @foreach($value->where('service_id',9)->sortBy('id') as $values_id)
                        @php
                            $tecnicalsTen=TechnicalReport::where('technical_report_type_id',13)->where('crm_schedule_id',$values_id->id)->orderBy('id','desc')->get();
                        @endphp
                        <ul>
                            @foreach ($tecnicalsTen as $i => $ten)
                                @if(filled($ten->observation))
                                    <li>{{$ten->observation}} ({{$ten->created_at}})</li>
                                @endif
                            @endforeach
                        </ul>
                    @endforeach
                    <h4>PRODUCTO APLICADO</h4>
                    <p>Maíz y agua en palomares y/o jaulas reloj</p>
                @endif
        Atentamente,<br><br><br>
        <table style="width: 100%;border:1px #000 solid !important">
                <tr >
                    @foreach ($coordinators_mip as $coordinator)
                        <td>
                            @if(isset($coordinator->signature))
                            <img style="height: 90px;
                            position: relative;
                            " src="{{$coordinator->signature}}">
                            @endif 
                        </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($coordinators_mip as $coordinator)
                        <th >{{$coordinator->name}}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($coordinators_mip as $coordinator)
                        <th style="color:#00b050"> Coordinador Gestión Integrada de Plagas</th>
                    @endforeach
                </tr>
        </table>
        </main>
        <br>
        <br>
        <br>
        <br>
        <div style="width: 100%" class="footer">
           <img id="image_footer" src="{{$background_footer}}" />

            @php 
                $headquarter=$schedules->first()->scheduling->headquarter ?? null;
            @endphp
            @if($headquarter)
                <p class="page"> 
                    <strong>{{$headquarter->address}}-{{$headquarter->cities->name}}<br>
                    <strong>PBX:</strong> (7) {{$headquarter->phone}} - <strong>Cel.</strong> {{$headquarter->cell_phone}}<br>
                    <strong>E-mail: </strong>{{$headquarter->email}}
                </p>
            @endif
        </div>
    <script src="<?=url("template/app-assets/vendors/js/vendors.min.js")?>"> type="text/javascript"></script>
    <script src="<?=url("assets/js/layouts/app.js")?>" type="text/javascript"> </script>

    <script src="<?= url("template/app-assets/vendors/js/charts/chart_3_6.min.js") ?>" type="text/javascript"></script>
    
</body>

</html>
