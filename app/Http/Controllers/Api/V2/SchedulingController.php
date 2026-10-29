<?php

namespace App\Http\Controllers\Api\V2;

use App\AreaOffice;
use App\Consumption;
use App\ControlInfestation;
use App\CrmSchedule;
use App\FileExtend;
use App\Http\Resources\InfestationGradeResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConsumptionResource;
use App\Http\Resources\ObservationResource;
use App\Http\Resources\ReportedProductResource;
use App\Http\Resources\ReportResource;
use App\Http\Resources\SignatureResource;
use App\InfestationGrade;
use App\MechanismReport;
use App\Observation;
use App\Product;
use App\ReportedProduct;
use App\Scheduling;
use App\Services\Api\V2\InventoryService;
use App\Signature;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class SchedulingController extends Controller
{

    //constructor
    public InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function update(Request $request, Scheduling $scheduling){

        $updated = $scheduling->update($request->all());

        try {
            if($request->completed == 1 && $scheduling->crm_schedule_id != null){
                $crmsSchedule = CrmSchedule::find($scheduling->crm_schedule_id);
                if($crmsSchedule != null){
                    $crmsSchedule->update(['status_id' => 3]); //finalizado
                }
            }elseif($request->completed == 0 && $scheduling->crm_schedule_id != null && $request->filled('start_management')){
                $crmsSchedule = CrmSchedule::find($scheduling->crm_schedule_id);
                if($crmsSchedule != null){
                    $crmsSchedule->update(['status_id' => 2]); //en ruta
                }
            }elseif($request->completed == 2 && $scheduling->crm_schedule_id != null){
                $crmsSchedule = CrmSchedule::find($scheduling->crm_schedule_id);
                if($crmsSchedule != null){
                    $crmsSchedule->update(['status_id' => 5]); //cancelado
                }
            }
        } catch (\Exception $th) {
           
        }
       
        
        return response()->json(["success" => $updated]);
    }

    public function saveObservations(Request $request, Scheduling $scheduling){

        $observationIds = [];

        foreach ($request->input('blob') as $index => $data) {


            if($data['id'] != null){
                $observation = Observation::updateOrCreate(
                    [
                        'id' => $data['id']
                    ],
                    [
                        'scheduling_id' => $scheduling->id,
                        'note' => $data['note'],
                        'user_id' => $data['userId']
                    ]
                );

                if($request->has("blob.$index.file")){
                    $file = $request->file("blob.$index.file");
                    $filename = "schedulings/{$scheduling->id}/observations/" . uniqid() . ".jpg";
                    $path = $file->storeAs('images', $filename, 'public');
                    $observation->update(['file_path' => $path]);
                }

                $observationIds[] = $observation->id;

            }

        }

        $observations = null;

        if(count($observationIds) > 0){
            $observations = Observation::whereIn('id', $observationIds)->get();
        }

        return response()->json(["success" => true, "data" => ObservationResource::collection($observations)]);
    }

    public function saveInfestationGrades(Request $request, Scheduling $scheduling){

        $infestationGradesIds = [];

        foreach ($request->input('infestation-grades') as $index => $data) {

            $controlInfestation = ControlInfestation::find($data['infestationId']);


            $infestationGrade = InfestationGrade::updateOrCreate(
                [
                    'id' => $data['id']
                ],
                [
                    'crm_id'                    => $scheduling->crm_id,
                    'office_id'                 => $scheduling->office_id,
                    'crm_schedule_id'           => $scheduling->crm_schedule_id,
                    'crm_schedule_control_id'   => $data['crmScheduleControlId'],
                    'infestation_id'            => $controlInfestation->infestation_id,
                    'control_infestation_id'    => $controlInfestation->id,
                    'value'                     => $data['value'],
                    'headquarter_id'            => $scheduling->headquarter_id,
                    'user_id'                   => $data['userId']

                ]
            );
            

            $infestationGradesIds[] = $infestationGrade->id;
        }

        $infestationGrades = null;

        if(count($infestationGradesIds) > 0){
            $infestationGrades = InfestationGrade::whereIn('id', $infestationGradesIds)->orderBy('created_at','asc')->get();
        }

        return response()->json(["success" => true, "data" => InfestationGradeResource::collection($infestationGrades)]);
    }

    public function saveConsumptions(Request $request, Scheduling $scheduling){

        $consumptions = [];
        $consumptionsData = [];
        $reportedProducts = [];
        $consumptionsIds = [];

        try {
            DB::beginTransaction();
                foreach ($request->input('reportedProducts') as $index => $data) {
                  
                    if(!($data['canBeDeleted'] && $data['canBeDeleted'] == true)){
                        $reportedProducts[] = $data['id'];
                        continue;
                    }

                    $reportedProduct = ReportedProduct::updateOrCreate(
                        [
                            'id' => $data['id']
                        ],
                        [
                            'crm_schedule_id'           => $scheduling->crm_schedule_id,
                            'user_id'                   => $data['userId'],
                            'control_id'                => $data['controlId'],
                            'product_id'                => $data['productId'],
                            'rsa'                       => $data['rsa'],
                            'plot'                      => $data['plot'],
                            'expiration_date'           => $data['expirationDate'] ? Carbon::parse($data['expirationDate'])->format('Y-m-d') : null,
                            'quantity'                  => $data['quantity'],
                            'dose'                      => $data['dose'],
                            'prepared'                  => $data['prepared']
                        ]
                    );
                
                    $reportedProducts[] = $reportedProduct->id;
                }

                foreach ($request->input('consumptions') as $index => $data) {

                    if(!($data['canBeDeleted'] && $data['canBeDeleted'] == true)){
                        $consumptionsIds[] = $data['id'];
                        continue;
                    }

                    if(isset($data['createdAt']) && $data['createdAt'] != null){
                        $format = 'Y-m-d\TH:i:s.u';
                        $createdAt = Carbon::createFromFormat($format, $data['createdAt']);
                    }else{
                        $createdAt = Carbon::now();
                    }
                  
        
                    $consumption = Consumption::updateOrCreate(
                        [
                            'id' => $data['id']
                        ],
                        [
        
                            'crm_schedule_id'           => $scheduling->crm_schedule_id,
                            'user_id'                   => $data['userId'],
                            'inventory_detail_id'       => $data['plotId'],
                            'quantity'                  => $data['quantity'],
                            'created_at'                => $createdAt
                        ]
                    );
                
                    $consumptionsIds[] = $consumption->id;
                    $consumptionsData[] = $consumption;
                    
                }

                if($consumptionsData != null && count($consumptionsData) > 0){
                    $this->inventoryService->deductUserInventoryByConsumptionFromArray($consumptionsData);
                }
        
                $consumptions = null;
        
                if(count($consumptionsIds) > 0){
                    $consumptions = Consumption::with('inventoryDetail.inventory')->whereIn('id', $consumptionsIds)->orderBy('created_at','asc')->get();
                }
        
                if(count($reportedProducts) > 0){
                    $reportedProducts = ReportedProduct::whereIn('id', $reportedProducts)->orderBy('created_at','asc')->get();
                }

            DB::commit();
    
            return response()->json(["success" => true, "data" => [
                "consumptions" => ConsumptionResource::collection($consumptions),
                "reportedProducts" => ReportedProductResource::collection($reportedProducts)
            ]]);
            
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $th->getMessage(), "trace" => $th->getTrace()],500);
        }

        
    }

    public function saveSignature(Request $request){
        
        $signaturesIds = [];

        foreach ($request->input('blob') as $index => $data) {
            $schedulingIds = explode(',',$data['schedulingIds']);

            

            foreach ($schedulingIds as $key => $schedulingId) {

                Signature::where('scheduling_id',$schedulingId)->get()->each(function($signature){
                    Storage::disk('public')->delete($signature->file_path);
                });

                $signature = Signature::updateOrCreate(
                    [
                        'scheduling_id' => $schedulingId
                    ],
                    [
                        'name' => $data['name'],
                        'document' => $data['document'],
                        'user_id' => $data['userId']
                    ]
                );

                
                if($request->has("blob.$index.file")){
                    $file = $request->file("blob.$index.file");
                    $filename = "signatures/".Carbon::now()->format('Y/m')."/" . uniqid() . ".png";
                    $path = $file->storeAs('images', $filename, 'public');
                    $signature->update(['file_path' => $path]);
                }
            }
            $signaturesIds[] = $signature->id;
        }

        $signature = null;

        if(count($signaturesIds) > 0){
            $signature = Signature::whereIn('id', $signaturesIds)->first();
        }

        return response()->json(["success" => true, "data" => new SignatureResource($signature)]);
    }

    public function saveReports(Request $request, Scheduling $scheduling, AreaOffice $area, FileExtend $mechanism ){

        $mechanismReportIds = [];

        foreach ($request->input('blob') as $index => $data) {


            $mechanismReport = MechanismReport::updateOrCreate(
                [
                    'id' => $data['id']
                ],
                [
                    'scheduling_id' => $data['schedulingId'],
                    'observation' => $data['observation'],
                    'user_id' => $data['userId'],
                    'file_extend_id' => $mechanism->id,
                    'quantity' => $data['quantity'],
                    'technical_report_type_id' => $data['reportTypeId'],
                ]
            );

            if($request->has("blob.$index.file")){
                $file = $request->file("blob.$index.file");
                $filename = "schedulings/{$scheduling->id}/reports/" . uniqid() . ".jpg";
                $path = $file->storeAs('images', $filename, 'public');
                $mechanismReport->update(['file_path' => $path]);
            }
             
            

            $mechanismReportIds[] = $mechanismReport->id;
        }

        $mechanismReports = null;

        if(count($mechanismReportIds) > 0){
            $mechanismReports = MechanismReport::whereIn('id', $mechanismReportIds)->get();
        }

        return response()->json(["success" => true, "data" => ReportResource::collection($mechanismReports)]);
    }
}
