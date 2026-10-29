<?php

namespace App\Http\Controllers\Administrative;

use App\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UnitController extends Controller
{
    public $traceError = "";
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgDeleted = "Unidad de medida eliminada satisfactoriamente";

    public function units(){
        return view('unit.index');
    }
    
    public function Lists(){
        $units = Unit::with('user')->orderBy('id', 'desc')->get();
        $app = "";
        $list = view('unit.sub.list',compact(['units','app']))->render();
        return response(['success' => true, 'view' => $list]);
    }
    
    public function Form(Request $request){
            $id = $request->input('id');
            $unit = Unit::find($id);
            $title = $request->input('id')?'EDITAR UNIDAD':'CREAR UNIDAD';
            $form = view ('unit.forms.unitForm',compact('unit','title'))->render();
            return response (['success'=>true, 'view' => $form,  'title' =>  $title ]);
    }
    
    public function Store(Request $request){
        $unit = new Unit();
        $success = $this->Save($request,$unit);
        return response(["success" => $success, "message" => $success ? "Unidad creada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Update(Request $request){
        $unit = Unit::find($request->input('id'));
        $success = $this->Save($request,$unit);
    
        return response(["success" => $success, "message" => $success ? "Unidad editada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Save(Request $request, Unit $unit){
    
        try {
            DB::beginTransaction();
            $unit->name             =   $request->input('name');
            $unit->enabled          =   $request->filled('enabled') ? $request->input('enabled') : ($request->filled('id') ? 0 : 1);
            $unit->updated_user     =   Auth::User()->id;
            $success=$unit->save();
    
            DB::commit();
            return $success;
        } catch (Exception $exc) {
            DB::rollback();
            $this->traceError = $exc->getMessage();
            return false;
        }
    
    }
    
    public function DeleteForm (Request $request){
        $id          = $request->input('id');
        $unit = Unit::find($id);
        $form        = view('unit.forms.deleteForm', compact('unit'))->render();
    
        return response(['success' => true, 'view' => $form]);
    }
    
    public function SoftDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = Unit::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }
}
