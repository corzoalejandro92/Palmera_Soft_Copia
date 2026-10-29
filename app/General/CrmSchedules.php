<?php

namespace App\General;

use \App\Crm;
use Exception;
use App\CrmArea;
use App\Service;
use Carbon\Carbon;
use App\Application;
use App\CrmSchedule;
use App\CrmScheduleControl;
use App\CrmScheduleObservation;
use App\Infestation;
use App\Periodicity;
use App\Workschedule;
use App\DetailService;
use App\CrmScheduleStatus;
use App\Quote;
use App\Route;
use App\Scheduling;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;
use Barryvdh\DomPDF\Facade as PDF;

use Illuminate\Support\Facades\Mail;
use App\Mail\ReportService;

trait CrmSchedules
{
    public static $error = "";
    public static $trace = null;
    
  
    public static function buildFromCrmRequest(Request $request, Crm $crm ){
        try {

         
            DB::beginTransaction();
                if($crm){

                    // actualizar el parámetro expired_notify de la sucursal
                    \App\Office::where('id',$request->input('office_id'))->update(['expired_notify' =>1]);
    
                    //1. Almacenar el registro de cronograma en workschedules
                    $workSchedule = Workschedule::updateOrCreate(
                        [
                            "id" => $request->input('workschedule_id')
                        ],
                        [
                            "headquarter_id" => session('headquarter_id'),
                            "name"           => "cronograma de actividades",
                            "date"           => date('Y-m-d'),
                            "user_id"        => Auth::User()->id,
                            "json"           => $request->all(),
                            "html"           => null,
                            "quote_id"       => $request->input('quote_id'),
                            "crm_id"         => $request->input('crm_id'),
                            "office_id"      => $request->input('office_id'),
                            "services_data"  => $request->input('services')
                        ]
                    );

                    //eliminar los controles de los servicios para volver a generar
                    $serviceUpdateDate = $request->filled('from_submit') ? Carbon::createFromFormat('d/m/Y',$request->input('from_submit')) : Carbon::now();
                    if($request->filled('from_submit')){       
                        $services = $workSchedule->services()->whereIn('status_id', [1,4,5,6])->whereDate('date', '>=',$serviceUpdateDate->format('Y-m-d'))->get();
                    }else{
                        $services = $workSchedule->services()->whereIn('status_id', [1,4,5,6])->get();
                    }
                   
                    foreach ($services as $service) {
                        
                        //si los servicios consultados tienen una fecha igual o mayor a la fecha de modificación
                        if(Carbon::parse($service->original_date)->startOfDay()->gte($serviceUpdateDate->startOfDay()) && Carbon::parse($service->date)->startOfDay()->gte($serviceUpdateDate->startOfDay())){

                            //se eliminan todos los controles para volever a generarlos
                            $service->controls()->whereDate('date', '>=',$serviceUpdateDate->format('Y-m-d'))->delete();

                            //se marcan como deshabilitados para saber mas adelante cual se elimina y cual se queda
                            $service->enabled = 0;
                            $service->save();
                        }
                    }

                    if($request->input('action') == 'force'){
                        //eliminar los servicio y controles asociados al servicio en cuestion
                        $servicesToDlt = $workSchedule->services()->get();

                        foreach ($servicesToDlt as $key => $tmpserd) {
                            $tmpserd->controls()->delete();
                        }
                        $workSchedule->services()->delete();
                        
                    }

                    
                    //eliminar los servicios que estén almacenados en la sesión
                    session()->forget('temp_services');



                    // 2. Alamacenar la configuración de servicios x cronogramas
                    $proccessed = false;
                    foreach ($request->input('services') as $requestService){

                        
                        $requestService = new Request($requestService);
                        //validar que la cantidad de semana no sea menor a la cantidad de visitas
                        if($requestService->input('visits') > count($requestService->input('weeks'))){
                            DB::rollBack();
                            abort('406', "La cantidad de semanas a programar no puede ser menor al número de visitas en el servicio '".Service::find($requestService->input('service_id'))->name. "' ");
                        } 

                        //3. crear los controles asociados al servicio del cronograma

                        //como se va a manejar el formato ISO, se debe sumar una unidad a el valor de la semana
                        $dayOfWeek = ($requestService->input('start_day')+1);

                        $date               = Carbon::createFromFormat('d/m/Y',$requestService->input('start_week'))->isoWeekday($dayOfWeek);

                        
                        if($requestService->input('periodicity_id') == 13){
                            $date->startOfWeek(Carbon::MONDAY)->weekday($requestService->input('start_day'));
                            self::saveService($requestService, $request, $workSchedule, $date, 1, 0);
                            continue; 
                        }



                        $endDate            = Carbon::createFromFormat('d/m/Y',$requestService->input('end_date'))->isoWeekday($dayOfWeek);
                        $qmonth             = ($date->diffInMonths($endDate)+1);
                        $visits             = $requestService->input('visits');
                        $monthDistance      = Periodicity::find($requestService->input('periodicity_id'))->value;
                        $weeks              = Periodicity::find($requestService->input('periodicity_id'))->weeks;
                        $mothDistribution   = [];

                        $dateControl        = $date->copy();

                        $realEndDate =  Carbon::createFromFormat('d/m/Y',$requestService->input('end_date'));

                        
                        
                        //3.1 recorrer la cantidad de meses por periodicidad establecida
                        $proccessed = false;
                        while($date->lt($realEndDate)){
                            
                            $weekCount = 1;
                            $monttemp = $date->month;

                            for ($j=0; $j < $weeks; $j++) { 
                                $proccessed = false;
                                //verificar si enable_fix_weeks fue activado o no
                                //esto es para omitir reacomodar los servicios que no empiezan en la primer semana
                                //y que se requiere aprovechar la qinta semana para ir acomodando los servicios atrazados
                                
                                if(!$requestService->filled('enable_fix_weeks')){
                                 
                                   // este flujo es si no se activa la función enable_fix_weeks
                                   //aplica para periodicidades NO MENSUALES 

                                     //si la periodicidad es semanal
                                     if($requestService->input('periodicity_id') == 3){

                                        //si la semana a guardar es mayor a la semana del mes en lecturavb 
                                        while($weekCount > $date->weekOfMonth && $date->month == $monttemp){
                                            $date->addWeeks(1)->startOfWeek(Carbon::MONDAY)->weekday($requestService->input('start_day'));
                                        }

                                            if(in_array($date->weekOfMonth,$requestService->input('weeks'))){
                                            
                                                if(!$date->lt($realEndDate)) break;
    
                                                    if(isset($mothDistribution["$date->month-$date->year"]) && count($mothDistribution["$date->month-$date->year"]) >= $visits){
                                                        $date->addWeeks(1)->startOfWeek(Carbon::MONDAY)->weekday($requestService->input('start_day'));
                                                        continue;
                                                    }else{
                                                        $mothDistribution["$date->month-$date->year"][$date->weekOfMonth] = $date->weekOfMonth; 
                                                    }
                                                    
                                                    $continue = true;
                                                    //verificar que el servicio a ingresar no coincida con un servicio programado o con estado superior
                                                    //if(!isset($serviceException[$requestService->input('service_id')][$date->month][$weekCount])){ 
                                                        self::saveService($requestService, $request, $workSchedule, $date, $date->weekOfMonth, $monthDistance);
                                                    //}
                                            
                                            }
                                            if($weekCount > $weeks){
                                                break;  
                                            }else{
                                                $weekCount++;
                                            }
                                            $date->addWeeks((($monthDistance-1) == 0? 1 : 0))->addMonths(($monthDistance-1))->startOfWeek(Carbon::MONDAY)->weekday($requestService->input('start_day'));

                                    }else{
                                        
                                        
                                        if(in_array($weekCount,$requestService->input('weeks'))){

                                            //control para contar semanas consecutivamente desde la fecha de incio de controles
                                            //esto hace que no se tenga relacion con la semana precisa del mes, para menejar 
                                            //de la semana 5 en adelante debido a que no se puede comparar directamente con el mes
                                            // sino con un contador virtual de semanas flotantes que comienzan desde el lunes [weekday(0)]
    
                                            //condcion para no usar la quinta semana del mes en los conteos virtuales
                                           
                                            if($weekCount > 4){
    
                                                $date->startOfWeek(Carbon::MONDAY)->isoWeekday(1);
                                                $dateControl->startOfWeek(Carbon::MONDAY)->isoWeekday(1);
                                                $weekDiff = (ceil($date->floatDiffInWeeks($dateControl))+1);


                                                #dump("{$dateControl->format('Y-m-d H:i:s')} -- {$date->format('Y-m-d H:i:s')} ");
                                                #dump("$weekDiff == $weekCount");
                                                #dump("{$weekDiff} -|- $weekCount");

                                                //dump(Service::find($requestService->input('service_id'))->name.' | '. $date->format('Y-m-d').' | '. $weekDiff .' | '.$weekCount);
                                                while(!( $weekDiff == $weekCount)){   
                                                    $date->addWeeks(1);
                                                }
                                                if($date->weekOfMonth == 5){
                                                    #se comenta esta linea para los trimestre  al parecer ya no es necesaria
                                                    //$date->addWeeks(1)->startOfWeek(Carbon::MONDAY)->isoWeekday($dayOfWeek);
                                                }
                                                //dump("{$weekDiff} *** $weekCount");
                                            }else{
    
                                                // este flujo control maneja las primeras 4 semanas las periodicidades NO MENSUALES 
                                                //se controla asi para tener precisicion de las semana de donde se desea comenzar
                                                //EJ: semana 2 de julio, y se controlo hasta 4 semanas porque es el maximo de semanas
                                                //que tiene un mes y que se pueden comparar directamente
                                                
                                                $date->startOfWeek(Carbon::MONDAY)->isoWeekday(1);
                                                //dump(Service::find($requestService->input('service_id'))->name.' | '. $date->format('Y-m-d'). ' | '.$weekCount);

                                                #dump("{$date->weekOfMonth} -- $weekCount");
                                                while(!($date->weekOfMonth == $weekCount)){                               
                                                    $date->addWeeks(1);
                                                    $dateControl->addWeeks(1);
                                                }
                                                #$date->startOfWeek(Carbon::MONDAY)->weekday(0); 
                                                //dump("{$date->weekOfMonth} *** $weekCount");
                                            }
    
                                            
                                            #dd("$weekCount---$weekDiff--{$date->format('Y-m-d')}");
                                            
                                            $date->startOfWeek(Carbon::MONDAY)->isoWeekday($dayOfWeek);
                                            self::saveService($requestService, $request, $workSchedule, $date, $weekCount, $monthDistance);
                                            #$proccessed = true;
                                            
                                        }else{
                                            
                                            //solo para casos donde la semana de la periodicidad no sobrepasa un caso mensual
                                            if($weekCount < $date->weekOfMonth && $weeks <= 5){
                                                $weekCount ++;
                                                continue;
                                            }
                                        }
    
                                        #dump("$weekCount---{$date->format('Y-m-d')}---{$requestService->id}". ($proccessed ? '--- true': ''));
                                        $date->addWeeks((($monthDistance-1) == 0? 1 : 0))->addMonths(($monthDistance-1))->startOfWeek(Carbon::MONDAY)->isoWeekday($dayOfWeek);
    
                                        

                                        if($weekCount >= $weeks)
                                        {
                                            $dateControl = $date->copy();
                                        }else if($weekCount > $weeks){
                                           break;
                                        }else{
                                            $weekCount++;
                                            #dump("$weekCount---{$date->format('Y-m-d')}---{$requestService->id}". ($proccessed ? '--- true': ''));
                                        }
                                        
                                    }
                                }else{
                                    
                                     //si la semana a guardar es mayor a la semana del mes en lectura
                                    while($weekCount > $date->weekOfMonth && $date->month == $monttemp){
                                        $date->addWeeks(1)->startOfWeek(Carbon::MONDAY)->isoWeekday($dayOfWeek);
                                    }
                                
                                    if(!self::checkMonthForFullVisits($requestService, $weeks,$mothDistribution, "$date->month-$date->year") && $date->lt($endDate)){    
                                        
                                        if(in_array($weekCount,$requestService->input('weeks'))){
                                            
                                            
                                            if(!$date->lt($realEndDate)) break;

                                                if(isset($mothDistribution["$date->month-$date->year"]) && count($mothDistribution["$date->month-$date->year"]) >= $visits){
                                                    $date->addWeeks(1)->startOfWeek(Carbon::MONDAY)->isoWeekday($dayOfWeek);
                                                    continue;
                                                }else{
                                                    $mothDistribution["$date->month-$date->year"][$date->weekOfMonth] = $weekCount; 
                                                }
                                                
                                                $continue = true;
                                                //verificar que el servicio a ingresar no coincida con un servicio programado o con estado superior
                                                //if(!isset($serviceException[$requestService->input('service_id')][$date->month][$weekCount])){
                                                    
                                                    self::saveService($requestService, $request, $workSchedule, $date, $weekCount, $monthDistance);
                                                
                                                //}
                                        
                                        }if($weekCount > $weeks){
                                            break;  
                                        }else{
                                            $weekCount++;
                                        }
                                        $date->addWeeks((($monthDistance-1) == 0? 1 : 0))->addMonths(($monthDistance-1))->startOfWeek(Carbon::MONDAY)->isoWeekday($dayOfWeek);
                                    }else{
                                        $date->addWeeks((($monthDistance-1) == 0? 1 : 0))->addMonths(($monthDistance-1))->startOfWeek(Carbon::MONDAY)->isoWeekday($dayOfWeek);
                                        break;
                                    } 
                                }
                                
                            }
                        }
                    
                    }
                }

                //eliminar lo que esté con enabled 0
                $workSchedule->services()->where('enabled', 0)->delete();
        

                //cambiar el estado de la cotización relacionada
                if($request->filled('quote_id')){
                    Quote::find($request->input('quote_id'))->update(['status_id'=> 4]);
                }


                CrmTrackings::report($request, $workSchedule , 'schedule', 'create');
            DB::commit(); 
            return true;
        } catch (Exception $ex) {
            self::$error = $ex->getMessage();
            self::$trace =  $ex->getTrace();
            return false;
        }
        
    }

    private static function saveService(Request $requestService, Request $request, $workSchedule, &$date, $weekCount, $monthDistance){
         //guardar un nuevo servicio

         //verificar si existe ya un servicio creado para
         //cliente > sucursal > servicio > semana asignada (assigned week) > día asignado (start_day) > numero de servicio (num)

        $serviceObject = CrmSchedule::where('workschedule_id',$workSchedule->id)
                    ->where('crm_id',$request->input('crm_id'))
                    ->where('office_id', $request->input('office_id'))
                    ->where('service_id', $requestService->input('service_id'))
                    ->where('assigned_week', $weekCount)
                    ->where('start_day', $requestService->input('start_day'));

        if($requestService->filled('num')){
            $serviceObject->where(function($ww) use($requestService){

                //aqui se valida si el request recibe numero
                //si num es 1 pero en la bd esta null, se asume que ese null == 1
                //ya que num es un campo que se introdujo en estabilizacion
                //y los cronogramas viejos no tienen nada seteado en ese campo
                //y por defecto el valor debe ser 1

                $ww->where('num',$requestService->input('num'));
                if($requestService->input('num') == '1'){
                    $ww->orWhereNull('num');
                }
            });
            
        }else{

            //si no se recibe num por el request se asume que es un
            //servicio viejo que posee valor null
            $serviceObject->whereNull('num');
        }

        $serviceObject->where(function($w) use($date){
            $w->where('original_date', $date->format('Y-m-d'))
                ->orWhere('date', $date->format('Y-m-d'));
        });

        $service = $serviceObject->get()->last();   
        $serviceUpdateDate = $request->filled('from_submit') ?  Carbon::createFromFormat('d/m/Y',$request->input('from_submit'))->startOfDay(): Carbon::now()->startOfDay();
        
        
        //validar si la accion a ejecutar es una edición de origen (soft)
        if(!is_null($service) && $request->input('action') == 'soft'){

            $estado = in_array($service->status_id, [2,3,7]) ? 
            $service->status_id : ($requestService->filled('init_status') ? $requestService->input('init_status') : ($requestService->filled('keep_config') && in_array('status',$requestService->input('keep_config')) ? $requestService->input('config.status') : CrmScheduleStatus::where('slug','init')->first()->id ));

            //si el estado es distinto a finalizado, cancelado, no realizado
            if( !in_array($service->status->slug, ['service-success', 'route','invoiced'])){
                

                // a partir de aqui se usa esta validción: $date->gte($serviceUpdateDate)
                // para controlar que las ediciones de origen solo afecten a servicios de la fecha
                // de edicion ($request->input('from')) en adelante y no modificar los servicios
                // de esa fecha hacia atrás

                    // modificar valores pero apartir de la fecha de modicación
                    if(Carbon::parse($date)->startOfDay()->gte($serviceUpdateDate)){
                        $service->update(
                            [
                                "coverage_id"       => $requestService->input('detail_service_id'),
                                "coverage_text"     => DetailService::find($requestService->input('detail_service_id'))->zone,
                                "price"             => $requestService->filled('price')  ? self::clear_format($requestService->input('price')) : 0, 
                                "periodicity_id"    => $requestService->input('periodicity_id'),
                                "periodicity_start" => Carbon::createFromFormat('d/m/Y',$requestService->input('start_date')),
                                "periodicity_end"   => Carbon::createFromFormat('d/m/Y',$requestService->input('end_date')),
                                "week_start"        => Carbon::createFromFormat('d/m/Y',$requestService->input('start_week')),
                                "visits"            => $requestService->input('visits'),
                                "weeks"             => $requestService->input('weeks'),
                                "week_data"         => $requestService->input('week'),
                                "time_value"        => $requestService->input('time_value'),
                                "time_unit_id"      => $requestService->input('time_unit'),
                                "quote_id"          => $request->input('quote_id'),
                                "crm_id"            => $request->input('crm_id'),
                                "office_id"         => $request->input('office_id'),
                                "enable_fix_weeks"  => $requestService->filled('enable_fix_weeks'),
                                "use_fifth_month_week" => $requestService->filled('use_fifth_month_week'),
                                "num"               => $requestService->filled('num') ? $requestService->input('num'): 1,
                                "enabled"           => 1,
                                "start_day"         => $requestService->input('start_day'),
                                "assigned_week"     => $weekCount,
                                "start_hour"        => $requestService->filled('start_hour') ? $requestService->input('start_hour') : ($requestService->filled('keep_config') && in_array('time',$requestService->input('keep_config')) ? $requestService->input('config.time') : null ),
                                "technical_quantity"=> $requestService->filled('technical_quantity') ? $requestService->input('technical_quantity') : ($requestService->filled('keep_config') && in_array('quantity',$requestService->input('keep_config')) ? $requestService->input('config.quantity') : 1),
                                "technicals"        => $requestService->filled('user') ? $requestService->input('user') : ($requestService->filled('keep_config') && in_array('technicals',$requestService->input('keep_config')) ? $requestService->input('config.technicals') : null),
                                "observation"       => ($requestService->input('periodicity_id') != 13 && ($requestService->input('start_date') != $requestService->input('end_date')) && ($requestService->input('end_date') != $requestService->input('start_week'))) ? $requestService->input('week.'.$weekCount.'.observations'): null,
                                "status_id"         => $estado
                                ]);

                            //cambiar el estado solo si está establecido en el request
                            //ya que esto se realiza si el campo estado incial es llenado

                            if($requestService->filled('init_status')){
                                $service->update(["status_id" => $requestService->input('init_status')]);
                            }else if($requestService->filled('keep_config') && in_array('status',$requestService->input('keep_config'))){
                                $requestService->input('config.status');
                            }


                            //sincronizar controles
                            self::syncControls($requestService, $service, $date, $weekCount, $workSchedule, $monthDistance);

                            //añadir a una ruta exitente (si aplica)
                            if($requestService->filled('routes')){
                                self::addToRoute($service, $requestService);
                            }
                    }
            }else {

                //si el servicio se encuentra en uno de los estados "NO EDITABLES"
                // no se toca nada dado que son servicios que ya fueron o estan siendo
                // ejecutados por los técnicos, en este caso, el área de programación
                //debe editar individualmente el servicio y no el origen,
                // se establece enabled = 1 para que este servicio no sea borrado
                //por la funcion de mas abajo que elimina servicios con enabled = 0
                if(Carbon::parse($date)->startOfDay()->gte($serviceUpdateDate)){
                    $service->update([
                            "periodicity_start" => Carbon::createFromFormat('d/m/Y',$requestService->input('start_date')),
                            "periodicity_end"   => Carbon::createFromFormat('d/m/Y',$requestService->input('end_date')),
                            "week_start"        => Carbon::createFromFormat('d/m/Y',$requestService->input('start_week')),
                            "visits"            => $requestService->input('visits'),
                            "weeks"             => $requestService->input('weeks'),
                            "week_data"         => $requestService->input('week'),
                            "start_day"         => $requestService->input('start_day'),
                            "quote_id"          => $request->input('quote_id'),
                            "crm_id"            => $request->input('crm_id'),
                            "office_id"         => $request->input('office_id'),
                            "enable_fix_weeks"  => $requestService->filled('enable_fix_weeks'),
                            "use_fifth_month_week" => $requestService->filled('use_fifth_month_week'),
                            "num" => $requestService->filled('num') ? $requestService->input('num'): 1,
                            "enabled"           => 1,
                            "observation"       => ($requestService->input('periodicity_id') != 13 && ($requestService->input('start_date') != $requestService->input('end_date')) && ($requestService->input('end_date') != $requestService->input('start_week'))) ? $requestService->input('week.'.$weekCount.'.observations'): null,
                            "status_id"         => $estado 
                    ]);  
                     
                    
                    
                    
                }
            }
       
        }else{

            //si al actualizar (con soft) no se encuentran coincidencias de un servicio en bd
            //se procede a crear uno nuevo ya que se asume que la config del origen del cronogramas
            //fue modificada para agregar mas Servicios

            //o si bien es la creación de un cronograma, reestablecimiento total (force)
            if($date->gte($serviceUpdateDate) ||  $request->input('action') == 'force'){
                $service = $workSchedule->services()->create(
                [
                    "workschedule_id"   => $workSchedule->id,
                    "service_id"        => $requestService->input('service_id'),
                    "coverage_id"       => $requestService->input('detail_service_id'),
                    "coverage_text"     => DetailService::find($requestService->input('detail_service_id'))->zone,
                    "price"             => $requestService->filled('price')  ? self::clear_format($requestService->input('price')) : 0, 
                    "periodicity_id"    => $requestService->input('periodicity_id'),
                    "periodicity_start" => Carbon::createFromFormat('d/m/Y',$requestService->input('start_date')),
                    "periodicity_end"   => Carbon::createFromFormat('d/m/Y',$requestService->input('end_date')),
                    "week_start"        => Carbon::createFromFormat('d/m/Y',$requestService->input('start_week')),
                    "visits"            => $requestService->input('visits'),
                    "weeks"             => $requestService->input('weeks'),
                    "week_data"         => $requestService->input('week'),
                    "date"              => $date->format('Y-m-d'),
                    "original_date"     => $date->format('Y-m-d'),
                    "time_value"        => $requestService->input('time_value'),
                    "time_unit_id"      => $requestService->input('time_unit'),
                    "start_day"         => $requestService->input('start_day'),
                    "quote_id"          => $request->input('quote_id'),
                    "crm_id"            => $request->input('crm_id'),
                    "office_id"         => $request->input('office_id'),
                    "assigned_week"     => $weekCount,
                    "observation"       => ($requestService->input('periodicity_id') != 13 && ($requestService->input('start_date') != $requestService->input('end_date') && ($requestService->input('end_date') != $requestService->input('start_week')))) ? $requestService->input('week.'.$weekCount.'.observations'): null,
                    "enable_fix_weeks"  => $requestService->filled('enable_fix_weeks'),
                    "use_fifth_month_week" => $requestService->filled('use_fifth_month_week'),
                    "num"               => $requestService->filled('num') ? $requestService->input('num'): 1,
                    "start_hour"        => $requestService->filled('start_hour') ? $requestService->input('start_hour') : ($requestService->filled('keep_config') && in_array('time',$requestService->input('keep_config')) ? $requestService->input('config.time') : null ),
                    "status_id"         => $requestService->filled('init_status') ? $requestService->input('init_status') : ($requestService->filled('keep_config') && in_array('status',$requestService->input('keep_config')) ? $requestService->input('config.status') : CrmScheduleStatus::where('slug','init')->first()->id ),
                    "technical_quantity"=> $requestService->filled('technical_quantity') ? $requestService->input('technical_quantity') : ($requestService->filled('keep_config') && in_array('quantity',$requestService->input('keep_config')) ? $requestService->input('config.quantity') : 1),
                    "technicals"        => $requestService->filled('user') ? $requestService->input('user') : ($requestService->filled('keep_config') && in_array('technicals',$requestService->input('keep_config')) ? $requestService->input('config.technicals') : null),
                    "enabled"           => 1
                ]);

                //nota interna si aplica (servicio exporadico)
                self::saveObservationFromSchedules($requestService, $service);
            
                //sincronizar controles
                self::syncControls($requestService, $service, $date, $weekCount, $workSchedule, $monthDistance);

                //añadir a una ruta exitente (si aplica)
                if($requestService->filled('routes')){
                    self::addToRoute($service, $requestService);
                }
            }

        }

        
        
    }

    private static function addToRoute(CrmSchedule $service, Request $request){
        $schedule = new Scheduling();
        $title = "{$service->service->name} {$service->crm->name} en sucursal {$service->office->name}";
        
        try {
            DB::beginTransaction();

            $schedule->name = $title;
            $schedule->scheduling_type_id = 1;
            $schedule->enabled = 1;
            $capture_date = Carbon::createFromFormat('d/m/Y H:i:s',$request->input('start_week'). ' ' . $request->input('start_hour').':00');
            $schedule->automation_init_date = $capture_date->format('Y-m-d H:i:s');
            

            $schedule->crm_id       = $service->crm->id;
            $schedule->office_id    = $service->office->id;
            $schedule->contact_id   = null;
            $schedule->crm_address  = $service->office->address;
            $schedule->crm_lat      = $service->office->lat;
            $schedule->crm_lng      = $service->office->lng;
            $schedule->start        = $capture_date->format('Y-m-d H:i:s');
            $schedule->crm_schedule_id   = $service->id;

            //definir la description
            $description = "";
            $intNotes = CrmScheduleObservation::where('crm_schedule_id', $service->id)->where('private', 0)->orderBy('created_at', 'desc')->get();
            if(!is_null($intNotes) && count($intNotes) > 0){

                $description = "[NOTAS INTERNAS]: ";
                foreach($intNotes as $note){
                    $description .=  $note->observation;
                    $description .=  $note->feedback ? ' [FED]: ' . $note->feedback . ' // ' : ' // ';
                }
            }
            if(!is_null($intNotes) && $service->observation && count($intNotes) > 0){
                $description.= "&#13;&#10;&#13;&#10;";
            }

            if($service->observation){
                $description .= "[NOTA DEL CRONOGRAMA]: {$service->observation}";
            }

            $schedule->description   = $description;

            switch($service->status_id){
                case 1:
                case 2:
                case 6:
                    $schedule->completed = 0;
                    break;
                case 3:
                    $schedule->completed = 1;
                    break;
                case 4:
                    $schedule->completed = 3;
                    break;
                    case 5:
                $schedule->completed = 2;
                break;
            }

            if($request->input('time_unit') == '1'){
                $schedule->end          = $capture_date->addMinutes($request->input('time_value'))->format('Y-m-d H:i:s');
            }else if($request->input('time_unit') == '2'){
                $schedule->end          = $capture_date->addHours($request->input('time_value'))->format('Y-m-d H:i:s');
            }
            
            $schedule->headquarter_id = session('headquarter_id');
            $schedule->save();
            $schedule->users()->sync($request->input('user'));

            //añadir a la hoja de ruta del técnico
                //obtener la automatización de la ruta
                foreach($request->input('routes') as $routeid ){
                    
                    $route = Route::find($routeid);
                    self::addToRoutePDF($route, $schedule);
                }
            

            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            self::$error .= '. '.$exc->getMessage();
            self::$trace = $exc->getTrace();
            return false;
        }





    }


    private static function addToRoutePDF($riginalroute = null, Scheduling $scheduling)
    {
        $success =  false;
        foreach ($scheduling->users as $user) {
                
            if ($riginalroute) {

                //eliminar el pdf existente
                $riginalroute->schedulings()->syncWithOutDetaching($scheduling->id);
                 $filename = "r-".Carbon::parse($riginalroute->date)->format('dmY') .'-'. $riginalroute->user->name;
                 $path = "routes/" . $filename. ".pdf";
                 $file = "storage/app/public/{$path}";
                 Storage::disk('public')->delete('file');

                //volver a generar el PDF
                self::remakePdf($riginalroute);

                $success = true;
            }
        }

        return $success;
    }

    private static function remakePdf($route){
        //guardar el archivo pdf de la ruta para poder mandarlo en cola
        $nroute = Route::
        with(
            'schedulings.users',
            'schedulings.crm',
            'schedulings.crmSchedule.service',
            'schedulings.crmSchedule.controls.infestation',
            'schedulings.crmSchedule.controls.product',
            'schedulings.crmSchedule.controls.area',
            'schedulings.crmSchedule.controls.dose',
            'schedulings.crmSchedule.controls.application',
            )
         ->find($route->id);

        //almacenar archivo en físico
        $route = $nroute;
        $subject =  "Reporte de ruta ".Date::parse($nroute->date)->format('l')."(". Carbon::parse($nroute->date)->format('d-m-Y') . ") - ". $nroute->user->name;
        $view = view('scheduling.sub.routePdf',compact(['route','subject']))->render();
        $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');

        $nfilename = "r-".Carbon::parse($nroute->date)->format('dmY') .'-'. $nroute->user->name;
        $npath = "routes/" . $nfilename . ".pdf";

        
        
        Storage::disk('public')->put($npath, $pdf->output());
    }

    private static function checkMonthForFullVisits(Request $request, $visits, $data, $index){

        if(isset($data[$index])){

            $visits  = Collection::times($visits)->implode('');
            $data    = Collection::make($data[$index])->implode('');

            if (strpos($data, $visits) !== false){
                return true;
            } 
            else {
                return false;
            }
        }else{
            return false;
        }
        
    } 

    private static function syncControls(Request $requestService, CrmSchedule $service, Carbon &$date, $j, $workSchedule, $monthDistance){

        //obtener la configuración de la semana a guardar
        $visitPerControl = 0;
            $weekConfig = $requestService->input('week')[$j];
            if(isset($weekConfig['control'])){
                foreach ($weekConfig['control'] as $key => $control) {
                    
                    $control = new Request($control);
    
                    //verificar si el area es nueva o ya existe
                    $area = null;
                    if($control->input('area')){
                        if(is_numeric($control->input('area'))){
                            $area = CrmArea::find($control->input('area'));
                            if(!$area){
                                $area = CrmArea::firstOrCreate(['name' => $control->input('area'), 'enabled' => 1]);
                            }
                        }else{
                            $area = CrmArea::firstOrCreate(['name' => $control->input('area'), 'enabled' => 1]);
                        }
                    }
    
    
                    //verificar si la aplicacion es nueva o ya existe
                    $application = null;
                    if($control->filled('application')){
                        if(is_numeric($control->input('application'))){
                            $application = Application::find($control->input('application'));
                            if(!$application){
                                $application = Application::firstOrCreate(['name' => $control->input('application'), 'enabled' => 1]);
                            }
                        }else{
                            $application = Application::firstOrCreate(['name' => $control->input('application'), 'enabled' => 1]);
                        }
                    }
    
                    
                    //verificar from - to (rango visitas ingrediente activo)
                    if($control->filled('product_from') && $control->filled('product_to') ){
    
                        //$controltmp = $service->controls()->where('infestation_id', $control->input('infestation'))->get();
                        $controltmp = CrmSchedule::whereHas('controls', function($controls) use($control){
                             $controls->where('infestation_id',$control->input('infestation'))
                             ->where('application_id', $control->input('application'))
                            //->where('product_id',$control->input('product'))
                            // ->where('dose_id', $control->input('dose'))
                            ->where('crm_area_id', $control->input('area'));
                        })
                        ->where('service_id',$requestService->input('service_id'))
                        ->where('assigned_week',$j)
                        ->where('start_day',$requestService->start_day)
                        ->where('enabled', 1)
                        ->where('num', $requestService->input('num')) // se añade esto para separar rotacion de ingredientes (SOPORTE KAREN DURAN)
                        ->where('workschedule_id',$workSchedule->id)
                        ->whereDate('date', '>=', Carbon::createFromFormat('d/m/Y',$requestService->input('start_date')))
                        ->whereDate('date', '<=', Carbon::createFromFormat('d/m/Y',$requestService->input('end_date')))
                        ->groupBy('date')
                        ->get();
        
                        $visitPerControl = $controltmp->count() +1;
                     
                        //dump($visitPerControl);
                        $lastPerControl  = $controltmp->last();

                        
                        if(isset($lastPerControl->original_date) && Carbon::parse($lastPerControl->original_date)->startOfDay()->equalTo($date->startOfDay())){
                           
                            //  dump('no fecha!!! --- ' . $visitPerControl . ' -- '.$control->input('product_from'). ' --- '. $control->input('product_to'). ' --- '. \App\Product::find($control->input('product'))->name. '---'.$lastPerControl->original_date.'---'.$date->startOfDay()->format('Y-m-d'));
                            continue;
                        }else{
                            
                        
                        if( !($visitPerControl >= $control->input('product_from')  && $visitPerControl <= $control->input('product_to'))){
                            //  dump('desbordado!!! --- ' . $visitPerControl . ' -- '.$control->input('product_from'). ' --- '. $control->input('product_to'). ' --- '. \App\Product::find($control->input('product'))->name);
                            
                            continue;
                        }

                        //  dump($visitPerControl . ' --- '. $control->input('product_from'). ' --- '. $control->input('product_to'). ' --- '. \App\Product::find($control->input('product'))->name);
                    }
                          
                        
                    }
    
                        //consultar si ya existen el control
                        $controlSave =  CrmScheduleControl::updateOrCreate(
                            [
                                "crm_schedule_id" => $service->id,
                                "infestation_id" => $control->input('infestation'),
                                "crm_area_id"    => $area->id,
                                "application_id" => $application->id,
                                "product_id"     => $control->input('product'),
     
                            ],
                            [
                                
                                "product_to"     => $control->input('product_to'),
                                "product_from"   => $control->input('product_from'),
                                
                                "dose_id"        => $control->input('dose'),
                                "date"           => $date->format('Y-m-d'),
                                'status_id'      => null
                            ]
                        );       
                       
                }
            }else{
                //eliminar los controles
                $service->controls()->delete();
            }
        
        
    }

    private static function saveObservationFromSchedules(Request $request, CrmSchedule $crmSchedule)
    {
        
        $service=$request->all();
   
        if(($service['periodicity_id'] == "13") && ($service['start_date'] == $service['end_date']) && ($service['start_date'] == $service['start_week'])){
                $weeks=$service['week'];
                if(isset($weeks)){
                    $observation=null;
                    foreach ($weeks as $key_id => $week) {
                        $observation= isset($week['observations']) ? $week['observations']: null;
                    }

                    if(isset($observation)){
                        self::saveObservation($observation, $crmSchedule);
                    }
                }
            
            
        }
    }

    private static function saveObservation($observation, CrmSchedule $crmSchedule)
    {

        $crmSchedule->observations()->create(
            [
                "user_id"   => Auth::User()->id,
                "observation" => $observation
            ]
        );
   
    }


    private static function clear_format($string){
        //if($string){
        $str = str_replace('.', '', $string);
        return str_replace(',', '', $str);
        //}
    }

    private static function getTimeAdded(Carbon &$date, $days){
        $date->addDays(($days));
    }

    /**
     * Obtiene la cantidad de veces que se repetirán las visitas 
     * establecidas (perodicidad)
     */
    private static function getCountRange(Carbon $startDate, Carbon $endDate, $periodicity, $controlId){
        $periodicity =  Periodicity::find($periodicity);
        $diff = 0;
        switch ($periodicity->unit) {
            case 'month':
                $diff = $startDate->diffInMonths($endDate);
                break;
            case 'day':
                $diff = $startDate->diffInDays($endDate);
                break;
            case 'year':
                $diff = $startDate->diffInYears($endDate);
                break;
            default:
                break;
        }

        //distribuir la cantidad por la periodicidad
        try {
            $result = ceil($diff/$periodicity->value);
            if($result <= 0){
                $controlName = Infestation::find($controlId);
                self::$error =  "La posible fecha de ejecución del control {$controlName} está fuera del rango del periodo a programar, corrige estos datos en inténtalo nuevamente.";
                return false;
            }
            return $result;
        } catch (\Exception $th) {
            $controlName = Infestation::find($controlId);
            self::$error =  "El rango de fechas del periodo a programar del control {$controlName} no es válido para generar el cronograma. Tipo de error: ".$th->getMessage();
            return false;
        }
    }

    private static function getVistisAVG(Carbon $startDate, $visits, $periodicity, $controlId){
        
        $periodicity = Periodicity::find($periodicity);
        $endDate = null;
        switch ($periodicity->unit) {
            case 'month':
                $endDate = $startDate->copy()->addMonths(($periodicity->value));
                break;
            case 'day':
                $endDate = $startDate->copy()->addDays(($periodicity->value));
                break;
            case 'year':
                $endDate = $startDate->copy()->addYears($periodicity->value);
                break;
            default:
                break;
        }
        
        //obtener la cantidad de dias que contiene el periodo
        $days = $startDate->diffInDays($endDate);
        
        try {
            $result = ceil($days/$visits);
            if($result <= 0 ){
                $controlName = Infestation::find($controlId);
                self::$error =  "La cantidad de visitas del control {$controlName} no es admitida para generar el cronograma.";
                return [null,0, 0]; 
            }
            return [$endDate, $result, 1]; 
        } catch (\Exception $th) {
            $controlName = Infestation::find($controlId);
            self::$error =  "La cantidad de visitas del control {$controlName} no es admitida para generar el cronograma. Tipo de error: ".$th->getMessage();
            return [null,0, 0]; 
        }

    }

    private static function getWeeksOfPeriodicity(Carbon $date, Carbon $endDate, $periodicity_id){
        //obtener las semanas de la periodicidad seleccionada
        $periodicity = Periodicity::find($periodicity_id);
        if($periodicity){
            $weeksNumber = $date->diffInWeeks($endDate);
            for ($i=0; $i <  $weeksNumber; $i++) { 
                $carbonWeek  = $date;
                $startWeek =  $carbonWeek->copy()->startOfWeek(Carbon::MONDAY);
                $endWeek    = $carbonWeek->copy()->endOfWeek(Carbon::SUNDAY);
                $tempWeeks[$startWeek->month][] = [
                    "id"     => ($i+1), 
                    "uuid"   => uniqid(),
                    "text"   => "Semana ". ($i+1) ." (del ". $startWeek->format('d/m/Y') . ' al '. $endWeek->format('d/m/Y') . ')',
                    "week"   => ($i+1),
                    "start"  => $startWeek->format('d/m/Y'),
                    "end"    => $endWeek->format('d/m/Y'),
                    "parent" => $periodicity
                ];
            }
            $data = collect($tempWeeks);
    
        }else{
            $data = [];
        }
    
        return $data;
        
    }

    public static function makeReportService(Request $request, CrmSchedule $schedule, $send= false){

        $file = null;

        //validar si el archivo ya fue generado en físico 
        $path = self::buildSchedulePath($schedule);
        $issetFile = Storage::disk('public')->exists($path);
        if ($issetFile) {
            $file = Storage::disk('public')->path($path);
        } else {
            $view = view('crm.sub.scheduleReportPdf', compact(['schedule']))->render();
            $pdf = PDF::loadHtml($view)->setPaper('legal', 'portrait');
            Storage::disk('public')->put($path, $pdf->output());
        }

        //verificar si se debe hacer el envío de la ruta
        if($send){

            //notificar a todos los contactos de la sucursal
            $contacts = $schedule->office->contacts;
            if(!$contacts || $contacts->count() == 0){
                // abort('406', 'El cliente no tiene ningun contacto registrado para recibir reportes al correo, desmarca la opción "Enviar reporte al correo" para continuar');
                return true;
            }else{
                foreach ($contacts as $contact){

                    $hour = \Carbon\Carbon::now()->format('H'); 
                    $greeting = ' Buen Día';                                
                    if ($hour < 12) $greeting =  'Buen Dia';
                    if ($hour >= 12 && $hour < 18) $greeting =  'Buena Tarde';
                    if ($hour >= 18) $greeting =  'Buena Noche';  
    
                    //preparar la data
                    $data = (object)collect([
                        'schedule' => $schedule,
                        'contact' =>  $contact,
                        'greeting' => $greeting
                    ])->all();
    
                    //mandar correo
                    try {
                        if(!is_null($contact) && $contact && isset($contact->email)){
                            Mail::to($contact->email)->queue(new ReportService($data, $path));
                        }
                        return true;  
                    } catch (\Exception $ex) {
                        self::$error =  $ex->getMessage();
                        self::$trace =  $data;
                        return false;
                    }
    
                }
            }
            
        }else{
            return $path;
        }

    }

    private static function buildSchedulePath(CrmSchedule $schedule)
    {
        $date = Carbon::parse($schedule->date)->format('Y/m');
        $path = '/crms/schedules/' . $schedule->crm_id . '/' . $schedule->office_id . '/' . $schedule->id . '.pdf';
        return $path;
    }
}

