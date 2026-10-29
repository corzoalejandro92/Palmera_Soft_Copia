<?php

namespace App\Http\Controllers\Api;

use App\ControlInfestation;
use App\Convection;
use App\CrmSchedule;
use App\Services\InventoryService;
use Jenssegers\Date\Date;
use App\CrmScheduleStatus;
use App\FileExtend;
use App\FoxconDocument;
use App\Route;
use App\User;
use App\Scheduling;
use App\UserTracking;
use App\Qr;
use App\TechnicalReportType;
use App\TechnicalReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AnswersSurveys;
use App\InfestationGrade;
use App\Inventory;
use App\InventoryDetail;
use App\Office;
use App\Poll;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use App\General\CrmSchedules;
use App\InventoryExpense;
use App\Services\ExternalNoteService;
use App\Services\FoxconService;
use App\SharedMechasnims;
use App\TransferInventory;
use App\Warehouse;

class RouteController extends Controller
{
    private $startDateSlug = 1;
    private $endDateSlug = 2;
    private $startServiceSlug = 3;
    private $endServiceSlug = 4;

    public function allRoutes(Request $request){

        $data = Route::where('user_id', $this->getUserIDToken($request))->orderBy('date','desc')->limit(20)->get();
        return $this->success($data);
    }

    public function index(Request $request)
    {   
        try{
            ini_set('memory_limit', '-1');
            DB::beginTransaction();

                $temporal = Route::getRoute($this->getUserIDToken($request), $request->input('date'))->get();
                $route = null;
                $qrs = null;
                if(count($temporal)> 0){
                    $route = Route::getRoute($this->getUserIDToken($request),$request->input('date'))
                    ->find($temporal->last()->id);
                }else{
                    return $this->success([]);
                }

                if($route){
                    
                    //verificar si ya inició el día y sino registrar el inicio
                    $register = $this->_checkIfStartDay($request, $route);
                    
                    if((!$register || is_null($register)) && $route->status_id <= 2){
                        $this->saveStartDate($request,$route);
                    }

                    //sede del usuario que consulta la información
                    $headquarter_id = User::find($this->getUserIDToken($request))->headquarter_id;

                    //obtener los qr que no han sido escaneados 
                    $qrs =  QR::select('id','token')->where('use',1)->with(['extend.file'=>function($ef){
                        $ef->select('id','name','path','filable_id');
                    }])->whereHas('qrlot',function($q) use ($headquarter_id){
                        $q->where('headquarter_id', $headquarter_id);
                    })
                    ->whereNull('file_extend_id')
                    ->enabled()
                    ->get()
                    ->keyBy('token');

                    $offices_id = $route->schedulings->pluck('office_id')->values()->unique();
                

                    //data de los clientes por separado y unique id (Optimización de las rutas)
                    $offices = Office::with(
                        [
                            'areas.apiFiles.extends.extendible',
                            'areas.apiFiles.extends.qr',
                            'areas.apiFiles.extends.technicalReports',
                            'areas.apiFiles.extends.technicalReports.type',
                            'areas.apiFiles.extends.technicalReports.pictures',
                            'areas.apiFiles.extends.technicalReports.user' ,
                            //'areas.apiFiles.extends.technicalReports.crmSchedule.scheduling',
                            'areas.apiFiles.extends.technicalReports.pest',
                            'areas.apiFiles.extends.inventories',
                        ]
                    )->whereIn('id', $offices_id)->withTrashed()->get()->keyBy('id');


                    $offices = $offices->map(function($office) use($headquarter_id){
                         $office->areas->map(function($area) use($headquarter_id){
                             $area->apiFiles->map(function($apiFile) use($headquarter_id){
                                 $apiFile->extends->map(function($extend) use($headquarter_id){

    
                                    $extend->setRelation('technicalReports', $extend->technicalReports->sortByDesc('created_at')->take(3));
                                    $extend->technicalReports->map(function($report){
                                        if(isset($report->user)){
                                            $report->user = $report->user->setVisible(['id','name','warehouse']);
                                        }
                                         
                                         return $report;
                                    });

                                    

                                   
                                    // if($extend->qr && count($extend->inventories) <= 0){
                                    //     //empalmar los mecanismos prestados que tienen qr pero que no estan en la bodega de M.PRESTADOS
                                        
                                    //     foreach ($extend->extendible->products as $key => $product) {
                                            
                                    //         try {
                                    //             DB::beginTransaction();
                                    //             $inventory = new Inventory();
                                    //             $inventory->headquarter_id = $headquarter_id;
                                    //             $inventory->warehouse_id = Warehouse::whereHasMorph('warehousable',[SharedMechasnims::class], function($q) use($headquarter_id){$q->where('headquarter_id',$headquarter_id);})->first()->id;
                                    //             $inventory->product_id =  $product->id;
                                    //             $inventory->stock =  $product->pivot->quantity;
                                    //             $inventory->file_extend_id =  $extend->id;
                                    //             $inventory->save();

                                    //             $inventory->detailInventory()->create([
                                    //                 "warehouse_id" =>$inventory->warehouse_id,
                                    //                 "product_id" =>$inventory->product_id,
                                    //                 "code" => "NA",
                                    //                 "plot" => "NA",
                                    //                 "date_expiration" => Carbon::now()->format('Y-m-s H:i:s'),
                                    //                 "stock" => $inventory->stock
                                    //             ]);

                                    //             $inventory->product = Product::select(['id', 'name'])->find($inventory->product_id);
                                    //             $extend->inventories = $extend->inventories->push($inventory);

                                    //             DB::commit();
                                    //         } catch (\Exception $th) {
                                    //             DB::rollBack();
                                    //             abort(500, $th->getMessage());
                                    //         }  
                                            
                                    //     }
                                       
                                    // }

                                    return $extend;
                                });
                                return $apiFile;
                            });
                            return $area;
                        });
                        return $office;
                    })->keyBy('id');
                    
                    


                    //obtener todos los productos del sistema
                    $products = Product::select(['id','avatar', 'name'])->where('enabled',true)->get();

                    //data del inventario de los técnicos por separado
                    $users_id = $route->schedulings->pluck('users')->flatten()->pluck('id')->values();
                    $users = User::with(['warehouse.inventory.detailInventories'=> function($q){
                        $q->with('product')->where('facturable',false)->whereHas('product', function($product){
                            $product->whereNotIn('category_id',[4,5]);
                        });
                    }])->whereIn('id',$users_id)->get()->unique('id')->keyBy('id');


                    $users = $users->map(function($user){
                        if(isset($user->warehouse->inventory)){
                            $user->warehouse->inventory =  $user->warehouse->inventory->map(function($inventory) {
                                $inventory->stock =  $inventory->detailInventories->sum('stock');
                                return $inventory;
                            });

                        }

                        return $user;
                    });

                    
                    if(
                
                        \App\Integration::find(1)->enabled && 
                        in_array(
                            $headquarter_id , \App\Integration::find(1)->headquarters
                        )
                    
                    ){
                        $officeWarehouses = Office::with(['warehouse.inventory.detailInventories'=> function($q){
                            $q->with('product')->where('facturable',false)->whereHas('product', function($product){
                                $product->whereNotIn('category_id',[4,5]);
                            });
                        }])->whereIn('id', $offices_id)->get()->unique('id')->keyBy('id');
    
                        $officeWarehouses = $officeWarehouses->map(function($user){
                            if(isset($user->warehouse->inventory)){
                                $user->warehouse->inventory =  $user->warehouse->inventory->map(function($inventory){
                                    $inventory->stock =  $inventory->detailInventories->sum('stock');
                                    return $inventory;
                                });
                            }
                            
                            return $user;
                        });

                        $users = $users->union($officeWarehouses);

                    }


                    //opciones disponibles para los mecanismos
                    $mechanismOptions = TechnicalReportType::with('childs')->where('slug', 'like', 'mechanism%')->get();

                    //opciones de Plagas
                    $infestations = ControlInfestation::with(['infestation'=> function($i){
                        $i->select('id','name');
                    }])->get();
                    
            
                    $newInfestations = $infestations->map(function($i){
                        if($i != null){
                        $i->makeHidden('base64');
                            if( $i->infestation != null){
                                $i->infestation->makeHidden('full_avatar');
                                $i->infestation->makeHidden('base64');
                            }
                        }
                        return $i;
                    });

                    //inventario del técnico
                    $wid = User::find($this->getUserIDToken($request))->warehouse->id;

                    $inventory = Inventory::with(['detailInventories' => function($q){
                     
                            $q->with('product')->where('facturable',false);
                        
                    }])
                    ->where('warehouse_id', $wid )
                    
                    ->orderBy('id', 'desc')
                    ->get();
                    
                    //return $this->success($inventory->toArray());
                    $inventory = $inventory->map(function($item){
                       
                        $item->detail_inventories = $item->detailInventories->filter(function($subitem){
                            return $subitem->facturable == 0;
                        });
                        
                        $item->stock = $item->detail_inventories->sum('stock');

                        return $item;
                    });

                    //cuestionario nacional de servcion
                    $poll = Poll::with('questions.answers')->where('enabled', 1)->get();
                    if(!is_null($poll->last())){
                        $poll = $poll->last();
                    }


                    
                    //convecciones (mecanismos)
                    $mechanisms = Convection::all();

                    $data = [
                        'route'             => $route ? $route->toArray(): [] , 
                        'offices'           => $offices->toArray(), 
                        'users'             => $users->each->setVisible(['id','name','warehouse']),
                        'qrs'               => $qrs ? $qrs->toArray(): [], 
                        'mechanismOptions'  => $mechanismOptions->toArray(), 
                        'infestations'      => $newInfestations->toArray(),
                        //'inventory'         => $inventory->toArray(), 
                        'poll'              => $poll->toArray(),
                        'products'          => $products->toArray(),
                        'mechanisms'        => $mechanisms->toArray(),
                    ];
                        
                }else{
                    return $this->error("Esta ruta ya no se encuentra disponible");
                }

            DB::commit();
            return $this->success($data);
        }catch(Exception $ex){
            DB::rollback();
            return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace()]);
        }
        
        
    }

    public function qrs(Request $request){
        $headquarter_id = User::find($this->getUserIDToken($request))->headquarter_id;

        $qrs =  QR::select('id','token')->where('use',1)->with(['extend.file'=>function($ef){
            $ef->select('id','name','path','filable_id');
        }])->whereHas('qrlot',function($q) use ($headquarter_id){
            $q->where('headquarter_id', $headquarter_id);
        })
        ->whereNull('file_extend_id')
        ->enabled()
        ->get()
        ->keyBy('token');

        return $this->success($qrs ? $qrs->toArray(): [] );
    }

    private function setCoordsHeader(Request $request){
        $user= User::find($this->getUserIDToken($request));
        $coords = $user->coords()->create([
            'lat'  => $request->header('lat'),
            'lng'  => $request->header('lng'),
            'date' => Carbon::now()->format('Y-m-d h:i:s'),
        ]);

        return $coords;
    }

    private function setCoords(Request $request, $_coords){
        $user= User::find($this->getUserIDToken($request));


        if(!is_null($_coords)){
            $coords = $user->coords()->create([
                'lat'  => $_coords['latitude'],
                'lng'  => $_coords['longitude'],
                'date' => Carbon::now()->format('Y-m-d h:i:s'),
            ]);
        }else{
            $coords = $user->coords()->create([
                'lat'  => null,
                'lng'  => null,
                'date' => Carbon::now()->format('Y-m-d h:i:s'),
            ]);
        }
        

        return $coords;
    }

    public function saveStartDate(Request $request, $route){

        //actualizar  el estado de la ruta
        $route->status_id = 2;
        $route->save();
        
        $route->userTracking()->create([
            'user_id'               => $this->getUserIDToken($request),
            'user_coord_id'         => $this->setCoordsHeader($request)->id,
            'user_tracking_type'    => $this->startDateSlug,
            'observation'           => "Inicio de labores del técnico",
            'date' => Carbon::now()->format('Y-m-d h:i:s'),
        ]);
    }

    public function saveEndDate(Request $request){
        try {
            ini_set('memory_limit', '-1');
            DB::beginTransaction();
                $route = Route::getRoute($this->getUserIDToken($request),$request->input('date'))->get()->last();
                //actualizar  el estado de la ruta
                $route->status_id = 3;
                $route->save();
                
                $route->userTracking()->create([
                    'user_id'               => $this->getUserIDToken($request),
                    'user_coord_id'         => $this->setCoordsHeader($request)->id,
                    'user_tracking_type'    => $this->endDateSlug,
                    'observation'           => "Finalización de labores del técnico",
                    'date' => Carbon::now()->format('Y-m-d h:i:s'),
                ]);
            DB::commit();
            return $this->success(true);
        } catch (\Exception $th) {
            DB::rollBack();
            return $this->error($th->getMessage(),200,["trace" => $th->getTrace()]);
        }
        
    }

    public function checkIfStartDay(Request $request, Route $route){

        $success = UserTracking::checkDay($this->getUserIDToken($request),$this->startDateSlug)->get()->count();
        return $this->success($success > 0 ? true : false);
    }

    public function _checkIfStartDay(Request $request, Route $route){

        $success = UserTracking::checkDay($this->getUserIDToken($request),$this->startDateSlug)->get()->count();
        return $success > 0 ? true : false;
    }


    //guardar información de inicialización del servicio
    public function startScheduling(Request $request, Scheduling $scheduling){
        
        if($request->filled('start')){

            $success = false;

            if(!is_null($request->input('id'))){
                $crmSchedule = CrmSchedule::find($request->input('id'));
                $success = $crmSchedule->userTrackings()->updateOrCreate(
                
                    //1.- registrar el inicio del servicio
                    [
                        'user_id'               => $this->getUserIDToken($request),
                        'user_tracking_type'    => $this->startServiceSlug,
                    ],
                    [
                        'user_coord_id'         => $this->setCoords($request, $request->input('start_coords'))->id,
                        'observation'           => "Inicio del servicio",
                        'date'                  => Carbon::parse($request->input('start'))->format('Y-m-d H:i:s'),
                    ]
                ); 
            }else{
                $sheduling = Scheduling::find($request->input('s_id'));
                $success = $sheduling->userTrackings()->updateOrCreate(
                
                    //1.- registrar el inicio del servicio
                    [
                        'user_id'               => $this->getUserIDToken($request),
                        'user_tracking_type'    => $this->startServiceSlug,
                    ],
                    [
                        'user_coord_id'         => $this->setCoords($request, $request->input('start_coords'))->id,
                        'observation'           => "Inicio de la actividad",
                        'date'                  => Carbon::parse($request->input('start'))->format('Y-m-d H:i:s'),
                    ]
                ); 
            }
            

            if($success){
                return $this->success($success);
            }else{
                return $this->error($success);
            }
            


        }else{
            abort('406', 'No se puede iniciar el servicio porque no se especificó una fecha');
        }
        
    }


    //guardar información de finalizacion del servicio
    public function endScheduling(Request $request, Scheduling $scheduling){
        try {
            ini_set('memory_limit', '-1');
            DB::beginTransaction();
                if($request->filled('date')){
                
                    if($scheduling->crm_schedule_id){
                        $case = 'CrmSchedule';

                        //verificar si la actividad aun existe
                        $tmp = CrmSchedule::find($scheduling->crm_schedule_id);
                        if(!is_null($tmp)){
                            $activity = CrmSchedule::find($scheduling->crm_schedule_id);
                        }else{
                            $case = 'Scheduling';
                            $activity = Scheduling::find($scheduling->id);
                        }

                        
                    }else{
                        $case = 'Scheduling';
                        $activity = Scheduling::find($scheduling->id);
                    }
                    
                    $success = $activity->userTrackings()->updateOrCreate(
                    
                        //1.- registrar el fin del servicio
                        [
                            'user_id'               => $this->getUserIDToken($request),
                            'user_tracking_type'    => $request->input('user_tracking_type'),
                        ],
                        [
                            'user_coord_id'         => $this->setCoords($request, $request->input('coords'))->id,
                            'observation'           => $request->filled('observation') ? $request->input('observation') : 'Fin del servicio',
                            'observation_receives'  => $request->filled('observation_receives') ? $request->input('observation_receives') : null,
                            'cc_receives'           => $request->filled('cc_receives') ? $request->input('cc_receives') : null, 
                            'name_receives'         => $request->filled('name_receives') ? $request->input('name_receives') : null, 
                            'date'                  => Carbon::parse($request->input('date'))->format('Y-m-d H:i:s'),
                            'file'                  => $request->filled('file') ? $request->input('file') : null
                        ]
                    ); 
        
                    //cambiar el estado de las actividades
                    $this->manageStatus($scheduling, $request->input('status'));

                    //verificar si viene las respuesta de las encuestas
                    if($request->filled('answers') && $request->input('answers') != false  && $request->filled('poll')){

                        //guaradar las Respuestas
                        $crmsch     = CrmSchedule::find($scheduling->crm_schedule_id);

                        //guardar la cabecera de la encuesta
                        $answerPoll = AnswersSurveys::create([
                            "headquarter_id" => User::find($this->getUserIDToken($request))->headquarter_id,
                            "office_id"      => $crmsch->office_id,
                            "crm_schedule_id" => $crmsch->id,
                            "polls_id" => $request->filled('poll'),
                            "user_id" => $this->getUserIDToken($request),
                         ]);

                         //guardar el cuerpo de la respuestas
                         foreach($request->input('answers') as $answer)
                         $answerPoll->detailsAnswer()->updateOrCreate([
                             "question_id" => $answer['question']
                         ],[
                             "answer_id" => $answer['answer']
                         ]);


                    }
                    
                    //generar el archivo del reporte
                    if($activity instanceof CrmSchedule){
                        //verificar si se debe hacer el envío de la ruta
                        $send = false;
                        if($request->filled('send_to_email') && $request->input('send_to_email') == 1){
                            $send = true; 
                        }
                        $response = CrmSchedules::makeReportService($request, $activity, $send);
                    
                        if($response){
                            DB::commit();
                            return $this->success($success);
                        }else{
                            DB::rollBack();
                            return $this->error('No se pudo guardar el reporte: '. CrmSchedules::$error ,500, 
                            [
                                "response" =>$success, 
                                "trace" => CrmSchedules::$trace]);
                        }
                    }else{
                        DB::commit();
                        return $this->success($success);
                    }
                    

                }else{
                    abort('406', 'No se puede cambiar el  estado del servicio porque no se especificó una fecha al seguimiento');
                }
          
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);

        }
        
        
        
    }

    public function backScheduling(Request $request, Scheduling $scheduling){
        
       
        try {
            DB::beginTransaction();
            $this->manageStatus($scheduling, 0);

            //poner la ruta de nuevo en pendiente

            $route = Route::getRoute($this->getUserIDToken($request),$request->input('date'))->get()->last();
            //actualizar  el estado de la ruta
            $route->status_id = 2;
            $route->save();
            DB::commit();
            return $this->success(true);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);

        }
    }
    

    private function manageStatus(Scheduling $scheduling, $status){
        switch($status){

            //servicio pendiente
            case '0':
                $scheduling->completed = 0;
                $scheduling->save();

                if($scheduling->crmSchedule){
                    CrmSchedule::find($scheduling->crm_schedule_id)->update(['status_id' => 2]);
                }
                break;
            //servicio finalizado correctamente
            case 'service-success':
                $scheduling->completed = 1;
                $scheduling->save();

                if($scheduling->crmSchedule){
                    CrmSchedule::find($scheduling->crm_schedule_id)->update(['status_id' => 3]);
                }
                break;
            //servicio cancelado
            case 'cancelled':
                $scheduling->completed = 2;
                $scheduling->save();

                if($scheduling->crmSchedule){
                    CrmSchedule::find($scheduling->crm_schedule_id)->update(['status_id' => 5]);
                }
                break;
            //servicio cancelado
            case 'service-fail':
                $scheduling->completed = 3;
                $scheduling->save();

                if($scheduling->crmSchedule){
                    CrmSchedule::find($scheduling->crm_schedule_id)->update(['status_id' => 4]);
                }
                break;
        }
    }

    public function showPdf(Request $request, $id){
        ini_set('memory_limit', '-1');
        $route = Route::
        with('schedulings.crm','schedulings.crmSchedule.service','schedulings.crmSchedule.controls.area','schedulings.crmSchedule.controls.infestation','schedulings.crmSchedule.controls.product','schedulings.crmSchedule.status')
        ->withCount(['schedulings as schedulings_completed_count' => function($q){$q->where('completed',1);}])
        ->find($id);

        //dd($route->toArray());

       try {
            $filename = "r-".Carbon::parse($route->date)->format('dmY') .'-'. $route->user->name;
            $path = "routes/" . $filename. ".pdf";

            $file = "storage/{$path}";

            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="'.$file.'"');
            readfile($file);
        } catch (\Throwable $th) {
            $nroute = Route::
            with(
                'schedulings.users',
                'schedulings.crm',
                'schedulings.crmSchedule.service',
                'schedulings.crmSchedule.controls.infestation',
                'schedulings.crmSchedule.controls.product',
                'schedulings.crmSchedule.controls.area',
                'schedulings.crmSchedule.controls.dose',
                'schedulings.crmSchedule.controls.application'
                )
                ->find($route->id);

            $subject =  "Reporte de ruta ".Date::parse($nroute->date)->format('l')."(". Carbon::parse($nroute->date)->format('d-m-Y') . ") - ". $nroute->user->name;

            //almacenar archivo en físico
            $view = view('scheduling.sub.routePdf',compact(['route','subject']))->render();
            $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');

            $filename = "r-".Carbon::parse($nroute->date)->format('dmY') .'-'. $nroute->user->name;
            $path = "routes/" . $filename . ".pdf";
            
            Storage::disk('public')->put($path, $pdf->output());

            $filename = "r-".Carbon::parse($route->date)->format('dmY') .'-'. $route->user->name;
            $path = "routes/" . $filename. ".pdf";

            $file = "storage/{$path}";


            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="'.$file.'"');
            readfile($file);
        }
       
    }


    public function saveInfestatioGrade(Request $request){
        try{
            ini_set('memory_limit', '-1');

            DB::beginTransaction();
            //el usuario
            $user = User::find($this->getUserIDToken($request));

            //buscar el crmSchedule
            $crmSchedule = CrmSchedule::find($request->input('crm_schedule_id'));
            if(is_null($crmSchedule)){
                DB::rollback();
                return $this->error('El servicio ya no se encuentra disponible',500,["trace" => null, 'success' => false]);
            }

            //se utiliza firstOrCreate para evitar duplicados
            $insfestationGrade = InfestationGrade::updateOrCreate(
                [
                    "crm_schedule_control_id"   => $request->input("control_id"),
                    "control_infestation_id"    => $request->input('control_infestation_id')
                ],
                [
                    "id"                        => $request->input("id"),
                    "crm_id"                    => $crmSchedule->crm_id,
                    "office_id"                 => $crmSchedule->office_id,
                    "crm_schedule_id"           => $request->input('crm_schedule_id'),
                    "infestation_id"            => $crmSchedule->controls()->find($request->input("control_id"))->infestation_id,
                    "value"                     => $request->input("value"),
                    "user_id"                   => $user->id,
                    "headquarter_id"            => $user->headquarter_id,
                    "created_at"                => $request->input("created_at"),

                ]);
                
                DB::commit();
                return $this->success(['success' => true]);
        }catch(\Exception $ex){
            DB::rollback();
                return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        }
    }

    public function deleteInfestatioGrade(Request $request){
        try{
            ini_set('memory_limit', '-1');

            DB::beginTransaction();
            //eliminar el registro
            $success = InfestationGrade::
            where('crm_schedule_control_id',$request->input("control_id"))
            ->where('control_infestation_id', $request->input('control_infestation_id'))
            ->where('value', $request->input('value'))->delete();

            if($success){
                //cambiar la fecha de eliminacion por la que registra el app
                InfestationGrade::
                where('crm_schedule_control_id',$request->input("control_id"))
                ->where('control_infestation_id', $request->input('control_infestation_id'))
                ->where('value', $request->input('value'))->withTrashed()->update(['deleted_at' => $request->input("deleted_at")]);
               
            }
            DB::commit();
            return $this->success(['success' => true]);
        }catch(\Exception $ex){
            DB::rollback();
                return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        }
    }
//R
    public function mechanimsReplace(Request $request){
        try{

            DB::beginTransaction();
        
            //el usuario
            $user = User::find($this->getUserIDToken($request));

            //el scheduling
            $scheduling = Scheduling::find($request->input('scheduling'));
            if(is_null($scheduling)){
                DB::rollback();
                return $this->error('El servicio al cual se desea realacionar el qr ya no se encuentra disponible o fue eliminado por un usuario administrativo',500,["trace" => null, 'success' => false]);
            }

            //consultar el old extend id
            $old_extend = FileExtend::find($request->input('old_id'));
            if(is_null($old_extend)){
                return $this->error('El mecanismo no tiene un antecesor activo, no se puede completar el reemplazo, quizás fue movido o eliminado por un usuario administrativo de la sede.',500, ['trace' =>null, 'success' => false]);
            }

            //heredar los datos del old extend al new extend
            $new_extend = new FileExtend();

            $new_extend->id                 = $request->input('new_id');
            $new_extend->file_id            = $old_extend->file_id ;
            $new_extend->extendible_type    = $old_extend->extendible_type;
            $new_extend->extendible_id     = $request->input('extendible_id');
            $new_extend->x                  = $old_extend->x;
            $new_extend->y                  = $old_extend->y;
            $new_extend->size               = $old_extend->size;
            $new_extend->num                = $old_extend->num;
            $new_extend->created_at         = $request->input('updated_at');
            $new_extend->updated_at         = $request->input('updated_at');

            $new_extend->save();

            //crear la asociación del qr para este nuevo extend_id
            $qr = Qr::where('token', $request->input('qr_token'))->enabled()->where('use',1)->get()->first(); 

            if($qr){

                $qr->file_extend_id     = $new_extend->id;
                $qr->lat                = $request->filled('lat') ? $request->input('lat'): null;
                $qr->lng                = $request->filled('lng') ? $request->input('lng'): null;
                $qr->scan_date          = $request->input('updated_at');
                $qr->scan_by            = $user->id;
                $qr->crm_id             = $scheduling->crm_id;
                $qr->office_id          = $scheduling->office_id;
                $qr->crm_schedule_id    = $scheduling->crm_schedule_id;
                $qr->scheduling_id      = $scheduling->id;

                $qr->save();

                $requestNew=['file_extend_id' => $new_extend->id];
                $newRequest = new Request($requestNew);

                if(\App\Integration::find(1)->enabled && 
                in_array(
                    \App\Headquarter::find(
                        $user->headquarter_id
                    )->id , \App\Integration::find(1)->headquarters
                )){
                    //metodos para registrar en la tabla de tranferencias y en las de foxcon
                    $this->requestDeleteElement($user,$old_extend, $request);
                    $this->requestNewReplaceElement($user, $newRequest,"replace");
                }
                

                //eliminar el file extends anterior junto con el qr anterior
                Qr::where('file_extend_id', $old_extend->id)->delete();
                

                $old_extend->deleted_at = $request->input('updated_at');
                $old_extend->delete_by = $user->id;
                $old_extend->enabled = 0;
                $success = $old_extend->save();
                
                DB::commit();
                return $this->success(['success' => $success]);
               
            }else{
                DB::rollback();
                return $this->error('El Qr <b>'.$request->input('qr_token').'</b> no coincide con los qr habilitados en el sistema, esto puede ser porque ha sido eliminado previamente por un admisnistrador o porque escanéaste un código que no es generado desde el soft administrativo',500,["trace" => null, 'success' => false]);
            }
                
        }catch(\Exception $ex){
            DB::rollback();
                return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        }
    }
    
    public function qrInstall(Request $request){
        try{
            ini_set('memory_limit', '-1');
            DB::beginTransaction();
        
            //el usuario
            $user = User::find($this->getUserIDToken($request));

            if(
                
                \App\Integration::find(1)->enabled && 

                in_array(
                    \App\Headquarter::find(
                        $user->headquarter_id
                    )->id , \App\Integration::find(1)->headquarters
                ) &&

                $request->input('affectInventory') == 1
            
            ){

                $scheduling = Scheduling::find($request->scheduling_id);
                $request->merge(["observation_foxcon" => $scheduling->name ?? null]);
                $this->requestNewReplaceElement($user, $request);
            }
            //el scheduling
            $scheduling = Scheduling::find($request->input('scheduling_id'));
            if(is_null($scheduling)){
                DB::rollback();
                return $this->error('El servicio al cual se desea relacionar el qr ya no se encuentra disponible o fue eliminado por un usuario administrativo',500,["trace" => null, 'success' => false]);
            }

            //el qr
            $qr = Qr::where('token', $request->input('qr_token'))->enabled()->where('use',1)->get()->first(); 

            if(!is_null($qr->file_extend_id)){
                return $this->error('El qr que intentas utilizar ya fue asociado a otro mecanismo',500,["trace" => null, 'success' => false]);
            }

            if($qr){

                $qr->file_extend_id     = $request->input('file_extend_id.id');
                $qr->lat                = $request->input('lat');
                $qr->lng                = $request->input('lng');
                $qr->scan_date          = $request->input('scan_date');
                $qr->scan_by            = $user->id;
                $qr->crm_id             = $scheduling->crm_id;
                $qr->office_id          = $scheduling->office_id;
                $qr->crm_schedule_id    = $scheduling->crm_schedule_id;
                $qr->scheduling_id      = $scheduling->id;

                $success = $qr->save();
                
                DB::commit();
                return $this->success(['success' => $success]);
               
            }else{
                DB::rollback();
                return $this->error('El Qr <b>'.$request->input('qr_token').'</b> no coincide con los qr habilitados en el sistema, esto puede ser porque ha sido eliminado previamente por un admisnistrador o porque escanéaste un código que no es generado desde el soft administrativo',500,["trace" => null, 'success' => false]);
            }
                
        }catch(\Exception $ex){
            DB::rollback();
                return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        }
    }

    public function newMechanism(Request $request){
        try{
           
            DB::beginTransaction();
            //crear el nuevo extend
            $extend  = new FileExtend;
            $extend->id = $request->input('id');
            $extend->file_id = $request->input('file_id');
            $extend->extendible_type = $request->input('extendible_type');
            $extend->extendible_id   = $request->input('extendible_id');
            $extend->x = $request->input('perX');
            $extend->y = $request->input('perY');
            $extend->size = $request->input('size');
            $extend->num = $request->input('num');
            $extend->created_at = $request->input('created_at');
            $extend->updated_at = $request->input('updated_at');
            $extend->updated_by = $request->input('updated_by');
            $success = $extend->save();

            if($request->input('affectInventory')){
                //return $this->error('No es posible descontar el mecanismo de tu bodega y trasladarlo a la bodega de mecanismos prestados. Esta funcionalidad se encuentra deshabilitada.',500, $extend->toArray());
            }

            DB::commit();
            return $this->success(['success' => $success]);   
                
        }catch(\Exception $ex){
            DB::rollback();
                return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        }
    }

    public function removeMechanism(Request $request){

        $extend = FileExtend::find($request->input('id'));
        $user = User::find($this->getUserIDToken($request));

        if($extend){

            try {
                DB::beginTransaction();

                
                $inventoryService = new InventoryService;
                $parent = $user->warehouse->parent;
                $inWarehouse = Warehouse::where('parent',$parent)->where('warehousable_type', 'App\SharedMechasnims')->first();

                $inventoryService->removeTransferMechanism(true,$user->warehouse->id, $inWarehouse->id, $extend->id,1,$user, $request);
            
                $extend->update(['delete_by' => $this->getUserIDToken($request)]);
                $success = FileExtend::destroy($request->input('id'));

                DB::commit();
                return $this->success($success);

            } catch (\Exception $ex) {
                DB::rollBack();
                return $this->error($ex->getMessage(),500, ['trace' =>$ex->getTrace(), 'success' => false]);
            }
            

        }else{
            return $this->error('El mecanismo que intentas retirar ya no se encuentra disponible',500, ['trace' =>null, 'success' => false]);
        }
    }
    
    public function qrReplaced(Request $request){
        try{
            ini_set('memory_limit', '-1');
            DB::beginTransaction();
        
            //el usuario
            $user = User::find($this->getUserIDToken($request));

            //buscar la informacion del qr anterior mediante el extend_id
            $old_qr = Qr::where('file_extend_id', $request->input('id'))->orderBy('updated_at')->first();

            if(is_null($old_qr)){
                return $this->error('El mecanismo no tiene un qr anteriormente asociado, no se puede completar el reemplazo',500, ['trace' =>null, 'success' => false]);
            }
            
            //buscar el nuevo qr mediante el token
            $qr = Qr::where('token', $request->input('qr_token'))->enabled()->where('use',1)->get()->first();
            if(!is_null($qr)){

                
                $qr->file_extend_id     = $old_qr->file_extend_id;
                $qr->lat                = $old_qr->lat;
                $qr->lng                = $old_qr->lng;
                $qr->scan_date          = $old_qr->scan_date;
                $qr->scan_by            = $old_qr->scan_by ?? $user->id;
                $qr->crm_id             = $old_qr->crm_id;
                $qr->office_id          = $old_qr->office_id ?? $request->input('office');
                $qr->crm_schedule_id    = $old_qr->crm_schedule_id;
                $qr->scheduling_id      = $old_qr->scheduling_id;
                $qr->created_at         = $old_qr->created_at;
                $qr->updated_at         = $request->input('updated_at');

               $qr->save();
 
               $old_qr->deleted_at = $request->input('updated_at');

               $success = $old_qr->save();

                    
                DB::commit();
                return $this->success(['success' => $success]);

            }else{
                DB::rollback();
                return $this->error('El Qr <b>'.$request->input('qr_token').'</b> no coincide con los qr habilitados en el sistema, esto puede ser porque ha sido eliminado previamente por un admisnistrador o porque escanéaste un código que no es generado desde el soft administrativo',500,["trace" => null, 'success' => false]);
            }
                
        }catch(\Exception $ex){
            DB::rollback();
                return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        }
    }

    /**
     * Funcionalidades que permiten registrar en las tablas correspondientes a todos los movimientos
     * en la tabla de tranferencias y Foxcon
     */

    private function requestDeleteElement(User $user,FileExtend $fileExtend, Request $originalRequest){
        $inventories=$fileExtend->inventories;
        foreach ($inventories as $inventory){
            $warehouseId=$inventory->warehouse_id;
            $warehouseTransfer=$inventory->warehouse->parent;
            foreach ($inventory->detailInventories as $detail){

                if(!isset($detail)) abort(406, "Operacion fallida, porque la bodega ".optional(optional($inventory)->warehouse)->parentt->name.", no tiene stock de ".$detail->product->name);
                $transferProd[] = [
                    "product_id"            => $detail->product_id,
                    "plot"                  => optional($detail)->plot,
                    "code"                  => optional($detail)->code,
                    "cost"                  => optional($detail)->cost,
                    "quantity"              => optional($detail)->stock,
                    "transfer_quantity"     => optional($detail)->stock,
                    "date_expiration"       => optional($detail)->date_expiration,
                ];
            }
        }
        if(!isset($warehouseId)) abort(406,"Este mecanismo no se encuentra, en la bodega de mecanismos prestados");
        $req = [
            "warehouse_id"          => $warehouseId,
            "warehouse_transfer"    => $warehouseTransfer,
            "transferInventory"     => $transferProd,
            "file_extend_id"        => $fileExtend->id,
            "user_id"               => $user->id,
            "headquarter_id"        => $user->headquarter_id,
        ];
        $newReq = new Request($req);
        $this->saveTranferInventory($newReq, $user, $originalRequest);
    }
    private function requestNewReplaceElement(User $user, Request $request, $action="install"){
        $fileExtend = FileExtend::find($request->input('file_extend_id.id'));
        $parent = $user->warehouse->parent;
        
        $warehouseShare = Warehouse::
            where('parent',$parent)
            ->where('warehousable_type', 'App\SharedMechasnims')
            ->first();

        $products = $fileExtend->extendible->products;

        foreach ($products as $product){

            $detailInventory = InventoryDetail
                ::where('product_id',$product->id)
                ->where('warehouse_id', $user->warehouse->id)
                ->first();


            if($detailInventory == null) abort(406, "Operacion fallida, no tienes stock en tu inventario del producto ".$product->name);
            
            $transferProd[] = [
                "product_id"            => $product->id,
                "plot"                  => optional($detailInventory)->plot,
                "code"                  => $product->code,
                "cost"                  => $product->cost,
                "quantity"              => optional($product->pivot)->quantity,
                "transfer_quantity"     => optional($product->pivot)->quantity,
                "date_expiration"       => optional($detailInventory)->date_expiration,
                "inventory_detail"      => $detailInventory
            ];
        }
        $req = [
            "user_id"               => $user->id,
            "headquarter_id"        => $user->headquarter_id,
            "warehouse_id"          => $user->warehouse->id,
            "warehouse_transfer"    => $warehouseShare->id,
            "transferInventory"     => $transferProd,
            "is_new"                => ($action == "install") ? 1: 0,
            "file_extend_id"        => $request->file_extend_id,
        ];

        $newReq = new Request($req);
        $this->saveTranferInventory($newReq, null, $request);
    }

    private function getDocumentFoxconTraWarehouse($id){ 
        $warehouse = Warehouse::find($id);
        if($warehouse->warehousable_type == "App\SharedMechasnims" || $warehouse->warehousable_type == "App\User") {
            $warehouse=$warehouse->parentt;
        }
        $result = optional(optional(optional($warehouse)->configurationFoxcon)->where('document_type_id', 2))->last();

        if(!$result && \App\Integration::find(1)->enabled && 
        in_array(
            \App\Headquarter::find(
                session('headquarter_id')
            )->id , \App\Integration::find(1)->headquarters
        )) abort(406,"Para registrar el Traslado en Foxcon, debe configurar los Traslados de documentos de foxcon, para la bodega > ".optional($warehouse)->name ?? '');
        
        return $result->id;
    }
    private function getDocumentFoxconTrasladWarehouse($id){ 
        
        $warehouse = Warehouse::find($id);
        if($warehouse->warehousable_type == "App\SharedMechasnims" || $warehouse->warehousable_type == "App\User") {
            $warehouse=$warehouse->parentt;
        }
        $result = optional(optional(optional($warehouse)->configurationFoxcon)->where('document_type_id', 2))->last();
        if(!$result && \App\Integration::find(1)->enabled && 
        in_array(
            \App\Headquarter::find(
                session('headquarter_id')
            )->id , \App\Integration::find(1)->headquarters
        )) abort(406,"Para registrar el Traslado en Foxcon, debe configurar los Traslados de documentos de foxcon, para la bodega > ".optional($warehouse)->name ?? '');
                return $result;
    }

    private function saveTranferInventory(Request $request, $user = null, Request $originalRequest){

        if($user == null){
            $user = User::find($this->getUserIDToken($originalRequest));
        }
       
        
            $transferInventory = TransferInventory::create([
                    'date_transfer'         => now()->format("Y-m-d H:i:s"),
                    'document_type_id'      => \App\Integration::find(1)->enabled && 
                in_array(
                    \App\Headquarter::find(
                        $user->headquarter_id
                    )->id , \App\Integration::find(1)->headquarters
                ) ? $this->getDocumentFoxconTraWarehouse($request->input('warehouse_id')): null,
                    'headquarter_id'        => $request->headquarter_id,
                    'warehouse_id'          => $request->warehouse_id,
                    'warehouse_transfer'    => $request->warehouse_transfer,
                    'user_id'               => $request->user_id,
                    'file_extend_id'        => $request->input("file_extend_id.id"),
            ]);
            
            //esta variable se envia a la funcion deductQuantityInventory para localizar la bodega saliente y desminuir 
            $warehouse_transfer_type=Warehouse::find($request->input('warehouse_transfer'));
            if($warehouse_transfer_type->warehousable_type === "App\SharedMechasnims"){

                $this->saveInventaryDirectly($request,$transferInventory,$originalRequest);

            }else{
                if($request->has('transferInventory')){
                    foreach ($request->input('transferInventory') as $indx => $content) {
                        $content = new Request($content);
                        
                        $collection = collect($request->input('transferInventory'));
                        $sum_value=$collection->where('product_id',$content->input('product_id'))->where('plot',$content->input('plot'))->pluck('transfer_quantity')->sum();
                        if($request->filled('file_extend_id')){
                            $productInventorysDeduct = Inventory::where('warehouse_id',$request->input('warehouse_id'))
                            ->where('product_id',$content->input('product_id'))
                            ->where('file_extend_id',$request->input("file_extend_id.id"))
                            ->get();
                        }else{
                            $productInventorysDeduct = Inventory::where('warehouse_id',$request->input('warehouse_id'))
                            ->where('product_id',$content->input('product_id'))
                            ->get();
                        }
                        $product= Product::find($content->product_id);
                        if(count($productInventorysDeduct) === 0) abort(406,"Stock insuficiente del producto $product->name");
                        foreach($productInventorysDeduct as $productInventoryDeduct){
                            $detailsInventori=$productInventoryDeduct->detailInventories->where('plot',$content->input('plot'));
                            foreach ($detailsInventori as $inventorydet){
                                $result=$inventorydet->stock-$sum_value;
                                if($result < 0){
                                    abort('406', "La cantidad a tranferir del producto '".$productInventoryDeduct->product->name."', NO debe ser mayor al Stock del lote '".$inventorydet->plot."'");
                                }
                            }
                        }
                        $date_expiration=Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00');
                        
                                //guardar
                                $transferInventory->detailTranferInventory()->create([
                                'product_id'        => $content->input('product_id'),
                                'rental'            => $content->input('rental'),
                                'facturable'        => $content->input('facturable'),
                                'cost'              => $content->cost,
                                'plot'              => $content->input('plot'),
                                'foxcon'            => $content->input('foxcon'),
                                'date_expiration'   => $date_expiration,
                                'quantity'          => $content->input('quantity'),
                                'transfer_quantity' => $content->input('transfer_quantity'),
                            ]);
                    }
                }
            }

    }

    //PERMITE AGREGAR LA TRANFERENCIA DIRECTAMENTE EN EL INVENTARIO, SIN NECESIDAD DE COMPROBACION DEL USURIO A RECIBIR LA TRANFERENCIA
    private function saveInventaryDirectly(Request $request,TransferInventory $transferInventory, Request $originalRequest){
        $user = User::find($this->getUserIDToken($originalRequest));
        $transferInventory->enabled=0;
        $transferInventory->save();
        if($request->has('transferInventory')){
            foreach ($request->input('transferInventory') as $indx => $content) {
                
                $content = new Request($content);
                $productInventory=null;

                $inventoryDetail = InventoryDetail::find($content->input('inventory_detail.id'));

                $productInventorysIncome = Inventory::firstOrCreate(
                    [
                        'warehouse_id' => $request->input('warehouse_transfer'),
                        'product_id' => $content->input('product_id')
                    ],
                    [
                        'stock' => 0,
                        'headquarter_id' => $content->input('headquarter_id')
                    ]
                );


                $productInventorysDeduct = Inventory::find($inventoryDetail->inventory_id);

                $product=Product::find($content->product_id);
                $decreseValue= intval($content->input('transfer_quantity'));

                    if( $request->is_new == 1) //si $request->is_new == 1 entonces se debe descontar del inventario de la bodega
                    {
                        if($productInventorysDeduct == null) abort(406,"Stock insuficiente en tu bodega del producto $product->name");

                        
                        
                        if($productInventorysDeduct->stock < $decreseValue){
                            abort('406', "La cantidad a tranferir del producto '".$productInventorysDeduct->product->name."', NO debe ser mayor al Stock del producto'" );
                        }
                    
                        
                            $productInventorysDeduct->stock -= $decreseValue;
                            $productInventorysDeduct->save();

                            $inventoryDetail->stock -= $decreseValue;
                            $inventoryDetail->save();

                    }

                

                    $productInventorysIncome->stock += $decreseValue;
                    $productInventorysIncome->save();
                

                    $incomeDetails = $productInventorysIncome->detailInventory()->firstOrCreate(
                        [
                            'warehouse_id'       => $request->input('warehouse_transfer'),
                            'product_id'         => $content->input('product_id'),
                            'plot'               => $content->input('plot') ?? 'N/A',
                        ],
                        [ 
                            'code'               => $content->input('foxcon') ?? 'N/A',
                            'date_expiration'    => $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A',
                            'stock'              => 0,
                        ]
                    );

                    $incomeDetails->stock += $decreseValue;
                    $incomeDetails->save();

                $this->registerTransference($content,$transferInventory);
                
            }
        }
        //Validar si la integración con foxcon está activa
        if(\App\Integration::find(1)->enabled && 
        in_array(
            \App\Headquarter::find(
                $user->headquarter_id
            )->id , \App\Integration::find(1)->headquarters
        )){
            $request->merge([
                'observation' => $originalRequest->observation,
                'created_at' => $originalRequest->scan_date
            ]);
            $this->saveFoxconDocumentsTechnical($request);
        }
        
    }

    private function registerTransference(Request $content,TransferInventory $transferInventory){
        
    
                //guardar
                $transferInventory->detailTranferInventory()->create([
                'product_id'        => $content->input('product_id'),
                'rental'            => $content->input('rental') ?? false,
                'facturable'        => $content->input('facturable') ?? false,
                'cost'              => $content->cost ?? 0,
                'plot'              => $content->input('plot') ?? 'N/A',
                'foxcon'            => $content->input('foxcon') ?? 'N/A',
                'date_expiration'   => $content->input('date_expiration') != 'N/A' ? Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00') : 'N/A',
                'quantity'          => $content->input('quantity'),
                'transfer_quantity' => $content->input('transfer_quantity'),
            ]);

    }
    private function getNitFoxcon( $id){ 
        $idd= $id == 1 ? 2 : $id;
        $warehouse= Warehouse::find($idd);
        $nit=null;
        if($warehouse->warehousable_type == 'App\Headquarter') $nit=$warehouse->warehousable->nit;
        if($warehouse->warehousable_type == 'App\User' || $warehouse->warehousable_type == 'App\SharedMechasnims' || $warehouse->warehousable_type == 'App\Office') $nit=$warehouse->parentt->warehousable->nit;
        if(!$nit) abort(406,"Para registrar la transacción en Foxcon, es necesario configurar el NIT para la sede $warehouse->name");
        return $nit;
    }
    private function getProductFoxcon($id){ 
        $product= Product::find($id);
        $code = $product->code;
        if(!$code) abort(406,"Por favor, configure el codigo Foxcon para el producto $product->name");
        return $code;
    }
    private function getUserIdFoxcon($id){

        $user=User::find($id);
        $id_foxcon=$user->headquarter->foxcon_user_id;
        $name=$user->headquarter->name;

        if(!$id_foxcon) abort(406,"Por favor, configure el id usuario foxcon de compras para la sede > ".$name);

        return $id_foxcon;

    }
    private function getWarehouseFoxcon($id){ 
        $warehouse= Warehouse::find($id);
        $code_foxcon = $warehouse->code_foxcon;
        if(!$code_foxcon) abort(406,"Por favor, configure el codigo foxcon para bodega $warehouse->name");
        return $code_foxcon;
    }
    private function getNumberFoxcon($number){ 
        $lenght=strlen($number);
        $rest=6-$lenght;
        $val='';
        for ($i=0; $i < $rest; $i++) { 
            $val.='0';
        }
        return $val.$number;
    }
    //PERMITE GUARDAR LA TRANSFERENCIA EN LA TABLA DE FOXCON CUANDO SE REALIZA LA TRANFERENCIA DESDE UNA BODEGA, NO NECESITA DE APROBACION
    private function saveFoxconDocumentsTechnical(Request $request){
        $configFoxcon=$this->getDocumentFoxconTrasladWarehouse($request->warehouse_id);
        $numero=$this->getNumberFoxcon($configFoxcon->consecutive+1);
        $foxconDocuments = FoxconDocument::create([
            "documento" => $configFoxcon->nomenclature,
            "numero" => $numero,
            "timestamp" => $request->created_at ?? now(),
            "grabacion" => $request->created_at ?? now(),
            "sync" => 0,
            "usuario" => $this->getUserIdFoxcon($request->user_id),
            "nit" => $this->getNitFoxcon($request->warehouse_id),
            "observation" => $request->observation_foxcon,
            "created_at" => $request->created_at ?? now(),
            "updated_at" => $request->created_at ?? now()
            
        ]);
        $configFoxcon->consecutive = $numero;
        $configFoxcon->save();

        if($request->has('transferInventory')){
            foreach ($request->input('transferInventory') as $indx => $content) {
                $content = new Request($content);
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $numero,
                            "timestamp" => $request->created_at ?? now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxcon($request->warehouse_id),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxcon($request->warehouse_id),
                            'tipoes'             => "S",
                            'plot'               => strtoupper($content->plot)
                    ]);
                    $foxconDocuments->details()->create([
                            'documento'          => $configFoxcon->nomenclature,
                            'numero'             => $numero,
                            "timestamp" => $request->created_at ?? now(),
                            'producto'           => $this->getProductFoxcon($content->product_id),
                            'bodega'             => $this->getWarehouseFoxcon($request->warehouse_transfer),
                            'cantidad'           => $content->transfer_quantity,
                            'nit'                => $this->getNitFoxcon($request->warehouse_transfer),
                            'tipoes'             => "E",
                            'plot'               => strtoupper($content->plot)
                    ]);
            }
        }
    }
 /**
  * Fin Funcionalidades
  */

    public function syncService(Request $request){

        $continue = false;
        foreach($request->input('data') as $service){
            $service = new  Request($service);
            
            try {
                DB::beginTransaction();
                    // buscar el servicio y cambiar el estado
                    if($service->filled('id')){
                        $crmSchedule = CrmSchedule::find($service->input('id'));
                    } 
                    else {
                        $crmSchedule = null;
                    }

                    if(!is_null($crmSchedule)){
                       
                        //poner el estado en el servicio
                        if($service->filled('status')){
                        
                            $crmSchedule->status_id = CrmScheduleStatus::where('slug',$service->input('status'))->first()->id;
                            $crmSchedule->save();

                            //dependiendo del estado que venga en la información del servicio se registrarán los seguimientos
                            switch($service->input('status')){

                                //el servicio fue finalizado con interrupciones
                                case "service-fail":
                                    $crmSchedule->userTrackings()->create(
                                        
                                        //1.- registrar el inicio del servicio
                                        [
                                            'user_id'               => $this->getUserIDToken($request),
                                            'user_coord_id'         => $this->setCoords($request, $service->input('start_coords'))->id,
                                            'user_tracking_type'    => $this->startServiceSlug,
                                            'observation'           => "Inicio del servicio",
                                            'date'                  => Carbon::parse($service->input('start'))->format('Y-m-d H:i:s'),
                                        ]
                                    );

                                    $crmSchedule->userTrackings()->create(
                                        
                                         //1.- registrar el FIN del servicio
                                         [
                                            'user_id'               => $this->getUserIDToken($request),
                                            'user_coord_id'         => $this->setCoords($request, $service->input('end_coords'))->id,
                                            'user_tracking_type'    => $this->endServiceSlug,
                                            'observation'           => $service->input('reason'),
                                            'file'                  => null,
                                            'date'                  => Carbon::parse($service->input('end'))->format('Y-m-d H:i:s'),
                                        ]
                                    );

                                //2 - registrar el cambio de estado en el evento de la agenda
                                $scheduling = Scheduling::find($service->input('s_id'));
                                $scheduling->completed = 3;
                                $scheduling->save();

                                    break;

                                //el servicio fue finalizado con interrupciones
                                case "cancelled":
                                   
                                    $crmSchedule->userTrackings()->create(
                                        
                                         //1.- registrar el FIN del servicio
                                         [
                                            'user_id'               => $this->getUserIDToken($request),
                                            'user_coord_id'         => $this->setCoords($request, $service->input('end_coords'))->id,
                                            'user_tracking_type'    => $this->endServiceSlug,
                                            'observation'           => $service->input('reason'),
                                            'file'                  => null,
                                            'date'                  => Carbon::parse($service->input('end'))->format('Y-m-d H:i:s'),
                                        ]
                                    );

                                //2 - registrar el cambio de estado en el evento de la agenda
                                $scheduling = Scheduling::find($service->input('s_id'));
                                $scheduling->completed = 2;
                                $scheduling->save();

                                break;

                                case "service-success":
  
                                    if($service->filled('start')){
                                        $crmSchedule->userTrackings()->create(
                                        
                                            //1.- registrar el inicio del servicio
                                            [
                                                'user_id'               => $this->getUserIDToken($request),
                                                'user_coord_id'         => $this->setCoords($request, $service->input('start_coords'))->id,
                                                'user_tracking_type'    => $this->startServiceSlug,
                                                'observation'           => "Inicio del servicio",
                                                'date'                  => Carbon::parse($service->input('start'))->format('Y-m-d H:i:s'),
                                            ]
                                        ); 
                                    }
                                    

                                    $crmSchedule->userTrackings()->create(
                                        
                                         //1.- registrar el FIN del servicio
                                         [
                                            'user_id'               => $this->getUserIDToken($request),
                                            'user_coord_id'         => $this->setCoords($request, $service->input('end_coords'))->id,
                                            'user_tracking_type'    => $this->endServiceSlug,
                                            'observation'           => $service->input('observation'),
                                            'file'                  => $this->getAvatar($service, 'signature', 'crms/signatures'),
                                            'date'                  => Carbon::parse($service->input('end'))->format('Y-m-d H:i:s'),
                                        ]
                                    );
                                if($service->filled('s_id')){
                                    
                                    //2 - registrar el cambio de estado en el evento de la agenda
                                    $scheduling = Scheduling::find($service->input('s_id'));
                                    $scheduling->completed = 1;
                                    $scheduling->save();
                                }
                                
                                    
                                break;
                                default:
                                    //buscar si el evento a reportar ya está marcado como finalizado y actualizar todos
                                    $scheduling = Scheduling::find($service->input('s_id'));
                                    if(!is_null($scheduling)){
                                        //verificar si el estado ya es completado
                                        if($scheduling->status == 1 && $service->filled('id')){
                                            
                                            $crmSchedule = CrmSchedule::find($service->input('id'));
                                            $crmSchedule->status_id = CrmScheduleStatus::where('slug', 'service-success')->first()->id;
                                            $crmSchedule->save();

                                        }else if($scheduling->status == 2 && $service->filled('id')){

                                            $crmSchedule = CrmSchedule::find($service->input('id'));
                                            $crmSchedule->status_id = CrmScheduleStatus::where('slug', 'cancelled')->first()->id;
                                            $crmSchedule->save();

                                        }else if($scheduling->status == 3 && $service->filled('id')){
                                            $crmSchedule = CrmSchedule::find($service->input('id'));
                                            $crmSchedule->status_id = CrmScheduleStatus::where('slug', 'service-fail')->first()->id;
                                            $crmSchedule->save();
                                        }

                                        if($service->filled('start')){
                                            $crmSchedule->userTrackings()->create(
                                            
                                                //1.- registrar el inicio del servicio
                                                [
                                                    'user_id'               => $this->getUserIDToken($request),
                                                    'user_coord_id'         => $this->setCoords($request, $service->input('start_coords'))->id,
                                                    'user_tracking_type'    => $this->startServiceSlug,
                                                    'observation'           => "Inicio del servicio",
                                                    'date'                  => Carbon::parse($service->input('start'))->format('Y-m-d H:i:s'),
                                                ]
                                            ); 
                                        }
                                        
    
                                        $crmSchedule->userTrackings()->create(
                                            
                                             //1.- registrar el FIN del servicio
                                             [
                                                'user_id'               => $this->getUserIDToken($request),
                                                'user_coord_id'         => $this->setCoords($request, $service->input('end_coords'))->id,
                                                'user_tracking_type'    => $this->endServiceSlug,
                                                'observation'           => $service->input('observation'),
                                                'file'                  => $this->getAvatar($service, 'signature', 'crms/signatures'),
                                                'date'                  => Carbon::parse($service->input('end'))->format('Y-m-d H:i:s'),
                                            ]
                                        );
                                        return $this->success(true);

                                    }else{
                                        return $this->success(false);
                                    }


                                    
                                break;
                            }
                        }
                          
                    }else{
                        if($service->filled('s_id')){
                            
                            //guaradar el evento/tarea
                            $scheduling = Scheduling::find($service->input('s_id'));
                            if($scheduling){
                               
                                switch($service->input('status')){
                                    case 'service-success':
                                        $scheduling->completed = 1;
                                        $scheduling->save();
                                        $scheduling->userTrackings()->create(
                                        
                                                    //1.- registrar el FIN del servicio
                                                    [
                                                    'user_id'               => $this->getUserIDToken($request),
                                                    'user_coord_id'         => $this->setCoords($request, $service->input('end_coords'))->id,
                                                    'user_tracking_type'    => $this->endServiceSlug,
                                                    'observation'           => $service->input('observation'),
                                                    'date'                  => Carbon::parse($service->input('end'))->format('Y-m-d H:i:s'),
                                                ]
                                            );
                                    break;
                                    case 'cancelled':
                                        $scheduling->completed = 2;
                                        $scheduling->save();
                                        $scheduling->userTrackings()->create(
                                        
                                                    //1.- registrar el FIN del servicio
                                                    [
                                                    'user_id'               => $this->getUserIDToken($request),
                                                    'user_coord_id'         => $this->setCoords($request, $service->input('end_coords'))->id,
                                                    'user_tracking_type'    => $this->endServiceSlug,
                                                    'observation'           => $service->input('observation'),
                                                    'date'                  => Carbon::parse($service->input('end'))->format('Y-m-d H:i:s'),
                                                ]
                                            );
                                    break;
                                    default:
                                    return $this->success(false);
                                    break;
                                    
                                }
                            }else{
                                return $this->success(false);
                            }
                        }else{
                            return $this->success(false);
                        }
                        
                    }
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollBack();
                return $this->success(false);
            }

        }

        return $this->success(true);
        
    }

    public function syncTechnicalReport(Request $request){
            try {
                DB::beginTransaction();

                //asociar con un servicio del cronograma
                $externalNote = null;
                $scheduling = Scheduling::find($request->input('scheduling'));
                if($scheduling){
                    
                    //sede del usuario que consulta la información
                    $user = User::find($this->getUserIDToken($request));
                    $headquarter_id = $user->headquarter_id;
                    
                    //consultar el extend
                    $extend = FileExtend::with(['file','qr'])->find($request->input('file_extend_id'));
                    
                    if($request->filled('reported_products') && count($request->reported_products) > 0){
                       
                    }
                    
                    //eliminar el elemento del inventario y registrar el consumo en foxcon
                    //guardar la nota de facturación (si aplica)
                    if($request->filled('reported_products') && count($request->reported_products) > 0){

                        $inventoryService = new InventoryService;

                        if(in_array($request->input('technical_report_type_id'), [
                            TechnicalReportType::DAMAGED, 
                            TechnicalReportType::NOT_FOUND])){
                            
                            //codigo para consumo de mecanismo no encontrado o deteriorado
                            $inventoryService->saveReportedMechismExpense($request, $scheduling, $user);
                            $externalNoteService = new ExternalNoteService; 
                            $externalNote = $externalNoteService->new($request, $scheduling, $user)->id;
                            $extend->reported_products = $request->input('reported_products');
                            $extend->save();

                            
                        }else if(in_array($request->input('technical_report_type_id'), [TechnicalReportType::REPLACE])){

                            //codigo para reponer trampa de mecanismos
                            $inventoryService->saveReplaceMechanismTransfer($request, $scheduling, $user);
                            //esta linea de arriba se comenta porque foxcon hara el desarrollo de informar mecanismos

                            $extend->reported_products = collect($extend->reported_products)->filter(function($product) use($request){
                                return ! in_array($product,$request->input('reported_products'));
                            });

                            $extend->save();
                        }
                        
                    }

                    //crear registro de reportes
                    $treport = new TechnicalReport();
                    $treport->user_id = $this->getUserIDToken($request);
                    $treport->user_coord_id = $this->setCoords($request, $request->input('coords'))->id;
                    $treport->headquarter_id = $headquarter_id;
                    $treport->file_extend_id = $request->input('file_extend_id');
                    $treport->technical_report_type_id = $request->input('technical_report_type_id');
                    $treport->observation = $request->input('observation');
                    $treport->control_infestation_id = $request->input('control_infestation_id');
                    $treport->quantity = $request->input('quantity');
                    $treport->timestamp = $request->input('timestamp');
                    $treport->crm_schedule_id = $scheduling->crm_schedule_id;
                    $treport->crm_id = $scheduling->crm_id;
                    $treport->office_id = $scheduling->office_id;
                    $treport->service_id = $scheduling->crmSchedule->service_id;
                    $treport->crm_external_note_id = $externalNote;

                   
                    //nuevos campos de coordenadas en el plano mecanismo
                    $treport->x = $extend ? $extend->x : null;
                    $treport->y = $extend ? $extend->y : null;
                    $treport->file_id = $extend && $extend->file ? $extend->file->id : null;
                    $treport->area_office_id = $extend  && $extend->file ? $extend->file->filable_id: null;
                    $treport->qr_token=  $extend && $extend->qr ? $extend->qr->token: null;

                    if($request->filled('reported_products') && count($request->reported_products) > 0){
                        $treport->reported_products = $request->input('reported_products');
                    }
                    
                    $treport->save();

                
                    //sincronizar las imagenes
                    if($request->filled('technical_report_pictures')){
                        foreach ($request->input('technical_report_pictures') as $picture) {
                            
                            $picture = new Request(["picture" => $picture]);
                            $pictureG = $treport->pictures()->create([
                                'picture' => $this->getAvatarFromBinary($picture, 'picture', 'technicals/reports',100,false)
                            ]);
                        }
                    }

                    DB::commit();
                    return $this->success(true);
                    
                }else{
                    return $this->error('La actividad a la cual desea asociar este reporte ya no se encuentra habilitada',500, ['trace' =>null, 'success' => false]);
                }
                
            }catch (Exception $ex){
                DB::rollback();
                return $this->error($ex->getMessage(),500,['trace' =>$ex->getTrace(), 'success' => false]);
            }
        

        
    }


    public function deleteTechnicalReport(Request $request){
        try{

            DB::beginTransaction();
            //eliminar el registro
            $success = TechnicalReport::
            where('file_extend_id',$request->input("file_extend_id"))
            ->where('crm_schedule_id', Scheduling::find($request->input('scheduling'))->crm_schedule_id)
            ->where('timestamp', $request->input('timestamp'))->delete();

            if($success){
                //cambiar la fecha de eliminacion por la que registra el app
                TechnicalReport::
                where('file_extend_id',$request->input("file_extend_id"))
                ->where('crm_schedule_id', Scheduling::find($request->input('scheduling'))->crm_schedule_id)
                ->where('timestamp', $request->input('timestamp'))->withTrashed()->update(['deleted_at' => $request->input("deleted_at")]);
               
            }
            DB::commit();
            return $this->success(['success' => true]);
        }catch(\Exception $ex){
            DB::rollback();
                return $this->error($ex->getMessage(),500,["trace" => $ex->getTrace(), 'success' => false]);
        }
    }

    public function syncTechnicalExpenses(Request $request){
        $continue = false;
        // foreach($request->input('data') as $expense){
        //     $expense = new  Request($expense);
        //     try {
        //         DB::beginTransaction();

        //         //asociar con un servicio del cronograma
        //         $inventory_detail_id = InventoryDetail::find($expense->input('inventory_detail_id'));
        //         if($inventory_detail_id){

        //             $stock = $inventory_detail_id->stock - $expense->input('stock');
        //             $inventory_detail_id->stock = $stock;
        //             $inventory_detail_id->save();

        //             //actualizar inventario padre tambien
        //             $inventory = Inventory::find($inventory_detail_id->inventory_id);
        //             $stock2 = $inventory->stock - $expense->input('stock');
        //             $inventory->stock = $stock2;
        //             $inventory->save();

                    
        //         }else{
        //             return $this->success(false);
        //         }

        //         DB::commit();
        //     }catch (Exception $ex){
        //         DB::rollback();
        //         return $this->success(false);
        //     }
        // }

        return $this->success(true);
    }

    public function syncExtendPosition(Request $request){
        $extend = FileExtend::find($request->input('id'));
        if($extend){

            try {
                DB::beginTransaction();

                $extend->x              = $request->input('x');
                $extend->y              = $request->input('y');
                $extend->updated_at     = $request->input('updated_at');
                $extend->updated_by     = $this->getUserIDToken($request);
                $success = $extend->save();

                DB::commit();
                return $this->success($success);

            } catch (\Exception $ex) {
                DB::rollBack();
                return $this->error($ex->getMessage(),500, ['trace' =>$ex->getTrace(), 'success' => false]);
            }
            

        }else{
            return $this->error('El mecanimo  que intentas mover ya no se encuentra disponible',500, ['trace' =>null, 'success' => false]);
        }
    }
 

    public function numMechanism(Request $request){
        $extend = FileExtend::find($request->input('id'));
        if($extend){

            try {
                DB::beginTransaction();


                //aqui se aplica la misma regla de reenumeracion que el 'soft'
    
                $category=$extend->extendible->category_conventions_id;
                $filesExtends=FileExtend::where('file_id',$extend->file_id)->orderBy('id','asc')->get();
                foreach ($filesExtends as $file) {
                    if($file->extendible->category_conventions_id == $category){
                        $numbers[$file->num]=$file->num;
                    }
                }
                $unique=in_array($request->input('num'),$numbers) ? false : true;
                if(!$unique){
                   return  $this->error('el número '.$request->input('num').'  ya está siendo usado en otro mecanismo',500, ['trace' =>null, 'success' => false]);
                }
                

                //en caso de que pase la validación
                $success = $extend->update(
                    [
                        'num' => $request->input('num'),
                        'updated_at' => $request->input('updated_at'),
                    ]);

                DB::commit();
                return $this->success($success);

            } catch (\Exception $ex) {
                DB::rollBack();
                return $this->error($ex->getMessage(),500, ['trace' =>$ex->getTrace(), 'success' => false]);
            }
            

        }else{
            return $this->error('El mecanismo que intentas reenumerar ya no se encuentra disponible',500, ['trace' =>null, 'success' => false]);
        }
    }


     //funcion obsoleta
     public function sync(Request $request){

        $continue = false;
        foreach($request->input('data.qrs') as $fqr){

            $qr = Qr::where('token', $fqr['token'])->where('use',1)->enabled()->get()->first();
            $qrid = false;
            if(isset($qr->id)){
                $qrid = $qr->id;
            }
            
            if($qrid){
                try{
                    DB::beginTransaction();
                        $qr = Qr::find($qrid);
                        $qr->file_extend_id = $fqr['extend'];
                        $qr->lat = $fqr['coords']['latitude'];
                        $qr->lng = $fqr['coords']['longitude'];
                        $qr->scan_date = $fqr['timestamp'];
                        $success = $qr->save();
                    DB::commit();
                    $continue = $success;
                }catch(Exception $ex){
                    $continue = false;
                    DB::rollback();
                    break;
                }   
            }
            // }else{
            //     return $this->success(false);
            //     break;
            // }
        }
        return $this->success($continue);
    }

}
