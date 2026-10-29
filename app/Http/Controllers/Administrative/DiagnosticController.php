<?php

namespace App\Http\Controllers\Administrative;

use App\Application;
use App\Block;
use App\ControlInfestation;
use Illuminate\Support\Facades\DB;
use App\Diagnostic;
use App\DiagnosticStatus;
use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MethodProduct;
use App\Quote;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class DiagnosticController extends Controller
{

    private $msgError = '';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $diagnostices = Diagnostic::with('file')
        ->where('office_id',$request->office)
        ->get();
        $list = view('crm.sub.listDiagnostices', compact('diagnostices'))->render();
        return response(['success' => true, 'view' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function creator(Request  $request, $quote)
    {

        /*
        * sistema borrador para evitar que se pierda
        * la información de diagnóstico ante cualquier 
        * recarga inesperada o cierre de sesió involuntario
        */
        $quotte=Quote::find($quote);
        $diagnostic = Diagnostic::with('file','status')->firstOrCreate(['quote_id'=> $quote],['created_user_id'=> Auth::user()->id, 'status_id' => 1,'office_id' => $quotte->office->id]);
        $diagnostic->with('file')->get();
        $quote =  Quote::with('crm')->find($quote);
        $blocks = Block::select(['id','name','description','slug'])->where('slug','like','diagnostic%')->get();
        $controlInfestations     = ControlInfestation::enabled()->get();
        $applications = Application::enabled()->get();

        //crear carpetas de almacenamiento de imagenes
     
        if (!file_exists(getcwd().'/public/storage/ckeditor/')) {
            mkdir(getcwd().'/public/storage/ckeditor/', 0777);
        }
        if (!file_exists(getcwd().'/public/storage/ckeditor/clipboard/')) {
            mkdir(getcwd().'/public/storage/ckeditor/clipboard/', 0777);
        }

        return view('diagnostic/creator', compact(['quote','blocks','diagnostic','controlInfestations','applications']));
    }

    public function show(Request  $request, $diagnostic)
    {

        ini_set('memory_limit', '-1');
        exec('mogrify -interlace none *.png');
        /*
        * sistema borrador para evitar que se pierda
        * la información de diagnóstico ante cualquier 
        * recarga inesperada o cierre de sesió involuntario
        */
        $diagnostic = Diagnostic::with('file','status','quote')->find($diagnostic);
       
        try{
            $path = 'public/storage/'.$diagnostic->file->path;
        }catch(\Exception $exc){
            $path = 'images/default/base_plano_diagnostico_f.png';
        }
        
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        // dd($diagnostic);
       /* echo view('diagnostic.formatPdf',compact('diagnostic','base64'))->render();
        die(); */

        $pdf = PDF::loadView('diagnostic.formatPdf',compact('diagnostic','base64'))->setPaper('letter', 'landscape');
        return $pdf->stream();
    }

    public function paint(Request  $request, $quote)
    {

        $mode = 'create';
        $diagnostic             = Diagnostic::firstOrCreate(['quote_id'=> $quote],['created_user_id'=> Auth::user()->id]);
        $quote                  = Quote::select(['id','consecutive'])->find($quote);
        $plan    = File::whereHas('fileType',function($q){
            $q->where('slug',['plan-init']);
        })
        ->where('filable_type', 'App\Diagnostic')
        ->where('filable_id',$diagnostic->id)
        ->get();
        if($plan){
            $plan = $plan->last();
        }
        return view('diagnostic/paint', compact(['quote','diagnostic','mode','plan']));
    }

    public function editPaint(Request  $request, $quote, File $plan)
    {

        $mode = 'edit';
        $diagnostic             = Diagnostic::find($plan->filable_id);
        $quote                  = Quote::select(['id','consecutive'])->find($diagnostic->quote_id);


        return view('diagnostic/paint', compact(['quote','diagnostic','mode','plan']));
    }
    public function creatorUser(Request  $request,$diagnostic=null)
    {

        /*
        * sistema borrador para evitar que se pierda
        * la información de diagnóstico ante cualquier 
        * recarga inesperada o cierre de sesión involuntario
        */
        $diagnostic = Diagnostic::with('file')->firstOrCreate(['id'=> $diagnostic],['created_user_id'=> Auth::user()->id, 'status_id' => 1,'office_id' => $request->office]);
        $blocks = Block::select(['id','name','description','slug'])->where('slug','like','diagnostic%')->get();
        $controlInfestations     = ControlInfestation::enabled()->get();
        $applications = Application::enabled()->get();

        //crear carpetas de almacenamiento de imagenes
     
        if (!file_exists(getcwd().'/public/storage/ckeditor/')) {
            mkdir(getcwd().'/public/storage/ckeditor/', 0777);
        }
        if (!file_exists(getcwd().'/public/storage/ckeditor/clipboard/')) {
            mkdir(getcwd().'/public/storage/ckeditor/clipboard/', 0777);
        }

        return view('diagnostic/creator', compact(['blocks','diagnostic','controlInfestations','applications']));
    }

    public function paintUser(Request  $request,Diagnostic $diagnostic)
    {

        $mode = 'create';
        $plan    = File::whereHas('fileType',function($q){
            $q->where('slug',['plan-init']);
        })
        ->where('filable_type', 'App\Diagnostic')
        ->where('filable_id',$diagnostic->id)
        ->get();
        if($plan){
            $plan = $plan->last();
        }
        return view('diagnostic/paint', compact(['diagnostic','mode','plan']));
    }

    public function editPaintUser(Request  $request, File $plan)
    {

        $mode = 'edit';
        $diagnostic             = Diagnostic::find($plan->filable_id);

        return view('diagnostic/paint', compact(['diagnostic','mode','plan']));
    }

    public function addRowToTable1(Request  $request, $id)
    {

        $infestation = ControlInfestation::find($id);
        $row =  view('diagnostic/frames/subframes/add_row_to_table1', compact(['infestation']))->render();
        return response(["success" => true, "row" => $row]);
    }

    public function addRowToTable3(Request  $request, $id)
    {

        $application = Application::find($id);
        $row =  view('diagnostic/frames/subframes/add_row_to_table3', compact(['application']))->render();
        return response(["success" => true, "row" => $row]);
    }




    public function blockContent(Request $request, $diagnostic, Block $block){

        $diagnostic = Diagnostic::with(['quote.crm','quote.office'])->find($diagnostic);



        //setear el html en la variable publica del controlador principal
        $this->html = json_decode($block->options)->html;
        //  dd($diagnostic->toArray(),$this->html);
        //reemplazar todos los comodines con datos reales
        $this->replaceDiagnostic($diagnostic);

  
        return response (['success'=>true, 'block' => $this->html]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd("will store...",$request->all());
    }

    public function plan(Request $request, $plan){
        
        $diagnostic = Diagnostic::with('file')->find($plan);
        return response(["success" => true, "plan" => '/public/storage/'.$diagnostic->file->path, "id" => $diagnostic->file->id ]);
                
    }

    public function planSave(Request $request){
        
        try {

            $diagnostic = Diagnostic::find($request->input('diagnostic_id'));
            DB::beginTransaction();
                $diagnostic->file()->create([
                    'name'          => "plano_generado_el_".date('Y-m-d').".png",
                    'path'          => $this->getFile($request,'image','diagnostic/plans'),
                ]);
            DB::commit();
            return response(["success" => true, "message" => "Plano generado correctamente, esta pestaña se cerrará para continuar trabajando en mas planos."]);
    
        } catch (\Exception $exc) {
            DB::rollBack();
            return response(["success" => false, "message" => "Se presentó un error guardando el plano.<br><br>".$exc->getMessage()]);
        }                 
    }
    
    public function planUpdate(Request $request){
    

        $file = File::find($request->input('plan_id'));
        $anotherFile = Storage::disk('public')->exists($file->path);
    
        try {
            DB::beginTransaction();
                if($anotherFile){
                    Storage::disk('public')->delete($file->path);
                }
                $file->path = $this->getFile($request,'image','diagnostic/plans');
                $file->save();
            DB::commit();
            return response(["success" => true, "message" => "Plano actualizado correctamente, esta pestaña se cerrará para continuar trabajando en mas planos."]);
    
        } catch (\Exception $exc) {
            DB::rollBack();
            return response(["success" => false, "message" => "Se presentó un error guardando el plano.<br><br>".$exc->getMessage()]);
        }                 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnosi  $diagnosi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $diagnostic = Diagnostic::find($id);
        $response = $this->save($request, $diagnostic);

        return response(['success' => $response, 'message' =>  $response ? 'Diagnóstico actualizado correctamente': 'Error: '.$this->msgError]);
    }

    private function save(Request $request, Diagnostic $diagnostic){
        
        try {
            if($request->filled('header')) $diagnostic->header              = $request->input('header'); else $diagnostic->header = '';
            if($request->filled('table1')) $diagnostic->real_infestations   = $request->input('table1'); else $diagnostic->real_infestations = '';
            if($request->filled('table2')) $diagnostic->future_infestations = $request->input('table2'); else $diagnostic->future_infestations = '';
            if($request->filled('table3')) $diagnostic->control_methods     = $request->input('table3'); else $diagnostic->control_methods = '';
            if($request->filled('table4')) $diagnostic->recomendations      = $request->input('table4'); else $diagnostic->recomendations = '';
            if($request->filled('table5')) $diagnostic->identification      = $request->input('table5'); else $diagnostic->identification = '';
            if($request->filled('footer')) $diagnostic->footer              = $request->input('footer'); else $diagnostic->footer = '';            
            $diagnostic->status_id = $request->input('status_id');
            $diagnostic->updated_user_id = Auth::user()->id;
            
            DB::beginTransaction();
                $diagnostic->save();
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->msgError = $ex->getMessage();
            return false;
        }
    }

    public function status(Request $request){
        $query = DiagnosticStatus::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $status = $query->get();
        return response(['results' => $status]);
    }

    public function deleteDiagnostic(Diagnostic $diagnostic)
    {
        try {
            DB::beginTransaction();
            $success = $diagnostic->delete();
            DB::commit();
            return response(["success" => true, "message" =>  "Diagnostico eliminado correctamente","id"=>$diagnostic->id]);
        } catch (Exception $ex) {
            return response(["success" => false, "message" => "Error al eliminar el Diagnostico"]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Diagnosi  $diagnosi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diagnostic $diagnostic)
    {
        $diagnostic->updated_user_id = Auth::user()->id;
        $diagnostic->save();
        $diagnostic->delete();
        return response(['success' => true, 'message' => 'Diagnóstico eliminado correctamente']);
    }
}
