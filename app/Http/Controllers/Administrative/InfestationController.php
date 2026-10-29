<?php

namespace App\Http\Controllers\Administrative;

use App\ControlInfestation;
use App\Infestation;
use App\Service;
use App\FileType;
use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;

class InfestationController extends Controller
{

    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Plaga agregada correctamente";
    public $msgUpdated = "Plaga actualizada satisfactoriamente";
    public $msgDeleted = "Plaga eliminada satisfactoriamente";
    public $msgDeletedDocument = "Archivo eliminado satisfactoriamente";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('infestation.index');
    }
    
    public function list()
    {
        $infestations = Infestation::select(['id','name','avatar','description'])
                ->with('services','plagas')
                ->orderBy('id', 'desc')
                ->get();
        $list = view('infestation.sub.list',compact('infestations'))->render();
        
        return response(['success' => true, 'view' => $list]);

    }

    public function form(Request $request){
        $id             = $request->input('id');
        $infestation    = Infestation::with('services')->find($id);
        $plaga          = null;
        $form           = view('infestation.forms.infestationForm', compact(['infestation','plaga']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function services(Request $request) 
    {
        $query = Service::enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function store(Request $request)
    {

        $infestation = new Infestation;
        $success = $this->save($request, $infestation, "create");

        return response(["success" => $success, "message" => $success ? $this->msgSuccess : $this->msgError]);  
    }

    public function save(Request $request, Infestation $infestation, $action ='create'){
        
        
    
        $this->validateForm($request, $infestation);
        
         try {

            DB::beginTransaction();


            $infestation->avatar = $this->getAvatar($request);
            $infestation->name = $request->input('name');
            $infestation->monthly = $request->input('monthly')? true : false;
            $infestation->description = $request->input('description');
            $infestation->save();
            $infestation->services()->sync($request->input('services'));

            //almacenar todas las plagas asociadas
            $this->savePlagas($request,$infestation, $action);

            //$this->saveFile($request, $infestation, 'infestations', 'file' ,true, 'plaga');
                
            DB::commit();
                return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            return false;
        } 
    }

    private function savePlagas(Request $request, Infestation $infestation){
        if($request->filled('plaga')){
            foreach ($request->all()['plaga'] as $plaga) {
                $plaga = new Request($plaga);

            if($plaga->filled('name')){
                if($plaga->filled('id')){    

                    $bind = [
                        "name" => $plaga->input('name'),
                        "description" => $plaga->input('description'),
                        "causes" => $plaga->input('causes')
                    ];

                    //condicionar si la imagen cambia
                    if($plaga->has('avatar')){
                        $bind["avatar"] = $this->getAvatarFromBinary($plaga);
                    }
    
                    $infestation->plagas()->where("id",$plaga->input('id'))->update($bind);
  
                }else{
                    $infestation->plagas()->create([
                        "name" => $plaga->input('name'),
                        "description" => $plaga->input('description'),
                        "causes" => $plaga->input('causes'),
                        "avatar" => $this->getAvatarFromBinary($plaga),
                    ]);
                }
            

                }
            }
        }
    }

    private function validateForm(Request $request, Infestation $infestation) {
        
        $niceNames = [
            'name' => 'Nombre',
            'document.*.file' => 'Archivo'
        ];
        
        $request->validate([
            'name' => 'required',
            'document.*.file' => 'mimes:jpeg,jpg,png,csv',
        ], 
        [], $niceNames);

    }

    public function update(Request $request)
    {

        $id           = $request->input('id');
        $infestation  = Infestation::find($id);
        $success      = $this->save($request, $infestation, 'update');

    
        return response(["success" => 'true', "message" => $success ? $this->msgUpdated : $this->msgError]);
    }

    public function deleteForm(Request $request){
        $id            = $request->input('id');
        $infestation   = Infestation::find($id);
        $form          = view('infestation.forms.deleteForm', compact('infestation'))->render();

        return response(['success' => true, 'view' => $form]);

    }

    public function softDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = Infestation::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }


    //Añadir nueva fila al formulario de imagenes de plagas

    public function addDocumentRow(Request $request){
        $plaga     = null;
        $key       = uniqid();
        $row       = view('infestation.forms.frames.document_new_row',compact(['key','plaga']))->render();
        return response(['success' => true, 'row' => $row]);
    }

    // Ocultar fila formulario imagenes de plagas

    public function deleteDocumentoForm(Request $request){
        $id        = $request->input('id');
        $plaga     = ControlInfestation::find($id);
        $form      = view('infestation.forms.deleteFormDocument', compact('plaga'))->render();

        return response(['success' => $plaga ? true: false, 'view' => $form]);

    }

    //Eliminar fila formulario de imagenes de plagas

    public function softDeleteDocument(Request $request)
    {
        $id  = $request->input('id');
        $success   = ControlInfestation::destroy($id);
        return response(["success" => $success, "message" => $success ? $this->msgDeletedDocument : $this->msgError]); 
    }

}
