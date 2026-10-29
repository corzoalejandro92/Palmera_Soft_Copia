<?php

namespace App\Http\Controllers\Administrative;

use App\Service;
use App\DetailService;
use App\Headquarter;
use App\Infestation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ParameterService;
use App\QuoteSerInf;
use Illuminate\Support\Facades\DB;
use Exception;

class ServiceController extends Controller
{


    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Servicio agregado correctamente";
    public $msgUpdated = "Servicio actualizado satisfactoriamente";
    public $msgDeleted = "Servicio eliminado satisfactoriamente";
    public $traceError = null;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::select(['id','name','avatar','description'])->get();
        return view ('service.index', compact('services'));
    }

    public function list(Request $request) {
        $service = Service::select(['id','name','avatar','description'])
                ->with('infestations')
                ->where('name', 'LIKE', '%' . $request->input('search') . '%')
                ->orderBy('id', 'desc')
                ->enabled()
                ->get();
        $list = view('service.sub.list', compact('service'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $service = new Service;
        $success = $this->save($request, $service);

        return response(["success" => $success, "message" => $success ? $this->msgSuccess : $this->msgError, "trace" => $this->traceError]);
  
    }

    public function save(Request $request, Service $service){
        
        $this->validateForm($request, $service);
        
        
        try {
            DB::beginTransaction();
            $service->name = $request->input('name');
            $service->avatar = $request->input('avatar');
            $service->description = $request->input('description');
            $service->allow_control_group = $request->filled('allow_control_group');
            
            $service->save();
            //$service->infestations()->sync($request->input('infestations'));

            if($request->filled('specificationService')){
                foreach ($request->input('specificationService') as $key => $detail) {
                    if(isset($detail['zone'])){
                        $detailService = DetailService::updateOrCreate(
                            [
                                'id'=> $detail['id']
                            ],
                            [
                            'service_id' => $service->id,
                            'zone'=>$detail['zone'],
                            'visits'=>$detail['visits'],
                            'cost'=>$this->clear_format($detail['cost']), 
                            'description'=>$detail['description']
                            ] 
                        );
        
                        $detailService->headquarters()->sync($detail['headquarter_id']);
                    }
                    
                }  
            }

            if($request->filled('parameterService')){
                foreach ($request->input('parameterService') as $key => $parameter) {

                    $paramService = ParameterService::updateOrCreate(
                        [
                            'id'=> $parameter['id']
                        ],
                        [
                        'service_id' => $service->id,
                        'item'=>$parameter['item'],
                        'cost'=>$this->clear_format($parameter['cost']), 
                        'description'=>$parameter['description']
                        ] 
                    );
    
                    $paramService->headquarters()->sync($parameter['headquarter_id']);
                }  
            }
            
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            $this->traceError = $exc->getTrace();
            return false;
        }

        
        
    }

    private function validateForm(Request $request, Service $service) {

        $niceNames = [
            'name' => 'Nombre',
            'specificationService'          => 'una fila de listado de precio',
            'specificationService.*.headquarter_id'   => 'sedes',
            'specificationService.*.zone'   => 'cobertura',
            'specificationService.*.cost'   => 'precio',
            'specificationService.*.cost'   => 'visitas',
            'parameterService'          => 'una fila de parámetros adicionales',
            'parameterService.*.item'   => 'item',
            'parameterService.*.cost'   => 'precio',
            'parameterService.*.headquarter_id'   => 'sedes',

        ];
        
        $request->validate([
            'name' => 'required',
            // 'specificationService.*.zone'           => 'required_with:specificationService',
            // 'specificationService.*.visits'           => 'required_with:specificationService',
            // 'specificationService.*.cost'           => 'required_with:specificationService',
            // 'specificationService.*.headquarter_id' => 'required_with:specificationService',
            'parameterService.*.item'               => 'required_with:parameterService',
            'parameterService.*.cost'               => 'required_with:parameterService',
            'parameterService.*.headquarter_id'     => 'required_with:parameterService',

        ], 
        [], $niceNames);
            
    }

    public function infestations(Request $request) 
    {
        $query = Infestation::enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get()->makeHidden(['base64']);
        return response(['results' => $data]);
    }

    public function headquarters(Request $request){
        $query = Headquarter::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $headquarters = $query->get();
        return response(['results' => $headquarters]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id        = $request->input('id');
        $service   = Service::find($id);
        $success   = $this->save($request, $service);

    
        return response(["success" => 'true', "message" => $success ? $this->msgUpdated : $this->msgError,"trace" => $this->traceError]);
    }

    public function deleteForm(Request $request){
        $id        = $request->input('id');
        $service   = Service::find($id);
        $form      = view('service.forms.deleteForm', compact('service'))->render();

        return response(['success' => true, 'view' => $form]);

    }
    
    public function softDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = Service::destroy($id);
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }

    //funciones para formulario dinámico de coberturas y precios

    public function formService(Request $request){
        $id = $request->input('id');
        $service = Service::with(['detailServices' =>function($q){
            $q->with('headquarters');
        },'infestations', 'parameters' => function($parameter){
            $parameter->with('headquarters')->headquarterPivot();
        }])->find($id);

        $directory ='template/assets/icons/svg';
        $avatars = [];
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        foreach ($scanned_directory as $key => $file) {
            $file =  explode('.', $file);
            if($file[1] == 'svg'){
                $avatars[] = 'custom-'.$file[0];
            } 
        }
        $form = view ('service.forms.serviceForm', compact('service','avatars'))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    public function addCoverageRow(Request $request){

        $detailService = null;
        $key= uniqid();
      
        $form = view ('service.forms.frames.coverage_new_row', compact('detailService','key'))->render();
        return response (['success'=>true, 'row' => $form]);
    }

    public function addParameterRow(Request $request){

        $parameter = null;
        $key= uniqid();
      
        $form = view ('service.forms.frames.parameter_new_row', compact('parameter','key'))->render();
        return response (['success'=>true, 'row' => $form]);
    }

    public function deleteCoverageForm(Request $request){
        $id           = $request->input('id');
        $coverage     = DetailService::find($id);
        $form         = view('service.forms.deleteFormCoverage', compact('coverage'))->render();

        return response(['success' => $coverage? true: false, 'view' => $form]);
    }

    public function deleteParameterForm(Request $request){
        $id           = $request->input('id');
        $parameter     = ParameterService::find($id);
        $form         = view('service.forms.deleteFormParameter', compact('parameter'))->render();

        return response(['success' => $parameter? true: false, 'view' => $form]);
    }

    public function deleteCoverage(Request $request){
        $id = $request->input('id');
        $success = DetailService::destroy($id);
        return response(["success" => 'true', "message" => $success ? 'Cobertura eliminada' : $this->msgError]);
    }

    public function deleteParameter(Request $request){
        $id = $request->input('id');
        $success = ParameterService::destroy($id);
        return response(["success" => 'true', "message" => $success ? 'Cobertura eliminada' : $this->msgError]);
    }

}
