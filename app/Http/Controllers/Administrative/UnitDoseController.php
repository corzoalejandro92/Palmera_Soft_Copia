<?php

namespace App\Http\Controllers\Administrative;

use App\UnitDose;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UnitDoseController extends Controller
{
    public $traceError = "";
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgDeleted = "Unidad de dosis eliminada satisfactoriamente";

    public function unitDoses(){
        return view('unitDose.index');
    }
    
    public function Lists(){
        $doseUnits = UnitDose::with('user')->orderBy('id', 'desc')->get();
        $app = "";
        $list = view('unitDose.sub.list',compact(['doseUnits','app']))->render();
        return response(['success' => true, 'view' => $list]);
    }
    
    public function Form(Request $request){
            $id = $request->input('id');
            $doseUnit = UnitDose::find($id);
            $title = $request->input('id')?'EDITAR UNIDAD':'CREAR UNIDAD';
            $form = view ('unitDose.forms.unitDoseForm',compact('doseUnit','title'))->render();
            return response (['success'=>true, 'view' => $form,  'title' =>  $title ]);
    }
    
    public function Store(Request $request){
        $unit_dose = new UnitDose();
        $success = $this->Save($request,$unit_dose);
        return response(["success" => $success, "message" => $success ? "Unidad creada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Update(Request $request){
        $unit_dose = UnitDose::find($request->input('id'));
        $success = $this->Save($request,$unit_dose);
    
        return response(["success" => $success, "message" => $success ? "Unidad editada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Save(Request $request, UnitDose $unit_dose){
    
        try {
            DB::beginTransaction();
            $unit_dose->name             =   $request->input('name');
            $unit_dose->enabled          =   $request->filled('enabled') ? $request->input('enabled') : ($request->filled('id') ? 0 : 1);
            $unit_dose->user_id     =   Auth::User()->id;
            $success=$unit_dose->save();
    
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
        $doseUnit = UnitDose::find($id);
        $form        = view('unitDose.forms.deleteForm', compact('doseUnit'))->render();
    
        return response(['success' => true, 'view' => $form]);
    }
    
    public function SoftDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = UnitDose::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }
}

