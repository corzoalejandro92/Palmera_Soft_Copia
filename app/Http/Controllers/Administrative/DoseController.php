<?php

namespace App\Http\Controllers\Administrative;

use App\Dose;
use App\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UnitDose;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoseController extends Controller
{
    public $traceError = "";
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgDeleted = "Dosis eliminada satisfactoriamente";

    public function doses(){
        return view('dose.index');
    }
    
    public function Lists(){
        $doses = Dose::with(['user','unit','headquarter'])->orderBy('id', 'desc')->get();
        $app = "";
        $list = view('dose.sub.list',compact(['doses','app']))->render();
        return response(['success' => true, 'view' => $list]);
    }
    
    public function Form(Request $request){
        $id = $request->input('id');
        $dose = Dose::with('unit')->find($id);
        $title = $request->input('id')?'EDITAR DOSIS':'CREAR DOSIS';
        $form = view ('dose.forms.doseForm',compact('dose','title'))->render();
        return response (['success'=>true, 'view' => $form,  'title' =>  $title ]);
    }

    public function units(Request $request){
        $query = UnitDose::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $units = $query->get();
        return response(['results' => $units]);
    }
    
    public function Store(Request $request){
        $dose = new Dose();
        $success = $this->Save($request,$dose);
        return response(["success" => $success, "message" => $success ? "Dosis creada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Update(Request $request){
        $dose = Dose::find($request->input('id'));
        $success = $this->Save($request,$dose);
    
        return response(["success" => $success, "message" => $success ? "Dosis editada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Save(Request $request, Dose $dose){
    
        try {
            DB::beginTransaction();

            //verificar si el area es nueva o ya existe
            $unitDose = null;
            if($request->filled('unit')){
                if(is_numeric($request->input('unit'))){
                    $unitDose = UnitDose::find($request->input('unit'));
                    if(!$unitDose){
                        $unitDose = UnitDose::firstOrCreate(['name' => $request->input('unit'), 'enabled' => 1]);
                    }
                }else{
                    $unitDose = UnitDose::firstOrCreate(['name' => $request->input('unit'), 'enabled' => 1]);
                }
            }


            $dose->start_range =   $request->input('start_range');
            $dose->end_range   =   $request->input('end_range');
            $dose->unit_dose_id  = $unitDose->id;
            $dose->enabled     =   $request->filled('enabled') ? $request->input('enabled') : ($request->filled('id') ? 0 : 1);
            $dose->updated_user     =   Auth::User()->id;
            $dose->headquarter_id   = session('headquarter_id');
            $success=$dose->save();
    
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
        $dose = Dose::find($id);
        $form        = view('dose.forms.deleteForm', compact('dose'))->render();
    
        return response(['success' => true, 'view' => $form]);
    }
    
    public function SoftDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = Dose::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }
}
