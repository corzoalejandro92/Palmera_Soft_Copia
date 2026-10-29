<?php

namespace App\Http\Controllers\Administrative;

use App\ConditionsPeakPlates;
use App\Http\Controllers\Controller;
use App\PeakPlate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PeakPlateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('peakPlate.index');
    }

    private $msgError = "";

    public function list(){
        $peakPlates = PeakPlate::orderBy('id','desc')->get();
        $list = view('peakPlate.sub.list',compact(['peakPlates']))->render();
        return response(['success' => true, 'view' => $list]);
    }

    public function form(Request $request){
        $id             = $request->input('id');
        $peakPlate    = PeakPlate::find($id);
        $form           = view('peakPlate.forms.peakPlateForm', compact(['peakPlate']))->render();

        return response(['success' => true, 'view' => $form, 'edit' => $request->filled('id')]);

    }

    public function rules(Request $request){
        $query = ConditionsPeakPlates::select(['id','name as text'])->get();

        $data = $query;
        return response(['results' => $data]);
    }


    private function datesRegister($startDate,$endDate, $days_week=[]){
        $dates=Array();
        if($startDate === $endDate)  return[$startDate];
        foreach (Carbon::parse($startDate)->range($endDate) as $date) {
            foreach ($days_week as $day){
                if($date->is($day)) $dates[]=$date->format('Y-m-d');
            }
        }  
        return $dates;
    }
    public function verifyUser(Request $request){
        $vehicles=self::VerifyPlate($request->user_id,$request->date);
        return $vehicles;
    }
    public static function VerifyPlate($id,$date){
        $user=User::find($id);
        if(!$user) return '';
        //obtener todas las vehiculos asociados al usuario
        $vehicles = $user->vehicless;
        $numberPlaque=Array();
        $number='';
        $title='';
        $message='<br>';
        $typeVehicle='';
        foreach ($vehicles as $vehicle){
            //obtener todos los pico y placas para este tipo de vehiculo en la fecha $date y en la ciudad $city
            $peakPlates = PeakPlate::whereDate('date',$date)->where('vehicle_type_id',$vehicle->vehicle_type_id)->where('headquarter_id',$user->headquarter->id)->get();
            //convirtiendo la placa en array    
            $characters=str_split($vehicle->plaque);
                //recorriendo la placa, y obteniento solo los numeros
                foreach ($characters as $character) if (ctype_digit($character)) $numberPlaque[]=$character;
                foreach ($peakPlates as $peakPlate){
                    
                    $title="El vehiculo con placa $vehicle->plaque, tipo: ".$peakPlate->vehicleType->name.", tiene pico y placa; en ".$peakPlate->city->name."(".$peakPlate->state->name.") - desde las $peakPlate->start_time, hasta $peakPlate->end_time ";
                    
                    if($peakPlate->vehicle_type_id == 1) $typeVehicle='<i title="'.$title.'" class="las la-motorcycle text-danger h4"><span style="position: relative;left: -3px;" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></i>  ';
                    if($peakPlate->vehicle_type_id == 2) $typeVehicle='<i title="'.$title.'" class="las la-car-side text-danger h4"><span style="position: relative;left: -3px;" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></i>  ';
                    if($peakPlate->vehicle_type_id == 3) $typeVehicle='<i title="'.$title.'" class="las la-car-alt text-danger h4"><span style="position: relative;left: -3px;" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></i>  ';
                    
                    if($peakPlate->rule->slug === 'first.number'){
                        $number=head($numberPlaque);
                        if(in_array($number, $peakPlate->digits)) $message.=$typeVehicle;
                    }
                    if($peakPlate->rule->slug === 'last.number'){
                        $number=last($numberPlaque);
                        if(in_array($number, $peakPlate->digits)) $message.=$typeVehicle;
                    }
                }
        }
        return $message;
    }

    public function store(Request $request){
        $success = $this->save($request);
        return response(['success' => $success, 'message' => $success ? 'Pico y placa creado correctamente' : 'Se presentó un error: '.$this->msgError]);
    }

    public function update(Request $request){
        $peakPlate  = PeakPlate::find($request->id);
        $success = $this->saveUpdate($request,$peakPlate);
        return response(['success' => $success, 'message' => $success ? 'Pico y placa editado correctamente' : 'Se presentó un error: '.$this->msgError]);
    }

    private function saveUpdate(Request $request, PeakPlate $peakPlate){
        $this->validateFormUpdate($request);
        try{
            DB::beginTransaction();
                $peakPlate->city_id          = $request->city_id;
                $peakPlate->states_id        = $request->states_id;
                $peakPlate->vehicle_type_id  = $request->vehicle_type_id;
                $peakPlate->condition_id     = $request->rule_id;
                $peakPlate->digits           = json_encode($request->digits);
                $peakPlate->start_time       = $request->start_time;
                $peakPlate->end_time         = $request->end_time;
                $peakPlate->save();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            $this->msgError = $ex->getMessage();
            return false;
        }
        
    }
    private function save(Request $request){
        $this->validateFormStore($request);
        $dates=$this->datesRegister($request->start_date,$request->end_date, $request->days_week);
        if(count($dates) == 0) abort(406,"No hay fechas, en el rango, para los dias seleccionados");
        try{
            DB::beginTransaction();
            foreach($dates as $date){
                PeakPlate::updateOrCreate(
                        [
                            'city_id'               => $request->city_id,
                            'states_id'             => $request->states_id,
                            'headquarter_id'        => auth()->user()->headquarter_id,
                            'date'                  => $date,
                            'vehicle_type_id'       =>$request->vehicle_type_id,
                            
                        ],
                        [
                            'condition_id'          => $request->rule_id,
                            'digits'                => json_encode($request->digits),
                            'start_time'            => $request->start_time,
                            'end_time'              => $request->end_time,
                        ]
                    );
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            $this->msgError = $ex->getMessage();
            return false;
        }
        
    }


    private function validateFormStore(Request $request){
            $niceNames= [
                "city_id"                  => "Ciudad",
                "states_id"                => "Departamento",
                "start_date"               => "Fecha inicial",
                "end_date"                 => "Fecha final",
                "vehicle_type_id"          => "Tipo de vehiculo",
                "rule_id"                  => "Regla",
                "digits"                   => "Numeros",
                "start_time"               => "Hora inicial",
                "end_time"                 => "Hora final",
                "days_week"                => "Dias de la semana"
            ];
            $messages = [
                'days_week.required_if'    => "El campo Dias de la semana es obligatorios cuando hay un rango de fecha"
            ];
            $request->validate([
                "city_id"                  => "required",
                "states_id"                => "required",
                "start_date"               => "required|date|before_or_equal:end_date",
                "end_date"                 => "required|date|after_or_equal:start_date",
                "vehicle_type_id"          => "required",
                "rule_id"                  => "required",
                "digits"                   => "required|array",
                "start_time"               => "required",
                "end_time"                 => "required",
                "days_week"                => "required_if:verify_days_week,0"

            ],$messages, $niceNames);
    }
    private function validateFormUpdate(Request $request){
            $niceNames= [
                "city_id"                  => "Ciudad",
                "states_id"                => "Departamento",
                "vehicle_type_id"          => "Tipo de vehiculo",
                "rule_id"                  => "Regla",
                "digits"                   => "Numeros",
                "start_time"               => "Hora inicial",
                "end_time"                 => "Hora final",
            ];
            $request->validate([
                "city_id"                  => "required",
                "states_id"                => "required",
                "vehicle_type_id"          => "required",
                "rule_id"                  => "required",
                "digits"                   => "required|array",
                "start_time"               => "required",
                "end_time"                 => "required",

            ],[], $niceNames);
    }

    public function deleteForm(Request $request){
        $id              = $request->input('id');
        $peakPlate      = PeakPlate::find($id);
        $form            = view('peakPlate.forms.deleteForm', compact('peakPlate'))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function softDelete(Request $request){
        $id     = $request->input('id');
        $peakPlate   = PeakPlate::destroy($id);
        return response(["success" => 'true', "message" => $peakPlate ? "Pico y placa eleminado" : $this->msgError]); 
    }
}
