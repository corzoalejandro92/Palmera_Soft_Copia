<?php

namespace App\Http\Controllers\Administrative;

use App\Automation;
use App\Availability;
use App\Consumption;
use App\Contact;
use App\Crm;
use App\CrmSchedule;
use App\CrmScheduleObservation;
use App\CrmScheduleStatus;
use App\CrmTracking;
use App\DetailService;
use App\General\CrmTrackings;
use App\Headquarter;
use App\Http\Controllers\Controller;
use App\InventoryExpense;
use App\Job;
use App\Jobs\SendSmsRoute;
use App\Mail\RequestConfirmation;
use App\Mail\RouteSend;
use App\MechanismReport;
use App\Office;
use App\Product;
use App\Quote;
use App\QuoteSerSup;
use App\Route;
use App\Scheduling;
use App\SchedulingSerObs;
use App\SchedulingSerSup;
use App\SchedulingSerTas;
use App\SchedulingService;
use App\SchedulingType;
use App\Service;
use App\TechnicalReport;
use App\User;
use App\UserTracking;
use App\WorkingHour;
use App\Zone;
use Barryvdh\DomPDF\Facade as PDF;
use Caffeinated\Shinobi\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;

class SchedulingController extends Controller
{
    use InteractsWithQueue;
    private $availabilityLog = [];
    private $serviceOrder = [];
    private $countRequestGmap = 0;
    private $schedulingTypeSlug = 1;
    private $userTimes = [];
    private $msgSuccess = 'Evento de agenda guardado correctamente';
    private $msgUpdated = 'Evento de agenda actualizado';
    private $msgError = 'No se pudo realizar la acción solicitada, inténtelo nuevamente';
    private $traceError;
    private $countServices = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('scheduling.index');
    }

    public function callsheet(Request $request)
    {
        // filtros recibidos desde los indicadores
        $startDate = $request->input('startDate', false);
        $endDate = $request->input('endDate', false);
        $year = $request->input('year', false);
        $services = $request->input('services', false);
        $status = $request->input('status', false);
        $autofilter = ($startDate || $endDate || $services || $status) ?? false;

        return view('scheduling.callsheet', compact(['autofilter', 'services', 'status', 'startDate', 'endDate']));
    }

    public function sheetList(Request $request)
    {
        $scheduleObject = CrmSchedule::with([
            'crm',
            'observations',
            'controls.infestation',
            'status',
            'workschedule.crm',
            'office.schedule_contacts',
            'crmTrackings',
            'service',
        ])
            // ->whereHas('status', function($status){
            //     $status->where('slug','<>','route');
            // })
            // ->whereHas('service', function($service){
            //     $service->oneHeadquarterOffice();
            // })

            ->oneHeadquarterOffice()
            ->whereBetween('date', [$request->start, $request->end])
            ->whereHas('crm', function ($crm) {
                $crm->whereNull('deleted_at');
            })
            ->whereHas('office', function ($crm) {
                $crm->whereNull('deleted_at');
            })
        ;

        if ($request->input('services')) {
            $scheduleObject->whereIn('service_id', $request->input('services'));
        }

        if ($request->input('statuses')) {
            $scheduleObject->whereIn('status_id', $request->input('statuses'));
        }

        // if($request->input('users')){
        //     foreach($request->input('users') as $idx => $user){
        //         $scheduleObject->where(function($w) use($idx,$user){
        //             if($idx == 0){
        //                 $w->whereRaw("technicals like '%\"$user\"%'");
        //             } else {
        //                 $w->orWhereRaw("technicals like '%\"$user%'");
        //             }
        //         });
        //     }
        // }

        if ($request->filled('id')) {
            $scheduleObject->where('id', $request->input('id'));
        }

        $schedules = $scheduleObject->orderBy('id', 'asc')->get();

        if ($request->filled('users')) {
            $schedules = $schedules->filter(function ($item) use ($request) {
                if ($item->technicals) {
                    $tech = $item->technicals->pluck('id')->values()->toArray();
                    if ($tech) {
                        foreach ($request->input('users') as $suser) {
                            if (in_array($suser, $tech)) {
                                return $item;
                            }
                        }
                    }
                }
            });
        }

        if ($request->filled('id')) {
            $schedule = $schedules->first();
            $date = Carbon::parse($schedule->date);
            $start = $date->copy()->format('d');
            $users = User::withTrashed()->whereIn('id', $schedule->raw_technical)->get()->pluck('name', 'id');
            $title_date = Str::ucfirst($date->copy()->monthName).' '.$start.' de '.$date->format('Y');
            $list = view('scheduling.sub.frames.sheet_list_tr', compact('schedule', 'title_date', 'users'))->render();

            return response(['success' => true, 'view' => $list]);
        }
        $users_ids = collect([]);
        foreach ($schedules as $schedule) {
            $users_ids = $users_ids->merge($schedule->raw_technical);
        }
        $users = User::withTrashed()->whereIn('id', $users_ids->unique())->get()->pluck('name', 'id');
        $list = view('scheduling.sub.sheetList', compact('schedules', 'users'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    /** Funcioniones para el cambio de estado */
    public function statusForm(Request $request)
    {
        $id = $request->input('id');
        $schedule = CrmSchedule::with('controls.infestation', 'status', 'crm', 'office')->find($id);
        $restrict = $request->filled('restrict') ? $request->input('restrict') : false;
        if (!$restrict) {
            $statuses = CrmScheduleStatus::where('slug', '<>', 'route')->get();
        } else {
            $statuses = CrmScheduleStatus::all();
        }

        $form = view('scheduling.forms.statusForm', compact(['statuses', 'schedule', 'restrict']))->render();

        return response(['success' => true, 'view' => $form, 'edit' => $schedule ? true : false]);
    }

    /** Funcioniones para el cambio de estado de eventos */
    public function statusFormSchedulings(Request $request)
    {
        $id = $request->input('id');
        $scheduling = Scheduling::with('users', 'crm', 'office')->find($id);
        $restrict = $request->filled('restrict') ? $request->input('restrict') : false;
        if (!$restrict) {
            $statuses = CrmScheduleStatus::where('slug', '<>', 'route')->get();
        } else {
            $statuses = CrmScheduleStatus::all();
        }

        $form = view('scheduling.forms.statusFormScheduling', compact(['statuses', 'scheduling', 'restrict']))->render();

        return response(['success' => true, 'view' => $form, 'edit' => $scheduling ? true : false]);
    }

    public function reports(Request $request)
    {
        $id = $request->input('schedule');
        $reports = MechanismReport::with(['type', 'user', 'extend.extendible', 'extend.file.filable'])
            ->where('scheduling_id', $id)
            ->orderBy('id', 'desc')
            ->get()
        ;

        $form = view('scheduling.forms.reportInfo', compact(['reports']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function expenses(Request $request)
    {
        $id = $request->input('schedule');
        $expenses = Consumption::with([ 'inventoryDetail.product.unit','inventoryDetail.inventory.warehouse'])
            ->where('crm_schedule_id', $id)
            ->get();

        $form = view('scheduling.forms.expensesInfo', compact(['expenses']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function statusMultipleForm(Request $request)
    {
        $ids = $request->input('id');
        // $schedule       = CrmSchedule::with('controls.infestation','status','crm','office')->find($id);
        $statuses = CrmScheduleStatus::where('slug', '<>', 'route')->get();
        $form = view('scheduling.forms.statusMultipleForm', compact(['statuses', 'ids']))->render();

        return response(['success' => true, 'view' => $form, 'edit' => $ids ? true : false]);
    }

    public function confirmRequest(Request $request)
    {
        $ids = $request->input('id');
        $schedules = CrmSchedule::select(['id', 'service_id', 'office_id', 'num', 'crm_id'])
            ->with(['service' => function ($service) {$service->select(['id', 'name']); },
                'crm' => function ($crm) {$crm->select(['id', 'name']); },
                'office' => function ($office) {$office->select(['id', 'name']); },
            ])
            ->whereIn('status_id', ['1', '4', '5'])
            ->find($ids)
            ->makeHidden(['datec', 'duration', 'duration_text', 'url_to_edit', 'office'])
            ->groupBy(['office.schedulecontact.id'])
        ;

        if (0 == count($schedules)) {
            abort(406, 'Los servicios seleccionados ya se encuentran programados o por confirmar');
        }

        $schedules = $schedules->map(function ($collect, $idx) {
            $newCollect = (object) ['contact' => null, 'schedules' => null];
            if (null != $idx) {
                $newCollect->contact = Contact::select('id', 'name', 'email')->with('offices.crm')->find($idx)->makeHidden(['city', 'avatar']);
                $newCollect->schedules = $collect;

                return $newCollect;
            }
            abort(406, 'La sucursal '.$collect[0]->office->name.' del cliente '.$collect[0]->crm->name.' no tiene un contacto de programación registrado, para continuar agrega uno desde el CRM o bien, deselecciona este cliente de la lista.');
        });

        try {
            DB::beginTransaction();
            CrmSchedule::whereIn('id', $ids)->update(['status_id' => 8]);
            $nSchedules = CrmSchedule::whereIn('id', $ids)->get();
            foreach ($nSchedules as $key => $schedule) {
                $tracking = CrmTracking::create(
                    [
                        'crm_id' => $schedule->crm_id,
                        'office_id' => $schedule->office_id,
                        'crm_tracking_type_id' => 6,
                        'date' => Carbon::now()->format('Y-m-d H:i:s'),
                        'title' => 'Confirmación del servicio',
                        'detail' => Auth::User()->name.' envío un correo de solicitud de confirmación al cliente',
                        'headquarter_id' => Auth::User()->headquarter_id,
                        'user_id' => Auth::User()->id,
                        'system' => 0,
                    ]
                );
                $schedule->crmTrackings()->saveMany([$tracking]);
            }

            foreach ($schedules as $key => $data) {
                // enviar correo de confirmación del servicio
                Mail::to($data->contact->email)->queue(new RequestConfirmation($data, ['email' => Auth::User()->email, 'name' => Auth::User()->name]));
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            abort(500, 'Se produjo un error cambiando el estado de los servicios:'.$ex->getMessage());
        }

        return response(['success' => true, 'message' => 'Se han enviado los correos de confirmación']);
    }

    public function updateStatus(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->input('id');
            $bind = [];

            if ($request->filled('status_id')) {
                $bind = array_merge($bind, ['status_id' => $request->input('status_id')]);
            }
            if ($request->filled('date')) {
                $bind = array_merge($bind, ['date' => $request->input('date')]);
            }
            if ($request->filled('start_hour')) {
                $bind = array_merge($bind, ['start_hour' => $request->input('start_hour')]);
            }
            if ($request->filled('time')) {
                $bind = array_merge($bind, ['start_hour' => $request->input('time')]);
            }
            if ($request->filled('user')) {
                $bind = array_merge($bind, ['technicals' => ('array' == gettype($id) ? json_encode($request->input('user')) : $request->input('user'))]);
            }
            if ($request->filled('technical_quantity')) {
                $bind = array_merge($bind, ['technical_quantity' => $request->input('technical_quantity')]);
            }

            if ('array' == gettype($id)) {
                CrmSchedule::whereIn('id', $id)->update($bind);
            } else {
                CrmSchedule::find($id)->update($bind);
            }

            // verificar si el servicio está asociado a un evento de ruta realizar alguna acción
            $crmSchedulesQuery = CrmSchedule::with('status', 'scheduling');
            if ('array' == gettype($id)) {
                $crmSchedulesQuery->whereIn('id', $id);
            } else {
                $crmSchedulesQuery->where('id', $id);
            }

            $crmSchedules = $crmSchedulesQuery->get();

            if (!is_null($crmSchedules)) {
                foreach ($crmSchedules as $crmSchedule) {
                    if ($crmSchedule->scheduling) {
                        switch ($request->input('status_id')) {
                            case 1:
                            case 6:
                                // anular/eliminar eventos existente
                                $crmSchedule->scheduling()->delete();

                                break;

                            case 3:
                            case 7:
                                // marcar como completado
                                $crmSchedule->scheduling()->update(['completed' => 1]);

                                break;

                            case 4:
                                // marcar como icompleto (naranja)
                                $crmSchedule->scheduling()->update(['completed' => 3]);

                                break;

                            case 5:
                                // marcar como no realizado (rojo)
                                $crmSchedule->scheduling()->update(['completed' => 2]);

                                break;

                            case 2:
                                // marcar como sin realizar (morado en ruta)
                                $crmSchedule->scheduling()->update(['completed' => 0]);

                                break;

                            default:
                                break;
                        }
                    }
                }
            }

            // CrmTrackings::report($request, $control, 'callsheet');
            DB::commit();

            return response(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } catch (\Exception $exc) {
            DB::rollback();

            return response(['success' => false, 'message' => $this->msgError.': '.$exc->getMessage(), 'trace' => $exc->getTrace()]);
        }
    }

    public function updateStatusScheduling(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->input('id');
            $bind = [];

            if ($request->filled('completed')) {
                $bind = array_merge($bind, ['completed' => $request->input('completed')]);
            }

            if ('array' == gettype($id)) {
                Scheduling::whereIn('id', $id)->update($bind);
            } else {
                Scheduling::find($id)->update($bind);
            }

            // CrmTrackings::report($request, $control, 'callsheet');
            DB::commit();

            return response(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } catch (\Exception $exc) {
            DB::rollback();

            return response(['success' => false, 'message' => $this->msgError.': '.$exc->getMessage(), 'trace' => $exc->getTrace()]);
        }
    }

    public function eventForm(Request $request)
    {
        $id = $request->input('id');
        $schedule = CrmSchedule::with('controls.infestation', 'status', 'crm', 'office')->find($id);
        $title = $request->input('id') ? 'EDITAR EVENTO' : 'CREAR EVENTO';
        // $crmTracking  = CrmTracking::where('crm_tracking_type_id', CrmTrackingTypes::where('slug','sheet')->first()->id)->where('trackable_id',$id)->get()->last();
        $crmTracking = null;

        $form = view('scheduling.forms.eventForm', compact(['crmTracking', 'schedule']))->render();

        return response(['success' => true, 'view' => $form, 'title' => $title]);
    }

    public function validateFormEvent(Request $request)
    {
        $niceNames = [
            'title' => 'Titulo',
            'detail' => 'Detalle',
            'crm_tracking_type_id' => 'Tipo de evento',
        ];

        $request->validate(
            [
                'title' => 'required',
                'crm_tracking_type_id' => 'required',
            ],
            [],
            $niceNames
        );
    }

    public function saveEvent(Request $request)
    {
        try {
            DB::beginTransaction();
            $schedule = CrmSchedule::find($request->input('schedule_id'));
            $this->validateFormEvent($request);

            $schedule->crmTracking()->create([
                'crm_id' => $request->input('crm_id'),
                'office_id' => $request->input('office_id'),
                'crm_tracking_type_id' => $request->input('crm_tracking_type_id'),
                'user_id' => Auth::user()->id,
                'date' => Carbon::parse($request->input('date'))->format('Y-m-d '.date('H:i:s')),
                'title' => $request->input('title'),
                'detail' => $request->filled('detail') ? $request->input('detail') : null,
                'headquarter_id' => session('headquarter_id'),
                'system' => 0,
            ]);
            DB::commit();

            return response(['success' => true, 'message' => 'Evento de seguimiento de programación agregdo correctamente']);
        } catch (\Exception $th) {
            DB::rollBack();

            return response(['success' => false, 'message' => 'Error guardando el evento: '.$th->getMessage(), 'trace' => $th->getTrace()]);
        }
    }

    public function filters(Request $request)
    {
        $schedulingTypes = SchedulingType::enabled()->get();
        $filters = view('scheduling.forms.filter', compact(['schedulingTypes']))->render();

        return response(['success' => $schedulingTypes ? true : false, 'view' => $filters]);
    }

    public function types(Request $request)
    {
        $query = SchedulingType::select(['id', 'name as text'])->enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $schedulingTypes = $query->get();

        return response(['results' => $schedulingTypes]);
    }

    public function statuses(Request $request)
    {
        $query = CrmScheduleStatus::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function typeFrame(Request $request)
    {
        $scheduling = Scheduling::find($request->input('scheduling_id'));
        $start_date = $request->filled('start') ? ucwords(\Jenssegers\Date\Date::createFromFormat('d/m/Y', $request->input('start'))->format('Y-m-d')) : ucwords(\Jenssegers\Date\Date::now()->format('d F, Y'));
        $end_date = $request->filled('end') ? ucwords(\Jenssegers\Date\Date::createFromFormat('d/m/Y', $request->input('end'))->format('Y-m-d')) : ucwords(\Jenssegers\Date\Date::now()->format('d F, Y'));

        if ($request->filled('start_hour')) {
            $start_hour = Carbon::createFromFormat('d/m/Y h:i A', $request->input('start').' '.$request->input('start_hour'));
            $end_hour = $start_hour->copy()->addHour();
        } else {
            $start_hour = false;
            $end_hour = false;
        }
        if ($request->filled('description')) {
            $description = $request->input('description');
        } else {
            $description = null;
        }

        $type = $request->input('type');

        switch ($type) {
            case '3':
                return response(['success' => true, 'view' => view('scheduling/forms/frames/reminder_frame', compact(['scheduling', 'start_date', 'end_date', 'start_hour', 'end_hour', 'description']))->render()]);

                break;

            default:
                return response(['success' => true, 'view' => view('scheduling/forms/frames/event_frame', compact(['scheduling', 'start_date', 'end_date', 'start_hour', 'end_hour', 'description']))->render()]);

                break;
        }
    }

    public function crms(Request $request)
    {
        if ($request->filled('full')) {
            $query = Crm::selectRaw('*')->enabled()->headquarterOffice();
        } else {
            $query = Crm::select(['id', 'name as text'])->enabled()->headquarterOffice();
        }

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->limit(20)->orderBy('updated_at', 'desc')->get();

        return response(['results' => $data]);
    }

    public function crmsOffices(Request $request)
    {
        $query = Office::select(['id', 'name as text'])
            ->enabled()
            ->headquarter()
            ->where('crm_id', $request->input('crm'))
        ;

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function crmsContacts(Request $request)
    {
        $id = $request->input('crm');
        $query = Contact::select(['id', 'name as text'])
            ->whereHas('offices', function ($query) use ($id) {
                return $query->where('crm_id', $id);
            })
        ;
        $contact = $query->get();

        return response(['results' => $contact]);
    }

    public function users(Request $request)
    {
        $query = User::select(['id', 'name as text'])->enabled()->headquarter();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }

        $query->whereHas('roles', function ($roles) {
            $roles->whereNotIn('role_id', [3]);
        });

        $data = $query->get();

        if(($request->input('start')) == null){
            return response(['results' => $data]);
        }
        
        $date = Carbon::parse($request->input('start')) ?? Carbon::now();
        
        $servicios = CrmSchedule::where(function ($query) use ($date) {
            $query->where('date', $date->format('Y-m-d'))
                  ->orWhere('date', $date->subDay()->format('Y-m-d'));
        })->where(function ($query) {
            $query->whereBetween('start_hour', ['00:00:00', '06:00:00'])
                  ->orWhereBetween('start_hour', ['19:00:00', '23:59:59']);
        })->get();

        $extraData = Scheduling::with('users')->where(function ($query) use ($date) {
            // Filtra por las fechas específicas
            $query->whereDate('start', $date->subDay()->format('Y-m-d'))
                  ->orWhereDate('start', $date->format('Y-m-d'));
        })->where(function ($query) {
            // Filtra por las horas específicas
            $query->whereTime('start', '>=', '00:00:00')
                  ->whereTime('start', '<=', '06:00:00')
                  ->orWhere(function ($query) {
                      $query->whereTime('start', '>=', '19:00:00')
                            ->whereTime('start', '<=', '23:59:59');
                  });
        })
        ->whereNotIn('scheduling_type_id', [0, 1])
        ->get();


        $data = $data->map(function ($v, $k) use ($servicios, $extraData) {

            $time = 0;
            foreach ($servicios as $key => $item) {

                $isTech = collect($item->technicals)->pluck('id')->contains($v->id);

                if($isTech){
                    $time += $item->duration / 3600;
                }

            }

            foreach ($extraData as $key2 => $e) {

                $isTech = collect($e->users)->pluck('id')->contains($v->id);

                if($isTech){
                    $time += $e->duration / 60;
                }

            }

            $v->tiempo = $time;
            $v->text =  $v->text . " - ($time)";

            return $v;
            
        })->values();

        return response(['results' => $data]);
    }

    public function agenda(Request $request)
    {
        $start = date('Y-m-d 00:i:s', $request->input('start'));
        $end = date('Y-m-d 23:59:59', $request->input('end'));
        $query = Scheduling::with(['schedulingType', 'users' => function ($users) {
            $users->where('user_id', Auth::user()->id);
        }, 'crm', 'crmSchedule'])
            ->whereBetween('start', [$start, $end])
        ;

        if ($request->input('filterTypes')) {
            $query->whereIn('scheduling_type_id', $request->input('filterTypes'));
        }

        // if ($request->input('crossingData')) {
        //     $relation = $request->input('crossing') == 'users' ? 'users' : 'crm';
        //     $field = $request->input('crossing') == 'users' ? 'user' : 'crm';
        //     $query
        //         ->whereHas($relation, function ($q) use ($request, $field) {
        //             $q->whereIn($field . '_id', $request->input('crossingData'));
        //         });
        // }

        $schedulings = $query->whereHas('users', function ($users) {
            $users->where('user_id', Auth::user()->id);
        })->headquarter()->enabled()->get();

        return response(['results' => $schedulings]);
    }

    public function form(Request $request)
    {
        $id = $request->input('id');
        $enabled = $request->filled('enabled') ? $request->input('enabled') : 1;
        $capture_date = $request->filled('capture_date') ? $request->input('capture_date') : false;
        $scheduling = Scheduling::with(['users', 'crm', 'schedulingType'])->find($id);

        // precargar campos si viene los params
        $user = null;
        $start_hour = null;
        $end_hour = null;
        $eventType = null;
        $params = false;
        $origin = 'init';
        $hasRoute = false;

        if ($request->filled('params')) {
            $user = User::find($request->input('params.user_id'));
            $start_hour = Carbon::parse($request->input('params.start'));
            $end_hour = Carbon::parse($request->input('params.start'))->addHour();
            $eventType = SchedulingType::find(2);
            $params = true;
            $origin = $request->input('params.origin');
            $hasRoute = Route::where('date', Carbon::parse($request->input('params.start'))->format('Y-m-d'))->where('user_id', $request->input('params.user_id'))->first();
        }

        $form = view('scheduling.forms.schedulingForm', compact(['scheduling', 'enabled', 'capture_date', 'user', 'start_hour', 'end_hour', 'eventType', 'params', 'hasRoute', 'origin']))->render();

        return response(['success' => true, 'view' => $form, 'edit' => $scheduling ? true : false]);
    }

    public function subform(Request $request)
    {
        $type = $request->input('type');

        switch ($type) {
            case 1:
                $scheduling = Scheduling::find($request->input('scheduling_id'));
                $description = $request->filled('description') ? $request->input('description') : '';
                $form = view('scheduling.forms.defaultForm', compact(['scheduling', 'description']))->render();
                $modalSize = 'md';

                break;

            case 0:
            case 2:
            case 3:
                $scheduling = Scheduling::with(
                    'crmScheduleControl.service.service',
                    'crm',
                    'office',
                    'contact',
                    'users',
                    'crmScheduleControl.infestation',
                    'crmScheduleControl.products.productable'
                )
                    ->find($request->input('scheduling_id'))
                ;
                $form = view('scheduling.forms.basicForm', compact(['scheduling']))->render();
                $modalSize = 'md';

                break;

            default:
                $scheduling = Scheduling::with(
                    'crmScheduleControl.infestation',
                    'crm',
                    'office',
                    'contact',
                    'users'
                )
                    ->find($request->input('scheduling_id'))
                ;
                $form = view('scheduling.forms.defaultForm', compact(['scheduling']))->render();
                $modalSize = 'md';

                break;
        }

        return response(['success' => true, 'view' => $form, 'modalSize' => $modalSize]);
    }

    public function services(Request $request)
    {
        $query = Service::enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function statues(Request $request)
    {
        $query = CrmScheduleStatus::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function technicals(Request $request)
    {
        $query = User::select(['id', 'name'])->enabled()->headquarter()
            ->whereHas('roles', function ($q) {
                $q->where('slug', 'technical');
            })
        ;
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function areas(Request $request)
    {
        $query = DetailService::select(['id', 'zone as text'])
            ->enabled()
            ->where('id', $request->input('service'))
        ;
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function tabServices(Request $request)
    {
        $services = [];
        $schedulingServices = [];
        $scheduling_id = $request->input('scheduling_id');
        if ($request->input('services')) {
            $services = Service::with('detailService')
                ->whereIn('id', $request->input('services'))
                ->enabled()
                ->get()
            ;
        }
        if ($request->input('scheduling_id')) {
            $schedulingServices = [];
        }
        $form = view('scheduling.forms.frames.tab_services', compact(['services', 'schedulingServices', 'scheduling_id']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function areaInfo(Request $request)
    {
        $area = DetailService::enabled()->find($request->input('area'));

        return response(['success' => true, 'area' => $area]);
    }

    public function productInfo(Request $request)
    {
        $product = Product::with('unit')->enabled()->find($request->input('product'));

        return response(['success' => true, 'product' => $product]);
    }

    public function roles(Request $request)
    {
        $query = Role::select(['id', 'name as text'])->whereNotIn('slug', ['root', 'admin']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function products(Request $request, $service)
    {
        $query = Product::relatedService($service)->enabled();

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function addObservationRow(Request $request)
    {
        $observation = null;
        $key = uniqid();
        $serviceId = $request->input('serviceId');
        $row = view('scheduling.forms.frames.observation_new_row', compact(['key', 'observation', 'serviceId']))->render();

        return response(['success' => true, 'row' => $row]);
    }

    public function deleteObservationForm(Request $request)
    {
        $id = $request->input('id');
        $observation = SchedulingSerObs::find($id);
        $form = view('scheduling.forms.deleteFormObservation', compact('observation'))->render();

        return response(['success' => $observation ? true : false, 'view' => $form]);
    }

    public function addSupplyRow(Request $request)
    {
        $supply = null;
        $key = uniqid();
        $serviceId = $request->input('serviceId');
        $row = view('scheduling.forms.frames.supply_new_row', compact(['key', 'supply', 'serviceId']))->render();

        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    public function deleteSupplyForm(Request $request)
    {
        $id = $request->input('id');
        $supply = QuoteSerSup::find($id);
        $form = view('quote.forms.deleteFormSupply', compact('supply'))->render();

        return response(['success' => $supply ? true : false, 'view' => $form]);
    }

    public function addTaskRow(Request $request)
    {
        $task = null;
        $key = uniqid();
        $serviceId = $request->input('serviceId');
        $row = view('scheduling.forms.frames.task_new_row', compact(['key', 'task', 'serviceId']))->render();

        return response(['success' => true, 'row' => $row]);
    }

    public function deleteTaskForm(Request $request)
    {
        $id = $request->input('id');
        $task = SchedulingSerTas::find($id);
        $form = view('scheduling.forms.deleteFormTask', compact('task'))->render();

        return response(['success' => $task ? true : false, 'view' => $form]);
    }

    public function store(Request $request)
    {
        $success = false;
        if ($request->filled('origin') && 'programmer' == $request->input('origin') && '1' == $request->input('type') && !$request->filled('route') && !$request->filled('append_to_route')) {
            if ($request->input('crm_schedule_id')) {
                $id = $request->input('crm_schedule_id');
                $crmSchedule = CrmSchedule::find($id);
                $success = $this->updateCrmScheduleProgrammer($request, $crmSchedule);
                $events = [];
            } else {
                abort('406', 'No has seleccionado un servicio en el cronograma del cliente');
            }
        } else {
            $schedule = new Scheduling();
            $success = $this->save($request, $schedule);

            $events = [];
            if ($request->filled('route')) {
                $route = Route::find($request->input('route'));
                $events = $this->prepareVisInfo($route);
            }
            
        }

        return response(['success' => $success, 'events' => $events, 'message' => $success ? $this->msgSuccess : $this->msgError, 'trace' => $this->traceError]);
    }

    public function update(Request $request)
    {
        if ($request->filled('origin') && 'programmer' == $request->input('origin') && '1' == $request->input('type') && !$request->filled('route')) {
            $id = $request->input('id');
            $schedule = Scheduling::find($id);
            $success = $this->save($request, $schedule);

            if ($request->filled('crm_schedule_id')) {
                $crmSchedule = CrmSchedule::find($request->input('crm_schedule_id'));
                $this->updateCrmScheduleProgrammer($request, $crmSchedule);
                $schedule->delete();
            }

            $events = [];

            if ($request->filled('route')) {
                $route = Route::find($request->input('route'));
                $events = $this->prepareVisInfo($route);
            }
        } else {
            $id = $request->input('id');
            $schedule = Scheduling::find($id);
            $success = $this->save($request, $schedule);

            $events = [];
            if ($request->filled('route')) {
                $route = Route::find($request->input('route'));
                $events = $this->prepareVisInfo($route);
            }
        }

        return response(['success' => $success, 'events' => $events, 'message' => $success ? $this->msgUpdated : $this->msgError, 'trace' => $this->traceError]);
    }

    public function updateCrmScheduleProgrammer(Request $request, CrmSchedule $crmSchedule)
    {
        try {
            DB::beginTransaction();
            $crmSchedule->technicals = $request->input('users');
            $crmSchedule->observation = $request->input('description');
            $crmSchedule->date = Carbon::createFromFormat('d/m/Y', $request->input('start_submit'))->format('Y-m-d');
            $crmSchedule->start_hour = Carbon::createFromFormat('d/m/Y h:i A', $request->input('start_submit').' '.$request->input('time_start'))->format('H:i');
            $crmSchedule->status_id = 6;
            $crmSchedule->save();
            DB::commit();

            return true;
        } catch (\Exception $th) {
            DB::rollback();
            $this->msgError .= $th->getMessage();
            $this->traceError = $th->getTrace();

            return false;
        }
    }

    public function save(Request $request, Scheduling $schedule)
    {
        $this->validateForm($request);

        $oldUsers = $schedule->users;

        // validacion adicional para servicio de un cronograma
        if (1 == $request->input('type') && !$request->filled('id')) {
            // valdiar que se haya seleccionado un servicio
            if (!$request->filled('crm_schedule_id')) {
                abort('406', 'No has seleccionado ningun servicio de cronograma en el calendario');
            }
        }

        try {
            DB::beginTransaction();

            $schedule->name = $request->input('title');
            $schedule->scheduling_type_id = $request->input('type');

            if ($request->has('enabled')) {
                $schedule->enabled = $request->input('enabled');

                if ($request->filled('capture_date') && 'false' != $request->input('capture_date')) {
                    $schedule->automation_init_date = Carbon::parse($request->input('capture_date'))->format('Y-m-d 00:00:00');
                } else {
                    $schedule->automation_init_date = Carbon::createFromFormat('d/m/Y', $request->input('start_submit'))->format('Y-m-d 00:00:00');
                }
            }

            if ($request->filled('crm_id')) {
                $schedule->crm_id = $request->input('crm_id');
            }
            if ($request->filled('office_id')) {
                $schedule->office_id = $request->input('office_id');
            }
            if ($request->filled('contact_id')) {
                $schedule->contact_id = $request->input('contact_id');
            }

            if (!is_null($request->input('office_id'))) {
                if ($request->filled('lat') && $request->filled('lng') && $request->filled('address')) {
                    if ($request->filled('address')) {
                        $schedule->crm_address = $request->input('address');
                    }
                    if ($request->filled('lat')) {
                        $schedule->crm_lat = $request->input('lat');
                    }
                    if ($request->filled('lng')) {
                        $schedule->crm_lng = $request->input('lng');
                    }
                } else {
                    $schedule->crm_address = Office::find($request->input('office_id'))->address;
                    $schedule->crm_lat = Office::find($request->input('office_id'))->lat;
                    $schedule->crm_lng = Office::find($request->input('office_id'))->lng;
                }
            } else {
                if ($request->filled('address')) {
                    $schedule->crm_address = $request->input('address');
                }
                if ($request->filled('lat')) {
                    $schedule->crm_lat = $request->input('lat');
                }
                if ($request->filled('lng')) {
                    $schedule->crm_lng = $request->input('lng');
                }
            }

            $schedule->start = Carbon::createFromFormat('d/m/Y h:i A', $request->input('start_submit').' '.$request->input('time_start'))->format('Y-m-d H:i:s');
            if ($request->filled('end_submit') && $request->filled('time_end')) {
                $schedule->end = Carbon::createFromFormat('d/m/Y h:i A', $request->input('end_submit').' '.$request->input('time_end'))->format('Y-m-d H:i:s');
            } else {
                $schedule->end = Carbon::createFromFormat('d/m/Y h:i A', $request->input('start_submit').' '.$request->input('time_start'))->addMinutes(60)->format('Y-m-d H:i:s');
            }

            if ($request->filled('crm_schedule_id')) {
                $schedule->crm_schedule_id = $request->input('crm_schedule_id');
            }

            $schedule->description = $request->input('description');
            $schedule->headquarter_id = session('headquarter_id');
            $schedule->save();
            $schedule->users()->sync($request->input('users'));

            // añadir a la hoja de ruta del técnico
            if ($request->filled('route')) {
                // colorear de cafe el evento
                if ($schedule->crm_schedule_id) {
                    $tdate = $request->filled('capture_date') && 'false' != $request->input('capture_date') ? Carbon::parse($request->input('capture_date'))->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $request->input('start_submit'))->format('Y-m-d');
                    CrmSchedule::find($schedule->crm_schedule_id)->update(['status_id' => 2, 'date' => $tdate]);
                }else{
                    $tdate =  Carbon::createFromFormat('d/m/Y', $request->input('start_submit'))->format('Y-m-d');
                }

                // desasociar o reasociar evento a la ruta x técnico (NEVO JEISON 28 Feb 2022)
                $troute = Route::whereIn('user_id', $request->input('users'))
                    ->whereBetween(
                        'date',
                        [
                            Carbon::parse($tdate)->format('Y-m-d 00:00:00'),
                            Carbon::parse($tdate)->format('Y-m-d 23:59:00'),
                        ]
                    )->get()
                ;

                if (!is_null($troute)) {
                    $ids = $troute->pluck('id');
                    $schedule->routes()->sync($ids);
                }

                // regenerar las rutas de los técnicos involucrados (NEVO JEISON 28 Feb 2022)
                $users = empty($oldUsers) ? $schedule->users : User::whereIn('id', $request->input('users'))->get()->merge($oldUsers);
                foreach ($users as $user) {
                    $route = Route::routeUser($user->id, $schedule->start)->get()->last();
                    $this->remakePdf($route);
                }
            }

            // añadir a la hoja de ruta del técnico desde el programador
            if ($request->filled('append_to_route')) {
                // colorear de cafe el evento segun la fecha
                if ($schedule->crm_schedule_id) {
                    // actualizar la información de técnico y hora fija para el evento
                    $crmSchedule = CrmSchedule::find($schedule->crm_schedule_id);
                    $this->updateCrmScheduleProgrammer($request, $crmSchedule);

                    $now = Carbon::now();
                    $date = Carbon::parse($schedule->start);
                    if ($date->startOfDay()->gte($now->startOfDay())) {
                        // poner estado en ruta
                        $status = 2;
                    } else {
                        // poner estado finalizado
                        $status = 3;
                    }

                    $tdate = $request->filled('capture_date') && 'false' != $request->input('capture_date') ? Carbon::parse($request->input('capture_date'))->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $request->input('start_submit'))->format('Y-m-d');
                    CrmSchedule::find($schedule->crm_schedule_id)->update(['status_id' => $status, 'date' => $tdate]);
                }

                $date = Carbon::parse($schedule->start);

                // obtener la automatización de las rutas
                $routes = Route::whereIn('user_id', $request->input('users'))->where('date', $date->format('Y-m-d'))->get();
                foreach ($routes as $route) {
                    $this->addToRoute($route, $schedule);
                }
            }

            // eliminar la notificación de recordatorio (si existe)

            if ($request->filled('deleteNotification')) {
                $not = Auth::User()->notifications->find($request->input('deleteNotification'));
                if ($not) {
                    $not->delete();
                }
            }

            // añadir a la hoja de gestión del cliente
            if ($request->filled('crm_id')) {
                CrmTrackings::report($request, $schedule, 'event', $action = 'create');
            }

            DB::commit();

            return true;
        } catch (\Exception $exc) {
            DB::rollback();
            $this->msgError .= '. '.$exc->getMessage();
            $this->traceError = $exc->getTrace();

            return false;
        }
    }

    public function updateDate(Request $request)
    {
        $id = $request->input('id');
        $schedule = Scheduling::find($id);
        $startHour = Carbon::parse($schedule->start)->format('H:i:s');
        $endHour = Carbon::parse($schedule->end)->format('H:i:s');

        try {
            DB::beginTransaction();
            if ($schedule) {
                // remover de la ruta atual del técnico
                $this->removeToRoute($schedule);

                $start = Carbon::createFromTimestamp($request->input('start'))->format('Y-m-d');
                $end = Carbon::createFromTimestamp($request->input('end'))->format('Y-m-d');
                $schedule->start = $start.' '.$startHour;
                $schedule->end = $end.' '.$endHour;
            }

            $success = $schedule->save();
            // añadir a la hoja de ruta del técnico
            if ($success && $schedule->scheduling_type_id == $this->schedulingTypeSlug) {
                $success = $this->addToRoute(null, $schedule);
            }

            DB::commit();

            return response(['success' => $success, 'message' => $success ? 'Cambios guardados correctamente.' : $this->msgError]);
        } catch (\Exception $exc) {
            DB::rollback();

            return response(['success' => false, 'message' => $this->msgError]);
        }
    }

    public function updateDateR(Request $request)
    {
        $id = $request->input('id');

        switch ($request->input('type')) {
            case 'App\Scheduling':
                $schedule = Scheduling::find($id);

                try {
                    DB::beginTransaction();
                    if ($schedule) {
                        $schedule->start = Carbon::parse($request->input('start'))->format('Y-m-d H:i:s');
                        $schedule->end = Carbon::parse($request->input('end'))->format('Y-m-d H:i:s');
                    }

                    if ($request->filled('user')) {
                        $schedule->users()->sync($request->input('user'));
                    }

                    $success = $schedule->save();
                    $routes = $schedule->routes()->get();

                    if ($success && $routes->count() > 0) {
                        foreach ($routes as $route) {
                            // remover de la ruta actual del técnico
                            $this->removeToRoute($schedule);
                            // añadir a la hoja de ruta del técnico nuevo
                            $success = $this->addToRoute($route, $schedule);
                        }
                    }

                    DB::commit();

                    return response(['success' => $success, 'message' => $success ? 'Cambios guardados correctamente.' : $this->msgError]);
                } catch (\Exception $exc) {
                    DB::rollback();

                    return response(['success' => false, 'message' => $this->msgError.' : '.$exc->getMessage()]);
                }

                break;

            case 'App\CrmSchedule':
                $schedule = CrmSchedule::find($id);
                $start = Carbon::parse($request->input('start'));
                $end = Carbon::parse($request->input('end'));
                $duration = $start->diffInMinutes($end);

                try {
                    DB::beginTransaction();

                    $schedule->date = $start->format('Y-m-d');
                    $schedule->start_hour = $start->format('H:i');
                    $schedule->time_value = $duration;
                    $schedule->time_unit_id = 1;

                    if ($request->filled('user')) {
                        $schedule->technicals = [$request->input('user')];
                    }

                    $success = $schedule->save();

                    $schedulings = $schedule->schedulings()->get();
                    // añadir a la hoja de ruta del técnico nuevo
                    if ($success && $schedulings->count() > 0) {
                        $scheduling = $schedulings->last();

                        if ($scheduling) {
                            $scheduling->start = Carbon::parse($request->input('start'))->format('Y-m-d H:i:s');
                            $scheduling->end = Carbon::parse($request->input('end'))->format('Y-m-d H:i:s');
                        }

                        if ($request->filled('user')) {
                            $scheduling->users()->sync($request->input('user'));
                        }

                        $success = $scheduling->save();

                        $routes = $scheduling->routes()->get();
                        if ($routes->count() > 0) {
                            foreach ($routes as $route) {
                                // remover de la ruta actual del técnico
                                $this->removeToRoute($scheduling);
                                // añadir a la hoja de ruta del técnico nuevo
                                $success = $this->addToRoute($route, $scheduling);
                            }
                        }
                    }
                    DB::commit();

                    return response(['success' => $success, 'message' => $success ? 'Cambios guardados correctamente.' : $this->msgError]);
                } catch (\Exception $exc) {
                    DB::rollback();

                    return response(['success' => false, 'message' => $this->msgError.' : '.$exc->getMessage()]);
                }

                break;
        }
    }

    public function updateAutomationDate(Request $request)
    {
        $id = $request->input('id');
        $schedule = Scheduling::find($id);

        try {
            DB::beginTransaction();
            if ($schedule) {
                $schedule->start = $request->input('start');
                $schedule->end = $request->input('end');
                $success = $schedule->save();
                if ($request->filled('user_id')) {
                    $schedule->users()->sync([$request->input('user_id')]);
                }
                DB::commit();
            } else {
                return response(['success' => false, 'message' => 'El evento que desea modificar no está disponible']);
            }

            return response(['success' => $success, 'message' => $success ? 'Cambios guardados correctamente.' : $this->msgError]);
        } catch (\Exception $exc) {
            DB::rollback();

            return response(['success' => false, 'message' => $this->msgError.': '.$exc->getMessage(), 'trace' => $exc->getTrace()]);
        }
    }

    public function validateForm(Request $request)
    {
        $niceNames = [
            'name' => 'Titulo',
            'type' => 'Tipo',
            'users' => 'Responsables',
            'start' => 'Fecha inicio',
            'time_start' => 'Hora inicio',
            'end' => 'Fecha fin',
            'time_end' => 'Hora fin',
            'crm_id' => 'Cliente',
            'description' => 'Observación',
            'office_id' => 'Sucursal',
            'contact_id' => 'Contacto',
            'services_id' => 'Servicios',
            'services.*.detail_service_id' => 'Área',
            'services.*.price' => 'Precio',
            'services.*.obs.*.role' => 'Rol',
            'services.*.obs.*.observation' => 'Observación',
            'services.*.obs.*.file' => 'Adjunto',
            'services.*.supplies.*.product' => 'Producto',
            'services.*.supplies.*.quantity' => 'Cant/Dosis',
            'services.*.tasks.*.description' => 'Tarea',
        ];

        $customMsg = [
            'services.required_if' => 'El campo :attribute es obligatorio cuando el tipo es "Servicio"',
            'crm_id.required_if' => 'El campo :attribute es obligatorio cuando el tipo es "Servicio"',
            'services.*.obs.*.observation.required_with' => 'El campo :attribute es obligatorio cuando Rol está presente',
            'end.required_unless' => 'El campo :attribute es obligatorio cuando el tipo no es "Recordatorio"',
            'time_end.required_unless' => 'El campo :attribute es obligatorio cuando el tipo no es "Recordatorio"',
        ];

        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'users' => 'required',
            'start' => 'required',
            'end' => 'required_unless:type,3',
            'time_start' => 'required',
            'time_end' => 'required_unless:type,3',
        ], $customMsg, $niceNames);
    }

    public function editRoute(Request $request)
    {
        try {
            $route = Route::find($request->input('id'));
            DB::beginTransaction();
            if ($route) {
                if ($request->filled('date')) {
                    $route->date = $request->input('date');
                }
                if ($request->filled('status')) {
                    $route->status_id = $request->input('status');
                }
                if ($request->filled('observation')) {
                    $route->observation = $request->input('observation');
                }
                $route->save();

                // actualizar la fecha de todos los servicios de la ruta
                foreach ($route->schedulings as $sh) {
                    $scheduling = Scheduling::find($sh->id);
                    $oldStart = Carbon::parse($sh->start)->format('H:i:s');
                    $oldEnd = Carbon::parse($sh->end)->format('H:i:s');

                    $scheduling->start = Carbon::parse($request->input('date'))->format("Y-m-d {$oldStart}");
                    $scheduling->end = Carbon::parse($request->input('date'))->format("Y-m-d {$oldEnd}");
                    $scheduling->save();
                }

                // recorrer los elementos editatos
                if ($request->filled('move_events')) {
                    foreach (json_decode($request->input('move_events')) as $moveEvent) {
                        // actualizar schedulings

                        $scheduling = Scheduling::find($moveEvent->id);
                        if ($scheduling) {
                            if (!is_null($moveEvent->start)) {
                                $scheduling->start = $carbon = Carbon::parse($moveEvent->start)->tz('America/Bogota')->format('Y-m-d H:i:s');
                            }
                            if (!is_null($moveEvent->end)) {
                                $scheduling->end = Carbon::parse($moveEvent->end)->tz('America/Bogota')->format('Y-m-d H:i:s');
                            }
                            $scheduling->save();
                        }
                    }
                }

                DB::commit();

                // eliminar archivo pdf del servidor y generarlo nuevamente
                $filename = 'r-'.Carbon::parse($route->date)->format('dmY').'-'.$route->user->name;
                $path = 'routes/'.$filename.'.pdf';
                $file = "storage/app/public/{$path}";

                Storage::disk('public')->delete('file');

                // desvincular y vincular los eventos nuevamente
                $route->schedulings()->sync([]);

                $schedulings = Scheduling::whereHas('users', function ($users) use ($route) {
                    $users->where('user_id', $route->user_id);
                })->whereBetween('start', [Carbon::parse($route->date)->format('Y-m-d 00:00:00'), Carbon::parse($route->date)->format('Y-m-d 23:59:59')])->get()->pluck('id');

                $route->schedulings()->sync($schedulings);

                if ($request->filled('remove_events')) {
                    // recorrer los elementos eliminados
                    foreach (json_decode($request->input('remove_events')) as $removeEvent) {
                        // actualizar schedulings
                        if (Scheduling::find($removeEvent->id)) {
                            $schedule = Scheduling::with('users')->find($removeEvent->id);

                            // desvincular el evento de la ruta del técnico
                            $route->schedulings()->detach($schedule->id);

                            // verificar si es el único técnico asociado
                            if ($schedule->users->count() < 2) {
                                // eliminar el evento por completo
                                Scheduling::find($removeEvent->id)->delete();
                            } else {
                                // desvincular solo a este técnico del evento
                                $schedule->users()->detach($route->user->id);
                            }
                        }
                    }
                }

                $nroute = Route::with(
                    'schedulings.users',
                    'schedulings.crm',
                    'schedulings.crmSchedule.service',
                    'schedulings.crmSchedule.controls.infestation',
                    'schedulings.crmSchedule.controls.product',
                    'schedulings.crmSchedule.controls.area',
                    'schedulings.crmSchedule.controls.dose',
                    'schedulings.crmSchedule.controls.application',
                )
                    ->find($route->id)
                ;

                // almacenar archivo en físico
                $route = $nroute;

                $subject = 'Reporte de ruta '.Date::parse($nroute->date)->format('l').'('.Carbon::parse($nroute->date)->format('d-m-Y').') - '.$nroute->user->name;
                $view = view('scheduling.sub.routePdf', compact(['route', 'subject']))->render();
                $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');

                $nfilename = 'r-'.Carbon::parse($nroute->date)->format('dmY').'-'.$nroute->user->name;
                $npath = 'routes/'.$nfilename.'.pdf';

                Storage::disk('public')->put($npath, $pdf->output());
            }

            return response(['success' => true, 'message' => 'Cambios guardados correctamente.']);
        } catch (\Exception $ex) {
            DB::rollBack();

            return response(['success' => false, 'message' => 'Se presentó un error guardando los cambios: '.$ex->getMessage(), 'trace' => $ex->getTrace()]);
        }
    }

    /** AUTOMATIZACIÓN*/
    public function automation()
    {
        return view('scheduling.automations');
    }

    public function automationCreate()
    {
        return view('scheduling.automation');
    }

    public function automationManual()
    {
        return view('scheduling.manual');
    }

    public function automationList()
    {
        $automations = Automation::enabled()->headquarter()->orderBy('id', 'desc')->limit(10)->get();
        $view = view('scheduling.sub.automationList', compact(['automations']))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function createAutomation()
    {
        return view('scheduling.automation');
    }

    public function validateStep(Request $request, $step)
    {
        switch ($step) {
            case 1:
                $niceNames = ['init_date' => 'Dia de la ruta a programar', 'end_date' => 'Fecha fin', 'availabilities' => 'Disponiblidades técnicas', 'users' => 'Tecnicos'];
                $rules = ['init_date' => 'required', 'availabilities' => 'required', 'users' => 'required'];

                break;

            case 11:
                $niceNames = ['init_date' => 'Dia de la ruta a programar', 'manual_users' => 'Técnicos a programar'];
                $rules = ['init_date' => 'required', 'manual_users' => 'required'];

                break;

            default:
                $niceNames = [];
                $rules = [];

                break;
        }
        $request->validate($rules, [], $niceNames);

        return response(['continue' => true]);
    }

    public function availabilities(Request $request)
    {
        $query = Availability::with('workingHours')->select(['id', 'name as text'])->headquarter()->enabled();

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%'.$request->input('term').'%');
        }
        $data = $query->get();

        $data = $data->map(function ($item) {
            $item->text = $item->text.' '.$item->workingHours->map(function ($subitem) {
                return $subitem->start.'-'.$subitem->end;
            });

            return $item;
        });

        return response(['results' => $data]);
    }

    public function userAvailabilities(Request $request)
    {
        $availabilities = $request->input('availabilities');
        $users = null;
        if (!is_null($availabilities)) {
            $users = Availability::with(['users' => function ($q) {
                $q->headquarter(true);
            }])
                ->headquarter(true)
                ->find($availabilities)
                ->pluck('users')
                ->flatten()
                ->groupBy('id')
                ->flatMap(function ($items) {
                    return $items;
                })
                ->unique('id')
            ;
        }

        $view = view('scheduling.forms.frames.user_availabilities', compact('users'))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function getCustomerStep(Request $request)
    {
        $technicalFixesOnly = false;
        if ($request->filled('only_techical_fixes')) {
            $technicalFixesOnly = true;
        }

        $init_date = Carbon::parse($request->input('init_date_submit'))->format('Y-m-d ');

        // el estado en el que solo se tomaran los eventos
        $status = CrmScheduleStatus::whereIn('slug', ['programed'])->get()->pluck('id')->toArray();
        $status_repeat = CrmScheduleStatus::whereIn('slug', ['cancelled', 'service-fail'])->get()->pluck('id')->toArray();
        $useStatusRepeat = !$request->filled('no_show_cancelled');

        // buscar servicios en el cronograma de servicio general
        $crms = Crm::with(['schedules' => function ($q) use ($init_date, $status, $status_repeat, $technicalFixesOnly, $request, $useStatusRepeat) {
            $q->whereHas('workschedule', function ($ws) {
                $ws->where('headquarter_id', session('headquarter_id'));
            })->whereHas('office', function ($ws) {
                $ws->whereNull('deleted_at');
            })
                ->where(function ($w) use ($init_date, $status, $status_repeat, $useStatusRepeat) {
                    $w->where('date', [$init_date])->whereIn('status_id', $status);
                    if ($useStatusRepeat) {
                        $w->orWhereIn('status_id', $status_repeat);
                    }
                })
            ;

            if ($technicalFixesOnly) {
                $q->where(function ($q) use ($request) {
                    $q->whereIn('technicals', $request->input('users'));
                    foreach ($request->input('users') as $user) {
                        $q->orWhereRaw("find_in_set({$user}, REPLACE(REPLACE(REPLACE(technicals, '\"', ''), '[', ''), ']',''))");
                    }
                });
            }
        }, 'offices' => function ($o) {
            $o->with('contacts')->where('headquarter_id', session('headquarter_id'));
        }])

            ->whereHas('schedules', function ($q) use ($init_date, $status, $status_repeat, $technicalFixesOnly, $request, $useStatusRepeat) {
                $q->whereHas('workschedule', function ($ws) {
                    $ws->where('headquarter_id', session('headquarter_id'));
                })->where(function ($w) use ($init_date, $status, $status_repeat, $useStatusRepeat) {
                    $w->where(function ($ww) use ($init_date, $status) {
                        $ww->where('date', [$init_date])->whereIn('status_id', $status);
                    });
                    if ($useStatusRepeat) {
                        $w->orWhereIn('status_id', $status_repeat);
                    }
                });

                if ($technicalFixesOnly) {
                    $q->where(function ($q) use ($request) {
                        $q->whereIn('technicals', $request->input('users'));
                        foreach ($request->input('users') as $user) {
                            $q->orWhereRaw("find_in_set({$user}, REPLACE(REPLACE(REPLACE(technicals, '\"', ''), '[', ''), ']',''))");
                        }
                    });
                }
            })->get()
        ;

        // consultar clientes que tienen un contrato
        // de servicio vigente a la fecha establecida
        // $crms = Crm::with(['contracts.quote' => function ($p) use ($start_date, $end_date, $request) {

        //     $p->with(['services' => function ($q) use ($start_date, $end_date, $request) {
        //         $q->with(['quote.office.city', 'service' => function ($sq) use ($request) {
        //             $sq->whereIn('id', $request->input('services'))->enabled();
        //         }])->whereBetween('start_date', [$start_date, $end_date]);;
        //     }])->enabled()->headquarter(true);
        // }])->whereHas('contracts', function ($q) use ($start_date, $end_date, $request) {
        //     $q->whereHas('quote', function ($sq) use ($start_date, $end_date, $request) {
        //         $sq->where('status_id', 3);
        //     })->where('status_id', 1)->headquarter('true')->whereBetween('start_date', [$start_date, $end_date]);
        // })
        //     ->headquarterOffice()
        //     ->get();
        // if ($crms) {

        //     $crms->map(function ($crm) {
        //         $crm->services = $crm->contracts
        //             ->pluck('quote')->flatten()
        //             ->pluck('services')->flatten()->filter()->toArray();
        //         return $crm;
        //     });
        // }

        $initDate = $request->input('init_date_submit');

        $view = view('scheduling.forms.frames.crm_services', compact(['crms', 'initDate']))->render();

        return response(['success' => count($crms) > 0 ? true : false, 'view' => $view]);
    }

    public function getAutomationStep(Request $request)
    {
        // quitar el limite de la ejecucion de php para esta tarea
        set_time_limit(0);
        ini_set('max_execution_time', '0');

        // eliminar los servicios asignados sin confirmar para este día
        $this->deleteAnotherShedulings(Carbon::parse($request->input('init_date_submit')));

        $rules = [
            'last_service' => 100,
            'technical_assigned' => 500,
            'coverage_zone' => 100,
            'technical_skill' => 50,
            'proximity' => 50,
            'coverage_zone_out' => 5,
            'available' => 5,
            'extra_time' => 5,
            'last_extra_time' => -10,
            'dont_available' => -255,
            'full_time_service' => -300,
        ];

        $possibleAssignment = [];
        $coverage = [];
        $coverageOutRange = [];

        // paso 0: obtener las zonas de coberturas habilitadas
        $zones = Zone::selectRaw('*')->headquarter()->enabled()->get();
        if (0 == $zones->count()) {
            abort(406, 'No se encontraron zonas de coberturas configuradas para esta sede, sin ellas el proceso de automatización no puede asignar servicios.');
        }

        // Paso 1: Centralizar los servicios a programar
        $schedules_id = Arr::flatten($request->input('servicesType'));

        /*Paso 2: Servicios a ejecutar con relación de clientes,
        oficina del cliente y tecnicos asignados al cliente*/

        $services = CrmSchedule::with([
            'crm.users' => function ($u) {
                $u->whereHas('roles', function ($r) {
                    $r->where('slug', 'technical')->where('headquarter_id', session('headquarter_id'));
                });
            }, 'office.city', 'service', 'timeUnit',
        ])
            ->orderByRaw("time_unit_id ASC, time_value ASC, technicals DESC, start_hour is null desc, str_to_date(start_hour,'%l:%i') DESC")
            ->find($schedules_id);

        /*Paso 3: obtener todos los técnicos seleccionados en la automatización
        con relación de sus habilidades y zonas de coberturas y clientes asignados*/
        $technicals = User::with([
            'services', 
            'zones', 
            'crms', 
            'availabilities' => function($q) use($request){$q->whereIn('availabilities.id', $request->availabilities);}, 
            'schedulings' => function ($q) use ($request) {
            $q
                ->whereBetween('start', [$request->input('start_date_submit').' 00:00:00', $request->input('end_date_submit').' 23:59:59'])
                ->whereBetween('end', [$request->input('start_date_submit').' 00:00:00', $request->input('end_date_submit').' 23:59:59'])
                ->headquarter()
            ;
        }])->inRandomOrder()->find($request->input('users'));

        // paso 4: evaluar el técnico mas apropiado para la automatización
        $lastTechnical = [];
        foreach ($services as $service) {
            $possibleAssignment[$service->id]['service'] = $service->service->name.' - '.($service->date ? $service->date : '').' - '.($service->technicals ? $service->technicals->implode('name', ', ') : '');
            $possibleAssignment[$service->id]['customer'] = $service->crm->name;
            $possibleAssignment[$service->id]['office'] = $service->office->name;
            $possibleAssignment[$service->id]['coords'] = ['lat' => $service->office->lat, 'lng' => $service->office->lng];
            $possibleAssignment[$service->id]['company'] = ($service->technicals ? $service->technicals->pluck('id') : null);

            // if($service->technicals){
            //     //verificar que los técnicos fijos estén seleccionados
            //     foreach($service->technicals as $st){
            //         if(!in_array($st->id, $technicals->pluck('id')->toArray())){
            //             abort(406, 'El técnico fijo ('.strtoupper($st->name).') del cliente '.strtoupper($service->crm->name) .', en sucursal '. strtoupper($service->office->name). ', para el servicio de'. strtoupper($service->service->name). ' no fueron seleccionados en el paso 1, debese seleccionarlos a ambos o bien, remover este servicio en el paso 2');
            //         }
            //     }

            // }

            

            if (count($service->technicals) == 0) {
                
                foreach ($technicals as $technical) {
                    // Almacenar la data necesaria para asignar el servicio
                    $possibleAssignment[$service->id]['technicals'][$technical->id] = $technical->name;
                    $possibleAssignment[$service->id][$technical->id]['availabilities'] = $this->joinaAvailabilitiesTime($technical, $request);

                    /** ordenar las disponibilidades técnicas */
                    $resultA = [];
                    $temp = ['start' => '23:59'];
                    $journal = $possibleAssignment[$service->id][$technical->id];

                    foreach ($journal['availabilities'] as $availability) {
                        $result = strcmp($availability['start'], $temp['start']);
                        if ($result > 0) {
                            array_unshift($resultA, $availability);
                            $temp = $availability;
                        } else {
                            array_push($resultA, $availability);
                        }
                    }

                    $possibleAssignment[$service->id][$technical->id]['availabilities'] = $resultA;

                    $possibleAssignment[$service->id]['score'][$technical->id] = 0;

                    // calificar la disponibilidad en el sistema (activo/inactivo)
                    $possibleAssignment[$service->id]['score'][$technical->id] += !$technical->enabled ? $rules['dont_available'] : 1;

                    // calificar asignación x servicio x sucursal del cliente

                    if (!is_null($service->technicals)) {
                        $possibleAssignment[$service->id]['score'][$technical->id] += $service->technicals->pluck('id')->contains($technical->id) ? $rules['technical_assigned'] : 0;
                    }

                    // calificar habilidad
                    $possibleAssignment[$service->id]['score'][$technical->id] += $technical->services->pluck('id')->contains($service->service->id) ? $rules['technical_skill'] : 0;

                    // determinar la zona de cobertura mas cercana al cliente
                    list($coverage, $coverageOutRange) = $this->getCoverage($service, $zones);

                    // calificar zona de cobertura
                    if (false !== $coverage) {
                        $possibleAssignment[$service->id]['score'][$technical->id] += $technical->zones->pluck('id')->contains($coverage) ? $rules['coverage_zone'] : 0;
                    }

                    // este puntaje se asigna si el técnico no tiene zonas de cobertura (null indica que esta disponible para todas la zonas)
                    $possibleAssignment[$service->id]['score'][$technical->id] += 0 == count($technical->zones) ? $rules['coverage_zone'] : 0;

                    // calificar zona de cobertura fuera de rango
                    if (false !== $coverageOutRange) {
                        $possibleAssignment[$service->id]['score'][$technical->id] += $technical->zones->pluck('id')->contains($coverageOutRange) ? $rules['coverage_zone_out'] : 0;
                    }

                    // calificar la disponibilidad en agenda para servicios fijos
                    if (!$this->hasAvailabilityAgendaForFixed($request, $service, $technical)) {
                        $possibleAssignment[$service->id]['score'][$technical->id] += 500;
                    }

                    if(in_array($technical->id,$lastTechnical)){
                        $possibleAssignment[$service->id]['score'][$technical->id] += 600;
                    }
                }
                
            } else {
                foreach ($service->technicals as $st) {
                    if (in_array($st->id, $technicals->pluck('id')->toArray())) {
                        $technical = $st;
                        $lastTechnical[] = $st->id;
                        $possibleAssignment[$service->id][$technical->id]['availabilities'] = $this->joinaAvailabilitiesTime($technical, $request);
                        $possibleAssignment[$service->id]['technicals'][$technical->id] = $technical->name;
                        $possibleAssignment[$service->id]['score'][$technical->id] = 600;
                    }
                }
            }
        }

        $lastTechnical = [];

        /*paso 4.1: Aplicar algoritmo de ditancias para identificar
        combinaciones de rutas cortas entre servicios y reorganizar el orden de los servicios*/

        // verificar si la sede posee coordenadas
        $coords = Headquarter::whereNotNull('lat')->whereNotNull('lng')->find(session('headquarter_id'));
        if (empty($coords->lat) || empty($coords->lng)) {
            abort('406', 'Esta sede no tiene configurada una dirección validada con Google Maps, pídele al administrador de la sede que las configure desde el módulo de sedes.<br><br> Necesitamos una dirección validada con Google Maps para poder obtener unas coordenadas reales y con ellas poder estimar una ruta de servicios con tramos cortos de desplazamientos.');
        }

        // Obtener el orden de los servicios con tramos mas cortos
        $this->crossJoinDistances($possibleAssignment, $coords->toArray(), false, true);
        // reordenar los servicios
        $possibleAssignment = $this->serviceOrder;

        // paso 5: asignar programación de servicios en base al puntaje obtenido
        $asignations = $this->assignServices($possibleAssignment, $services, $technicals, $request, $coords);

        // paso 6: rellenar campos vacios
        // if($request->filled('convert_white_spaces')){
        //     $this->covertWhiteSpaces($request);
        // }

        return response(['success' => $asignations, 'message' => $asignations ? 'Se ha completado la automatizacion de los servicios seleccionado, es probable que no todos hayan sido incluido debido a la disponibilidad de los técnicos, a continuación podrás confirmar/corregir las asignaciones realizadas por el sistema' : 'Ocurrió un problema intentando guardar alguna asignación.']);
    }

    public function covertWhiteSpaces(Request $request)
    {
        $date = Carbon::parse($request->input('init_date_submit'))->format('Y-m-d 00:00:00');

        // eliminar los traslados existentes
        Scheduling::where('automation_init_date', $date)
            ->where('enabled', 0)
            ->where('headquarter_id', session('headquarter_id'))
            ->where('scheduling_type_id', 0)->delete()
        ;

        // obtener todos los eventos para la automatizacion seleccionada
        $users = User::with(['schedulings' => function ($s) use ($date) {
            $s->where('automation_init_date', $date)
                ->where('enabled', 0)
                ->where('headquarter_id', session('headquarter_id'))
                ->whereNotIn('scheduling_type_id', [0])
                ->orderBy('start', 'asc')
            ;
        }])->whereHas('schedulings', function ($s) use ($date) {
            $s->where('automation_init_date', $date)
                ->where('enabled', 0)
                ->where('headquarter_id', session('headquarter_id'))
                ->whereNotIn('scheduling_type_id', [0])
            ;
        })->get();

        // recorrerlos todos y generar  los traslados de rutas
        foreach ($users as $user) {
            $lastSH = null;
            if ($user->schedulings) {
                foreach ($user->schedulings as $shN => $shV) {
                    if (null == $lastSH) {
                        $lastSH = $shV;

                        continue;
                    }

                    // if($user->id == 42){
                    //     dd($user->schedulings->count(), $user->toArray());
                    // }

                    // if(($shN + 1) == $user->schedulings->count()){
                    //     $lastSH = null;
                    //     continue;
                    // }

                    // if($user->id == 42){
                    //     dd($user->toArray());
                    // }

                    if (Carbon::parse($lastSH->end)->gte(Carbon::parse($shV->start))) {
                        continue;
                    }

                    // crear el servicio de traslado
                    $scheduling = Scheduling::create([
                        'name' => 'TRASLADO DE RUTA',
                        'description' => '',
                        'scheduling_type_id' => 0,
                        'crm_id' => $shV->crm_id,
                        'office_id' => $shV->office_id,
                        'crm_address' => $shV->crm_address,
                        'crm_lat' => $shV->crm_lat,
                        'crm_lng' => $shV->crm_lng,
                        'start' => $lastSH->end,
                        'end' => $shV->start,
                        'automation_init_date' => $shV->automation_init_date,
                        'headquarter_id' => $shV->headquarter_id,
                        'completed' => 0,
                        'enabled' => 0,
                    ]);
                    $scheduling->users()->sync([$user->id]);
                    $lastSH = $shV;
                }
            }
        }

        return response(['success' => true]);
    }

    public function getCalendarStep(Request $request)
    {
        $date = Carbon::parse($request->input('init_date_submit'));
        $tech = Scheduling::with(['users' => function ($users) use ($request) {
            $users->where('headquarter_id', session('headquarter_id'))
                ->whereIn('user_id', $request->input('users'))
            ;
        }])
            ->whereBetween('start', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])
            ->whereHas('users', function ($users) use ($request) {
                $users->where('headquarter_id', session('headquarter_id'))
                    ->whereIn('user_id', $request->input('users'))
                ;
            })
        ;

        try {
            $order = 0;
            // obtener los técnicos programados por la automatización
            $resources = $tech->where('enabled', 0)
                ->get()->pluck('users')->collapse()->unique('id')->map(function ($v, $k) use (&$order, $date) {
                    ++$order;

                    $hasRoute = Route::where('user_id', $v->id)->where('date', $date->format('Y-m-d 00:00:00'))->get();

                    return
                        [
                            'id' => "{$v->id}",
                            'title' => $v->medium_name,
                            'avatar' => $v->full_avatar,
                            'order' => $order,
                            'dispatched' => $hasRoute->count() > 0 ? true : false,
                        ];
                })->values()
            ;

            // obtener los inicios de las jornadas seleccionadas en la automatizacion
            $availabilities = collect([]);
            $workingHours = WorkingHour::select('id', 'start', 'end', 'extra')->whereIn('availability_id', $request->input('availabilities'))->get();
            foreach ($workingHours as $workingHour) {
                $availabilities->push($workingHour->toArray());
            }

            // obtner todos los eventos para el dia de la ruta automatizada
            $data = Scheduling::with(['users' => function ($users) {
                $users->where('headquarter_id', session('headquarter_id'));
            }, 'schedulingType', 'crmSchedule.service'])
                ->whereBetween('start', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])
                ->where('headquarter_id', session('headquarter_id'))
                ->whereHas('users', function ($users) {
                    $users->where('headquarter_id', session('headquarter_id'));
                })
                ->get()->map(function ($schedule, $sk) use ($date, $availabilities, $request) {
                    $tmp[] = $schedule->users->map(function ($user, $uk) use ($schedule, $date, $availabilities, $request) {
                        if (in_array($user->id, $request->input('users')) && 0 == $schedule->enabled) {
                            $ordinary = $this->verifyWorkingHourType($availabilities, $schedule->start, $schedule->end, 'ordinary');
                            $extra = $this->verifyWorkingHourType($availabilities, $schedule->start, $schedule->end, 'extra');

                            $this->userTimes[$user->id]['id'] = $user->id;
                            $this->userTimes[$user->id]['user'] = $user->name;
                            $this->userTimes[$user->id]['availabilities'] = $request->input('availabilities');

                            try {
                                $this->userTimes[$user->id]['ordinary'] += $ordinary;
                            } catch (\Exception $th) {
                                $this->userTimes[$user->id]['ordinary'] = $ordinary;
                            }

                            try {
                                $this->userTimes[$user->id]['extra'] += $extra;
                            } catch (\Exception $th) {
                                $this->userTimes[$user->id]['extra'] = $extra;
                            }

                            try {
                                $this->userTimes[$user->id]['total'] += ($ordinary + $extra);
                            } catch (\Exception $th) {
                                $this->userTimes[$user->id]['total'] = ($ordinary + $extra);
                            }
                        }

                        return [
                            'id' => $schedule->id,
                            'resourceId' => "{$user->id}",
                            'title' => $schedule->name,
                            'icon' => isset($schedule->crmSchedule) ? $schedule->crmSchedule->service->avatar : '',
                            'backgroundColor' => $schedule->automation_init_date == $date->format('Y-m-d 00:00:00') ? $schedule->schedulingType->color : $schedule->schedulingType->disabled_color,
                            'editable' => $schedule->automation_init_date == $date->format('Y-m-d 00:00:00') ? true : false,
                            'start' => Carbon::parse($schedule->start)->format('Y-m-d H:i:s'),
                            'end' => Carbon::parse($schedule->end)->format('Y-m-d H:i:s'),
                        ];
                    });

                    return $tmp;
                })->flatten(2)
            ;

            // añadir usuario sin agenda
            $users = User::whereIn('id', $request->input('users'))->get();
            foreach ($users as $key => $user) {
                if (!$resources->contains('id', $user->id)) {
                    ++$order;
                    $hasRoute = Route::where('user_id', $user->id)->where('date', $date->format('Y-m-d 00:00:00'))->get();
                    $resources->push([
                        'id' => "{$user->id}",
                        'title' => $user->medium_name,
                        'avatar' => $user->full_avatar,
                        'order' => $order,
                        'dispatched' => $hasRoute->count() > 0 ? true : false,
                    ]);
                }
            }

            $totalTime = $this->userTimes;

            // guardar las horas estimadas de los técnicos en la session
            Session::forget('totalTime');
            Session::put('totalTime', $this->userTimes);
            Session::save();
        } catch (\Exception $th) {
            abort('406', 'Al parecer las asignaciones ya no se encuentran disponibles, vuelve a intentarlo nuevamente, <br><br> '.$th->getMessage());
        }

        $view = view('scheduling.forms.frames.calendar_automation', compact('data', 'totalTime'))->render();

        return response(['success' => count($data) > 0 ? true : false, 'view' => $view, 'data' => $data->toArray(), 'resources' => $resources->all(), 'date' => $date->format('Y-m-d'), 'workigHours' => $availabilities->toArray()]);
    }

    public function getManualCalendarStep(Request $request)
    {
        $date = Carbon::parse($request->input('init_date_submit'));
        $tech = Scheduling::with(['users' => function ($users) {
            $users->where('headquarter_id', session('headquarter_id'));
        }, 'schedulingType', 'crmSchedule.service'])
            ->whereBetween('start', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])->whereHas('users', function ($users) {
                $users->where('headquarter_id', session('headquarter_id'));
            })
            ->where('headquarter_id', session('headquarter_id'))
            ->whereHas('users', function ($users) {
                $users->where('headquarter_id', session('headquarter_id'));
            })
        ;
        $order = 0;

        try {
            // obtener los técnicos programados por la automatización manual
            $resources = $tech->where('enabled', 0)
                ->get()->pluck('users')->collapse()->unique('id')->map(function ($v, $k) use ($order, $date) {
                    ++$order;
                    $hasRoute = Route::where('user_id', $v->id)->where('date', $date->format('Y-m-d 00:00:00'))->get();

                    return
                        [
                            'id' => "{$v->id}",
                            'title' => $v->name,
                            'headquarter' => $v->headquarter_id,
                            'avatar' => $v->full_avatar,
                            'order' => $order,
                            'dispatched' => $hasRoute ? true : false,
                        ];
                })->values()
            ;

            // obtener los inicios de las jornadas seleccionadas en la automatizacion
            $availabilities = collect([]);
            $workingHours = [];
            foreach ($workingHours as $workingHour) {
                $availabilities->push($workingHour->toArray());
            }

            // obtner todos los eventos para el dia de la ruta automatizada
            $data = Scheduling::with(['users' => function ($users) {
                $users->where('headquarter_id', session('headquarter_id'));
            }, 'schedulingType', 'crmSchedule.service', 'schedulingType', 'crmSchedule.service'])
                ->whereBetween('start', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])
                ->where('headquarter_id', session('headquarter_id'))
                ->whereHas('users', function ($users) {
                    $users->where('headquarter_id', session('headquarter_id'));
                })
                ->get()->map(function ($schedule, $sk) use ($date, $availabilities, $request) {
                    $tmp[] = $schedule->users->map(function ($user, $uk) use ($schedule, $date, $availabilities, $request) {
                        if (in_array($user->id, $request->input('manual_users')) && 0 == $schedule->enabled) {
                            $ordinary = $this->verifyWorkingHourType($availabilities, $schedule->start, $schedule->end, 'ordinary');
                            $extra = $this->verifyWorkingHourType($availabilities, $schedule->start, $schedule->end, 'extra');

                            $this->userTimes[$user->id]['id'] = $user->id;
                            $this->userTimes[$user->id]['user'] = $user->name;
                            $this->userTimes[$user->id]['availabilities'] = $request->input('availabilities');

                            try {
                                $this->userTimes[$user->id]['ordinary'] += $ordinary;
                            } catch (\Exception $th) {
                                $this->userTimes[$user->id]['ordinary'] = $ordinary;
                            }

                            try {
                                $this->userTimes[$user->id]['extra'] += $extra;
                            } catch (\Exception $th) {
                                $this->userTimes[$user->id]['extra'] = $extra;
                            }

                            try {
                                $this->userTimes[$user->id]['total'] += ($ordinary + $extra);
                            } catch (\Exception $th) {
                                $this->userTimes[$user->id]['total'] = ($ordinary + $extra);
                            }
                        }

                        return [
                            'id' => $schedule->id,
                            'resourceId' => "{$user->id}",
                            'title' => $schedule->name,
                            'icon' => isset($schedule->crmSchedule) ? $schedule->crmSchedule->service->avatar : '',
                            'backgroundColor' => $schedule->automation_init_date == $date->format('Y-m-d 00:00:00') ? $schedule->schedulingType->color : $schedule->schedulingType->disabled_color,
                            'editable' => $schedule->automation_init_date == $date->format('Y-m-d 00:00:00') ? true : false,
                            'start' => Carbon::parse($schedule->start)->format('Y-m-d H:i:s'),
                            'end' => Carbon::parse($schedule->end)->format('Y-m-d H:i:s'),
                        ];
                    });

                    return $tmp;
                })->flatten(2)
            ;

            // añadir usuario sin agenda
            $users = User::whereIn('id', $request->input('manual_users'))->get();
            $order = $resources->count();
            foreach ($users as $key => $user) {
                ++$order;
                if (!$resources->contains('id', $user->id)) {
                    $resources->push([
                        'id' => "{$user->id}",
                        'title' => $user->name,
                        'avatar' => $user->full_avatar,
                        'order' => $order,
                        'dispatched' => Route::where('user_id', $user->id)->where('date', $date->format('Y-m-d 00:00:00'))->get() ? true : false,
                    ]);
                }
            }

            $totalTime = $this->userTimes;

            // guardar las horas estimadas de los técnicos en la session
            Session::forget('totalTime');
            Session::put('totalTime', $this->userTimes);
            Session::save();
        } catch (\Exception $th) {
            abort('406', 'Al parecer las asignaciones ya no se encuentran disponibles, vuelve a intentarlo nuevamente, <br><br> '.$th->getMessage());
        }

        $view = view('scheduling.forms.frames.calendar_automation', compact('data', 'totalTime'))->render();

        return response(['success' => count($data) > 0 ? true : false, 'view' => $view, 'data' => $data->toArray(), 'resources' => $resources->all(), 'date' => $date->format('Y-m-d'), 'workigHours' => $availabilities->toArray()]);
    }

    public function getResumeStep(Request $request)
    {
        $date = Carbon::parse($request->input('init_date_submit'));
        $dateFront = new Date($date);
        $dateFront = ucwords($dateFront->format('d F, Y'));
        $users = User::with(['schedulings' => function ($q) use ($date) {
            $q->with('crm')
                ->whereBetween('start', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])
            ;
        }])->whereIn('id', $request->input('users'))->get();

        $view = view('scheduling.forms.frames.resume_automation', compact(['users', 'dateFront']))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function dispatcher(Request $request, $user)
    {
        ini_set('max_execution_time', '300');

        $date = Carbon::parse($request->input('init_date_submit'))->format('Y-m-d 00:00:00');
        $automation = new Automation();
        $automation->date = $date;
        $automation->date_range = ['start' => Carbon::parse($request->input('start_date_submit'))->format('Y-m-d 00:00:00'), 'end' => Carbon::parse($request->input('start_date_submit'))->format('Y-m-d 23:59:59')];
        $automation->availabilities = $request->input('availabilities');
        $automation->users = [$user];
        $automation->services = $request->input('services');
        $automation->services_config = $request->input('servicesFix');
        $automation->user_id = Auth::user()->id;
        $automation->headquarter_id = session('headquarter_id');

        try {
            DB::beginTransaction();

            $automation->save();

            // cambiar el estado de estos servicios en el cronograma a 'En Ruta'
            $schedulings = Scheduling::where('automation_init_date', $date)
                ->where('enabled', 0)
                ->whereNotIn('scheduling_type_id', [0])
                ->whereHas('users', function ($users) use ($user) {
                    $users->where('user_id', $user);
                })
                ->where('headquarter_id', session('headquarter_id'))
                ->get()
            ;

            foreach ($schedulings as $scheduling) {
                if ($scheduling->crm_schedule_id) {
                    $scheduling->crmSchedule()->update(
                        [
                            'status_id' => CrmScheduleStatus::where('slug', 'route')->get()->last()->id,
                            'date' => Carbon::parse($request->input('init_date_submit'))->format('Y-m-d'),
                            'start_hour' => Carbon::parse($scheduling->start)->format('H:i'),
                        ]
                    );
                }
            }

            // cambiar el estado de los eventos
            Scheduling::where('automation_init_date', $date)
                ->where('enabled', 0)
                ->whereHas('users', function ($users) use ($user) {
                    $users->where('user_id', $user);
                })
                ->where('headquarter_id', session('headquarter_id'))
                ->update(['enabled' => 1])
            ;

            // almacenar las rutas generadas
            $users = User::with(['schedulings' => function ($q) use ($date, $request, $user) {
                $q->with('crm')
                    ->where('headquarter_id', session('headquarter_id'))
                    ->whereHas('users', function ($users) use ($user) {
                        $users->where('user_id', $user);
                    })
                    ->whereBetween('start', [$date, Carbon::parse($request->input('init_date_submit'))->format('Y-m-d 23:59:59')])
                ;
            }])->where('id', $user)->get();

            $continue = $this->addMultipleToRoute($users, $date, $automation->id, $request);
            if (!$continue) {
                return response(['success' => false, 'message' => 'Error preparando el envío de la ruta: '.$this->msgError]);
            }

            DB::commit();

            return response(['success' => true, 'message' => 'Técnico despachado correctamente']);
        } catch (\Exception $ex) {
            $this->msgError = 'No se pudo guardar la automatización. <br><br> '.$ex->getMessage();

            return response(['success' => false, 'message' => $this->msgError, 'trace' => $ex->getTrace()]);
        }
    }

    public function getManualResumeStep(Request $request)
    {
        $date = Carbon::parse($request->input('init_date_submit'));
        $dateFront = new Date($date);
        $dateFront = ucwords($dateFront->format('d F, Y'));
        $users = User::with(['schedulings' => function ($q) use ($date) {
            $q->with('crm')
                ->whereBetween('start', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])
            ;
        }])->whereIn('id', $request->input('manual_users'))->get();

        $view = view('scheduling.forms.frames.resume_automation', compact(['users', 'dateFront']))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function saveAutomation(Request $request)
    {
        ini_set('max_execution_time', '300');

        $date = Carbon::parse($request->input('init_date_submit'))->format('Y-m-d 00:00:00');
        $automation = new Automation();
        $automation->date = $date;
        $automation->date_range = ['start' => Carbon::parse($request->input('start_date_submit'))->format('Y-m-d 00:00:00'), 'end' => Carbon::parse($request->input('start_date_submit'))->format('Y-m-d 23:59:59')];
        $automation->availabilities = $request->input('availabilities');
        $automation->users = $request->input('users');
        $automation->services = $request->input('services');
        $automation->services_config = $request->input('servicesFix');
        $automation->user_id = Auth::user()->id;
        $automation->headquarter_id = session('headquarter_id');

        try {
            DB::beginTransaction();

            $automation->save();

            // cambiar el estado de estos servicios en el cronograma a 'En Ruta'
            $schedulings = Scheduling::where('automation_init_date', $date)
                ->where('enabled', 0)
                ->whereNotIn('scheduling_type_id', [0])
                ->where('headquarter_id', session('headquarter_id'))
                ->get()
            ;

            foreach ($schedulings as $scheduling) {
                if ($scheduling->crm_schedule_id) {
                    $scheduling->crmSchedule()->update(
                        [
                            'status_id' => CrmScheduleStatus::where('slug', 'route')->get()->last()->id,
                            'date' => Carbon::parse($request->input('init_date_submit'))->format('Y-m-d'),
                        ]
                    );
                }
            }

            // cambiar el estado de los eventos
            Scheduling::where('automation_init_date', $date)
                ->where('enabled', 0)
                ->where('headquarter_id', session('headquarter_id'))
                ->update(['enabled' => 1])
            ;

            // almacenar las rutas generadas
            $users = User::with(['schedulings' => function ($q) use ($date, $request) {
                $q->with('crm')
                    ->where('headquarter_id', session('headquarter_id'))
                    ->whereBetween('start', [$date, Carbon::parse($request->input('init_date_submit'))->format('Y-m-d 23:59:59')])
                ;
            }])->whereIn('id', $request->input('users'))->get();

            $continue = $this->addMultipleToRoute($users, $date, $automation->id, $request);
            if (!$continue) {
                return response(['success' => false, 'message' => 'Error preparando el envío de la ruta: '.$this->msgError]);
            }

            DB::commit();

            return response(['success' => true, 'message' => 'automatización guardada correctamente']);
        } catch (\Exception $ex) {
            $this->msgError = 'No se pudo guardar la automatización. <br><br> '.$ex->getMessage();

            return response(['success' => false, 'message' => $this->msgError, 'trace' => $ex->getTrace()]);
        }
    }

    public function saveManualAutomation(Request $request)
    {
        ini_set('max_execution_time', '300');

        $date = Carbon::parse($request->input('init_date_submit'))->format('Y-m-d 00:00:00');
        $automation = new Automation();
        $automation->date = $date;
        $automation->date_range = ['start' => Carbon::parse($request->input('start_date_submit'))->format('Y-m-d 00:00:00'), 'end' => Carbon::parse($request->input('start_date_submit'))->format('Y-m-d 23:59:59')];
        $automation->availabilities = null;
        $automation->users = $request->input('manual_users');
        $automation->services = null;
        $automation->services_config = $request->input('servicesFix');
        $automation->user_id = Auth::user()->id;
        $automation->headquarter_id = session('headquarter_id');

        try {
            DB::beginTransaction();

            $automation->save();

            // cambiar el estado de estos servicios en el cronograma a 'En Ruta'
            $schedulings = Scheduling::where('automation_init_date', $date)
                ->where('enabled', 0)
                ->where('headquarter_id', session('headquarter_id'))
                ->whereNotIn('scheduling_type_id', [0])
                ->get()
            ;

            foreach ($schedulings as $scheduling) {
                if ($scheduling->crm_schedule_id) {
                    $scheduling->crmSchedule()->update(
                        [
                            'status_id' => CrmScheduleStatus::where('slug', 'route')->get()->last()->id,
                            'date' => Carbon::parse($request->input('init_date_submit'))->format('Y-m-d'),
                        ]
                    );
                }
            }

            // cambiar el estado de los eventos
            Scheduling::where('automation_init_date', $date)
                ->where('headquarter_id', session('headquarter_id'))
                ->where('enabled', 0)
                ->update(['enabled' => 1])
            ;

            // almacenar las rutas generadas
            $users = User::with(['schedulings' => function ($q) use ($date, $request) {
                $q->with('crm')
                    ->where('headquarter_id', session('headquarter_id'))
                    ->whereBetween('start', [$date, Carbon::parse($request->input('init_date_submit'))->format('Y-m-d 23:59:59')])
                ;
            }])->whereIn('id', $request->input('manual_users'))->get();

            $continue = $this->addMultipleToRoute($users, $date, $automation->id);
            if (!$continue) {
                return response(['success' => false, 'message' => 'Error preparando el envío de la ruta: '.$this->msgError, 'trace' => $this->traceError]);
            }

            DB::commit();

            return response(['success' => true, 'message' => 'automatización guardada correctamente']);
        } catch (\Exception $ex) {
            $this->msgError = 'No se pudo guardar la automatización. <br><br> '.$ex->getMessage();

            return response(['success' => false, 'message' => $this->msgError, 'trace' => $ex->getTrace()]);
        }
    }

    public function hardDeleleteScheduling(Request $request, $id)
    {
        $shceduling = Scheduling::find($id);
        if ($shceduling->crm_schedule_id) {
            // cambiar estado del servicio a sin programar
            CrmSchedule::find(Scheduling::find($id)->crm_schedule_id)->update(['status_id' => 1]);
        }
        $success = $shceduling->delete();

        return response(['success' => $success, 'message' => $success ? 'Evento eliminado correctamente' : 'Error al tartar de eliminar este evento']);
    }

    public function routes(Request $request)
    {
        $object = Route::with(['schedulings.routes', 'status'])
            ->withCount(['schedulings as schedulings_completed_count' => function ($q) {
                $q->where('completed', 1);
            }])
            ->headquarter()
            ->whereHas('schedulings')
            ->whereNotNull('automation_id')
            ->enabled()
            ->orderBy('date', 'desc');

        $term = '';
        if ($request->filled('search')) {
            $term = $request->input('search');
            $object->where(function ($w) use ($term) {
                $w->whereHas('user', function ($u) use ($term) {
                    $u->where('name', 'LIKE', "%{$term}%");
                });
            });
        }

        if ($request->filled('date')) {
            $term = $request->input('date');
            $object->whereBetween('date', [Carbon::parse($term)->startOfDay()->format('Y-m-d H:i:s'), Carbon::parse($term)->endOfDay()->format('Y-m-d H:i:s')]);
        }

        $routes = $object->paginate($request->filled('perpage') ? $request->input('perpage') : 10);

        if ($request->ajax()) {
            $list = view('scheduling.sub.list', compact('routes', 'term'))->render();

            return response(['success' => true, 'view' => $list]);
        }

        return view('scheduling.routes', compact('routes', 'term'));
    }

    public function execution(Request $request)
    {
        $user = $request->filled('user') ? User::find($request->input('user')) : '';
        $date = $request->filled('date') ? $request->input('date') : '';
        $date2 = $request->filled('date2') ? $request->input('date2') : '';

        return view('scheduling.execution', compact(['user', 'date', 'date2']));
    }

    public function hours(Request $request)
    {
        $user = $request->filled('user') ? User::find($request->input('user')) : '';
        $date = $request->filled('date') ? $request->input('date') : '';
        $date2 = $request->filled('date2') ? $request->input('date2') : '';

        return view('scheduling.hours', compact(['user', 'date', 'date2']));
    }

    public function routesList(Request $request)
    {
        $routes = Route::with('mailqueue', 'smsqueue', 'schedulings', 'status')
            ->withCount(['schedulings as schedulings_completed_count' => function ($q) {
                $q->where('completed', 1);
            }])
            ->headquarter()
            ->whereHas('schedulings')
            ->whereNotNull('automation_id')
            ->enabled()
            ->orderBy('id', 'desc')
            ->limit(65)
            ->paginate(10)
        ;

        $list = view('scheduling.sub.list', compact('routes'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function route(Request $request, $route)
    {
        $route = Route::with('schedulings.crm', 'schedulings.crmSchedule.service', 'schedulings.crmSchedule.controls.area', 'schedulings.crmSchedule.controls.infestation', 'schedulings.crmSchedule.controls.product', 'schedulings.crmSchedule.status')
            ->withCount(['schedulings as schedulings_completed_count' => function ($q) {
                $q->where('completed', 1);
            }])
            ->find($route);

        // dd($route->toArray());

        try {
            $filename = 'r-'.Carbon::parse($route->date)->format('dmY').'-'.$route->user->name;
            $path = 'routes/'.$filename.'.pdf';

            $file = "storage/{$path}";

           
            $issetFile = Storage::disk('public')->exists($path);
            if ($issetFile && !$request->filled('force')) {

                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="'.$file.'"');
                readfile($file);
    
            }else{
                $nroute = Route::with(
                    'schedulings.users',
                    'schedulings.crm',
                    'schedulings.crmSchedule.service',
                    'schedulings.crmSchedule.controls.infestation',
                    'schedulings.crmSchedule.controls.product',
                    'schedulings.crmSchedule.controls.area',
                    'schedulings.crmSchedule.controls.dose',
                    'schedulings.crmSchedule.controls.application'
                )->find($route->id);
    
                $subject = 'Reporte de ruta '.Date::parse($nroute->date)->format('l').'('.Carbon::parse($nroute->date)->format('d-m-Y').') - '.$nroute->user->name;
    
                // almacenar archivo en físico
                $view = view('scheduling.sub.routePdf', compact(['route', 'subject']))->render();
                $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');
    
                $filename = 'r-'.Carbon::parse($nroute->date)->format('dmY').'-'.$nroute->user->name;
                $path = 'routes/'.$filename.'.pdf';
    
                Storage::disk('public')->put($path, $pdf->output());
    
                $filename = 'r-'.Carbon::parse($route->date)->format('dmY').'-'.$route->user->name;
                $path = 'routes/'.$filename.'.pdf';
    
                $file = "storage/{$path}";
    
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="'.$file.'"');
                readfile($file);
            }

        } catch (\Throwable $th) {
            $nroute = Route::with(
                'schedulings.users',
                'schedulings.crm',
                'schedulings.crmSchedule.service',
                'schedulings.crmSchedule.controls.infestation',
                'schedulings.crmSchedule.controls.product',
                'schedulings.crmSchedule.controls.area',
                'schedulings.crmSchedule.controls.dose',
                'schedulings.crmSchedule.controls.application'
            )
                ->find($route->id)
            ;

            $subject = 'Reporte de ruta '.Date::parse($nroute->date)->format('l').'('.Carbon::parse($nroute->date)->format('d-m-Y').') - '.$nroute->user->name;

            // almacenar archivo en físico
            $view = view('scheduling.sub.routePdf', compact(['route', 'subject']))->render();
            $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');

            $filename = 'r-'.Carbon::parse($nroute->date)->format('dmY').'-'.$nroute->user->name;
            $path = 'routes/'.$filename.'.pdf';

            Storage::disk('public')->put($path, $pdf->output());

            $filename = 'r-'.Carbon::parse($route->date)->format('dmY').'-'.$route->user->name;
            $path = 'routes/'.$filename.'.pdf';

            $file = "storage/{$path}";

            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="'.$file.'"');
            readfile($file);
        }
    }

    public function editRouteForm(Request $request, $route)
    {
        $route = Route::with('schedulings.crm', 'schedulings.crmSchedule.service', 'schedulings.crmSchedule.controls.area', 'schedulings.crmSchedule.controls.infestation', 'schedulings.crmSchedule.controls.product', 'schedulings.crmSchedule.status')
            ->withCount(['schedulings as schedulings_completed_count' => function ($q) {
                $q->where('completed', 1);
            }])
            ->find($route)
        ;

        // preparar la informacón de los eventos como la necesita javascript
        // para mostrar la linea de tiempo
        $events = $this->prepareVisInfo($route);

        $list = view('scheduling.forms.routeEditForm', compact(['route']))->render();

        return response(['success' => true, 'view' => $list, 'events' => $events]);
    }

    public function sendForm(Request $request, $route)
    {
        $route = Route::with('schedulings.crm', 'schedulings.crmSchedule.service', 'schedulings.crmSchedule.controls.area', 'schedulings.crmSchedule.controls.infestation', 'schedulings.crmSchedule.controls.product', 'schedulings.crmSchedule.status')
            ->withCount(['schedulings as schedulings_completed_count' => function ($q) {
                $q->where('completed', 1);
            }])
            ->find($route)
        ;

        $hour = \Carbon\Carbon::now()->format('H');

        if ($hour < 12) {
            $greeting = 'Buen dia';
        }
        if ($hour >= 12 && $hour < 18) {
            $greeting = 'Buena tarde';
        }
        if ($hour >= 18) {
            $greeting = 'Buena noche';
        }
        $list = view('scheduling.forms.routeSendForm', compact(['route', 'greeting']))->render();

        return response(['success' => true, 'view' => $list, 'destinatary' => $route->user->email]);
    }

    public function routeSend(Request $request, $route)
    {
        $route = Route::with('mailqueue', 'smsqueue', 'schedulings.crm', 'schedulings.crmSchedule.service', 'schedulings.crmSchedule.controls.area', 'schedulings.crmSchedule.controls.infestation', 'schedulings.crmSchedule.controls.product', 'schedulings.crmSchedule.controls.dose', 'schedulings.crmSchedule.controls.application', 'schedulings.crmSchedule.status')
            ->withCount(['schedulings as schedulings_completed_count' => function ($q) {
                $q->where('completed', 1);
            }])
            ->find($route);

        $subject = 'Reporte de ruta '.Date::parse($route->date)->format('l').'('.Carbon::parse($route->date)->format('d/m/Y').') - '.$route->user->name;
        $filename = 'r-'.Carbon::parse($route->date)->format('dmY').'-'.$route->user->name;
        $path = 'routes/'.$filename.'.pdf';

        // dd($route->toArray());

        // verificar si el correo destinatario es del técnico
        if (in_array($route->user->email, $request->input('destinatary'))) {
            $route->update(['mail_queue' => null]);
        }

        $issetFile = Storage::disk('public')->exists($path);
            if (!$issetFile){
                $nroute = Route::with(
                    'schedulings.users',
                    'schedulings.crm',
                    'schedulings.crmSchedule.service',
                    'schedulings.crmSchedule.controls.infestation',
                    'schedulings.crmSchedule.controls.product',
                    'schedulings.crmSchedule.controls.area',
                    'schedulings.crmSchedule.controls.dose',
                    'schedulings.crmSchedule.controls.application'
                )->find($route->id);
    
                $subject = 'Reporte de ruta '.Date::parse($nroute->date)->format('l').'('.Carbon::parse($nroute->date)->format('d-m-Y').') - '.$nroute->user->name;
    
                // almacenar archivo en físico
                $view = view('scheduling.sub.routePdf', compact(['route', 'subject']))->render();
                $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');
    
                $filename = 'r-'.Carbon::parse($nroute->date)->format('dmY').'-'.$nroute->user->name;
                $path = 'routes/'.$filename.'.pdf';
    
                Storage::disk('public')->put($path, $pdf->output());
    
            }

        // mandar correo
        try {
            Mail::to($request->input('destinatary'))->send(new RouteSend($request->all(), $path, false));

            return response(['success' => true, 'message' => 'Ruta enviada correctamente']);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function deleteRoute(Request $request, Route $route)
    {
        $id = $request->input('id');

        try {
            DB::beginTransaction();
            // cambiar estado de los servicios del cronograma
            foreach ($route->schedulings as $scheduling) {
                if ($scheduling->crm_schedule_id) {
                    $scheduling->crmSchedule()->update(['status_id' => CrmScheduleStatus::where('slug', 'programed')->get()->last()->id]);
                }
            }

            // eliminar automatización (softdelete)
            if ($route->automation && 1 == Route::where('automation_id', $route->automation->id)->get()->count()) {
                $route->automation->delete();
            }

            // eliminar eventos de la agenda (softdelete)
            foreach ($route->schedulings as $scheduling) {
                // verificar si solo un usuario está vinculado al evento para desasociarlo o para eliminar todo
                if ($scheduling->users()->get()->count() > 1) {
                    $scheduling->users()->detach([$route->user_id]);
                } else {
                    $scheduling->users()->detach();
                    Scheduling::destroy($scheduling->id);
                }
            }

            Scheduling::whereBetween('start', [Carbon::parse($route->date)->format('Y-m-d 00:00:00'), Carbon::parse($route->date)->format('Y-m-d 23:59:59')])->where('scheduling_type_id', 0)->delete();

            // eliminar el correo en cola de envío (hardelete)
            if (!is_null($route->mailqueue)) {
                Job::find($route->mail_queue)->delete();
            }

            // eliminar el sms en cola de envío (hardelete)
            if (!is_null($route->smsqueue)) {
                Job::find($route->sms_queue)->delete();
            }

            // eliminar ruta (softdelete)
            Route::destroy($route->id);

            DB::commit();

            return response(['success' => true, 'message' => 'Ruta eliminada correctamente']);
        } catch (\Exception $ex) {
            DB::rollBack();

            return response(['success' => false, 'message' => 'Error eliminando la ruta: '.$ex->getMessage(), 'trace' => $ex->getTrace()]);
        }
    }

    public function routesExcecution(Request $request)
    {
        $route = null;
        $date = Carbon::parse($request->input('start'));
        $date2 = $request->filled('end') ? Carbon::parse($request->input('end')) : null;
        $user = $request->input('users', []);
        $customer = $request->input('customers', []);
        $date22 = (null != $date2) ? $date2->endOfDay()->format('Y-m-d') : $date->endOfDay()->format('Y-m-d');
        if (count($user) > 0) {
            $route = Route::whereIn('user_id', $user)->whereDate('date', '>=', $date->startOfDay()->format('Y-m-d'))->whereDate('date', '<=', $date22)->get();
        } else {
            $technical = User::select(['id'])->whereHas('roles', function ($roles) {
                $roles->where('slug', 'technical');
            })->where('headquarter_id', session('headquarter_id'))->get();
            $technicalIds = $technical->pluck('id')->toArray();
            if (count($technicalIds) > 0) {
                $route = Route::whereIn('user_id', $technicalIds)->whereDate('date', '>=', $date->startOfDay()->format('Y-m-d'))->whereDate('date', '<=', $date22)->get();
            }
        }
        $schedules = [];
        if ($route) {
            $scheduleObject = Scheduling::with(['crm', 'office', 'users', 'mechanismReports','signature', 'crmSchedule.userTrackings', 'crmSchedule.technicalReports', 'crmSchedule.answersSurveys',
            ])
                ->whereHas('routes', function ($r) use ($route) {
                    $r->whereIn('route_id', $route->pluck('id'));
                })
            ;
            if (count($customer) > 0) {
                $scheduleObject->whereHas('office', function ($office) use ($customer) {
                    $office->whereIn('crm_id', $customer);
                });
            }
            if ($request->filled('reviewed')) {
                $scheduleObject->where('reviewed', $request->input('reviewed'));
            }
            $schedules = $scheduleObject->orderBy('start', 'asc')->get();
        }

        $list = view('scheduling.sub.executionList', compact('schedules'))->render();

        return response(['success' => true, 'view' => $list, 'end_date' => $date22]);
    }

    public function settlementHours(Request $request)
    {
        $route = null;
        $date = Carbon::parse($request->input('start'));
        // $date2  =  $request->filled('end') ? Carbon::parse($request->input('end')) : null;
        $user = $request->input('users', []);
        // $date22 = ($date2 != null) ? $date2->endOfDay()->format('Y-m-d') :  $date->endOfDay()->format('Y-m-d');
        if (count($user) > 0) {
            $schedules = UserTracking::with(['user', 'trackable'])->where('trackable_type', 'App\CrmSchedule')->whereIn('user_id', $user)->whereIn('user_tracking_type', [3, 4])->whereDate('date', $date->startOfDay()->format('Y-m-d'))->orderBy('id', 'asc')->get();
        }

        $schedules = $schedules->groupBy('user_id');
        $list = view('scheduling.sub.hoursList', compact('schedules'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function markAsOk(Request $request, Scheduling $scheduling)
    {
        $scheduling->reviewed = !$scheduling->reviewed;
        $scheduling->reviewed_at = Carbon::now();
        $scheduling->reviewed_by = Auth::user()->id;
        $scheduling->save();

        return response(['success' => true, 'toggle' => $scheduling->reviewed]);
    }

    public function programmer(Request $request)
    {
        return view('scheduling.programmer')->render();
    }

    public function deleteEvent(Request $request)
    {
        $success = false;

        switch ($request->input('type')) {
            case 'App\CrmSchedule':
                $crmSchedule = CrmSchedule::find($request->input('id'));
                if ($crmSchedule->schedulings()->get()->count() > 0) {
                    $scheduling = $crmSchedule->schedulings()->get()->last();
                    if ($scheduling->routes()->get()->count() > 0) {
                        $scheduling->delete();
                        foreach ($scheduling->routes()->get() as $route) {
                            // eliminar el pdf existente
                            // $route->schedulings()->syncWithOutDetaching($scheduling->id);
                            $filename = 'r-'.Carbon::parse($route->date)->format('dmY').'-'.$route->user->name;
                            $path = 'routes/'.$filename.'.pdf';
                            $file = "storage/app/public/{$path}";
                            Storage::disk('public')->delete('file');
                            // actualizar el pdf del
                            $this->remakePdf($route);
                        }
                    } else {
                        $scheduling->delete();
                    }
                }
                $success = $crmSchedule->delete();

                break;

            case 'App\Scheduling':
                $scheduling = Scheduling::find($request->input('id'));
                if ($scheduling->routes()->get()->count() > 0) {
                    $success = $scheduling->delete();
                    foreach ($scheduling->routes()->get() as $route) {
                        // eliminar el pdf existente
                        // $route->schedulings()->syncWithOutDetaching($scheduling->id);
                        $filename = 'r-'.Carbon::parse($route->date)->format('dmY').'-'.$route->user->name;
                        $path = 'routes/'.$filename.'.pdf';
                        $file = "storage/app/public/{$path}";
                        Storage::disk('public')->delete('file');

                        // actualizar el pdf del técnico
                        $this->remakePdf($route);
                    }
                } else {
                    $success = $scheduling->delete();
                }

                break;

            default:
                $success = false;

                break;
        }

        return response(['success' => $success, 'message' => $success ? 'Evento eliminado correctamente' : 'No se ha eliminado ningun evento']);
    }

    public function deleteTracking(Request $request, CrmTracking $tracking)
    {
        $success = $tracking->delete();

        return response(['success' => $success, 'message' => $success ? 'Nota de seguimiento eliminada' : 'No se pudo eliminar la nota de seguimiento']);
    }

    public function programmerResources(Request $request)
    {
        $dateinit = date('Y-m-d', $request->input('start')) ?? Carbon::now();
        $date = Carbon::parse($dateinit);
        $tech = User::whereHas('roles', function ($roles) {
            $roles->where('slug', 'technical');
        })->where('headquarter_id', session('headquarter_id'))->get();
        $order = 0;

        try {

            $servicios = CrmSchedule::where('date', $date)->get();

            $extraData = Scheduling::with('users')->whereBetween('start', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])
            ->whereNotIn('scheduling_type_id', [0, 1])
            ->get();

            // obtener los técnicos programados por la automatización
            $resources = $tech->map(function ($v, $k) use ($order, $servicios, $extraData) {
                ++$order;

                $time = 0;
                foreach ($servicios as $key => $item) {

                    $isTech = collect($item->technicals)->pluck('id')->contains($v->id);

                    if($isTech){
                        $time += $item->duration / 3600;
                    }

                }

                foreach ($extraData as $key2 => $e) {

                    $isTech = collect($e->users)->pluck('id')->contains($v->id);

                    if($isTech){
                        $time += $e->duration / 60;
                    }

                }


                // $serviciosNigth = CrmSchedule::where(function ($query) use ($date) {
                //     $query->where('date', $date->format('Y-m-d'))
                //           ->orWhere('date', $date->subDay()->format('Y-m-d'));
                // })->where(function ($query) {
                //     $query->whereBetween('start_hour', ['00:00:00', '06:00:00'])
                //           ->orWhereBetween('start_hour', ['19:00:00', '23:59:59']);
                // })->get();




                return
                    [
                        'id' => "{$v->id}",
                        'title' => $v->name,
                        'avatar' => $v->full_avatar,
                        'order' => $k,
                        'tiempo' => $time,
                    ];
            })->values();
        } catch (\Exception $th) {
            abort('406', 'Al parecer hubo un problema consultando el programador, <br><br> '.$th->getMessage());
        }

        return response(['success' => count($resources) > 0 ? true : false, 'resources' => $resources->all(), 'date' => $date->format('Y-m-d')]);
    }

    public function programmerData(Request $request)
    {
        $tdate = date('Y-m-d H:i', $request->input('start'));
        $date = Carbon::parse($tdate);

        try {
            // obtner todos los eventos de cronograma para el dia de la consulta
            $data = CrmSchedule::with([
                    'service', 
                    'scheduling.users', 
                    'schedulings.users', 
                    'schedulings.routes', 
                    'office', 
                    'crm', 
                    'status',
                ])
                ->where('date', $date->format('Y-m-d'))
                ->whereHas('workschedule', function ($w) {
                    $w->withTrashed()->where('headquarter_id', session('headquarter_id'));
                })->where(function ($subwhere) {
                    $subwhere->whereNotNull('start_hour')->whereNotNull('technicals')->orWhereHas('schedulings');
                })
                ->get();

                //return $data;

            $data = $data->map(function ($schedule, $sk) use ($date) {
                $tmp = null;
                if (count($schedule->schedulings) == 0) {
                    
                    $time = Carbon::parse($schedule->date.' '.$schedule->start_hour);
                    $scheduling = $schedule->schedulings->whereBetween('start', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])->last();

                    if ($scheduling && $scheduling->users->count() > 0) {
                        $tmp[] = $scheduling->users->map(function ($user, $uk) use ($schedule, $scheduling) {
                            $time = Carbon::parse($scheduling->start);
                            $route = $scheduling->routes->where('user_id', $user->id)->first();

                            return [
                                'id' => $schedule->id,
                                'type' => 'App\CrmSchedule',
                                'resourceId' => "{$user->id}",
                                'title' => $schedule->service->name,
                                'customer' => $schedule->crm->name,
                                'office' => $schedule->office->name,
                                'crm_id' => $schedule->crm->id,
                                'office_id' => $schedule->office->id,
                                'user_id' => $user->id,
                                'observation' => $schedule->observation,
                                'status' => $schedule->status,
                                'note_programmer' => $schedule->note_programmer,
                                'icon' => isset($schedule->service) ? $schedule->service->avatar : '',
                                'backgroundColor' => $schedule->status->color,
                                'editable' => true,
                                'start' => $scheduling ? Carbon::parse($scheduling->start)->format('Y-m-d H:i:s') : $time->format('Y-m-d H:i:s'),
                                'end' => $scheduling ? Carbon::parse($scheduling->end)->format('Y-m-d H:i:s') : $time->copy()->addSeconds($schedule->duration)->format('Y-m-d H:i:s'),
                                'route' => $route ? $route->id : null,
                            ];
                        });
                    } else {
                        $tmp[] = collect($schedule->technicals)->map(function ($user_id, $uk) use ($schedule, $scheduling, $time) {
                            return [
                                'id' => $schedule->id,
                                'type' => 'App\CrmSchedule',
                                'resourceId' => "{$user_id->id}",
                                'title' => $schedule->service->name,
                                'customer' => $schedule->crm->name,
                                'crm_id' => $schedule->crm->id,
                                'office_id' => $schedule->office->id,
                                'office' => $schedule->office->name,
                                'user_id' => $user_id,
                                'observation' => $schedule->observation,
                                'status' => $schedule->status,
                                'note_programmer' => $schedule->note_programmer,
                                'icon' => isset($schedule->service) ? $schedule->service->avatar : '',
                                'backgroundColor' => $schedule->status->color,
                                'editable' => true,
                                'start' => $scheduling ? Carbon::parse($scheduling->start)->format('Y-m-d H:i:s') : $time->format('Y-m-d H:i:s'),
                                'end' => $scheduling ? Carbon::parse($scheduling->end)->format('Y-m-d H:i:s') : $time->copy()->addSeconds($schedule->duration)->format('Y-m-d H:i:s'),
                                'route' => null,
                            ];
                        });
                    }
                } else {
                    $tmp[] = $schedule->schedulings->last()->users->map(function ($user, $uk) use ($schedule) {
                        $time = Carbon::parse($schedule->scheduling->start);
                        $route = $schedule->scheduling->routes->where('user_id', $user->id)->first();

                        return [
                            'id' => $schedule->id,
                            'type' => 'App\CrmSchedule',
                            'resourceId' => "{$user->id}",
                            'title' => $schedule->service->name,
                            'customer' => $schedule->crm->name,
                            'office' => $schedule->office->name,
                            'crm_id' => $schedule->crm->id,
                            'office_id' => $schedule->office->id,
                            'user_id' => $user->id,
                            'observation' => $schedule->observation,
                            'status' => $schedule->status,
                            'note_programmer' => $schedule->note_programmer,
                            'icon' => isset($schedule->service) ? $schedule->service->avatar : '',
                            'backgroundColor' => $schedule->status->color,
                            'editable' => true,
                            'start' => $schedule->scheduling ? Carbon::parse($schedule->scheduling->start)->format('Y-m-d H:i:s') : $time->format('Y-m-d H:i:s'),
                            'end' => $schedule->scheduling ? Carbon::parse($schedule->scheduling->end)->format('Y-m-d H:i:s') : $time->copy()->addSeconds($schedule->duration)->format('Y-m-d H:i:s'),
                            'route' => $route ? $route->id : null,
                        ];
                    });
                }

                return $tmp;
            })->flatten(2);

            // anexar los eventos que no sean de cronograma
            $extraData = Scheduling::with(['users' => function ($users) {
                $users->where('headquarter_id', session('headquarter_id'));
            }, 'schedulingType', 'crmSchedule.service', 'routes', 'users'])
                ->whereBetween('start', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])
                ->where('headquarter_id', session('headquarter_id'))
                ->whereNotIn('scheduling_type_id', [0, 1])
                ->whereHas('users', function ($users) {
                    $users->where('headquarter_id', session('headquarter_id'))
                        ->whereHas('roles', function ($roles) {
                            $roles->where('slug', 'technical');
                        })
                    ;
                })
                ->get()->map(function ($schedule, $sk) use ($date) {
                    $tmp[] = $schedule->users->map(function ($user, $uk) use ($schedule, $date) {
                        $background = '';

                        switch ($schedule->completed) {
                            case '0':
                                if ('scheduling_type_id' != 1) {
                                    $background = $schedule->schedulingType->color;
                                } else {
                                    $background = '#795548';
                                }

                                break;

                            case '1':
                                $background = '#419644';

                                break;

                            case '2':
                                $background = '#f44336';

                                break;

                            case '3':
                                $background = '#FF9800';

                                break;
                        }

                        $route = $schedule->routes->where('user_id', $user->id)->first();

                        return [
                            'id' => $schedule->id,
                            'type' => 'App\Scheduling',
                            'second_type' => $schedule->schedulingType->name,
                            'resourceId' => "{$user->id}",
                            'title' => $schedule->name,
                            'icon' => isset($schedule->crmSchedule) ? $schedule->crmSchedule->service->avatar : '',
                            'backgroundColor' => $background,
                            'editable' => $schedule->automation_init_date == $date->format('Y-m-d 00:00:00') ? true : false,
                            'start' => Carbon::parse($schedule->start)->format('Y-m-d H:i:s'),
                            'end' => Carbon::parse($schedule->end)->format('Y-m-d H:i:s'),
                            'observation' => $schedule->description,
                            'user_id' => $user->id,
                            'route' => $route ? $route->id : null,
                        ];
                    });

                    return $tmp;
                })->flatten(2)
            ;

            $data = array_merge($data->toArray(), $extraData->toArray());
        } catch (\Exception $th) {
            abort('406', 'Al parecer hubo un problema consultando el programador, <br><br> '.$th->getMessage().' '.$th->getTraceAsString());
        }

        return response(['success' => count($data) > 0 ? true : false, 'data' => $data]);
    }

    public function addEventQuote(Request $request, $quote, $user, $notification)
    {
        // buscar cotización
        $quote = Quote::with(['crm', 'commercial', 'contact', 'office'])->where('status_id', 1)->find($quote);

        if ($quote) {
            $user = User::find($user);

            // crear una colección de laravel
            $scheduling = new Scheduling();

            $scheduling->name = "Recordatorio Cotización #{$quote->consecutive} {$quote->crm->name}";
            $scheduling->schedulingType = SchedulingType::find(3);
            $scheduling->users = [$user];
            $scheduling->crm = $quote->crm;
            $scheduling->office = $quote->office;
            $scheduling->contact = $quote->contact;
            $scheduling->description = 'Realizar Llamada al cliente';

            $view = view('scheduling.forms.schedulingForm', compact(['scheduling', 'notification']))->render();

            return view('scheduling.index')->withOpenForm('true')->withAction('create')->withView($view);
        }
        // eliminar la notificación
        Auth::User()->notifications->find($notification)->delete();
        abort('405', 'La cotización a la que hace referencia esta notificación ya no existe o cambió de a un estado que no es pendiente');
    }

    private function saveSchedulingService(Request $request, Scheduling $schedule)
    {
        if ($request->has('services')) {
            foreach ($request->all()['services'] as $key => $service) {
                $service = new Request($service);
                $schedulingService = null;

                if ($service->has('id')) {
                    $schedulingService = SchedulingService::find($service->input('id'));
                    $schedulingService->area = DetailService::find($service->input('detail_service_id'))->zone;
                    $schedulingService->area_id = $service->input('detail_service_id');
                    $schedulingService->price = $service->input('price');
                    $schedulingService->save();
                } else {
                    $schedulingService = $schedule->schedulingServices()->create([
                        'service_id' => $service->input('service_id'),
                        'area' => DetailService::find($service->input('detail_service_id'))->zone,
                        'price' => $service->input('price'),
                        'headquarter_id' => session('headquarter_id'),
                    ]);
                }

                // guardar las observaciones
                $this->saveSchedulingServiceObservation($service, $schedulingService);
                // guardar los insumos
                $this->saveSchedulingServiceSupply($service, $schedulingService);
                // guardar las tareas
                $this->saveSchedulingServiceTask($service, $schedulingService);
            }
        }
    }

    private function saveSchedulingServiceObservation(Request $service, SchedulingService $schedulingService)
    {
        if ($service->has('obs')) {
            foreach ($service->input('obs') as $key => $observation) {
                $observation = new Request($observation);
                $schedulingSerObs = null;
                if (!is_null($observation->input('role'))) {
                    if ($observation->has('id')) {
                        $schedulingSerObs = SchedulingSerObs::find($observation->input('id'));
                        $schedulingSerObs->content = $observation->input('observation');
                        $schedulingSerObs->user_id = Auth::user()->id;
                        $schedulingSerObs->role_id = $observation->input('role');
                        $schedulingSerObs->save();
                    } else {
                        $schedulingSerObs = $schedulingService->schedulingSerObs()->create([
                            'role_id' => $observation->input('role'),
                            'user_id' => Auth::user()->id,
                            'content' => $observation->input('observation'),
                        ]);
                        $this->saveFile($service, $schedulingSerObs, 'scheduling/observations', 'file', true, 'obs');
                    }
                }
            }
        }
    }

    private function saveSchedulingServiceSupply(Request $service, SchedulingService $schedulingService)
    {
        if ($service->has('supplies')) {
            foreach ($service->input('supplies') as $key => $supply) {
                $supply = new Request($supply);
                if ($supply->has('id')) {
                    $schedulingSerSup = SchedulingSerSup::find($supply->input('id'));
                    $schedulingSerSup->product_id = $supply->input('product');
                    $schedulingSerSup->quantity = $supply->input('quantity');
                    $schedulingSerSup->save();
                } else {
                    $schedulingService->schedulingSerSup()->create([
                        'product_id' => $supply->input('product'),
                        'quantity' => $supply->input('quantity'),
                    ]);
                }
            }
        }
    }

    private function saveSchedulingServiceTask(Request $service, SchedulingService $schedulingService)
    {
        if ($service->has('tasks')) {
            foreach ($service->input('tasks') as $key => $task) {
                $task = new Request($task);
                if (!is_null($task->input('description'))) {
                    if ($task->has('id')) {
                        $schedulingSerTas = SchedulingSerTas::find($task->input('id'));
                        $schedulingSerTas->description = $task->input('description');
                        $schedulingSerTas->save();
                    } else {
                        $schedulingService->schedulingSerTas()->create([
                            'description' => $task->input('description'),
                        ]);
                    }
                }
            }
        }
    }

    private function addToRoute($riginalroute = null, Scheduling $scheduling, $rawUsers = [])
    {
        $success = false;

        $users = empty($rawUsers) ? $scheduling->users : $scheduling->users->merge($rawUsers);

        foreach ($users as $user) {
            $route = Route::routeUser($user->id, $scheduling->start)->get()->last();

            if ($route) {
                // eliminar el pdf existente
                $route->schedulings()->syncWithOutDetaching($scheduling->id);
                $filename = 'r-'.Carbon::parse($route->date)->format('dmY').'-'.$route->user->name;
                $path = 'routes/'.$filename.'.pdf';
                $file = "storage/app/public/{$path}";
                Storage::disk('public')->delete('file');

                // volver a generar el PDF
                $this->remakePdf($route);

                $success = true;
            } else {
                // crear una nueva ruta
                $route = new Route();
                $route->user_id = $user->id;
                $route->date = $scheduling->start;
                $route->headquarter_id = session('headquarter_id');
                $route->automation_id = $riginalroute->automation_id;
                $route->status_id = 1;
                $route->save();
                $route->schedulings()->sync($scheduling->id);

                // generar el PDF
                $this->remakePdf($route);
                $success = true;
            }
        }

        return $success;
    }

    private function remakePdf($route)
    {
        // guardar el archivo pdf de la ruta para poder mandarlo en cola
        $nroute = Route::with(
            'schedulings.users',
            'schedulings.crm',
            'schedulings.crmSchedule.service',
            'schedulings.crmSchedule.controls.infestation',
            'schedulings.crmSchedule.controls.product',
            'schedulings.crmSchedule.controls.area',
            'schedulings.crmSchedule.controls.dose',
            'schedulings.crmSchedule.controls.application'
        )
            ->find($route->id)
        ;

        // almacenar archivo en físico
        $route = $nroute;
        $subject = 'Reporte de ruta '.Date::parse($nroute->date)->format('l').'('.Carbon::parse($nroute->date)->format('d-m-Y').') - '.$nroute->user->name;
        $view = view('scheduling.sub.routePdf', compact(['route', 'subject']))->render();
        $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');

        $nfilename = 'r-'.Carbon::parse($nroute->date)->format('dmY').'-'.$nroute->user->name;
        $npath = 'routes/'.$nfilename.'.pdf';

        Storage::disk('public')->put($npath, $pdf->output());
    }

    private function addMultipleToRoute($users, $date, $automationId, Request $request = null)
    {
        $observation = '';
        if (!is_null($request)) {
            // incluir la jornada laboral como observación del ruta
            if ($request->filled('include_journal_time')) {
                if ($request->filled('availabilities')) {
                    $obstemp = [];
                    foreach ($request->input('availabilities') as $availability) {
                        $obstemp[] = Availability::find($availability)->full_name;
                    }
                    $observation = implode('<br>', $obstemp);
                }
            }
        }

        $success = false;
        foreach ($users as $user) {
            $route = null;
            if ($user->schedulings->isNotEmpty()) {
                $route = Route::routeUser($user->id, $date)->get()->last();

                if ($route) {
                    $route->automation_id = $automationId;
                    $route->observation = $observation;
                    $route->observation_user_id = Auth()->user()->id;
                    $route->save();
                    $route->schedulings()->syncWithOutDetaching($user->schedulings->pluck('id'));
                    $success = true;
                } else {
                    // crear una nueva ruta
                    $route = new Route();
                    $route->automation_id = $automationId;
                    $route->user_id = $user->id;
                    $route->date = $date;
                    $route->status_id = 1;
                    $route->headquarter_id = session('headquarter_id');
                    $route->observation = $observation;
                    $route->observation_user_id = Auth()->user()->id;
                    $route->save();
                    $route->schedulings()->sync($user->schedulings->pluck('id'));
                    $success = true;
                }

                //  //alamcenar las horas de los Técnicos
                //  $totalHours = Session::get('totalTime');

                //  $userWorkingHour = $route->workingHours()->create([
                //      "user_id"   => $user->id,
                //      "ordinary"  => $totalHours["{$user->id}"]["ordinary"],
                //      "extra"  => $totalHours["{$user->id}"]["extra"],
                //      "total"  => $totalHours["{$user->id}"]["total"]
                //  ]);

                //  //asociar las disponibilidades para las horas del tecnico
                //  $bind = $totalHours["{$user->id}"]["availabilities"];
                //  $userWorkingHour->availabilities()->sync($bind);

                // guardar el archivo pdf de la ruta para poder mandarlo en cola

                $nroute = Route::with(
                    'schedulings.users',
                    'schedulings.crm',
                    'schedulings.crmSchedule.service',
                    'schedulings.crmSchedule.controls.infestation',
                    'schedulings.crmSchedule.controls.product',
                    'schedulings.crmSchedule.controls.area',
                    'schedulings.crmSchedule.controls.dose',
                    'schedulings.crmSchedule.controls.application'
                )
                    ->find($route->id)
                ;

                $subject = 'Reporte de ruta '.Date::parse($nroute->date)->format('l').'('.Carbon::parse($nroute->date)->format('d-m-Y').') - '.$nroute->user->name;

                // almacenar archivo en físico
                $view = view('scheduling.sub.routePdf', compact(['route', 'subject']))->render();
                $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');

                $filename = 'r-'.Carbon::parse($nroute->date)->format('dmY').'-'.$nroute->user->name;
                $path = 'routes/'.$filename.'.pdf';

                Storage::disk('public')->put($path, $pdf->output());
                // Storage::put($subject, $pdf->output());

                // almacenar envío de ruta en la cola de laravel
                // $success = $this->setRouteQueue($route->id);
                $success = true;
                if (!$success) {
                    return false;
                }
            }
        }

        return $success;
    }

    private function setRouteQueue($id)
    {
        $hour = \Carbon\Carbon::now()->format('H');
        if ($hour < 12) {
            $greeting = 'Buen dia';
        }
        if ($hour >= 12 && $hour < 18) {
            $greeting = 'Buena tarde';
        }
        if ($hour >= 18) {
            $greeting = 'Buena noche';
        }

        $route = Route::with('schedulings.crm', 'schedulings.crmSchedule.service', 'schedulings.crmSchedule.controls.areas', 'schedulings.crmSchedule.controls.infestation', 'schedulings.crmSchedule.controls.products.productable', 'schedulings.crmSchedule.status')
            ->withCount(['schedulings as schedulings_completed_count' => function ($q) {
                $q->where('completed', 1);
            }])
            ->find($id)
        ;

        $subject = 'Reporte de ruta '.Date::parse($route->date)->format('l').'('.Carbon::parse($route->date)->format('d/m/Y').') - '.$route->user->name;

        $body = new HtmlString("
    
            {$greeting} ".explode(' ', $route->user->name)[0].',

            Esperamos que te encuentres muy bien.

            Nos permitimos compartir tu ruta de prestación de servicio para el dia '.Date::parse($route->date)->format('l').' ('.Carbon::parse($route->date)->format('d/m/Y').').

            Quedamos muy atentos a cualquier duda o inquietud que puedas tener al respecto.');

        $pdf = PDF::loadView('scheduling.sub.routePdf', compact(['route', 'subject']))->setPaper('letter', 'portrait');

        // guardar el archivo pdf de la ruta para poder mandarlo en cola
        $filename = 'r-'.Carbon::parse($route->date)->format('dmY').'-'.$route->user->name;
        $path = 'routes/'.$filename.'.pdf';
        Storage::disk('public')->put($path, $pdf->output());
        Storage::put($subject, $pdf->output());

        // mandar correo a la cola
        try {
            $route->mail_queue = Mail::to('jcarmona@swebsas.com')->queue(new RouteSend(['subject' => $subject, 'body' => $body], $path, false));

            // mandar sms a la cola

            $serviceInfo = $route->schedulings->first()->name.' '.$route->schedulings->first()->crm_address.' a las '.Carbon::parse($route->schedulings->first()->start)->format('H:i: a');

            SendSmsRoute::dispatch([
                'sms' => 'Palmera Junior: Hemos generado tu ruta para el '.Date::parse($route->date)->format('l').' ('.Carbon::parse($route->date)->format('d/m/Y').'). Primer servicio: '.$serviceInfo.'. Mas detalles en tu correo.',
                'phone' => $route->user->phone,
            ]);

            $route->sms_queue = Job::all()->last()->id;
            $route->save();

            return true;
        } catch (\Exception $ex) {
            $this->msgError = $ex->getMessage();

            return false;
        }
    }

    private function removeToRoute(Scheduling $schedule)
    {
        $routes = Route::routeScheduling($schedule->id)->get();
        if ($routes) {
            foreach ($routes as $route) {
                $route->schedulings()->detach($schedule->id);
            }
        }
    }

    private function verifyWorkingHourType($availabilities, $start, $end, $type)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        foreach ($availabilities as $availability) {
            $availability['start'] = Carbon::parse($start->format('Y-m-d').' '.$availability['start'].':00');
            $availability['end'] = Carbon::parse($start->format('Y-m-d').' '.$availability['end'].':59')->subMinute();

            if ($start->between($availability['start'], $availability['end'])) {
                if ('extra' == $type && 0 == $availability['extra']) {
                    return number_format(($start->diffInSeconds($end) / 60) / 60, 1);
                }
                if ('ordinary' == $type && 1 == $availability['extra']) {
                    return number_format(($start->diffInSeconds($end) / 60) / 60, 1);
                }

                return 0;
            }
            if ('extra' == $type && 1 == $availability['extra']) {
                return number_format(($start->diffInSeconds($end) / 60) / 60, 1);
            }
        }

        return 0;
    }

    private function crossJoinDistances($possibleAssignment, $coords, $dd = false, $first = false)
    {
        $log = [];
        foreach ($possibleAssignment as $idx => $service) {
            if (empty($service['coords']['lat']) || empty($service['coords']['lng'])) {
                abort('406', "La sede {$service['office']} del cliente {$service['customer']} no tiene configurada una dirección validada con Google Maps para esta sede, Sin la información de coordenadas, el sistema de automatización no podrá calcular tramos de distancias cortas entre servicios y no podrá continuar con el proceso de automatización. <br><br> Ve a los datos del cliente y agrega o modifica un sucursal con una dirección válida.");
            }

            // se pone esta validacion para que primero se asignen los servicios con técnico fijo y se
            // respete el orden de asignacion
            if ($service['company']) {
                $distance = 0;
            } else {
                $distance = $this->getDistance($coords['lat'], $coords['lng'], $service['coords']['lat'], $service['coords']['lng']);
            }

            $service['distance'] = $distance;
            $service['time'] = '';
            $log[] = [
                'service_id' => $idx,
                'distance' => $distance, // se usa esta condicón para no calcular distancia del primer servicio
                'customer' => $service['customer'],
                'office' => $service['office'],
                'coords' => $service['coords'],
                'service' => $service,
            ];
        }

        if (count($log) > 0) {
            $log = collect($log);
            $log = $log->sortBy('distance')->first();

            $this->serviceOrder[$log['service_id']] = $log['service'];
            unset($possibleAssignment[$log['service_id']]);
            $this->crossJoinDistances($possibleAssignment, collect($log['coords']));
        }
    }

    private function assignServices($possibleAssignment, $services, $technicals, $request, $headquarterCoords)
    {
        $success = true;
        $asignations = collect([]);
        $coords = $headquarterCoords->toArray();
        $lastTechnical = null;
        $date = Carbon::parse($request->input('init_date_submit'))->format('Y-m-d');
        $lastDate = Carbon::parse($request->input('init_date_submit'))->format('Y-m-d');
        foreach ($possibleAssignment as $serviceId => $data) {
            // añadir 50 puntos si el servicio anterior fue del mismo técnico
            if (!is_null($lastTechnical) && isset($data['score'][$lastTechnical])) {
                $data['score'][$lastTechnical] += 50;
            }

            if (isset($data['score'])) {
                // obtener los datos del servicio
                $service = $services->find($serviceId);
                arsort($data['score']);

                /*intentar asignar a los posibles técnicos del servicio en orden
                acorde a su score en orden descendente*/

                foreach ($data['score'] as $techId => $score) {
                    if ($score > 0) {
                        if ($lastTechnical != $techId || $lastDate != $date) {
                            $coords = $headquarterCoords->toArray();
                        }

                        $workingHour = collect($data[$techId]['availabilities'])->first();
                        list($time, $timeEnd) = $this->getInitDates($request, $service, $workingHour);

                        $technical = $technicals->find($techId);

                        // verificar recursivamente y obtener la hora del servicio
                        list($start, $end, $firstService, $assigned, $drive) = $this->verifyAvailabilityDay($service, $data, $technical, $request, $coords);
                        if ($assigned) {
                            try {
                                
                                ++$this->countServices[$technical->id];
                                if(count($data['company']) > 0){
                       
                                    foreach ($data['company'] as $key => $company) {
                                        if($technical->id != $company){
                                            ++$this->countServices[$company];
                                        }
                                        
                                    }
                                }

                            } catch (\Throwable $th) {
                              
                                $this->countServices[$technical->id] = 1;
                                if(count($data['company']) > 0){
                                   
                                    foreach ($data['company'] as $key => $company) {
                                        if($technical->id != $company) {
                                            $this->countServices[$company] = 1;
                                        }
                                    }
                                }
                            }

                            $lastTechnical = $technical->id;
                            $coords['lat'] = $data['coords']['lat'];
                            $coords['lng'] = $data['coords']['lng'];
                            $lastDate = $start->format('Y-m-d');
                            $asignations->push(collect(['service' => $service, 'start' => $start, 'end' => $end, 'technical' => $technical, 'drive' => $drive]));
                            $success = $this->addShedulingEvent($asignations->last(), $request, $drive, $technical, 1 == $this->countServices[$technical->id] ? true : false, $data['company']);
                            if (!$success) {
                                return false;
                            }

                            break;
                        }
                    }
                }
            }
        }

        //die();

        return $success;
    }

    private function verifyAvailabilityDay($service, $data, $technical, $request, $lastCoords = null, $dd = false)
    {
        // obtener la primer hora de disponibilidad
        $workingHour = collect($data[$technical->id]['availabilities'])->first();
        list($time, $timeEnd) = $this->getInitDates($request, $service, $workingHour);
        Log::channel('scheduling')->info('Jornada '.$time.' - '.$timeEnd);
        $originaTime = $time->copy();

        // Obtener el tiempo que demora el traslado del servicio A al servicio B
        if (is_null($service->duration) || empty($service->duration) || $service->duration < 1) {
            abort('406', "El servicio {$service->service->name} (id: {$service->id}) para la sede {$service->office->name} del cliente {$service->crm->name} no tiene una duración establecida en el cronograma.");
        }

        if (!$service->start_hour) {
            $drive = $this->getDrivingDistance($data['coords']['lat'], $data['coords']['lng'], $lastCoords['lat'], $lastCoords['lng']);
        } else {
            $drive = 0;
        }

        $duration = $time->copy()->addMinutes(($service->duration + $drive) / 60);

        // si la posible fecha del servicio es mayor a la capacidad de la jornada
        // dejar pasar si hay una hora fija ($service->start_hour)
        if ($time->gte($timeEnd) || !$duration->between($time, $timeEnd) && !$service->start_hour && $workingHour['extra'] == 0) {
            $key = array_keys($data[$technical->id]['availabilities'], min($data[$technical->id]['availabilities']));
            unset($data[$technical->id]['availabilities'][$key[0]]);

            if (!$service->start_hour) {
                if (0 == count($data[$technical->id]['availabilities'])) {
                    Log::channel('scheduling')->info("0 -no se programó  el servicio de  {$time->format('Y-m-d h:i a')} - {$timeEnd->format('Y-m-d h:i a')} debido a que no hay una disponibilidad para el técnico");

                    return [null, null, false, false, null];
                }
                Log::channel('scheduling')->info("1 -la hora inicio del servicio {$time->format('Y-m-d h:i a')}- {$timeEnd->format('Y-m-d h:i a')} se desborda de la jornada");

                list($start, $end, $firstService, $assigned, $_drive) = $this->verifyAvailabilityDay($service, $data, $technical, $request, $lastCoords, true);

                return [$start, $end, $firstService, $assigned, $_drive];
            }
        }

        // verificar si el técnico tiene eventos la jornada seleccionada
        // dejar pasar si tiene una hora fija ($service->start_hour) o si es el técnico fijo del servicio ($service->tehnicals)
        $technicalShedulings = User::with(['schedulings' => function ($q) use ($time, $workingHour) {
            $q->whereBetween('start', [$time->format("Y-m-d {$workingHour['start']}:00"), $time->format("Y-m-d {$workingHour['end']}")])->headquarter();
        }])->find($technical->id);

        foreach ($technicalShedulings->schedulings as $schedule) {
            $scheduleStart = Carbon::parse($schedule->start);
            $scheduleEnd = Carbon::parse($schedule->end);
            if (!$scheduleStart->eq($scheduleEnd) && !$service->start_hour) {
                $fakeScheduleEnd = Carbon::parse($schedule->end)->subMinute();
                $fakeScheduleStart = Carbon::parse($schedule->start)->addMinute();

                if ($time->between($fakeScheduleStart, $fakeScheduleEnd)) {
                    // cambiar la disponibilidad del técnico y volver a validar
                    $this->changeTechnicalAvailability($technical, $data, $time, $scheduleEnd);
                    Log::channel('scheduling')->info("2 -la hora inicio del servicio {$time} se interpone con el evento {$schedule->name} de  {$scheduleStart->format('Y-m-d h:i a')} - {$scheduleEnd->format('Y-m-d h:i a')}");
                    list($start, $end, $firstService, $assigned, $_drive) = $this->verifyAvailabilityDay($service, $data, $technical, $request, $lastCoords, false);

                    return [$start, $end, $firstService, $assigned, $_drive];
                }

                if (!is_null($schedule->lat) && !is_null($schedule->lng)) {
                    $drive = $this->getDrivingDistance($schedule->lat, $schedule->lng, $data['coords']['lat'], $data['coords']['lng']);
                    $duration = $time->copy()->addMinutes(($service->duration + $drive) / 60);
                }

                if ($fakeScheduleStart->between($time, $duration)) {
                    // cambiar la disponibilidad del técnico y volver a validar
                    $this->changeTechnicalAvailability($technical, $data, $time, $scheduleEnd);
                    Log::channel('scheduling')->info("3 - el servicio de  {$time->format('Y-m-d h:i a')} - {$duration->format('Y-m-d h:i a')}  se interpone con el evento {$schedule->name} a las {$scheduleStart->format('Y-m-d h:i a')}");
                    list($start, $end, $firstService, $assigned, $_drive) = $this->verifyAvailabilityDay($service, $data, $technical, $request, $lastCoords, false);

                    return [$start, $end, $firstService, $assigned, $_drive];
                }
            }
        }
        // si no hay nada programado para el técnico en la jornada, se procede a asignar el servicio
        $this->availabilityLog[] = "El servicio {$service->name} {$service->office->name} para el cliente {$service->crm->name} se programó al técnico {$technical->name} de ".$time->format('H:i').' a '.$duration->format('H:i');

        return [$time, $duration, $originaTime == $time ? true : false, true, $drive];
    }

    private function getInitDates($request, $service, $workingHour)
    {
        $time = null;
        $timeEnd = null;

        $fixedDate = null;
        $fixedTime = $service->start_hour;

        if (!is_null($fixedDate) && !is_null($fixedTime)) {
            $time = Carbon::createFromFormat('Y-m-d H:i', $fixedDate.' '.$fixedTime);
            $timeEnd = Carbon::parse($fixedDate.' '.$workingHour['end']);
        } elseif (!is_null($fixedDate) && is_null($fixedTime)) {
            $time = Carbon::parse($fixedDate.' '.$workingHour['start']);
            $timeEnd = Carbon::parse($fixedDate.' '.$workingHour['end']);
        } elseif (is_null($fixedDate) && !is_null($fixedTime)) {
            $time = Carbon::createFromFormat('Y-m-d H:i', $request->input('init_date_submit').' '.$fixedTime);
            $timeEnd = Carbon::parse($request->input('init_date_submit').$workingHour['end']);
        } else {
            $time = Carbon::parse($request->input('init_date_submit').$workingHour['start']);
            $timeEnd = Carbon::parse($request->input('init_date_submit').$workingHour['end']);
        }

        return [$time, $timeEnd];
    }

    private function changeTechnicalAvailability($technical, &$data, Carbon $time, Carbon $scheduleEnd)
    {
        $temp = collect($data[$technical->id]['availabilities'])->first();
        $key = array_search($temp, $data[$technical->id]['availabilities']);

        $diff = $time->diffInSeconds($scheduleEnd);
        $time->addMinutes($diff / 60);
        $data[$technical->id]['availabilities'][$key]['start'] = $time->format('H:i');
    }

    private function joinaAvailabilitiesTime($technical, $request)
    {
        $collect = collect([]);
        foreach ($request->input('availabilities') as $availability) {
            $collect->push($availability);
        }
        $collect = $collect->merge($technical->availabilities->pluck('id'));
        $collect = $collect->duplicates();

        // verificar que la disponibilidad tenga cobertura para el dia de semana
        $dateInit = Carbon::parse($request->input('init_date_submit').' 00:00:00');

        foreach ($collect as $cvalue) {
            if (!$this->verifyDayOfWeek($request, $cvalue, $dateInit)) {
                $collect = $collect->filter(function ($item) use ($cvalue) {
                    return $item != $cvalue;
                });
            }
        }

        if (0 == $collect->count()) {
            abort(406, 'Las disponibilidades técnicas seleccionadas en el paso 1, no tienen cobertura establecida para el día '.$dateInit->dayName.' '.$dateInit->format('d').'. Seleccione otra disponibilidad o configure las disponibilidades existentes para incluir el día '.$dateInit->dayName.' como parte de la jornada.');
        }

        $availabilities = WorkingHour::select('start', 'end','extra')->whereIn('availability_id', $collect)->orderBy('start', 'ASC')->get();
        if (0 == $availabilities->count()) {
            abort(406, 'Las disponibilidades seleccionadas para esta automatización no poseen jornadas laborales configuradas. Seleccione disponibiliddes diferentes o ve a la configuración de los horarios de los técnicos para corregir esto.');
        }

        return $availabilities->toArray();
    }

    private function verifyDayOfWeek($request, $availability, Carbon $dateInit)
    {
        // verificar que el día de la ruta este cubierta por alguna disponibilidad
        $availabilities = Availability::with(['businessDays' => function ($q) use ($dateInit) {
            $q->wherePivot('business_day_id', $this->parseNumberDate($dateInit->dayOfWeek));
        }])
            ->whereHas('businessDays', function ($q) use ($dateInit) {
                $q->where('business_day_id', $this->parseNumberDate($dateInit->dayOfWeek));
            })->where('id', $availability)->get()
        ;

        return $availabilities->count() > 0 ? true : false;
    }

    private function parseNumberDate($numberDay)
    {
        if ($numberDay <= 0) {
            $numberDay = 7;
        }

        return $numberDay;
    }

    private function hasAvailabilityAgendaForFixed(Request $request, $service, $technical)
    {
        $fixedDate = null;
        $fixedTime = $service->start_hour;

        // verificar que el técnico sea el asignado
        if ($service->technicals) {
            if (!$service->technicals->pluck('id')->contains($technical->id)) {
                return false;
            }
        }

        if (($fixedDate && $fixedTime) || $fixedTime) {
            foreach ($technical->schedulings as $scheduling) {
                $schedulingStart = Carbon::parse($scheduling->start);
                $schedulingEnd = Carbon::parse($scheduling->end);

                $automationStart = Carbon::createFromFormat('Y-m-d h:i a', ($fixedDate ?? $request->input('init_date_submit')).' '.$fixedTime);
                $automationEnd = $automationStart->copy()->addMinutes($service->duration / 60);

                /*verificar el servicio a programar se interpone
                    en medio de algún evento ya programado*/
                if ($automationStart->between($schedulingStart, $schedulingEnd)) {
                    return false;
                }

                /*verificar el servicio a programar tiene una duración que
                sobrepasa un evento programado posterior*/
                if ($schedulingStart->between($automationStart, $automationEnd)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function getCoverage($service, $zones)
    {
        $var = [];
        $varout = [];
        foreach ($zones as $zone) {
            $result = $this->getDistance($service->office->lat, $service->office->lng, $zone->lat, $zone->lng);
            if ($result <= $zone->radius) {
                $var["{$zone->id}"] = $result;
            } else {
                $varout["{$zone->id}"] = $result;
            }
        }

        try {
            $rvar = min($var);
        } catch (\Exception $ex) {
            $rvar = $var;
        }

        try {
            $rvarout = min($varout);
        } catch (\Exception $ex) {
            $rvarout = $varout;
        }

        return [array_search($rvar, $var), array_search($rvarout, $varout)];
    }

    private function getDistance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));

        return $earthRadius * $c;
    }

    private function getDrivingDistance($lat1, $long1, $lat2, $long2)
    {
        /*se utiliza el sleep de 1 segundos cada 500 peticiones simultaneas
        para no violar el límite de peticiones por segundo de Google Maps API (error OVER_QUERY_LIMIT [1000 queries per second])*/
        if (500 == $this->countRequestGmap) {
            sleep(1);
            $this->countRequestGmap = 0;
        }

        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?key='.config('google.maps.api_key')."&origins={$lat1},{$long1}&destinations={$lat2},{$long2}&mode=driving&language=es-CO";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);

        ++$this->countRequestGmap;

        try {
            if (!in_array($response_a['status'], ['OK'])) {
                abort('406', "El Api de Google Maps ha rechazado la verificación de distancia entre servicios. Se recibe el código de estado <a href='https://developers.google.com/maps/documentation/distance-matrix/intro#DistanceMatrixResponses' target='_blank' title='{$response_a['status']}' ><b><i>{$response_a['status']}</i></b></a>. Comunícate con soporte técnico del sistema para evaluar el código de respuesta y apoyar el proceso para solventar el inconveniente.");
            }

            if (!in_array($response_a['rows'][0]['elements'][0]['status'], ['ZERO_RESULTS', 'NOT_FOUND', 'MAX_ROUTE_LENGTH_EXCEEDED'])) {
                $time = $response_a['rows'][0]['elements'][0]['duration']['value'];

                return $time + 600; // se añaden 10 minutos mas de lo que calcula google como holgura ante incidentes en la vía
            }
            // por defecto 15 minutos
            return 900;
        } catch (\Exception $th) {
            abort('406', 'El Api de Google Maps no responde o se ha perdido la conexion a internet: '.$th->getMessage());
        }
    }

    private function deleteAnotherShedulings($date)
    {
        $schedulings = Scheduling::where('automation_init_date', $date->format('Y-m-d 00:00:00'))
            ->where('enabled', 0)
            ->where('headquarter_id', session('headquarter_id'))
            ->whereIn('scheduling_type_id', [0, 1])
        ;

        foreach ($schedulings->get() as $schedule) {
            // eliminar relacion de usuarios
            $schedule->users()->detach();
            $schedule->forceDelete();
        }
    }

    private function addShedulingEvent($asignation, $request, $drive, $technical, $firstService = false, $company)
    {
        $service = $asignation['service'];

        // crear el evento de tipo traslado
        $driveTime = $asignation['start']->copy();
        if ($firstService) {
            $driveTimeEnd = $driveTime;
        } else {
            $driveTimeEnd = $driveTime->copy()->addMinutes($drive / 60);
        }

        // no crea un evento de traslado de ruta si el siguiente servicio
        // es para la misma dirección

        $ok = true;
        $sok = true;
        if (!$driveTime->eq($driveTimeEnd)) {
            $ok = $this->addEvent($request, 'Traslado de Ruta hacia '. $service->crm->name, '', 0, $driveTime, $driveTimeEnd, $technical);
        }

        if ($ok) {
            $description = '';
            // buscar notas internas del servicio para poner en la description
            $intNotes = CrmScheduleObservation::where('crm_schedule_id', $service->id)->where('private', 0)->orderBy('created_at', 'desc')->get();
            if (!is_null($intNotes) && count($intNotes) > 0) {
                $description = '[NOTAS INTERNAS]: ';
                foreach ($intNotes as $note) {
                    $description .= $note->observation;
                    $description .= $note->feedback ? ' [FED]: '.$note->feedback.' // ' : ' // ';
                }
            }
            if (!is_null($intNotes) && $service->observation && count($intNotes) > 0) {
                $description .= '&#13;&#10;&#13;&#10;';
            }

            if ($service->observation) {
                $description .= "[NOTA DEL CRONOGRAMA]: {$service->observation}";
            }

            $sok = $this->addEvent($request, "{$service->service->name} {$service->crm->name} en sucursal {$service->office->name}", $description, 1, $driveTimeEnd, $asignation['end'], $technical, $service->crm, $service->office, null, $service, $company);
        } else {
            abort('406', 'No se pudo guardar la asignación de tiempos de recorridos.');
        }

        return $sok;
    }

    private function addEvent($request, $name, $description = '', $type = 1, Carbon $start, Carbon $end, $technical = null, $crm = null, $office = null, $contact = null, $service = null, $company = null)
    {
        // validar si el evento ya fue creado y omitir (despachos de rutas)
        $anotherScheduling = Scheduling::where('crm_schedule_id', $service ? $service->id : null)
            ->where('automation_init_date', Carbon::parse($request->input('init_date_submit'))->format('Y-m-d 00:00:00'))
            ->where('enabled', 1)
            ->where('scheduling_type_id', $type)
            ->get()
            ->count()
        ;

        if (0 == $anotherScheduling) {
            $scheduling = new Scheduling();
            $scheduling->name = $name;
            $scheduling->description = $description;
            $scheduling->scheduling_type_id = $type;
            $scheduling->start = $start->format('Y-m-d H:i:s');
            $scheduling->end = $end->format('Y-m-d H:i:s');
            $scheduling->automation_init_date = Carbon::parse($request->input('init_date_submit'))->format('Y-m-d 00:00:00');
            $scheduling->crm_schedule_id = $service ? $service->id : null;
            $scheduling->headquarter_id = session('headquarter_id');
            $scheduling->enabled = 0;
            $scheduling->completed = 0;

            if (!is_null($crm)) {
                $scheduling->crm_id = $crm->id;
            }

            if (!is_null($office)) {
                $scheduling->office_id = $office->id;
                $scheduling->crm_address = $office->address.', '.$office->city->name;
                $scheduling->crm_lat = $office->lat;
                $scheduling->crm_lng = $office->lng;
            }

            if (isset($office->schedulecontact)) {
                $scheduling->contact_id = $office->schedulecontact->id;
            }

            try {
                DB::beginTransaction();
                $scheduling->save();

                // asociar al técnico
                $scheduling->users()->sync([$technical->id]);

                if (!is_null($company) && count($company) > 0) {
                    // asociar técnicos acompañantes
                    $scheduling->users()->sync($company);
                }

                DB::commit();

                return true;
            } catch (\Exception $exc) {
                DB::rollback();
                abort('406', 'No se pudo guardar un evento de servicio en el calendario, inténtalo nuevamente. <br><br>'.$exc->getMessage());

                return false;
            }
        } else {
            return true;
        }
    }

    private function getTimes($user, $schedulings, $sothers, $_availabilities)
    {
        // obtener los inicios de las jornadas seleccionadas en la automatizacion
        $availabilities = collect([]);
        $capacity = 0;
        $workingHours = WorkingHour::select('start', 'end', 'extra')->whereIn('availability_id', $_availabilities)->get();
        foreach ($workingHours as $workingHour) {
            $start = Carbon::parse(date('Y-m-d').' '.$workingHour->start);
            $end = Carbon::parse(date('Y-m-d').' '.$workingHour->end);

            $capacity += number_format(($start->diffInSeconds($end) / 60) / 60, 1);

            $availabilities->push($workingHour->toArray());
        }

        foreach ($schedulings as $key => $schedule) {
            $ordinary = $this->verifyWorkingHourType($availabilities, $schedule->start, $schedule->end, 'ordinary');
            $extra = $this->verifyWorkingHourType($availabilities, $schedule->start, $schedule->end, 'extra');

            $userTimes['id'] = $user;

            try {
                $userTimes['ordinary'] += $ordinary;
            } catch (\Exception $th) {
                $userTimes['ordinary'] = $ordinary;
            }

            try {
                $userTimes['extra'] += $extra;
            } catch (\Exception $th) {
                $userTimes['extra'] = $extra;
            }

            try {
                $userTimes['total'] += ($ordinary + $extra);
            } catch (\Exception $th) {
                $userTimes['total'] = ($ordinary + $extra);
            }

            try {
                $userTimes['capacity'] = $capacity;
            } catch (\Exception $th) {
                $userTimes['capacity'] = 0;
            }
        }

        $userTimes['other_events'] = 0;
        foreach ($sothers as $sother) {
            $other = $this->verifyWorkingHourType($availabilities, $sother->start, $sother->end, 'ordinary');

            try {
                $userTimes['other_events'] += $other;
            } catch (\Exception $th) {
                $userTimes['other_events'] = $other;
            }
        }

        return $userTimes;
    }

    private function prepareVisInfo($route)
    {
        // preparar la informacón de los eventos como la necesita javascript
        // para mostrar la linea de tiempo
        $schedulings = Scheduling::whereHas('users', function ($users) use ($route) {
            $users->where('user_id', $route->user_id);
        })->whereBetween('start', [Carbon::parse($route->date)->format('Y-m-d 00:00:00'), Carbon::parse($route->date)->format('Y-m-d 23:59:59')])->get();

        $events = [];

        if (!is_null($schedulings)) {
            foreach ($schedulings as $item) {
                array_push($events, [
                    'id' => $item->id,
                    'content' => $item->name.(!is_null($item->office) ? ' - '.$item->office->name : ''),
                    'start' => $item->start,
                    'end' => $item->end,
                    'style' => "color: #fff; background-color: {$item->schedulingType->color};",
                ]);
            }
        }

        return [
            'start' => Carbon::parse($route->date)->format('Y-m-d 00:00:00'),
            'end' => Carbon::parse($route->date)->format('Y-m-d 29:59:59'),
            'data' => $events,
        ];
    }
}
