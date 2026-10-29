<?php

namespace App\Http\Controllers\Administrative;

use App\Availability;
use App\BusinessDay;
use App\User;
use App\Headquarter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;

class AvailabilityController extends Controller
{
    
    public $msgError    = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Disponibilidad configurada correctamente";
    public $msgUpdated = "Disponibilidad actualizada satisfctoriamente";
    public $msgDeleted = "Disponibilidad eliminada satisfctoriamente";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('availability.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $headquarters = Headquarter::with('zones')->where('enabled',1)->get();
        
        return view('user.create',compact(['headquarters']));
    }
    
    public function list() {
        $availabilities = Availability::with('businessDays')
            ->headquarter()
            ->orderBy('id', 'desc')->get();
        $list = view('availability.sub.list', compact('availabilities'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    public function form(Request $request){
        $id             = $request->input('id');
        $availability   = Availability::with(['businessDays','workingHours'])->find($id);
        $businessDays   = BusinessDay::where('enabled',true)->get();
        $form           = view('availability.forms.availabilityForm', compact(['businessDays','availability']))->render();

        return response(['success' => true, 'view' => $form, 'edit' => $availability ? true: false]);

    }
    
    public function addWorkingHourRow(Request $request){
        $workingHour  = null;
        $key       = uniqid();
        $row       = view('availability.forms.frames.working_hours_new_row',compact(['key','workingHour']))->render();
        return response(['success' => true, 'row' => $row]);
    }
        
    public function users(Request $request){
        $query = User::select(['id', 'id as realid', 'name as text'])
                ->whereHas('roles',function($q){
                $q->where('slug','technical');
        })->headquarter();

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $users = $query->get();
        return response(['results' => $users]);
    }
 
    public function store(Request $request){
        $availability = new Availability();
        $success = $this->save($request, $availability);

        return response(["success" => 'true', "message" => $success ? $this->msgSuccess : $this->msgError]);  
    }
    
    public function update(Request $request){
        $id             = $request->input('id');
        $availability   = Availability::find($id);
        $success        = $this->save($request, $availability);

        return response(["success" => 'true',"message" => $success ? $this->msgUpdated : $this->msgError]);
    }
    
    public function save(Request $request, Availability $availability){
              
        $this->validateForm($request);
        try {
            DB::beginTransaction();
                $availability->name = $request->input('name');
                $availability->holidays = $request->input('holidays');
                $availability->headquarter_id = session('headquarter_id');
                $availability->save();
                $availability->workingHours()->delete();
                $availability->businessDays()->sync($request->input('businessDay','workingHours'));
                $availability->users()->sync($request->input('users'));
                
                
                foreach($request->input('workingHour') as $key => $workingHour){
                    
                        //registrar las jornadas laborales
                       $availability->workingHours()->create([
                            "start" => date('H:i',strtotime(str_replace(' ', '',$workingHour['start']))),
                            "end"   => date('H:i',strtotime(str_replace(' ', '',$workingHour['end']))),
                            "extra" => isset($workingHour['extra']) ? 1:0    
                        ]); 
                    
                    
                }
                                 
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            return false;
        }
    }
    
    private function validateForm(Request $request) {

        $niceNames = [
            'name' => 'Nombre identificador',
            'workingHour.*.start' => 'Hora de inicio',
            'workingHour.*.end' => 'Hora fin',
            'holidays' => 'Festivos',
            'users' => 'Técnicos de esta disponibilidad'
        ];
        
        $request->validate([
            'name'                  => 'required',
            'workingHour.*.start'   => 'required',
            'workingHour.*.end'     => 'required'
        ], 
        [], $niceNames);
            
    }

    public function deleteForm(Request $request){
        $id                 = $request->input('id');
        $availability       = Availability::find($id);
        $form               = view('availability.forms.deleteForm', compact('availability'))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function softDelete(Request $request){
        $id     = $request->input('id');
        $success   = Availability::destroy($id);
        return response(["success" => 'true', "message" => $success ? $this->msgDeleted : $this->msgError]); 
    }
        
}
