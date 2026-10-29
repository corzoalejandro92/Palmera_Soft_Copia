<?php

namespace App\Http\Controllers;

use App\User;
use App\Headquarter;
use App\Zone;
use App\File;
use App\FileType;
use App\Availability;
use App\Contact;
use App\Crm;
use App\Mail\UserAccess;
use App\Service;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Mail;
use  App\General\Warehouses;
use App\Warehouse;

class UserController extends Controller{

    private $userFileTypeSlug = 'user';
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Usuario agregado correctamente";
    public $msgUpdated = "Usuario actualizado satisfctoriamente";
    public $msgDeleted = "Usuario eliminado satisfctoriamente";
    public $msgDeletedDocument = "Archivo eliminado satisfactoriamente";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/login');
        //cambiar por un redirect
    }
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('user.create');
    }
    
    public function list() {
        $users = User::with(['headquarter','roles'])
                ->headquarter('true')
                ->orderBy('id', 'desc')
                ->get();
        $list = view('user.sub.list', compact('users'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    public function form(Request $request){
        $id             = $request->input('id');
        $profile        = $request->input('profile') == 'true' ? true : false;
        $user           = User::with(['roles','files','headquarter','availabilities','crms','services'])->find($id);
        $filetypes      = FileType::where('slug',$this->userFileTypeSlug)->enabled()->get();
        $form           = view('user.forms.userForm', compact(['user','profile','filetypes']))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function addDocumentRow(Request $request){
        $document  = null;
        $key       = uniqid();
        $filetypes      = FileType::where('slug',$this->userFileTypeSlug)->enabled()->get();
        $row       = view('user.forms.frames.document_new_row',compact(['key','document','filetypes']))->render();
        return response(['success' => true, 'row' => $row]);
    }
    
    public function documentForm(Request $request){
        $id        = $request->input('id');
        $document  = null;
        $key       = uniqid();
        $filetypes      = FileType::where('slug',$this->userFileTypeSlug)->enabled()->get();
        $form      = view('user.forms.documentForm',compact(['key','document','filetypes']))->render();

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

        if(!Auth::user()->roles->pluck('slug')->contains('root')){
            $query->whereNotIn('slug', ['root']);
        }

        $roles = $query->get();
        return response(['results' => $roles]);
    }

    public function zones(Request $request){
        $query = Zone::select(['id', 'name as text'])->where('headquarter_id',session('headquarter_id'));
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function availabilities(Request $request){
        $query = Availability::select(['id', 'name as text'])->headquarter(true);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function customers(Request $request){
        $query = Crm::select(['id', 'name as text'])->headquarterOffice()->enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function services(Request $request){
        $query = Service::select(['id', 'name as text'])->enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function profile(){
        $view           = view('layouts.header.navbar.profile')->render();
        return response(['success' => true, 'view' => $view]);
    }
 
    public function store(Request $request){
        $user = new User();
        $success = $this->save($request, $user);
        $profile = $request->input('profile') == 'true' ? true : false;

        return response(["success" => 'true', "profile" => $profile, "message" => $success ? $this->msgSuccess : $this->msgError]);  
    }
    
    public function update(Request $request){
        $id        = $request->input('id');
        $user      = User::find($id);
        $success   = $this->save($request, $user);
        $profile = $request->input('profile') == 'true' ? true : false;

        return response(["success" => $success, "profile" => $profile,"message" => $success ? $this->msgUpdated : $this->msgError]);
    }
    
    public function save(Request $request, User $user){
        
        $profile = $request->input('profile');
        if($profile) $this->validateProfile($request, $user);
        else $this->validateForm($request, $user);

        if (!empty($request->input('password'))){
            $password = $request->input('password');
            $user->password = bcrypt($password);
        }else{
            //verificar si se usa clave genérica
            if($request->filled('generic')){
                $password = Str::random(10);
                $user->password = bcrypt($password);
            }
        }
            
          
        if (!$user->id) 
            $user->remember_token = Str::random(10);
        
        try {
            DB::beginTransaction();
                
                $user->name         = $request->input('name');
                $user->avatar       = $this->getAvatar($request);
                $user->signature    = $this->getAvatar($request, 'signature','signatures');
                $user->phone        = $request->input('phone');
                $user->position     = $request->input('position');

                if(!$profile){
                    
                    $user->email            = $request->input('email');
                    $user->headquarter_id   = $request->input('headquarter_id');
                    $user->document         = $request->input('document_number');
                    
                }
                $user->save();

                if($request->has('role_id')){
                   
                    $user->roles()->sync($request->input('role_id'));
                    //almacenar todos los archivos si el rol es técnico
                    if(in_array(11,$request->input('role_id'))){

                        //sincronizar las especialidades (servicios)
                        $user->services()->sync($request->input('service_id'));

                        //sincronizar zonas de cobertura
                        $user->zones()->sync($request->input('zone_id'));

                        //sincronizar disponibilidades
                        $user->availabilities()->sync($request->input('availability_id'));

                        //sincronizar clientes asignados
                        $user->crms()->sync($request->input('customer_id'));

                        //almacenar los documentos enviados
                        if($request->filled('document.*.file')){
                            $this->saveFile($request, $user, 'technicals','file',true);
                        }
                        
                        //crear-actualizar bodega de tipo técnico
                        Warehouses::setWarehouse($user, $request->input('headquarter_id'),'technical', $request->input('warehouse_foxcon'));
                    }else if(in_array(3,$request->input('role_id'))){

                        //sincronizar clientes asignados
                        $user->crms()->sync($request->input('customer_id'));

                        //buscar el contacto del cliente que coincida con el correo y darle acceso al sistema
                        $contact = Contact::where('email',$user->email)->first();
                        if($contact){
                            $contact->user_id = $user->id;
                            $contact->save();
                        }


                    }
                    else if(in_array(4,$request->input('role_id'))){
                        //sincronizar clientes asignados
                        $user->crms()->sync($request->input('customer_id'));
                    }
                }

                if($request->filled('send_access')){
                    //enviar los datos de acceso al correo registrado
                    Mail::to($user->email)->send(new UserAccess($user, $password));
                }
                
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError .= ': '. $exc->getMessage() .': '. $exc->getTraceAsString()  ;
            return false;
        }
    }
    
    
    private function validateForm(Request $request, User $user) {

        $niceNames = [
            'name' => 'Nombre',
            'email' => 'Correo electrónico',
            'headquarter_id' => 'Sede',
            'id_foxcon' => 'Id Foxcon',
            'password' => 'Contraseña',
            'confirm_password' => 'Confirmar Contraseña',
            'role_id' => 'Rol',
            'document.*.file' => 'Archivo',
            'document.*.type_id' => 'Tipo',
            'generic' => 'Usar Clave Genérica'
        ];
        
        $request->validate([
            'name' => 'required',
            'email' => $request->filled('id') ? 'required|email|unique:users,email,NOT NULL,id,deleted_at,NULL' . $user->id : 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'id_foxcon' => $request->filled('id') ? 'nullable|unique:users,id_foxcon,' . $user->id : 'nullable|unique:users,id_foxcon',
            'headquarter_id' => 'required',
            'password' => !is_null($request->input('id')) && $user->password ? 'nullable|min:6' : 'required_without:generic',
            'confirm_password' => 'same:password|required_with:password',
            'role_id' => 'required',
            'document.*.type_id' => 'required_with:document.*.file',
            'document.*.file' => 'mimes:jpeg,png,pdf,docx,xls,xlsx,csv,rar,zip'
            
        ], 
        [], $niceNames);
            
    }
    
    private function validateProfile(Request $request, User $user) {

        $niceNames = [
            'name' => 'Nombre',
            'password' => 'Contraseña',
            'confirm_password' => 'Confirmar Contraseña'
        ];
        
        $request->validate([
            'name' => 'required',
            'password' => !is_null($request->input('id')) && $user->password ? 'nullable|min:6' : 'required|min:6',
            'confirm_password' => 'same:password|required_with:password',
            'document.*.type' => 'required_with:document.*.file',
            'document.*.file' => 'mimes:jpeg,png,pdf,docx,xls,xlsx,csv'
            
        ], 
        [], $niceNames);
            
    }

    public function deleteForm(Request $request){
        $id        = $request->input('id');
        $user      = User::find($id);
        $form      = view('user.forms.deleteForm', compact('user'))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function softDelete(Request $request){
        $id     = $request->input('id');

        //eliminar bodega del técnico
        $warehouseUser = User::with('warehouse')->find($id)->warehouse;

        if(!is_null($warehouseUser)){

            //verificar si el técnico tiene inventario
            $warehouse = Warehouse::with('inventory')->find($warehouseUser->id);
            if(count($warehouse->inventory) > 0){
                abort('406', 'No se puede eliminar al usuario por que tiene stock de productos  y/o  mecanismos en su bodega, primero se debe dar de baja al inventario de la bodega del técnico o realizar una transferencia de dicho stock.');
            }

            Warehouse::destroy($warehouseUser->id);
        }

        $user   = User::destroy($id);

        return response(["success" => 'true', "message" => $user ? $this->msgDeleted : $this->msgError]); 
    }
    
    public function deleteDocumentForm(Request $request){
        $id        = $request->input('id');
        $document  = File::find($id);
        $form      = view('user.forms.deleteFormDocument', compact('document'))->render();

        return response(['success' => $document? true: false, 'view' => $form]);

    }
    
    public function softDeleteDocument(Request $request)
    {
        $id     = $request->input('id');
        $success   = File::destroy($id);
        return response(["success" => $success, "message" => $success ? $this->msgDeletedDocument : $this->msgError]); 
    }

    public function destroy($id)
    {
        //
    }

    function refreshCsrf(){
        return  csrf_token();
    }

}
