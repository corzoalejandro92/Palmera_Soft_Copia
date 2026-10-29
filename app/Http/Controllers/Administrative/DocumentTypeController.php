<?php

namespace App\Http\Controllers\Administrative;

use App\Http\Controllers\Controller;
use App\FileType;
use App\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentTypeController extends Controller
{
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Documento agregado correctamente";
    public $msgUpdated = "Documento actualizado satisfactoriamente";
    public $msgDeleted = "Documento eliminado satisfactoriamente";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('documentType.index');
    }

    public function list()
    {
        $documentTypes = FileType::orderBy('id', 'desc')->get();
       
        $list = view('documentType.sub.list', compact('documentTypes'))->render();
        
        return response(['success' => true, 'view' => $list]);
        
    }

    public function form(Request $request){
        $id = $request->input('id');
        $documentType = FileType::find($id);
        $title = $request->input('id')?'EDITAR TIPO DE DOCUMENTO':'CREAR TIPO DE DOCUMENTO';
        $form = view ('documentType.forms.documentTypesForm',compact('title','documentType'))->render();
        return response (['success'=>true, 'view' => $form,  'title' =>  $title ]);
    }

    public function store(Request $request)
    {
        $documentType = new FileType();
        $success = $this->Save($request,$documentType);
        return response(["success" => $success, "message" => $success ? "Documento creado correctamente" : $this->msgError]);
    }

    public function documentType(Request $request){
        $query = FileType::select(['id', 'slug as text']);
        if ($request->input('term')) {
            $query->where('slug', 'LIKE', '%' . $request->input('term') . '%');
        }
        $documentType = $query->get();
        return response(['results' => $documentType]);
    }

    public function update(Request $request)
    {
        $documentType = FileType::find($request->input('id'));
        $documentType->name = $request->input('name'); 
        $documentType->slug = $request->input('slug');
        $documentType->enabled = $request->input('enabled') ? '1' : '0';
        $success = $this->Save($request,$documentType);
        $success=$documentType->save();
        
        return response(["success" => $success, "message" => $success ? "Documento actualizado satisfactoriamente" : $this->msgError]);
    }

    public function Save(Request $request, FileType $documentType){

        try {
            DB::beginTransaction();
            $documentType->name = $request->input('name');
            $documentType->slug = $request->input('slug') ?? 'Otro';
            $success=$documentType->save();
    
            DB::commit();
            return $success;
        } catch (Exception $exc) {
            DB::rollback();
            $this->traceError = $exc->getMessage();
            return false;
        }
    
    }

    public function DeleteForm (Request $request){
        $id = $request->input('id');
        $documentType = FileType::find($id);
        $form = view('documentType.forms.deleteFormDocument', compact('documentType'))->render();
    
        return response(['success' => true, 'view' => $form]);
    }
    
    public function SoftDelete(Request $request)
    {
        $id = $request->input('id');
        $note = FileType::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }

}
