<?php

namespace App\Http\Controllers\Administrative;

use App\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public $traceError = "";
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgDeleted = "Aplicación eliminada satisfactoriamente";

    public function applications(){
        return view('application.index');
    }
    
    public function Lists(){
        $applications = Application::with('user')->orderBy('id', 'desc')->get();
        $app = "";
        $list = view('application.sub.list',compact(['applications','app']))->render();
        return response(['success' => true, 'view' => $list]);
    }
    
    public function Form(Request $request){
            $id = $request->input('id');
            $application = Application::find($id);
            $title = $request->input('id')?'EDITAR APLICACIÓN':'CREAR APLICACIÓN';
            $form = view ('application.forms.applicationForm',compact('application','title'))->render();
            return response (['success'=>true, 'view' => $form,  'title' =>  $title ]);
    }
    
    public function Store(Request $request){
        $application = new Application();
        $success = $this->Save($request,$application);
        return response(["success" => $success, "message" => $success ? "Aplicación creada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Update(Request $request){
        $application = Application::find($request->input('id'));
        $success = $this->Save($request,$application);
    
        return response(["success" => $success, "message" => $success ? "Aplicación editada correctamente" : $this->msgError, 'traceError' => $this->traceError]);
    }
    
    public function Save(Request $request, Application $application){
    
        try {
            DB::beginTransaction();
            $application->name             =   $request->input('name');
            $application->description      =   $request->input('description');
            $application->avatar           =   $this->getAvatar($request);
            $application->enabled          =   $request->filled('enabled') ? $request->input('enabled') : ($request->filled('id') ? 0 : 1);
            $application->updated_user     =   Auth::User()->id;
            $success=$application->save();
    
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
        $application = Application::find($id);
        $form        = view('application.forms.deleteForm', compact('application'))->render();
    
        return response(['success' => true, 'view' => $form]);
    }
    
    public function SoftDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = Application::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }
}
