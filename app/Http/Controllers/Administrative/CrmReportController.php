<?php

namespace App\Http\Controllers\Administrative;

use App\AreaOffice;
use App\Office;
use App\Crm;
use Carbon\Carbon;
use App\CrmSchedule;
use App\Infestation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\TechnicalReportType;
use App\TechnicalReport;
use App\ControlInfestation;
use Illuminate\Support\Facades\URL;

class CrmReportController extends Controller
{

    public $traceError = "";
    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Cliente agregado correctamente";
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return view('crm.index');
    }
   
    public function areas(Request $request, $office)
    {
        $query = AreaOffice::select('id', 'name as text')
        ->where('office_id', $office);

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function reportTypes(Request $request)
    {
        $query = TechnicalReportType::select('id', 'name as text')
        ->whereIn('id', [6,7,9]);

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function controls(Request $request)
    {
        $query = Infestation::select('id', 'name as text','avatar');

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();

        $data= $data->makeHidden(['base64']);
        return response(['results' => $data]);
    }

    public function pests(Request $request, $control)
    {
        $query = ControlInfestation::select('id', 'name as text','avatar')->where('infestation_id', $control);

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();

        $data= $data->makeHidden(['base64']);
        return response(['results' => $data]);
    }

    public function getData(Request $request){
        
        //obtener los mecanismos del plano

        $reportsObj = TechnicalReport::with(['file','area','extend' => function ($e){
            $e->withTrashed();
        },'type']);

        $reportsObj->where('area_office_id',$request->input('heatmap_area'));

        if($request->filled('heatmap_control')){
            $reportsObj->whereHas('pest', function($pest)use ($request){
                $pest->where('infestation_id',$request->input('heatmap_control')); 
                if($request->filled('heatmap_pest')){
                    $pest->where('id',$request->input('heatmap_pest')); 
                }
            });
        }else{
            if($request->input('heatmap_pest')){
                $reportsObj->whereHas('pest');
            }
            
        }

        if($request->filled('heatmap_start') && $request->filled('heatmap_end')){
            $reportsObj->whereDate('timestamp','>=', $request->input('heatmap_start'))
            ->whereDate('timestamp','<=', $request->input('heatmap_end'));
        }

        if($request->filled('heatmap_technical_report_type')){
            $reportsObj->where('technical_report_type_id', $request->input('heatmap_technical_report_type'));
        }

        if($request->filled('heatmap_start') && $request->filled('heatmap_end')){
            $reportsObj->whereDate('timestamp','>=', $request->input('heatmap_start'))
            ->whereDate('timestamp','<=', $request->input('heatmap_end'));
        }

        if($request->filled('heatmap_technical_report_type')){
            $reportsObj->where('technical_report_type_id', $request->input('heatmap_technical_report_type'));
        }

        $reports = $reportsObj->get();


        $simpleArea = AreaOffice::with(['file'=> function($ft) use ($request){
            $ft->whereHas('fileType', function($type){
                $type->where('slug','plan-mec');
            });
        }])->find($request->input('heatmap_area'));


        $binary = base64_decode(base64_encode(file_get_contents( $simpleArea->file->url)));
        $temp = getimagesizefromstring($binary);
        unset($temp[3]);
        $simpleArea->file->imageProp = $temp;

        
        //convertir los datos en un array leible por el plugin del mapa de calor
        $data = [];
        foreach ($reports as $key => $report) {
            $quantity = $report->quantity;
            //dump($report->file_extend_id. ' - ' .$quantity);
            if($quantity > 0) {

                $prex =($report->x * $simpleArea->file->imageProp[0])/100;
                $prey =($report->y * $simpleArea->file->imageProp[1])/100;
                
                    
                for ($i=0; $i < $quantity; $i++) { 
                    $x = rand($prex-5, $prex+20);
                    $y = rand($prey-5, $prey+20); 
                    array_push($data, [$x, $y, 2]);
                }
                
            }
        }
        
        if($request->filled('request_origin') && $request->input('request_origin') == 'api'){

            $office = $request->input('office');
            $area = $simpleArea;
            return  view('crm.reports.frames._apiHeatMap', compact(['office','area','data']));
        
        }else{
            return response(['data' => $data, 'area'=> $simpleArea]);
        }
       
    }

    public function reportServices(Request $request){

        $object = CrmSchedule::
        where('office_id', $request->get('office'))
        ->whereIn('status_id',[3,7])
        ->orderBy('date', 'desc');

         $term = '';
        if ($request->filled('search') && $request->filled('search') != '') {
            $term = $request->input('search');
            $object->where(function ($w) use ($term) {
                $w->where('id', 'LIKE', "%$term%");
                $w->orWhereHas('service', function ($s) use ($term) {
                    $s->where('name', 'LIKE', "%$term%");
                });
                $w->orWhereHas('controls', function ($c) use ($term) {
                    $c->whereHas('infestation', function ($i) use ($term) {
                        $i->where('name', 'LIKE', "%$term%");
                    }); 
                });
                $w->orWhereHas('userTrackings', function ($tr) use ($term) {
                    $tr->where('observation','LIKE',"%$term%")
                    ->where('user_tracking_type', 4);
                });
            });
        }

        if ($request->filled('start_date')) {
            $term = carbon::parse($request->input('start_date'))->startOfDay()->toDateString();
            $object->whereDate('date', '>=', $term);
        }

        if ($request->filled('end_date')) {
            $term = carbon::parse($request->input('end_date'))->endOfDay()->toDateString();
            $object->whereDate('date', '<=', $term);
        }

        $object->whereDate('date', '>=', Carbon::createFromFormat('Y-m-d', '2022-01-12')->toDateString());

        $reportServices = $object->paginate($request->filled('perpage') ? $request->input('perpage') : 10);
        //dd($reportServices->toArray());
        $list = view('crm.reports.frames._reportServiceList', compact('reportServices', 'term'))->render();
        return response(['success' => true, 'view' => $list]);
        
    }

    public function getConsolidated(Request $request)
    {

        $office=$request->office;
        $end_date=Carbon::parse($request->end_date)->format('Y-m-d');
        $start_date=Carbon::parse($request->star_date)->format('Y-m-d');
        $schedules=CrmSchedule::whereDate('date','>=',$start_date)->WhereDate('date','<=',$end_date)->where('office_id',$office)->whereHas('status', function ($status){
            $status->where('slug','service-success');
        })->orderBy('date', 'asc')->get();

        if($schedules->count() == 0){
            abort(406,"No existen servicios registrado en este periodo");

        }else{
            $random_name=$request->filled('regenerate') ? "-".Str::random(4) : '';
            $name=$start_date."-".$end_date."-consolidated-".$request->input('office').$random_name;
            $path = storage_path("app/public/crms/$name.pdf");
            $path2=$this->buildConsolidatePath($name);
            $url = URL::signedRoute('consolidated2', $request->only(['office','star_date', 'end_date']));
            $issetFile = Storage::disk('public')->exists($path2);
            $urlajax = url('/')."/storage/app/public/crms/$name.pdf";
            if($issetFile === true){
                return response(["url"=>$urlajax]);
            }

            $command = "google-chrome --headless --disable-gpu --disable-software-rasterizer --no-first-run --no-sandbox --print-to-pdf-no-header --print-to-pdf=$path \"$url\" 2>&1";
            exec($command, $output);
            return response(["url"=>$urlajax]);
        }
    }

    private function buildConsolidatePath($office = "test")
    {
        $path = 'crms/'.$office.'.pdf';
        return $path;
    }
}
