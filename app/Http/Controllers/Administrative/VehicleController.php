<?php

namespace App\Http\Controllers\Administrative;

use App\Vehicle;
use App\VehicleType;
use App\Headquarter;
use App\File;
use App\FileType;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVehicleRequest;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Vehículo agregado correctamente";
    public $msgSuccessUser = "Usuario asignado correctamente";
    public $msgUpdated = "Vehículo actualizado satisfactoriamente";
    public $msgDeleted = "Vehículo eliminado satisfactoriamente";
    public $msgDeletedDocument = "Archivo eliminado satisfactoriamente";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('vehicle.index');
    }

    public function list() {
        $vehicles = Vehicle::with(['vehicleTypes','files','users'])
                ->orderBy('id', 'desc')
                ->headquarter()
                ->enabled()
                ->get();
        $list = view('vehicle.sub.list', compact('vehicles'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    public function formVehicle(Request $request)
    {
        $id = $request->input('id');
        $vehicle = Vehicle::with(['vehicleTypes','files'])->find($id);
        $document  = null;
        $filetypes = FileType::where('slug','vehicle')->enabled()->get();
        $form = view ('vehicle.forms.vehicleForm', compact(['vehicle','document', 'filetypes']))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    public function typeVehicle(Request $request){
        $query = VehicleType::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $typeVehicle = $query->get();
        return response(['results' => $typeVehicle]);
    }

    public function headquarter(Request $request){
        $query = Headquarter::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $headquarter = $query->get();
        return response(['results' => $headquarter]);
    }

    public function user(Request $request){
        $query = User::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $user = $query->get();
        return response(['results' => $user]);
    }

    public function addDocumentRow(Request $request){
        $document  = null;
        $key       = uniqid();
        $filetypes = FileType::where('slug','vehicle')->enabled()->get();
        $row       = view('vehicle.forms.frames.document_new_row',compact(['key','document','filetypes']))->render();
        return response(['success' => true, 'row' => $row]);
    }
    
    public function softDeleteDocument(Request $request)
    {
       $id  = $request->input('id');
        $success   = File::destroy($id);
        return response(["success" => $success, "message" => $success ? $this->msgDeletedDocument : $this->msgError]); 
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $vehicle = new Vehicle;
        $success = $this->save($request, $vehicle);

        return response(["success" => 'true', "message" => $success ? $this->msgSuccess : $this->msgError]);

    }

    public function storeUser(Request $request, Vehicle $vehicle){
        $vehicle->user_id = $request->input('user');

        $success=$vehicle->save();
        

        return response(["success" => $success, "message" => $success ? $this->msgSuccessUser : $this->msgError]); 
    }

    public function save(Request $request, Vehicle $vehicle){
        
        $this->validateForm($request, $vehicle);
        
        
        try {
            DB::beginTransaction();
            $vehicle_type_id = null;
            if(!is_numeric($request->input('vehicle_type_id'))){
                $vehicle_type = VehicleType::firstOrCreate(['name' => $request->input('vehicle_type_id')]);
            } else{
                $vehicle_type = VehicleType::find($request->input('vehicle_type_id'));
            }

        
            $vehicle->plaque = $request->input('plaque');
            $vehicle->vehicle_type_id = $vehicle_type->id;
            $vehicle->headquarter_id = $request->input('headquarter_id');
            $vehicle->date_soat = $request->input('date_soat');
            $vehicle->date_mechanics = $request->input('date_mechanics');
            $vehicle->date_secure = $request->input('date_secure');
            $vehicle->save();

            //almacenar todos los archivos del producto
            $this->saveFile($request, $vehicle, 'vehicles', 'file' ,true, 'document');
                  
            DB::commit();
            return true;
        } catch (\Exception $exc) {
            DB::rollback();
            return false;
        }

    }

    private function validateForm(Request $request, Vehicle $vehicle) {

        $niceNames = [
            'plaque' => 'Placa',
            'vehicle_type_id' => 'Vehiculo'

        ];
        
        $request->validate([
            'vehicle_type_id' => 'required',
            'plaque' => $request->filled('id') ? 'required|unique:vehicles,plaque,NOT NULL,id,deleted_at,NULL' . $vehicle->id : 'required|unique:vehicles,plaque,NULL,id,deleted_at,NULL'
        ], 
        [], $niceNames);
            
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    public function update(Request $request)
    {

        $id        = $request->input('id');
        $vehicle   = Vehicle::find($id);
        $success   = $this->save($request, $vehicle);

    
        return response(["success" => 'true', "message" => $success ? $this->msgUpdated : $this->msgError]);
    }

    public function deleteForm(Request $request){
        $id        = $request->input('id');
        $vehicle   = Vehicle::find($id);
        $form      = view('vehicle.forms.deleteForm', compact('vehicle'))->render();

        return response(['success' => true, 'view' => $form]);

    }

    public function deleteDocumentoForm(Request $request){
        $id        = $request->input('id');
        $document  = File::find($id);
        $form      = view('vehicle.forms.deleteFormDocument', compact('document'))->render();

        return response(['success' => $document? true: false, 'view' => $form]);

    }
    
    public function softDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = Vehicle::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }

}
