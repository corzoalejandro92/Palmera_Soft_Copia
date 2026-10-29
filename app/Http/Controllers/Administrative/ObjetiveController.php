<?php

namespace App\Http\Controllers\Administrative;

use App\Objetive;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ObjetiveType;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ObjetiveController extends Controller  
{
    public $traceError = "";
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgDeleted = "Meta eliminada satisfactoriamente";

    public function objetives(){
        return view('objetive.index');
    }
    
    public function Lists(){
        $objetives = Objetive::with('type')->orderBy('year', 'desc')->get();
        $app = "";
        $list = view('objetive.sub.list',compact(['objetives','app']))->render();
        return response(['success' => true, 'view' => $list]);
    }
    
    public function Form(Request $request){
        $id = $request->input('id');
        $objetive = Objetive::find($id);
        $type=ObjetiveType::all();
        $title = $request->input('id')?'EDITAR META':'CREAR META';
        $form = view ('objetive.forms.objetiveForm',compact('objetive','title','type'))->render();
        return response (['success'=>true, 'view' => $form,  'title' =>  $title ]);
    }

    
    public function Store(Request $request){
        $objetive = new Objetive();
        $success = $this->Save($request,$objetive);
        return response(["success" => $success, "message" => $success ? "Meta creada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Update(Request $request){
        $objetive = Objetive::find($request->input('id'));
        $success = $this->Save($request,$objetive);
    
        return response(["success" => $success, "message" => $success ? "Meta editada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Save(Request $request, Objetive $objetive){
        
        //validando datos del formulario
        $this->ValidateData($request);
        try {
            DB::beginTransaction();

            $objetive->code   =    $request->input('code');
            $objetive->value  =    $request->input('value');
            $objetive->year   =    $request->input('year');
            $objetive->type_objetive_id   =    $request->input('type');
            $success=$objetive->save();
    
            DB::commit();
            return $success;
        } catch (Exception $exc) {
            DB::rollback();
            $this->traceError = $exc->getMessage();
            return false;
        }
    
    }
    private function ValidateData(Request $request){
        $id=$request->input('id');
        $unique= isset($id) ? "unique:objetives,code,$id" : "unique:objetives,code";
        $niceNames = [
            'value' => 'Valor',
            'year' => 'Año',
            'type' => 'Tipo de Meta'
        ];
        $request->validate(
            [
                'value' => 'required|integer|min:0|max:9223372036854775807',
                'year' => "required|integer|min:2021|max:2050",
                'type' => "required|exists:objetive_types,id",
                'code' => "$unique",
            ],
            ['code.unique' => "Esta meta ya esta registrada"],
            $niceNames
        );
    }
    public function DeleteForm (Request $request){
        $id          = $request->input('id');
        $objetive = Objetive::with('type')->find($id);
        $form        = view('objetive.forms.deleteForm', compact('objetive'))->render();
    
        return response(['success' => true, 'view' => $form]);
    }
    
    public function SoftDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = Objetive::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }
}
