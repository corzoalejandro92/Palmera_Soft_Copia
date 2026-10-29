<?php

namespace App\Http\Controllers\Administrative;

use App\Application;
use App\DetailService;
use App\Infestation;
use App\Periodicity;
use App\Product;
use App\Crm;
use App\Contact;
use App\Quote;
use App\QuoteSerSup;
use App\QuoteService;
use App\QuoteStatus;
use App\Contract;
use App\Service;
use App\User;
use App\Office;
use App\TimeUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use App\AreaOffice;
use App\Block;
use App\CrmArea;
use App\File;
use App\FileType;
use App\QuoteSerInfProd;
use App\General\CrmSchedules;
use App\General\CrmTrackings;
use App\Headquarter;
use App\Mail\QuoteSend;
use App\Notifications\ApprovedQuote;
use App\Notifications\MakeQuoteReminder;
use App\ParameterService;
use App\QuoteTemplate;
use App\Setting;
use Exception;
use Illuminate\Support\Facades\Mail;


class QuoteController extends Controller
{
    private $msgSuccess = "Cotización creada correctamente";
    private $msgSuccessAreasCustomer = "Area creada correctamente";
    private $msgUpated = "Cotización actualizada correctamente";
    private $msgError = "Error procesando la solicitud realizada";
    private $msgSuplyDeleted = "Insumo eliminado correctamente";
    private $traceError = null;
    private $id = null;


    public function index(Request $request)
    {
        $object = Quote::with(['file','services.infestations','headquarter.city','crm','office.city','contact','coordinator','commercial','status','contract', 'file' => function($q){
            $q->where('type_id', FileType::where('slug','quote')->first()->id);
        }])
            ->orderBy('id', 'desc')
            ->QuoteUser()
            ->headquarter()
            ->limit(100);


            $term = '';
            if ($request->filled('search')) {
                $term = $request->input('search');
                $object->where('consecutive', 'LIKE', "%$term%");

                $object->orWhere(function ($w) use ($term) {
                    $w->whereHas('commercial', function ($u) use ($term) {
                        $u->where('name', 'LIKE', "%$term%");
                    });
                });
                $object->orWhere(function ($w) use ($term) {
                    $w->whereHas('crm', function ($u) use ($term) {
                        $u->where('name', 'LIKE', "%$term%");
                        $u->orWhere('commercial_name', 'LIKE', "%$term%");
                    });
                });
                $object->orWhere(function ($w) use ($term) {
                    $w->whereHas('services', function ($services) use ($term) {
                        $services->whereHas('service', function ($u) use ($term) {
                            $u->where('name', 'LIKE', "%$term%");
                        });
                    });
                });
                $object->orWhere(function ($w) use ($term) {
                    $w->whereHas('status', function ($u) use ($term) {
                        $u->where('name', 'LIKE', "%$term%");
                    });
                });

            }

            $date = '';
            if ($request->filled('date')) {

                $date = $request->input('date');
                $object->whereDate('created_at', $date);
                $object->orWhereDate('approved_at', $date);

            }
            $quotes = $object->headquarter()->paginate($request->filled('perpage') ? $request->input('perpage') : 10);
    
                if($request->ajax()){
                    $list = view('quote.sub.list', compact('quotes'))->render();
                    return response(['success' => true, 'view' => $list]);
                } else {
                    return view('quote.index', compact('quotes'));
                }

    }

    public function list(Request $request) {
        $object = Quote::with(['file','services.infestations','headquarter.city','crm','office.city','contact','coordinator','commercial','status','contract', 'file' => function($q){
            $q->where('type_id', FileType::where('slug','quote')->first()->id);
        }])
            ->orderBy('id', 'desc')
            ->QuoteUser()
            ->headquarter()
            ->limit(100);
            //->get();

            $term = '';
            if ($request->filled('search')) {
                $term = $request->input('search');
                $object->where('name', 'LIKE', "%$term%");
                $object->orwhere('commercial_name', 'LIKE', "%$term%");
                $object->orwhere('document', 'LIKE', "%$term%");
                
            }
            $quotes = $object->headquarter()->paginate($request->filled('perpage') ? $request->input('perpage') : 10);
    
                if($request->ajax()){
                    $list = view('quote.sub.list', compact('quotes'))->render();
                    return response(['success' => true, 'view' => $list]);
                } else {
                    return view('quote.index', compact('quotes'));
                }
        /* $list = view('quote.sub.list', compact('quotes'))->render();

        return response(['success' => true, 'view' => $list]); */
    }

    public function listConfig() {
        $templates = QuoteTemplate::with(['headquarters','user'])
            ->orderBy('id', 'desc')
            ->headquarterPivot()
            ->get();
        $list = view('quote.sub.listConfig', compact('templates'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function deleteConfig(Request $request, $id){
        $success = QuoteTemplate::find($id)->delete();
        if($success){
            return response(["success" => true,  "message" => 'Formato eliminado correctamente']);
        }else{
            return response(["success" => false,  "message" => 'No se pudo eliminar este formato']);
        }
    }

    public function configForm(Request $request) {

        $id             = $request->input('format');
        $template       = QuoteTemplate::with(['headquarters','user'])
                            ->headquarterPivot()
                            ->find($id);

        $blocks         = Block::all();
        $form           = view('quote.forms.templateForm', compact(['template','blocks']))->render();

        return response(['success' => true,  'view' => $form]);
    
    }

    public function headquarters(Request $request){
        $query = Headquarter::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function updateTemplate(Request $request){
        $quoteTemplate =  QuoteTemplate::find($request->input('id'));
        $success =  $this->saveTemplate($request, $quoteTemplate);

        return response(['success' => $success, 'message' =>  $success ? 'Formato guardado correctamente' : 'No se pudo guardar el formato: '. $this->msgError]);
    }

    public function storeTemplate(Request $request){
        $quoteTemplate =  new QuoteTemplate();
        $success =  $this->saveTemplate($request, $quoteTemplate);

        return response(['success' => $success, 'message' =>  $success ? 'Formato guardado correctamente' : 'No se pudo guardar el formato: '. $this->msgError]);
    }

    public function saveTemplate(Request $request, QuoteTemplate $quoteTemplate){
        $this->validateTemplate($request);
        try {
            DB::beginTransaction();
                $quoteTemplate->name        = $request->input('name');
                $quoteTemplate->content     = $request->input('body');
                $quoteTemplate->user_id     = Auth::User()->id;
                $quoteTemplate->save();
                $quoteTemplate->headquarters()->sync($request->input('headquarters'));
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->msgError = $ex->getMessage();
            return false;
        }
    }

    private function validateTemplate(Request $request){

        $niceNames = [
            'name' => 'Nombre Formato',
            'headquarters' => 'Sedes',
            'body' => 'Diseño de la plantilla',
        ];

        $rules = [
            'name' => 'required',
            'headquarters' => 'required',
            'body' => 'required'
        ];

        return $request->validate($rules, [], $niceNames);
    }

    public function blockContent(Request $request, Block $block){
        return response (['success'=>true, 'block' => $block]);
    }

    public function formats(Request $request){
        $query = QuoteTemplate::select(['id', 'name as text'])->headquarterPivot();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function format(Request $request, QuoteTemplate $format){

        //setear el html en la variable publica del controlador principal
        $this->html = $format->content;
       

        //obtener la cotización a generar
        $quote = Quote::with(['services'=>function($q){
            $q->with('detailService','infestations.infestation')->where('genuine_service',1);
        }],'crm','office','contact','coordinator','commercial','status')
        ->headquarter()
        ->find($request->input('quote'));

        //reemplazar todos los comodines con datos reales
        $this->replaceQuote($quote);


        return response(['success' => true, 'content' => $this->html]);
    }

    public function aplyFormat(Request $request){

        try {
            DB::beginTransaction();
                $quote = Quote::find($request->input('id'));
                $format_html_before  = $quote->format_html;

                $quote->format_id = $request->input('format');
                $quote->format_html = $request->input('body');
                $quote->save();

                //recordatorio de añadir evento a la agenda
                if($format_html_before == "" || is_null($format_html_before)){
                    $quote->commercial()->get()->last()->notify(new MakeQuoteReminder($quote));
                }
                

                $success = true;
            DB::commit();
            
        } catch (Exception $ex){
            DB::rollBack();
            $success = false;
            $this->msgError .= ' '. $ex->getMessage();
            $this->traceError = $ex->getTrace();
        }
        

        return response(['success' => $success, 'message' => $success ? $this->msgSuccess : $this->msgError, 'trace' => $this->traceError]);
    }

    public function destroy(Request $request, Quote $quote ){
        $success = $quote->delete();    
        return response(["success" => $success, "message" => $success ? "Cotización eliminada correctamente": "No se pudo eliminar la cotización"]);
    }   

    public function areaList(Request $request) {
        $id = $request->input('id');
        $service = QuoteService::with(['AreaOffices', 'quote'])->find($id);
        $quote =  $service->quote;
        $list = view('quote.forms.areaForm', compact('service','quote'))->render();

        return response(['success' => true, 'view' => $list]);
    }


    public function create()
    {
        $quote= new Quote();
        return view('quote.basic',compact('quote'));
        
    }

    public function sendEmail(Request $request)
    {
        $id = $request->input('id');
        $email = Contact::selectRaw("id, email as text, 1 as 'selected'")
        ->where('crm_id',$id)->get()->first();
        $form = view ('quote.forms.sendEmail',compact('email'))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    public function documentDiagnosis(Request $request)
    {
        $quote      = $request->input('id');
        $fileType   = FileType::where('slug','quote')->first();
        $form       = view ('quote.forms.documentQuote', compact(['quote','fileType']))->render();
        
        return response (['success'=>true, 'view' => $form]);
    }

    public function generalPdf($id)
    {
        $quote = Quote::with(['services.infestations.infestation','services.service','crm','coordinator','commercial','status'])
            ->orderBy('id', 'desc')
            ->headquarter()
            ->find($id);


            $view = view('quote.sub.quotePdf',compact('quote'))->render();
            dd($view);
            $pdf = PDF::loadHtml($view)->setPaper('letter', 'landscape');
            return $pdf->stream();
        
    }

    public function formatPdf($id)
    {
        $quote = Quote::with(['status'])
            ->headquarter()
            ->find($id);

        $headquarter =  Headquarter::find(session('headquarter_id'));

        // echo view('quote.sub.formatPdf',compact('quote'))->render();
        // die();

        $view = view('quote.sub.formatPdf',compact('quote','headquarter'))->render();


        $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');
       
        //$pdf = PDF::loadView('quote.sub.formatPdf',compact('quote'))->setPaper('letter', 'portrait');
        return $pdf->stream();
        
    }

    public function send(Request $request, $quote){

        $quote = Quote::with(['status','file'])
            ->headquarter()
            ->find($quote);

            $headquarter =  Headquarter::find(session('headquarter_id'));

        // echo view('quote.sub.formatPdf',compact('quote'))->render();
        // die();

        //$pdf = PDF::loadView('quote.sub.formatPdf',compact('quote','headquarter'))->setPaper('letter', 'portrait');
        
    
        //mandar correo
        try{
            Mail::to($request->input('destinatary'))->send(new QuoteSend($request,$quote));
            return response(['success' => true, 'message' => 'Cotización enviada correctamente']);
        }catch(Exception $ex){
            return response(['success' => false, 'message' => $ex->getMessage()]);
        }
        
    }


    public function validateStep(Request $request,$step){
        switch ($step){
            case 3:
                $niceNames = ['services.*.periodicity_id' => 'Periodicidad','services.*.infestations' => 'Control', 'services.*.price' => 'Precio', 'services.*.visits' => 'Visitas', 'services.*.detailService' =>'Cobertura','services.*.service' =>'Servicio'];
                $rules = ['services.*.periodicity_id' => 'required','services.*.price' => 'required', 'services.*.visits' => 'required', 'services.*.detailService' => 'required', 'services.*.service' => 'required'];
                break;
            case 1:
                $niceNames = ['crm_id' => 'Cliente', 'office_id' => 'Sucursal', 'contact_id' => 'Contacto'];
                $rules = ['crm_id' => 'required', 'office_id' => 'required', 'contact_id' => 'required'];
                break;
            default:
                $niceNames = [];
                $rules = [];
                break;

        }
        $request->validate($rules, [], $niceNames);
        return response(['continue'=>true]);
    }

    public function edit($quote)
    {
        $quote = Quote::with(['services'=>function($q){
            $q->with(['infestations.infestation','parameters.parameter','periodicity','service.parameters'=>function($parameter){
                $parameter->headquarterPivot();
            }])->where('genuine_service',1);
        }],'crm','office','contact','coordinator','commercial','status','format')
        ->headquarter()
        ->find($quote);


        if(isset($quote->id)){
            return view('quote.basic',compact('quote'));
        }else{
            return redirect('quotes');
        }
    }

    public function store(Request $request){
        $quote = new Quote();
        $success = $this->save($request,$quote);

        return response(["success" => $success, "id" => $this->id, "message" => $success ? $this->msgSuccess : $this->msgError]);
    }

    public function templateFrame(Request $request){
        $quote = Quote::find($request->input('id'));

        $view = view('quote/forms/steps/template', compact(['quote']))->render();

        return response(["success" => true, "html" => $view ]);
    }

    public function storeplanos(Request $request){

        if($request->has('services')){
            foreach($request->all()['services'] as $service){
                $service = new Request($service);
                if($service->has('areas')){
                    foreach($service->all()['areas'] as $area_id => $area){
                        $area = new Request($area);
                        if($area->has('plan')){
                            $AreaOffice = AreaOffice::find($area_id);
                            
                            $this->saveFile($area, $AreaOffice,'quotes/plans', 'file', true, 'plan');
                        }
                    }
                }
            }
        }
        

        return response(["success" => true, "message" => $this->msgSuccessAreasCustomer]);
    }

    public function storeDocumentoQuote(Request $request){
        //almacenar los documentos enviados
        if($request->has('document')){
            $quote =  Quote::find($request->input('quote_id'));
            $this->saveFile($request, $quote, 'quotes','document',false);
            return response(["success" => true,  "message" => 'Archivo guardado correctamente']);
        }else{
            return response(["success" => false,  "message" => 'No se pudo encontrar la cotización que desea asociar.']);
        }
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $quote = Quote::find($id);
        $success = $this->save($request,$quote, 'update');
        return response(["success" => $success, "id" => $this->id, "message" => $success ? $this->msgUpated : $this->msgError, 'trace' => $this->traceError]);
    }

    private function save(Request $request, Quote $quote, $action="create"){

        try {
            DB::beginTransaction();
        $consSaveAsId = false;
        if($action == 'create'){
            $consecutive = Setting::select('quote')->where('headquarter_id',session('headquarter_id'))->orderBy('id','DESC')->limit(1)->get();
            if($consecutive->count() > 0){
                $consSaveAsId = false;
                $quote->consecutive = $consecutive->last()->quote + 1;
                Setting::where('headquarter_id',session('headquarter_id'))->update(['quote'=> $quote->consecutive]);
            }else{
                $consSaveAsId = true;
            }
        }
        

        if(!$quote->id){
            $quote->commercial_id = Auth::user()->id;
            $quote->headquarter_id = session('headquarter_id');
        }

        $this->bindCrmData($request,$quote);
    
        $quote->possible_date = Carbon::createFromFormat('d/m/Y',$request->input('possible_date_submit'))->format('Y-m-d H:i:s');
        $quote->diagnostic = $request->input('diagnostic') ?? 0;
        if($quote->diagnostic) $quote->coordinator_id = $request->input('coordinator_id');
        $quote->has_discount = $request->input('has_discount') ?? 0;
        if($quote->has_discount){
            $quote->discount_percent = $request->input('discount_percent');
            $quote->discount_value = $this->clear_format($request->input('discount_value'));
        }
        $quote->expire_date = Carbon::createFromFormat('d/m/Y',$request->input('expire_date_submit'))->format('Y-m-d H:i:s');
        $quote->status_id = 1;

        
                $quote->save();
                if($consSaveAsId){
                    $quote->consecutive = $quote->id;
                    $quote->save();
                }
                //almacenar los servicios de esta cotización
                $continue = $this->saveServices($request, $quote);
                if(! $continue){
                    DB::rollback();
                    return false;
                }

                //calcular precio de la cotización

                $subtotalParam = $this->getPriceParameterAllServices($request);

                $quote->subtotal = $quote->services->where('genuine_service',1)->pluck('price')->sum();
                $quote->total = ($quote->subtotal+$subtotalParam)-$quote->discount_value;
                $quote->save();
                $this->id = $quote->id;

                //registro del evento en el seguimiento del cliente
                CrmTrackings::report($request, $quote, 'quote', $action);

                //crear notificacion de recordatorio
                $this->manageQuoteStatusNotification($quote);


            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError .= ' : ' . $exc->getMessage();
            $this->traceError = $exc->getTrace();
            return false;
        }
    }

    private function bindCrmData(Request $request, Quote &$quote){
        //verificar si el cliente es nuevo o ya existe
        $crm = null;
        if($request->input('crm_id')){
            if(is_numeric($request->input('crm_id'))){
                $crm = Crm::find($request->input('crm_id'));
                if(!$crm){
                    $crm = Crm::firstOrCreate(['name' => $request->input('crm_id'), 'enabled' => 1],["user_id" => Auth::user()->id]);
                }
            }else{
                $crm = Crm::firstOrCreate(['name' => $request->input('crm_id'), 'enabled' => 1],["user_id" => Auth::user()->id]);
            }
        }

        //verificar si la sucursal es nueva o ya existe
        $office = null;
        if($request->input('office_id')){
            if(is_numeric($request->input('office_id'))){
                $office = Office::find($request->input('office_id'));
                if(!$office){
                    $office = Office::firstOrCreate(
                        [
                            'name' => $request->input('office_id'), 
                            "crm_id" => $crm->id ,
                            'enabled' => 1
                        ],
                        [
                            "headquarter_id" => Auth::user()->headquarter->id,
                            "city_id"        => Auth::user()->headquarter->city_id,
                            "address"        => $request->input('office_id')
                        ]);
                }
            }else{
                $office = Office::firstOrCreate(
                    [
                        'name' => $request->input('office_id'), 
                        "crm_id" => $crm->id ,
                        'enabled' => 1
                    ],
                    [
                        "headquarter_id" => Auth::user()->headquarter->id,
                        "city_id"        => Auth::user()->headquarter->city_id,
                        "address"        => $request->input('office_id')
                    ]);
            }
        }

        //verificar si el contacto es nuevo o ya existe
        $contact = null;
        if($request->input('contact_id')){
            if(is_numeric($request->input('contact_id'))){
                $contact = Contact::find($request->input('contact_id'));
                if(!$contact){
                    $contact = Contact::firstOrCreate(
                        [
                            'name' => $request->input('contact_id'), 
                            'enabled' => 1
                        ]);

                    $office->contacts()->sync([$contact->id]);
                }
            }else{
                $contact = Contact::firstOrCreate(
                    [
                        'name' => $request->input('contact_id'), 
                        'enabled' => 1
                    ]);

                $office->contacts()->sync([$contact->id]);
            }
        }

        $quote->crm_id = $crm->id;
        $quote->office_id = $office->id;
        $quote->contact_id = $contact->id;

    }

    private function getPriceParameterAllServices(Request $request){
        $sum = 0;
        foreach ($request->services as $iservice => $service) {
            if(isset($service['parameter'])){
                foreach ($service['parameter'] as $iparam => $parameter) {
                    $sum += $this->clear_format($parameter['value']);
                   }
            }
           
        }
        return $sum;
    }



    public function saveServices(Request $request, Quote $quote){
        if($request->has('services')){
            $success = true;
            foreach ($request->input('services') as $service){
                $service = new Request($service);
                $quoteService = null;
                $success = $this->_saveServices($quote,$service,$quoteService);
                if(!$success){
                    return false;
                }
            }
            return $success;
        }else{
            $service = $request;
            $quoteService = null;
            $success =  $this->_saveServices($quote,$service,$quoteService);
            return response(["success" => $success,  "message" => $success ? 'Servicio añadido al cronograma' : $this->msgError]);
        }
        
    }

    private function _saveServices($quote, $service,$quoteService){
            
        try {
            DB::beginTransaction();
            //actualizar
                if($service->has('id')){
                    $quoteService = QuoteService::find($service->input('id'));
                    $quoteService->service_id = $service->input('service');
                    $quoteService->coverage_id = $service->input('detailService');
                    $quoteService->coverage = DetailService::find($service->input('detailService'))->zone;
                    $quoteService->quantity = $this->clear_format($service->input('quantity'));
                    $quoteService->price = $this->clear_format($service->input('price'));
                    $quoteService->visits = $this->clear_format($service->input('visits'));
                    $quoteService->periodicity_id = $this->clear_format($service->input('periodicity_id'));
                    $quoteService->observation = $service->input('observation');
                    $quoteService->public_observation = $service->input('public_observation');
                    $quoteService->save();

                    //eliminar la asociación de los controles y crearlos nuevamente
                    $quoteService->infestations()->delete();

                    if($service->filled('infestations')){
                        foreach ($service->input('infestations') as $infestation) {
                            $qsi = $quoteService->infestations()->create([
                                'infestation_id' => $infestation
                            ]);
                        }
                    } 

                    //eliminar la asociación  de los parámetros y crearlos nuevamente
                    $quoteService->parameters()->delete();
                    if($service->filled('parameter')){
                        foreach ($service->input('parameter') as $parameter) {
                            $qsp = $quoteService->parameters()->create([
                                'parameter_service_id' => $parameter['id'],
                                'price' => $this->clear_format($parameter['value'])
                            ]);
                        }
                    }

                }else{
                    //crear
                    if( $service->has('detailService')){
                        $quoteService = $quote->services()->create([
                            "service_id" => $service->input('service'),
                            "coverage_id" => $service->input('detailService'),
                            "coverage" => DetailService::find($service->input('detailService'))->zone,
                            "quantity" => $this->clear_format($service->input('quantity')),
                            "price" => $this->clear_format($service->input('price')),
                            "visits" => $this->clear_format($service->input('visits')),
                            "observation" => $service->input('observation'),
                            "public_observation" => $service->input('public_observation'),
                            "periodicity_id" => $service->input('periodicity_id')
                        ]);

                        //guardar los controles
                        if($service->has('infestations')){
                            foreach ($service->input('infestations') as $infestation) {
                                $qsi = $quoteService->infestations()->create([
                                    'infestation_id' => $infestation
                                ]);
                            }
                        }

                        //guardar los parámetros
                        if($service->has('parameter')){
                            foreach ($service->input('parameter') as $parameter) {
                                $qsp = $quoteService->parameters()->create([
                                    'parameter_service_id' => $parameter['id'],
                                    'price' =>  $this->clear_format($parameter['value']),
                                ]);
                            }
                        }

                    }else{
                        $quoteService = $quote->services()->create(["service_id" => $service->input('service'),"genuine_service"=> 0, 'visits' =>1]);
                    }
                    
                }
            DB::commit();
            return true;
        } catch (Exception $ex) {
            $this->msgError =  $ex->getMessage();
            return false;
        }
    }

    public function emailContact(Request $request){
        $id = $request->input('id');
        $query = Contact::selectRaw("id, email as text, 1 as 'selected'")
        ->where('crm_id',$id);
        if ($request->input('term')) {
            $query->where('email', 'LIKE', '%' . $request->input('term') . '%');
        }
        $emailContact = $query->get();
        if($emailContact){
            $emailContact->map(function($item){
                $item->selected = true;
                return $item;
            });
        }
        return response(['results' => $emailContact]);
    }

    public function customer (Request $request){

        $query = Crm::with('crmCategory','offices.headquarter','user')->commercialCrm()->enabled();
        if ($request->input('term')) {
            $query->where(function($w) use($request){
                $w->where('name', 'LIKE', '%' . $request->input('term') . '%')
                ->orWhere('commercial_name', 'LIKE', '%' . $request->input('term') . '%');
            });
        }
        $customer = $query->limit(30)->orderBy('created_at','desc')->get();
        return response(['results' => $customer]);
    }


    public function offices(Request $request, $customerOffices = null){

        $query = Office::selectRaw("*")->where('crm_id',$customerOffices);
        

        if($customerOffices!=null){
            $crm = Crm::find($customerOffices);
            if($crm && $crm->customer_national){
                $contactOffice  = $query->get();
            }else{
                $contactOffice = $query->headquarter('true')->get();
            }
        }else{
            $contactOffice = $query->headquarter('true')->get();
        }


        if ($request->input('term')) {
            $contactOffice =  $contactOffice->filter(function ($item) use ($request) {
                // replace stristr with your choice of matching function
                $result = stripos(strtolower($item->name), $request->input('term'));
                if(false === $result){
                    $result = stripos(strtolower($item->full_address), $request->input('term'));
                }
                return false !== $result;
            })->values()->all();
        }    
        
        return response(['results' => $contactOffice]);
    }

     public function contactCustomer($customerContact){
      
         $query = Contact::select(['id', 'name as text'])
         ->whereHas('offices',function($query) use($customerContact)
         {
            return $query->where('office_id',$customerContact);
         })->whereHas('types',function($query)
         {
            return $query->where('contact_type_id',1);
         });
         $contactCustomer = $query->get();
        
         return response(['results' => $contactCustomer]);

    }

    public function coordinator(Request $request){
        $query = User::with('headquarter')
        ->whereHas('roles',function($q){
            $q->where('slug','mip_coordinator');
        })->headquarter();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $user = $query->get();
        return response(['results' => $user]);
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

    public function deleteServiceForm(Request $request){
        $id           = $request->input('id');
        $service       = QuoteService::with('service')->find($id);
        $form         = view('quote.forms.deleteFormService', compact('service'))->render();

        return response(['success' => $service ? true: false, 'view' => $form]);
    }

    public function coverages(Request $request, $service) 
    {
        $query = DetailService::select(['id', 'zone as text', 'cost', 'visits'])
            ->headquarterPivot()
            ->enabled()
            ->where('service_id',$service);
        if ($request->input('term')) {
            $query->where('zone', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
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

    public function addServiceRow(Request $request)
    {
        $service    = null;
        $key       = uniqid();
        $quoteId = $request->input('quoteId');
        $row       = view('quote.forms.frames.service_new_row',compact(['key','service','quoteId']))->render();
        return response(['success' => true, 'row' => $row, 'key'=>$key]);
    }

    public function parameters(Request $request, $service){
        $key = $request->input('key');
        $parameters = ParameterService::where('service_id', $service)->headquarterPivot()->get();
        $quoteParameters =  collect([]);
        $view = view('quote/forms/frames/add_parameter_row',compact(['parameters','key','quoteParameters']))->render();
        return response(['success' => true, 'view' => $view]);
        
    }

    public function resume(Request $request){

        //reemplazar lo valores en la collección
        $quote = Quote::find($request->input('id'));
        $services = collect([]);
        if($request->has('services')){
            foreach ($request->input('services') as $idx =>$detail){
                $detail['detailService'] = DetailService::find($detail['detailService']);
                $detail['infestations'] = isset($detail['infestations']) ? Infestation::find($detail['infestations']) : [];
                $service =  Service::find($detail['service']);
                $parameterValue = $this->getPriceParameterPerService($detail);
                $detail['price'] = $this->clear_format($detail['price'])+ $parameterValue;
                if($service) $services->put($idx,collect($service)->merge($detail));

            }
            $view  = view('quote.forms.steps.review',compact(['services','quote']))->render();
            return response(['success' => true, 'view' => $view]);
        }else{
            return response(['success' => false, 'message' => 'Debes agregar almenos un servicio a esta cotización para poder continuar con el resumen.']);
        }
    }
    
    private function getPriceParameterPerService($detail){
        $value = 0;
        if(isset($detail['parameter'])){
            foreach($detail['parameter'] as $parameter){
                $value += $this->clear_format($parameter['value']);
            }
        }
        
        return $value;
    }


    public function typeFilePlan(Request $request){
        $query = FileType::select(['id', 'name as text'])->whereIn('slug',['plan-init','plan-mec','plan-risk','plan-route','plan']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $typeFilePlan = $query->get();
        return response(['results' => $typeFilePlan]);
    }

    public function addPlanRow(Request $request){
        $area      = AreaOffice::find($request->input('area'));
        $service   = QuoteService::find($request->input('service'));
        $quote     = Quote::find($request->input('quote'));
        $plan      = null;
        $key       = uniqid();
        $row       = view('quote.forms.frames.planos_new_row',compact(['key','area','service','plan','quote']))->render();
        return response(['success' => true, 'row' => $row]);
    }

    public function applications(Request $request) {
        $query = Application::select(['id','name as text']);

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function products(Request $request, $category, $control) {
        $query = Product::with('unit')->enabled();

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->limit(30)->get();
        return response(['results' => $data]);
    }

    /** Funcioniones para submodulo de insumos y periodicidades (cronograma de actividades)*/

    public function suppliesForm(Request $request){
        $id             = $request->input('quote');
        $quote          = Quote::with(['services'=>function($q){
                                $q->with(['periodicity','infestations' => function($sq){
                                    $sq->with(['periodicity','infestation','products' => function ($ssq){
                                        $ssq->with('application','area','product');
                                    }]);
                                }])->enabled();
                        }])->find($id);

        $timeUnits      = TimeUnit::all();
        $form           = view('quote.forms.suppliesForm', compact(['quote','timeUnits']))->render();
        $serviceActive = $quote->services->first()->service->id;

        return response(['success' => true, 'serviceActive' => $serviceActive, 'view' => $form, 'edit' => $quote ? true: false]);
    }

    public function addControlFrame(Request $request){
        $infestation = (object) ["infestation" => [], "areas" => [], "periodicity" => []];
        $service = QuoteService::find($request->input('service'));
        $infestation->infestation = Infestation::find($request->input('control'));
        $key = $request->input('control');
        $suplyNewRowFrameKey = uniqid();
        $timeUnits  = TimeUnit::all();
        $html     = view('quote.forms.frames.control_supply',compact(['service','infestation','timeUnits','suplyNewRowFrameKey']))->render();
        return response(['success' => true, 'html' => $html, 'key' => $key, 'suplyNewRowFrameKey' => $suplyNewRowFrameKey]);

    }

    public function periodicities(Request $request) {
        $query = Periodicity::select('id','name as text')->enabled();

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    /**
     * Estos son las areas nuevas vigentes para los cronogramas
     */
    public function crmAreas(Request $request) {
        $query = CrmArea::select('id','name as text')->enabled();

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function areasCustomer(Request $request) {
        $quote = Quote::find($request->input('quote'));

        $query = AreaOffice::select('id','name as text')
        ->where('crm_id',$quote->crm_id);

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }

    public function addSupplyRow(Request $request){
        $key = uniqid();
        $supply= null;
        $quoteServiceId = $request->input('quoteServiceId');
        $serviceId = $request->input('serviceId');
        $infestationId = $request->input('infestationId');
        $control = $request->input('control');

        $timeUnits      = TimeUnit::all();


        $row       = view('quote.forms.frames.supply_new_row',compact(['key','supply','quoteServiceId','serviceId', 'infestationId', 'control','timeUnits']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    public function storeSupplies(Request $request){

       $this->validateSupplies($request);
       //buscar los datos del cliente por medio de la cotización
       $quote =   Quote::find($request->input('id'));
       $quote = Quote::find($request->input('id'));
       $createSchedule = false;
       if($quote){
        try {
            DB::beginTransaction();
            foreach ($request->input('services') as $service){
                $service = new Request($service);
                if($service->has('id')){
                    $quoteService = QuoteService::find($service->id);
                    if($quoteService){
                     $quoteService->enabled        = 1;
                        if($service->filled('visits')){ 

                                $quoteService->visits     = $service->input('visits');
                        }

                        //si el servicio a guardar no es originario de la cotización
                        if($service->has('price')) $quoteService->price = $this->clear_format($service->input('price'));
                        if($service->has('detail_service_id')) $quoteService->coverage = DetailService::find($service->input('detail_service_id'))->zone;
                        if($service->has('detail_service_id')) $quoteService->coverage_id = $service->input('detail_service_id');

 
                        $quoteService->save();

                        if($service->has('control')){
                            //sincronizar controles
                            $createSchedule = true;
                            $this->syncControls($service, $quoteService);
                        }
                    }else{
                        DB::rollback();
                        return response(["success" => false,  "message" => "Uno o mas servicios de esta cotización ya no se encuentran disponibles,por favor, cierre este formulario y vuelva a intentarlo nuevamente"]);
                    }
                }else{
                    DB::rollback();
                    return response(["success" => false,  "message" => "Esta cotización no tiene servicios relacionados, debe editar la cotización e intentarlo nuevamente"]);
                }
            }

            //generar cronograma del cliente
            // if($createSchedule){
            //     $continue = CrmSchedules::buildFromQuoteRequest($request, $quote->crm_id, $quote->id, $quote->office_id);
            //     if(!$continue){
            //         DB::rollback();
            //         return response(["success" => false,  "message" => CrmSchedules::$error, "location" => 2]);
            //     }
            // }
            

            DB::commit();
            return response(["success" => true,  "message" => $this->msgUpated]);
           
        } catch (Exception $exc) {
            DB::rollback();
            return response(["success" => false,  "message" => $exc->getMessage(),  "location" => 1]);
        }
       }else{
            DB::rollback();
           return response(["success" => false,  "message" => "La cotización que desea actualizar ya no se encuentra disponible"]);
       }
    }

    private function validateSupplies(Request $request){

        $niceNames = [
            'services.*.control.*.periodicity_id' => 'Periodicidad',
            'services.*.control.*.time_value' => 'Duración',
            'services.*.control.*.time_unit_id' => 'Duración',
            'services.*.control.*.visits' => 'Visitas',
            'services.*.control.*.start_date' => 'Posible fecha de ejecución',
        ];

        $rules = [
            'services.*.control.*.periodicity_id' => 'required',
            'services.*.control.*.time_value' => 'required',
            'services.*.control.*.time_unit_id' => 'required',
            'services.*.control.*.visits' => 'required',
            'services.*.control.*.start_date' => 'required',
        ];

        return $request->validate($rules, [], $niceNames);
    }

    private function calcFinishDate(Request $request){
        $start_date = Carbon::createFromFormat('d/m/Y',$request->input('start_date'));
        $periodicity = Periodicity::find($request->input('periodicity_id'));
        switch ($periodicity->unit) {
            case 'month':
                return $start_date->addMonths(($periodicity->value*$request->input('visits'))-$periodicity->value)->format('Y-m-d 23:59:59');
                break;
            case 'day':
                return $start_date->addDays(($periodicity->value*$request->input('visits'))-$periodicity->value)->format('Y-m-d 23:59:59');
                break;
            case 'year':
                return $start_date->addYears(($periodicity->value*$request->input('visits'))-$periodicity->value)->format('Y-m-d 23:59:59');
                break;
            default:
                return null;
                break;
        }
    }

    private function syncControls(Request $service, QuoteService $quoteService){
        if($service->has('control')){
            //Eliminar los controles creados y generarlos nuevamente
            $quoteService->infestations()->delete();
            foreach ($service->control as $infestationId => $control){
                $control = new Request($control);


                //guardar relación servicio - control
                $qsi = $quoteService->infestations()->create([
                        "infestation_id"    =>  $infestationId,
                        "visits"            =>  $control->input('visits'),
                        "periodicity_id"    =>  $control->input('periodicity_id'),
                        "time_value"        =>  $control->input('time_value'),
                        "time_unit_id"      =>  $control->input('time_unit_id'),
                        "possible_date"     =>  Carbon::createFromFormat('d/m/Y',$control->input('possible_date'))->format('Y-m-d 00:00:00'),
                        "start_date"        =>  Carbon::createFromFormat('d/m/Y',$control->input('start_date'))->format('Y-m-d 00:00:00'),
                        "end_date"          =>  Carbon::createFromFormat('d/m/Y',$control->input('end_date'))->format('Y-m-d 00:00:00'),
                        "observation"       =>  $control->input('observations')
                ]);

                //guardar relación control - areas
                if($control->has('areas')){
                    $qsi->areas()->sync($control->input('areas'));
                }
                
                //guardar relación control-producto
                if($control->has('product')){
                    foreach ($control->product as $pindex => $product) {
                        $product = new Request($product);

                        //verificar si el area es nueva o ya existe
                        $area = null;
                        if($product->filled('area')){
                            if(is_numeric($product->input('area'))){
                                $area = CrmArea::find($product->input('area'));
                                if(!$area){
                                    $area = CrmArea::firstOrCreate(['name' => $product->input('area')], ['enabled' => 1]);
                                }
                            }else{
                                $area = CrmArea::firstOrCreate(['name' => $product->input('area')], ['enabled' => 1]);
                            }
                        }


                        //verificar si la aplicacion es nueva o ya existe
                        $application = null;
                        if($product->filled('application')){
                            if(is_numeric($product->input('application'))){
                                $application = Application::find($product->input('application'));
                                if(!$application){
                                    $application = Application::firstOrCreate(['name' => $product->input('application')], ['enabled' => 1]);
                                }
                            }else{
                                $application = Application::firstOrCreate(['name' => $product->input('application')], ['enabled' => 1]);
                            }
                        }
                        
                        $qsi->products()->create([
                            'product_id'        => $product->filled('supply') ? $product->input('supply'): null,
                            'crm_area_id'       => !is_null($area) ? $area->id: null,
                            'application_id'    => !is_null($application) ? $application->id: null,
                            'quantity'          => ($product->filled('quantity') ? $product->input('quantity') : null),
                            'from'              => $product->input('from'),
                            'to'              => $product->input('to')
                        ]);
                    }
                }

                //eliminar productos huerfanos (null)
                QuoteSerInfProd::whereNull('quote_service_infestation_id')->delete();

                //eliminar areas herfanas (null)
                DB::table('area_customer_quote_service_infestation')->whereNull('quote_service_infestation_id')->delete();
            }
        }
    }
    


    public function deleteSupplyForm(Request $request){
        $id           = $request->input('id');
        $supply       = QuoteSerInfProd::find($id);
        $form         = view('quote.forms.deleteFormSupply', compact('supply'))->render();

        return response(['success' => $supply? true: false, 'view' => $form]);
    }

    public function deleteSupply(Request $request){
        $id = $request->input('id');
        $success = QuoteSerSup::destroy($id);
        return response(["success" => 'true', "message" => $success ? $this->msgSuplyDeleted : $this->msgError]);
    }

    public function disableServices(Request $request){
        $id = $request->input('id');
        $success = false;
        $quoteService = QuoteService::find($id);
        if($quoteService->genuine_service){
            $quoteService->enabled = 0;
            $success = $quoteService->save();
        }else{
            QuoteService::destroy($id);
            $success = true;
        }
        
        return response(["success" => 'true', "message" => $success ? 'Servicio eliminado correctamente' : $this->msgError]);
    }

    public function restoreServices(Request $request){
        $id = $request->input('id');
        $quoteService = QuoteService::where('enabled',0)->update(['enabled' => 1]);

        return response(["success" => $quoteService > 0 ? true: false, "message" => $quoteService > 0 ? 'Servicios recuperados correctamente' : 'No se encontraron servicios para restaurar']);
    }



     /** Funcioniones para el cambio de estado */

    public function statusForm(Request $request){
        $id             = $request->input('quote');
        $quote          = Quote::with('status','crm')->find($id);
        $statuses       = QuoteStatus::enabled()->get();
        if($request->crmStatus){
            $form= view('quote.forms.crmStatusForm', compact(['quote','statuses']))->render();
        }else{
            $form= view('quote.forms.statusForm', compact(['quote','statuses']))->render();
        }

        return response(['success' => true,  'view' => $form, 'edit' => $quote ? true: false]);
    }

    public function updateStatus(Request $request){
        DB::beginTransaction();
        try{
            $id                 = $request->input('id');
            $quote              = Quote::find($id);
            $quote->status_id   = $request->input('status_id');
            $quote->save();

            if($quote->status_id == 2){
                if($request->filled('aproved_id')){
                    foreach($request->input('aproved_id') as $sid){
                        QuoteService::where('id', '=', $sid)->update([
                            "approved"      => 1,
                            "approved_date" => Carbon::now()->format('Y-m-d H:i:s'),
                            "approved_user" => Auth::user()->id
                        ]);
                    }
                if(!isset($quote->approved_at)){
                    $quote->approved_at = Carbon::now();
                    $quote->save();
                }
                }else{
                    abort(406, 'Debes seleccionar almenos un servicio para poder aprobar esta cotización.');
                }
            }else if(in_array($quote->status_id, [1,3])){
                if(isset($quote->approved_at)){
                    $quote->approved_at = null;
                    $quote->save();
                }
                QuoteService::where('quote_id', '=', $quote->id)->update([
                    "approved"      => 0,
                    "approved_date" => null,
                    "approved_user" => null
                ]);
            }

            $quote              = Quote::with('status')->find($id);
            CrmTrackings::report($request, $quote, 'status');
            $this->manageQuoteStatusNotification($quote);
            $success = true;
            DB::commit();
       }catch (Exception $exc) {
            $success = false;
           DB::rollback();
       }

        return response(['success' => $success,  'message' => $success ? "Estado actualizado correctamente": "Error: ".$exc->getMessage(), "trace" => $success ? null : $exc->getTrace()]);
    }
    

    private function manageQuoteStatusNotification($quote){
        switch($quote->status_id){
            case '1':
                    $quote->commercial()->get()->last()->notify(new MakeQuoteReminder($quote));
                break;
            case '2':
                $user = $quote->commercial()->get()->last();

                if($quote->diagnostic){
                    $cc = User::with('roles')->whereHas('roles', function($roles){
                        $roles->whereIn('slug',['mip_coordinator','mip_assistant','programming']);
                    })->where('headquarter_id',$quote->headquarter_id)->where('email', '!=',$user->email)->get()->unique('email')->pluck('email');
                }else{
                    $cc = User::with('roles')->whereHas('roles', function($roles){
                        $roles->whereIn('slug',['mip_assistant','programming']);
                    })->where('headquarter_id',$quote->headquarter_id)->where('email', '!=',$user->email)->get()->unique('email')->pluck('email');
                }                

                //hace un solo envío con copia cc
                $user->notify(new ApprovedQuote($quote, $cc));

                break;
        }
    }


    /** Funcioniones para el manejo de contratos */

    public function contractForm(Request $request){
        $id                  = $request->input('quote');
        $quote               = Quote::with('crm','services')->find($id);
        $contract            = Contract::where('id',$request->input('id'));
        $possible_start_date = $quote->services->min('start_date');
        $possible_end_date   = $quote->services->max('end_date');
        $form                = view('quote.forms.contractForm', compact(['quote','contract','possible_start_date','possible_end_date']))->render();

        return response(['success' => true,  'view' => $form, 'edit' => $contract ? true: false]);
    }

    public function saveContract(Request $request){
        DB::beginTransaction();
        try{

                $contract                   =  new Contract();
                $contract->number           =  $request->input('number');
                $contract->crm_id           =  $request->input('crm_id');
                $contract->quote_id         =  $request->input('quote_id');
                $contract->crm_id           =  $request->input('crm_id');
                $contract->start_date       =  Carbon::createFromFormat('d/m/Y',$request->input('start_date_submit'))->format('Y-m-d 00:00:00');
                $contract->end_date         =  Carbon::createFromFormat('d/m/Y',$request->input('end_date_submit'))->format('Y-m-d 23:59:59');
                $contract->status_id        =  1;
                $contract->headquarter_id   =  session('headquarter_id');
                $contract->user_id          =  Auth::user()->id;
                $success = $contract->save();
                $quote  = Quote::with('contract')->find($request->input('quote_id'));
                CrmTrackings::report($request, $quote, 'contract');

            DB::commit();
       }catch (Exception $exc) {
           DB::rollback();
           return false;
       }

        return response(['success' => $success,  'message' => $success ? "Estado actualizado correctamente": $this->msgError]);
    }

    public function config(Request $request){
        return view('quote/config');
    }

    /** Funciones para el envío de cotozaciones */
    public function sendForm(Request $request, $quote){
        
        $quote = Quote::with('contact')->find($quote);
        $contact = $quote->contact;
        $hour = \Carbon\Carbon::now()->format('H');
                                              
        if ($hour < 12) {
            $greeting =  'Buen dia';
        }
        if ($hour >= 12 && $hour < 18) {
            $greeting =  'Buena tarde';
        }
        if ($hour >= 18) {
            $greeting =  'Buena noche';
        }
        $view = view('quote.forms.sendQuoteForm',compact(['quote', 'contact','greeting']))->render();
        return response(['success' => true, 'view' => $view, 'destinatary' => $contact->email, 'cc' => $quote->commercial->email]);
    }

    public function deleteQFile(Request $request)
    {
        $id     = $request->input('id');
        $success   = File::destroy($id);
        return response(["success" => $success, "message" => $success ? 'Archivo eliminado correctamente' : $this->msgError]); 
    }

}
