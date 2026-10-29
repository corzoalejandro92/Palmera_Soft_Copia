<?php

namespace App\Http\Controllers\Api;

use App\AreaOffice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Office;
use App\FileType;
use App\Crm;
use App\User;
use App\Contact;
use App\Quote;
use App\QuoteService;
use App\CrmSchedule;
use App\TicketQuestion;
use App\CategoryTicket;
use Carbon\Carbon;
use App\General\CrmTrackings;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Notifications\ApprovedQuote;
use App\Notifications\MakeQuoteReminder;
use \App\Http\Controllers\Administrative\CrmController as AdminCrmController;
use App\PriorityTicket;
use App\Notifications\NewTicket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Notifications\AnswerTicket;

class CrmController extends Controller
{

    public function getCrm(Request $request, $id)
    {
       $data = Crm::find($id);
       return response()->json(["success"=>true, "data" => $data->toArray()],200);
        
    }

  public function getOffices(Request $request, $id){
      //obtener el id del usuario
      $user = User::find($this->getUserIDToken($request));

      //obtener el id del contacto por medio del usuario
      $contact = Contact::where('user_id',$user->id)->where('crm_id',$id)->first();
      

      $data = Office::where('crm_id',$id)->whereHas('contacts', function($c)use($contact){
        $c->where('contact_id',$contact->id);
      })->get();

      return response()->json(["success"=>true, "data" => $data->toArray()],200);
  }

    public function getContacts(Request $request, $id)
    {
      $data = Contact::with('user')->whereHas('offices', function($o)use($id){
        $o->where('office_id',$id);
      })->get();
      return response()->json(["success"=>true, "data" => $data->toArray()],200);
    }

    public function getQuotes(Request $request, $id)
    {
      $data = Quote::with(['commercial','services.service','services.periodicity','status','file'])->where('office_id',$id)->get();
      return response()->json(["success"=>true, "data" => $data->toArray()],200);
    }

    public function ApprovedQuote(Request $request)
    {
      DB::beginTransaction();
      try{
        //obtener el id del usuario
        $user = User::find($this->getUserIDToken($request));

        //buscar los datos en base al id del servicio aprobados
        $quoteService = null;
        $quote = null;
      
        if($request->filled('selected')){
          $quoteService = QuoteService::find($request->all()['selected'][0]);
        }else{
          return response()->json(["success"=>false, "data" => null, 'message'=>'Debes seleccionar almenos un servicio para aprobar esta cotización'],200);
        }

        if($quoteService != null){
          $quote = Quote::find($quoteService->quote_id);
        }else{
          return response()->json(["success"=>false, "data" => null, 'message'=>'el servicio que desea aprobar ya no se encuentra disponible'],200);
        }

        if($quote != null){
          $quote->status_id   = 2;
          $quote->save();
          foreach($request->input('selected') as $sid){
              QuoteService::where('id', '=', $sid)->update([
                  "approved"      => 1,
                  "approved_date" => Carbon::now()->format('Y-m-d H:i:s'),
                  "approved_user" => $user->id
              ]);
          }
          if(!isset($quote->approved_at)){
              $quote->approved_at = Carbon::now();
              $quote->save();
          }

              $quote = Quote::with('status')->find($quote->id);
              CrmTrackings::report($request, $quote, 'status', 'update',$user);
              $this->manageQuoteStatusNotification($quote);
              $success = true;
              DB::commit();
              return response()->json(["success"=>$success, "data" => null, 'message'=>'Cotización aprobada satisfactoriamente'],200);
        }else{
          return response()->json(["success"=>false, "data" => null, 'message'=>'La cotización que desea aprobar ya no se encuentra disponible'],200);
        }
      }catch (Exception $exc) {
        $success = false;
        return response()->json(["success"=>false, "data" => null, 'message'=> $exc->getMessage(), 'trace' => $exc->getTrace()],500);
       DB::rollback();
      }
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

  public function reportServices(Request $request, $id){

    $object = CrmSchedule::
    with(['service','controls.infestation','userTrackings'=>function($ut){
      $ut->with('user')
      ->where('user_tracking_type',4);
    }])
    ->where('office_id', $id)
    ->whereIn('status_id',[3,7])
    //->whereDate('date', '>=', Carbon::parse('05-12-2021 00:01:00')->toDateString())
    ->orderBy('date', 'desc');

    $date = null;

    if($request->filled('date') && $request->filled('date') != ''){
      $date = Carbon::parse($request->input('date'));
      $object->whereDate('date', '>=', $date->startOfMonth())->where('date', '<=', $date->endOfMonth());
    }

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

    $reportServices = $object->paginate($request->filled('perpage') ? $request->input('perpage') : 10);

    //añadir la url del documento
    $reportServices->transform(function($item){
      $item->url = route('crmschedule.report',[$item->id, "email"=> false]);
      return $item;
    });


    return response(['success' => true, 'data' => $reportServices]);
  }


  public function getSchedules(Request $request, Office $office){
    $crmController = new AdminCrmController();
    $crm = Crm::find($office->crm_id);
    $start = Carbon::parse($request->input('date'));
    $end = Carbon::parse($request->input('date'));


    $newRequest = new Request(["office_id" => $office->id, "start" => $request->input('date'), "end" => $request->input('date')]);
    
    list($schedules, $startDate, $endDate, $office) = $crmController->_getSchedulesCalendar($newRequest, $crm, true);
    //$list = view('crm.sub.listSchedule',compact(['schedules','crm', 'startDate', 'endDate','office']))->render();
    return response(['success' => true, 'schedules' => $schedules, 'isCustomer' => true]);
  }

  public function getSchedulesOfMonth(Request $request, Office $office){
    $crmController = new AdminCrmController();
    $crm = Crm::find($office->crm_id);
    $start = Carbon::parse($request->input('date'))->startOfMonth()->format('Y-m-d');
    $end =  Carbon::parse($request->input('date'))->endOfMonth()->format('Y-m-d');


    $newRequest = new Request(["office_id" => $office->id, "start" => $start, "end" => $end]);
    
    list($schedules, $startDate, $endDate, $office) = $crmController->_getSchedulesCalendar($newRequest, $crm, true);
    //$list = view('crm.sub.listSchedule',compact(['schedules','crm', 'startDate', 'endDate','office']))->render();
    return response(['success' => true, 'schedules' => $schedules->groupBy('date'), 'isCustomer' => true]);
  }

  public function getTickets(Request $request, $id){

        $officeTicket  = TicketQuestion::with(['office','priority','registered','category','read','answers.registered'])
        ->where('office_id', $id)
        ->orderBy('updated_at', 'desc')->get();

        $categories=CategoryTicket::orderBy('id', 'asc')->get();

        $open_question_count=TicketQuestion::whereNull('solved')->where('office_id', $id)->count();
        $close_question_count=TicketQuestion::whereNotNull('solved')->where('office_id', $id)->count();



      $data = ["tickets" => $officeTicket->toArray(), "open" => $open_question_count, "close" => $close_question_count, 'categories' => $categories];
      return response()->json(["success"=>true, "data" => $data],200);
  }

  public function listTicketsFilter(Request $request, $id){
    $objet  = TicketQuestion::with(['office','priority','registered','category','read','answers.registered'])->withCount('answers')
    ->where('office_id', $id);
    
    if($request->filled('status')) {
        if($request->status == '1'){
            $objet->whereNotNull('solved');
        }else{
            $objet->whereNull('solved');
        }
    }
    if($request->filled('category')) {

        $objet->whereHas('category', function ($query) use($request) {
            $query->where('slug', $request->category);
        });

    }
    $term = '';
    if ($request->filled('search')) {
        $term = $request->input('search');
        $objet->where('subject', 'like',"%$term%");

    }

    if($request->filled('order') ) {
        $objet->orderByRaw($request->order);
    }else{
        $objet->orderBy('updated_at', 'desc');
    }

    $data=$objet->paginate($request->filled('perpage') ? $request->input('perpage') : 10);

    return response()->json(["success"=>true, "data" => $data],200);
  }

  public function listTicketsCategories(Request $request, $id){
    $data=CategoryTicket::orderBy('id', 'asc')->get();
    return response()->json(["success"=>true, "data" => $data->toArray()],200);
  }

  public function listTicketsPriorities(Request $request, $id){
    $data=PriorityTicket::orderBy('id', 'asc')->get();
    return response()->json(["success"=>true, "data" => $data->toArray()],200);
  }

  private function uploadFileTicket(Request $request, $name){
        $path=null;
        $file = $request->file($name);
        //obtenemos el nombre del archivo
        if ($request->hasFile($name)) {
            $nameFile = Str::random(20).'#'.$file->getClientOriginalName();
            $path = Storage::putFileAs('public/tickets', $request->file($name), $nameFile);
        }
        return $path;
  }

  
  public function validateFormTicket(Request $request){

        $niceNames = [
            'subject' => 'Asunto',
            'category_id' => 'Categoria',
            'message' => 'Mensaje',
            'priority_id' => 'Prioridad',
            'file' => 'Archivo',
        ];

        $request->validate(
            [
                'subject' => 'required',
                'category_id' => 'required',
                'message' => 'required',
                'priority_id' => 'required',
                'file' => 'mimes:jpg,png,jpg,jpeg,webp,pdf,mp4,zip,rar,docx,xlsx|file|max:15000'
            ],
            [],
            $niceNames
        );
  }

    
    private function newTicketsNotification($ticket, $user_id){
        $slug_roles=$ticket->category->roles->pluck('slug');
        if($ticket->category->slug == 'quotes'){
            $user=$ticket->office->crm->user;
            if($user){
                $user->notify(new NewTicket($ticket,$user->email));
            };
        }else{
            $users = User::where('id','!=', $user_id)->whereHas('roles', function($roles) use ($slug_roles){
                    $roles->whereIn('slug',$slug_roles);
                })->where('headquarter_id',$ticket->headquarter_id)->get();
            foreach($users as $user){
                $delay = now()->addSeconds(2);
                $user->notify((new NewTicket($ticket,$user->email))->delay($delay));
            }
            
        }
    }

    public function saveTicket(Request $request, $id){
      $this->validateFormTicket($request);

      try {
        DB::beginTransaction();
        $office = Office::find($id);
        $success =  $office->ticketQuestions()->create([
            'subject' => $request->input('subject'),
            'category_id' => $request->input('category_id'),
            'message' => $request->input('message'),
            'priority_id' => $request->input('priority_id'),
            'file' => $this->uploadFileTicket($request,'file'),
            'registered_by_id' => $request->input('user_id'),
            'headquarter_id' => $office->headquarter_id,
            'registered_at' => now(),
        ]);
        if($success)$this->newTicketsNotification($success, $request->input('user_id'));
        DB::commit();
        return response()->json(["success" => $success, "message" => $success ? 'Ticket creado correctamente' : 'No fue posible crear el ticket']);
      } catch (\Exception $exc) {
        DB::rollBack();
        return response()->json(["success"=>false, "data" => null, 'message'=> $exc->getMessage(), 'trace' => $exc->getTrace()],500);
      }
        
    }

    public function validateFormTicketAnswer(Request $request)
    {

        $niceNames = [
            'message' => 'Mensaje',
            'file' => 'Archivo',
        ];

        $request->validate(
            [
                'message' => 'required',
                'file' => 'mimes:jpg,png,jpg,jpeg,webp,pdf,mp4,zip,rar,docx,xlsx|file|max:15000'
            ],
            [],
            $niceNames
        );
    }

    public function saveTicketAnswer(Request $request, $id){
      $success = false;
      try {
        $this->validateFormTicketAnswer($request);

        DB::beginTransaction();
        $ticket = TicketQuestion::find($request->input('id'));
        $success =  $ticket->answers()->create([
            'message' => $request->input('message'),
            'file' => $this->uploadFileTicket($request,'file'),
            'registered_by' => $request->input('user_id')
        ]);
        if($success){
             if($request->input('solved') && $request->input('solved') == 'true'){
               $ticket->solved = 1;
               
             }else{
              $ticket->solved = null;
             }
             $ticket->save();
             
            $count = $request->input('user_id') == $ticket->registered_by_id ? $ticket->count_answers : $ticket->count_answers+1;
            $ticket->update(['updated_at' => now(),'count_answers'=> $count ]);
        } 
        if($success) $this->answerTicketsNotification($ticket,$request->input('user_id'),$success);
        DB::commit();
        return response()->json(["success" => $success, "message" => $success ? 'Respuesta enviada correctamente' : 'No fue posible enviar la respuesta']);
      } catch (\Exception $exc) {
        DB::rollback();
        return response()->json(["success"=>false, "data" => null, 'message'=> $exc->getMessage(), 'trace' => $exc->getTrace()],500);
      }
    }

    private function answerTicketsNotification($ticket, $user_id, \App\TicketAnswer $answer=null){
      $fuser = User::find($user_id);
      if($fuser->roles->pluck('slug')->contains('customer')){

          if($ticket->category->slug == 'quotes'){
              $user=$ticket->office->crm->user;
              if($user){
                  $user->notify(new AnswerTicket($ticket,$user->email,$answer));
              }
          }else{
              $slug_roles=$ticket->category->roles->pluck('slug');
              
              $users = User::whereHas('roles', function($roles) use ($slug_roles){
                          $roles->whereIn('slug',$slug_roles);
                      })->where('headquarter_id',$ticket->office->headquarter_id)->get();
                      
              foreach($users as $user){
                  $delay = now()->addSeconds(2);
                  $user->notify((new AnswerTicket($ticket,$user->email,$answer))->delay($delay));
                  
              }
          }
      }else{
          $user=$ticket->registered;
          $user->notify(new AnswerTicket($ticket,$user->email,$answer));
      }
  }

  public function files(Request $request, $id)
    {

        $office    = Office::with(['files'])->where('id', $id)->whereHas('files')->get()->first();
        $fileTypes = FileType::where('slug','crm')->get();
        $files = [];
        if(!is_null($office->files) && count($office->files) > 0){
          $files = $office->files->sortByDesc('created_at');
        }

        return response()->json(["success"=>true, "data" => ["files" => array_values($files->toArray()), 'types'=> $fileTypes->toArray()]],200);
    }

    public function plans(Request $request, $id)
    {

        $areasObj= AreaOffice::with(['files' => function($q){$q->orderBy('id','desc');}])->where('office_id', $id)->whereHas('files')->get();
        $areas = [];
        if(!is_null($areasObj) && count($areasObj) > 0){
          $areas = $areasObj->toArray();
        }

        $fileTypes = FileType::where('slug','like','%plan-%')->get();

        return response()->json(["success"=>true, "data" => ["files" => array_values($areas), 'types'=> $fileTypes->toArray()]],200);
    }

    public function previewPlan(Request $request){

      $elements = null;
      $type='';
      $file      = \App\Models\Api\File::with(['filetype', 'extends' => function ($q) {
          $q->with('extendible');
      }])->find($request->input('id'));

      if ($file->fileType->slug == 'plan-mec') {
          $elements  = \App\Convection::enabled()->whereIn('id', $file->extends->pluck('extendible_id'))->get();
          $type      = 'Mecanismos';
      } else if ($file->fileType->slug == 'plan-risk') {
          $elements  = \App\ControlInfestation::enabled()->whereIn('id', $file->extends->pluck('extendible_id'))->get();
          $type      = 'Riesgos';
      }else{
        return $this->previewPlanRoute($request);
      }
      return view('crm.plan', compact(['file', 'elements', 'type']));
    }

    public function previewPlanRoute(Request $request)
    {


        $file = \App\Models\Api\File::with(['fileType'])->find($request->input('id'));

        if ($file->fileType->slug == 'plan-route') {
            $elements  = [];

            //añadir las Convecciones

            $element0 = new \App\Convection();
            $element0->name = "Inicio de desisectación";
            $element0->description = "";
            $element0->slug = "ID";
            $element0->avatar = "#68af68";
            array_push($elements, $element0);

            $element1 = new \App\Convection();
            $element1->name = "Ruta de desinsectación";
            $element1->description = "";
            $element1->slug = "RD";
            $element1->avatar = "red";
            array_push($elements, $element1);


            $element2 = new \App\Convection();
            $element2->name = "Fin desinsectación";
            $element2->description = "";
            $element2->slug = "FD";
            $element2->avatar = "blue";
            $type      = 'Ruta';
            array_push($elements, $element2);




        } else if ($file->fileType->slug == 'plan-risk') {
            $elements  = null;
            $type      = '';
        }


        return view('crm.planRoute', compact(['file', 'elements', 'type']));
    }
}