<?php

namespace App\Http\Controllers\Administrative;

use App\Headquarter;
use App\Zone;
use App\City;
use App\Crm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Validation\Rule;
use Exception;


class HeadquarterController extends Controller
{
    
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Sede agregada correctamente";
    public $msgUpdated = "Sede actualizada satisfctoriamente";
    public $msgDeleted = "Sede eliminado satisfctoriamente";
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('headquarter.index');
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
        $headquarters = Headquarter::with(['cities.states','zones'])
                ->orderBy('id', 'desc')
                ->get();
        $list = view('headquarter.sub.list', compact('headquarters'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    public function form(Request $request){
        $id             = $request->input('id');
        $headquarter    = Headquarter::with(['cities.states','zones'])->find($id);
        $form           = view('headquarter.forms.headquarterForm', compact(['headquarter']))->render();

        return response(['success' => true, 'view' => $form, 'edit' => $request->filled('id')]);

    }
        
    public function addDocumentRow(Request $request){
        $document  = null;
        $key       = uniqid();
        $row       = view('user.forms.frames.document_new_row',compact(['key','document']))->render();
        return response(['success' => true, 'row' => $row]);
    }
    
    public function documentForm(Request $request){
        $id        = $request->input('id');
        $document  = null;
        $key       = uniqid();
        $form      = view('user.forms.documentForm',compact(['key','document']))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function headquarters(Request $request){
        $query = Headquarter::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $headquarters = $query->get();
        return response(['results' => $headquarters]);
    }
    
    public function roles(Request $request){
        $query = Role::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $roles = $query->get();
        return response(['results' => $roles]);
    }

 
    public function store(Request $request){
        $headquarter = new Headquarter();
        $success = $this->save($request, $headquarter);

        return response(["success" => 'true', "message" => $success ? $this->msgSuccess : $this->msgError]);  
    }
    
    public function update(Request $request){
        $id        = $request->input('id');
        $headquarter      = Headquarter::find($id);
        $success   = $this->save($request, $headquarter);

        return response(["success" => $success, "message" => $success ? $this->msgUpdated : $this->msgError]);
    }
    
    public function save(Request $request, Headquarter $headquarter){
        
        $this->validateForm($request, $headquarter);

      

        $city=null;
        if(!is_numeric($request->input('city_id'))){
            $city = City::firstOrCreate([ 'name' => $request->input('city_id'), 'state_id' => $request->input('states_id')]);
            $city->state_id = $request->input('states_id');
            $city->save();
        } else{
            $city = City::find($request->input('city_id'));
        }
        
        $headquarter->name          = $request->input('name');
        $headquarter->city_id       = $city->id;
        $headquarter->address       = $request->input('address');
        $headquarter->lat           = $request->input('lat');
        $headquarter->lng           = $request->input('lng');
        $headquarter->phone         = $request->input('phone');
        $headquarter->cell_phone    = $request->input('cell_phone');
        $headquarter->email         = $request->input('email');
        $headquarter->nit           = $request->input('nit');
        $headquarter->foxcon_user_id = $request->input('foxcon_user_id');
        
         try {
            DB::beginTransaction();
            
                $headquarter->save();
                //$this->createZones($request, $headquarter);
                $this->createOrupdateHeadquarterWarehouse($request, $headquarter);
                
            DB::commit();
                return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            return false;
        } 
    }
    
    private function createOrupdateHeadquarterWarehouse(Request $request, Headquarter $headquarter){
        if(is_null($request->input('id'))){
           $headquarter->warehouse()->create([
                    "name" => $headquarter->name,
                    "parent" => 1,
                    "buttons" => ['add' => true, 'buy'=> true, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
                    "icon" => 'headquarter',
                    "action" => true
                ]); 
        }else{
            $headquarter->warehouse()->update([
                    "name" => $headquarter->name
                ]); 
        }
        
    }
   
    private function validateForm(Request $request, Headquarter $headquarter) {

        $niceNames = [
            'city_id' => 'Ciudad',
            'state' => 'Departamento',
            'name' => 'Nombre',
            'address' => 'Dirección',
            'phone' => 'Teléfono',
            'cell_phone' => 'Celular',
            'email' => 'Correo',
            'nit' => 'Nit',
    
        ];
        
        $inputs = [
                'city_id'    => 'required',
                'address'    => 'required',
                'name'       => $request->filled('id') ? 'required|unique:headquarters,name,NOT NULL,id,deleted_at,NULL' . $headquarter->id : 'required|unique:headquarters,name,NULL,id,deleted_at,NULL',
                'cell_phone' => 'required',
                'email'      => $request->filled('id') ? 'required|email|unique:headquarters,email,NOT NULL,id,deleted_at,NULL' . $headquarter->id : 'required|email|unique:headquarters,email,NULL,id,deleted_at,NULL',
                'nit'        => 'required',
        ];
        
        $request->validate($inputs,[], $niceNames);
            
    }

    public function deleteForm(Request $request){
        $id             = $request->input('id');
        $headquarter    = Headquarter::find($id);
        $form           = view('headquarter.forms.deleteForm', compact('headquarter'))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function deleteFormZone(Request $request){
        $id             = $request->input('id');
        $zone    = Zone::with('headquarter')->find($id);
        $form           = view('headquarter.forms.deleteFormZone', compact('zone'))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function softDelete(Request $request){
        $id        = $request->input('id');
        Headquarter::find($id)->warehouse()->delete();
        $success   = Headquarter::destroy($id);
        return response(["success" => $success, "message" => $success ? $this->msgDeleted : $this->msgError]); 
    }

    
    public function switch($id){
        $headquarter = Headquarter::find($id);
        if($headquarter){
            
            session(['headquarter_id' => $headquarter->id]);
            session(['headquarter_name' => $headquarter->name]);
            
          return response(['success' => true, 'message' => 'Cambio de sede satisfactorio']);  
        }else{
          return response(['success' => false, 'message' => 'La sede seleccionada ya no se encuentra disponible, lo sentimos.']); 
        }
        
    }
    
    public function switchCrm($id){
        $crm = Crm::find($id);
        if($crm){
            
            session(['crm_id' => $crm->id]);
            session(['crm_name' => $crm->name]);
         
          return response(['success' => true, 'message' => 'Cambio de cliente satisfactorio',"id" => $crm->id]);  
        }else{
          return response(['success' => false, 'message' => 'El cliente seleccionada ya no se encuentra disponible, lo sentimos.']); 
        }
        
    }
    
    public function formZone(Request $request){
        $headquarter    = Headquarter::find($request->input('headquarter'));
        $zone           = Zone::find($request->input('id'));
        $form           = view('headquarter.forms.zonesForm', compact(['zone','headquarter']))->render();

        return response(['success' => true, 'view' => $form, 'coords' => $zone ? ['lat' => $zone->lat, 'lng' => $zone->lng] : false]);

    }
    
    public function storeZone(Request $request){
        $zone = new Zone();
        $success = $this->saveZone($request, $zone);

        return response(["success" => 'true', "message" => $success ? "Zona agregada correctamente" : $this->msgError]);  
    }
    
    public function updateZone(Request $request){
        $zone = Zone::find($request->input('id'));
        $success = $this->saveZone($request, $zone);

        return response(["success" => 'true', "message" => $success ? "Zona actualizada correctamente" : $this->msgError]);  
    }
    
    public function saveZone(Request $request, Zone $zone){
        
        $this->validateFormZone($request,$zone);
        
        $zone->name             = $request->input('name');
        $zone->headquarter_id   = $request->input('headquarter_id');
        $zone->radius           = $request->input('radius');
        $zone->lat              = $request->input('lat');
        $zone->lng              = $request->input('lng');
        
         try {
            DB::beginTransaction();
                $zone->save();               
            DB::commit();
                return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            return false;
        } 
    }
    
    private function validateFormZone(Request $request, Zone $zone) {
        
        $niceNames = [
            'name' => 'Nombre zona',
            'radius' => 'Tamaño del radio',
    
        ];
        
        $inputs = [
                'radius'   => 'required',
                'name'      => ['required',Rule::unique('zones','name')
                    ->where('headquarter_id', $request->input('headquarter_id'))
                    ->ignore($zone)]
            ];
        
        $request->validate($inputs,[], $niceNames);
            
    }

    public function destroyZone(Request $request){
        $id        = $request->input('id');
        $success   = Zone::destroy($id);
        return response(["success" => $success, "message" => $success ? 'Zona eliminada correctamente' : $this->msgError]); 
    }

}
