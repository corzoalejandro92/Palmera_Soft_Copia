<?php

namespace App\Http\Controllers\Administrative;

use App\Application;
use App\AreaOffice;
use App\CategoryTicket;
use App\City;
use App\Contact;
use App\ContactType;
use App\Contract;
use App\Country;
use App\Crm;
use App\CrmArea;
use App\CrmExternalNote;
use App\CrmSchedule;
use App\CrmScheduleObservation;
use App\CrmScheduleStatus;
use App\CrmTracking;
use App\CrmTrackingTypes;
use App\CrmType;
use App\DetailService;
use App\Dose;
use App\File;
use App\FileExtend;
use App\FileType;
use App\FoxconDocument;
use App\General\Contrast;
use App\General\CrmSchedules;
use App\General\Warehouses;
use App\Headquarter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCrmSimpleRequest;
use App\Infestation;
use App\Inventory;
use App\Mail\CrmAccess;
use App\Mail\CrmSchedule as MailCrmSchedule;
use App\MeanContact;
use App\Notification;
use App\Notifications\AnswerTicket;
use App\Notifications\NewTicket;
use App\Office;
use App\Periodicity;
use App\PriorityTicket;
use App\Product;
use App\Qr;
use App\Quote;
use App\Route;
use App\Scheduling;
use App\Service;
use App\Services\InventoryService;
use App\State;
use App\TechnicalReport;
use App\TicketQuestion;
use App\TimeUnit;
use App\TransferInventory;
use App\User;
use App\Warehouse;
use App\Workschedule;
use App\Zone;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;

class CrmController extends Controller
{
    public $traceError = '';
    public $msgError = 'No se pudo realizar la acción solicitada';
    public $msgSuccess = 'Cliente agregado correctamente';
    public $msgSuccessContact = 'Contacto agregado correctamente';
    public $msgSuccessUser = 'Usuario asignado corectamente';
    public $msgSuccessOffice = 'Sucursal agregada correctamente';
    public $msgSuccessFile = 'Archivo agregado correctamente';
    public $msgSuccessTechnical = 'Parámetro agregado correctamente';
    public $msgSuccessEvent = 'Evento agregado correctamente';
    public $msgSuccessTicket = 'Ticket agregado correctamente';
    public $msgUpdated = 'Cliente actualizado satisfactoriamente';
    public $msgUpdatedContact = 'Contacto actualizado correctamente';
    public $msgUpdatedOffice = 'Sucursal actualizada correctamente';
    public $msgUpdatedFile = 'Archivo actualizado correctamente';
    public $msgUpdatedTechnical = 'Parámetro actualizado correctamente';
    public $msgUpdatedEvent = 'Evento actualizado correctamente';
    public $msgUpdatedConfigBilling = 'Configuración actualizada correctamente';
    public $msgSuccessCategoryCrm = 'Categoria CRM actualizada corectamente';
    public $msgDeleted = 'Cliente eliminado satisfactoriamente';
    public $msgDeletedContact = 'Contacto eliminado satisfactoriamente';
    public $msgDeletedOffice = 'Sucursal eliminada satisfactoriamente';
    public $msgDeletedUser = 'Asignación eliminada satisfactoriamente';
    public $msgDeletedFile = 'Archivo eliminado satisfactoriamente';
    public $msgDeletedTechnical = 'Parámetro eliminado satisfactoriamente';
    private $crmFileTypeSlug = 'crm';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('crm.index');
    }

    public function list(Request $request)
    {
        $object = Crm::with(['crmCategory', 'user', 'meanContact', 'offices' => function ($offices) {
            $offices->with('workschedules')->headquarter();
        }])
            ->headquarterOffice()
            ->orderBy('id', 'desc');

        $term = '';
        if ($request->filled('search')) {
            $term = $request->input('search');
            $object->where('name', 'LIKE', "%{$term}%");
            $object->orwhere('commercial_name', 'LIKE', "%{$term}%");
            $object->orwhere('document', 'LIKE', "%{$term}%");

            $object->orWhere(function ($w) use ($term) {
                $w->whereHas('offices', function ($u) use ($term) {
                    $u->where('name', 'LIKE', "%{$term}%");
                });
            });
            $object->orWhere(function ($w) use ($term) {
                $w->whereHas('user', function ($u) use ($term) {
                    $u->where('name', 'LIKE', "%{$term}%");
                });
            });
        }
        $object->headquarterOffice();
        $crms = $object->paginate($request->filled('perpage') ? $request->input('perpage') : 25);

        if ($request->ajax()) {
            $list = view('crm.sub.list', compact('crms'))->render();

            return response(['success' => true, 'view' => $list]);
        }

        return view('crm.index', compact('crms'));
    }

    public function formCrm(Request $request)
    {
        $data_click = 'save';
        $openForm = 'customer_create';
        $id = $request->input('id');

        $form = view('crm.forms.customerForm', compact(['data_click', 'openForm']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function infoForm(Request $request)
    {
        $data_click = 'save';
        $openForm = 'customer_create';
        $crmId = DB::table('crms')->max('id') + 1;
        $id = $request->input('id');
        $crm = Crm::find($id);

        $form = view('crm.forms.infoForm', compact(['crm', 'data_click', 'openForm', 'crmId']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function typeCrm(Request $request)
    {
        $query = CrmType::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $crmtype = $query->get();

        return response(['results' => $crmtype]);
    }

    public function officeCustomer(Request $request)
    {
        $officesCustomer = [];
        // verificar si el usuario actual es cliente
        if (Auth::user()->roles->pluck('slug')->contains('customer')) {
            // obtener la iformacion de sucursales vinculadas al usuario
            $contact = Contact::where('user_id', Auth::User()->id)->where('crm_id', session('crm_id'))->get();
            if ($contact->count() > 0) {
                $offices = $contact->last()->offices()->where('crm_id', session('crm_id'))->get();

                $officeId = $offices->pluck('id')->values()->toArray();
                if (!is_null($offices)) {
                    $query = Office::where(function ($w) use ($request, $officeId) {
                        $w->whereIn('id', $officeId)
                            ->orWhere(function ($w) use ($request, $officeId) {
                                if (0 == count($officeId)) {
                                    $w->where('crm_id', $request->input('id'))->headquarter(true);
                                }
                            });
                    });

                    if ($request->filled('term')) {
                        $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
                    }
                    $officesCustomer = $query->get();
                }
            } else {
                $crm = Crm::find(session('crm_id'));
                $officesCustomer = optional($crm)->offices()->get();
            }
        } else {
            $id = $request->input('id');
            $query = Office::where('crm_id', $id)
                ->headquarter(true);
            if ($request->input('term')) {
                $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
            }
            $officesCustomer = $query->get();
        }

        return response(['results' => $officesCustomer]);
    }

    public function meanContact(Request $request)
    {
        $query = MeanContact::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $meanContact = $query->get();

        return response(['results' => $meanContact]);
    }

    public function typeFileCrm(Request $request)
    {
        $query = FileType::select(['id', 'name as text'])->where('slug', ['crm']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $typeFileCrm = $query->get();

        return response(['results' => $typeFileCrm]);
    }

    public function contactForm(Request $request)
    {
        $data_click = 'save';
        $id = $request->input('id');
        $contact = Contact::with('types')->find($id);
        $form = view('crm.forms.contactForm', compact('contact', 'data_click'))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function typeContact(Request $request)
    {
        $query = ContactType::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $typeContact = $query->get();

        return response(['results' => $typeContact]);
    }

    public function offices(Request $request)
    {
        if (Crm::find($request->input('id'))->customer_national) {
            $id = $request->input('id');
            $query = Office::where('crm_id', $id);
            if ($request->input('term')) {
                $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
            }
            $typeSeat = $query->get();

            return response(['results' => $typeSeat]);
        }

        $id = $request->input('id');
        $query = Office::where('crm_id', $id)
            ->headquarter(true);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $typeSeat = $query->get();

        return response(['results' => $typeSeat]);
    }

    public function mapFrame(Request $request)
    {
        $address = $request->input('address');
        $city = $request->input('city');

        $view = view('crm/forms/frames/map', compact(['address', 'city']))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function cities(Request $request)
    {
        // obtener el departamento de la sede de palmera actual
        $stateId = Headquarter::with('city.state')->find(session('headquarter_id'))->city->state->id;

        $id = $request->input('id');
        $query = City::with('state')->whereHas('states', function ($states) {
            $states->whereHas('country', function ($country) {
                $country->where('code', 'CO');
            });
        })->limit(50);

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->orderBy('name', 'ASC')->get();

        return response(['results' => $data]);
    }

    public function countryOffice(Request $request)
    {
        $query = Country::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $countryOffice = $query->limit(100)->get();

        return response(['results' => $countryOffice]);
    }

    public function statesOffice(Request $request, $country)
    {
        $query = State::select(['id', 'name as text'])
            ->where('country_id', $country);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $statesOffice = $query->limit(100)->get();

        return response(['results' => $statesOffice]);
    }

    public function cityOffice(Request $request, $city)
    {
        $query = City::select(['id', 'name as text'])
            ->where('state_id', $city);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $cityOffice = $query->limit(100)->get();

        return response(['results' => $cityOffice]);
    }

    public function headquarters(Request $request)
    {
        $query = Headquarter::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $headquarters = $query->get();

        return response(['results' => $headquarters]);
    }

    public function users(Request $request)
    {
        $query = User::with('headquarter')->select(['*'])->whereHas('roles', function ($roles) use ($request) {
            if ($request->filled('roles') && count($request->input('roles')) > 0) {
                $roles->whereIn('slug', $request->input('roles'));
            } else {
                $roles->where('slug', '!=', 'customer');
            }
        })->enabled()->limit(10);

        // if(!$request->filled('restrictHeadquarter')){
        //     $query->headquarter();
        if ($request->filled('restrictHeadquarter') && 1 == $request->input('restrictHeadquarter')) {
            $query->headquarter();
        }

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->orderBy('created_at', 'desc')->get();

        return response(['results' => $data]);
    }

    public function usersCommercial(Request $request)
    {
        $query = User::with('headquarter', 'roles')->select(['*'])->whereHas('roles', function ($roles) {
            $roles->where('slug', 'commercial');
        })->enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();

        return response(['results' => $data]);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function technicals(Request $request)
    {
        $data = $this->getUsersByRole($request, 'technical');

        return response(['results' => $data]);
    }

    public function commercials(Request $request)
    {
        $data = $this->getUsersByRole($request, 'commercial');

        return response(['results' => $data]);
    }

    public function findARoute(Request $request)
    {
        if ($request->filled('user')) {
            // buscar una ruta para la fecha y tecnico seleccionado
            $service = $request->input('service');
            $routes = Route::whereIn('user_id', $request->input('user'))->where('date', Carbon::createFromFormat('d/m/Y', $request->input('date'))->startOfDay()->format('Y-m-d H:i:s'))->get();

            return response(['success' => true, 'view' => view('crm.forms.frames.find_a_route', compact(['routes', 'service']))->render()]);
        }

        return response(['success' => true, 'view' => null]);
    }

    public function scheduleStatuses(Request $request)
    {
        $query = CrmScheduleStatus::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function zonaOffice(Request $request, $headquarter)
    {
        $query = Zone::select(['id', 'name as text'])->whereHas('headquarters', function ($q) use ($headquarter) {
            $q->where('headquarter_id', $headquarter);
        });
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $zone = $query->get();

        return response(['results' => $zone]);
    }

    public function fileForm(Request $request)
    {
        $id = $request->input('id');
        $cdocument = null;
        $filetypes = FileType::where('slug', 'crm')->enabled()->get();
        $key = uniqid();
        $form = view('crm.forms.fileForm', compact(['key', 'cdocument', 'filetypes']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function addDocumentRow(Request $request)
    {
        $seatype = $request->input('seatype');
        $cdocument = null;
        $key = uniqid();
        $filetypes = FileType::where('slug', 'crm')->enabled()->get();
        $row = view('crm.forms.frames.document_new_row', compact(['key', 'cdocument', 'filetypes', 'seatype']))->render();

        return response(['success' => true, 'row' => $row]);
    }

    /**
     * Adicionar formulario para asignar tecnicos a cliente.
     */
    public function technicalForm(Request $request)
    {
        $id = $request->input('id');
        $technical = null;
        $services = Service::with(['detailServices'])->enabled()->get();
        $key = uniqid();
        $form = view('crm.forms.technicalForm', compact(['key', 'technical', 'services']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    /**
     * Añadir fila para asignacion de técnicos a cliente.
     */
    public function addTechnicalRow(Request $request)
    {
        $technical = null;
        $key = uniqid();
        $services = Service::with(['detailServices'])->enabled()->get();
        $row = view('crm.forms.frames.technical_new_row', compact(['key', 'technical', 'services']))->render();

        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    public function addObservationRow(Request $request)
    {
        $technical = null;
        $key = uniqid();
        $crmSchedule = new crmSchedule();
        $row = view('crm.forms.frames.observation_new_row', compact(['key', 'crmSchedule']))->render();

        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    public function storeObservation(Request $request, $crm)
    {
        $crmSchedule = CrmSchedule::find($request->input('id'));
        $success = $this->saveObservation($request, $crmSchedule);

        return response(['success' => $success, 'message' => $success ? 'Nota externa guardada correctamente' : $this->msgError, 'trace' => $this->traceError]);
    }

    public function saveObservation(Request $request, CrmSchedule $crmSchedule)
    {
        try {
            DB::beginTransaction();
            foreach ($request->input('observation') as $key => $observation) {
                $observation = new Request($observation);
                if ($observation->filled('user_id') && $observation->input('user_id') == Auth::User()->id) {
                    $crmSchedule->observations()->updateOrCreate(
                        [
                            'id' => $observation->id,
                        ],
                        [
                            'user_id' => Auth::User()->id,
                            'observation' => $observation->observation,
                            'feedback' => $observation->feedback,
                            'feedback_by' => $observation->feedback ? Auth::User()->id : null,
                            'feedback_at' => $observation->feedback ? Carbon::now()->format('Y-m-d H:i:s') : null,
                            'private' => $observation->private ? $observation->private : 0,
                        ]
                    );
                } else {
                    $crmSchedule->observations()->updateOrCreate(
                        [
                            'id' => $observation->id,
                        ],
                        [
                            'feedback' => $observation->feedback,
                            'feedback_by' => $observation->feedback ? Auth::User()->id : null,
                            'feedback_at' => $observation->feedback ? Carbon::now()->format('Y-m-d H:i:s') : null,
                        ]
                    );
                }
            }
            DB::commit();

            return true;
        } catch (\Exception $th) {
            DB::rollback();
            $this->msgError = $th->getMessage();
            $this->traceError = $th->getTrace();

            return false;
        }
    }

    public function store(Request $request)
    {
        $crm = new Crm();
        $crm->user_id = Auth::User()->id;
        $success = $this->save($request, $crm);

        return response(['success' => $success, 'message' => $success ? $this->msgSuccess : $this->msgError]);
    }

    public function save(Request $request, Crm $crm)
    {
        $this->validateForm($request, $crm);

        try {
            DB::beginTransaction();
            $mean_contact_id = null;
            if ($request->filled('mean_contact_id')) {
                if (!is_numeric($request->input('mean_contact_id'))) {
                    $mean_contact_id = MeanContact::firstOrCreate(['name' => $request->input('mean_contact_id')])->id;
                } else {
                    $mean_contact_id = MeanContact::find($request->input('mean_contact_id'))->id;
                }
            }

            $crm->name = $request->input('name');
            $crm->mean_contact_id = $mean_contact_id;

            if ($request->filled('type_customer')) {
                $crm->type_customer = $request->input('type_customer');
            }
            if ($request->filled('document')) {
                $crm->document = $request->input('document');
            }
            if ($request->filled('crm_type_id')) {
                $crm->crm_type_id = $request->input('crm_type_id');
            }
            if ($request->filled('customer_national')) {
                $crm->customer_national = $request->input('customer_national');
            }
            if ($request->filled('customer_subcontract')) {
                $crm->customer_subcontract = $request->input('customer_subcontract');
            }
            if ($request->filled('commercial_name')) {
                $crm->commercial_name = $request->input('commercial_name');
            }
            if ($request->filled('ei_email')) {
                $crm->ei_email = $request->input('ei_email');
            }

            $crm->save();

            // sincronizar las sedes que administrarán este cliente
            if ($request->filled('customer_national') && '1' == $request->input('customer_national')) {
                $crm->headquarters()->sync($request->input('headquarters'));
            } else {
                $crm->headquarters()->sync([session('headquarter_id')]);
            }

            if ($request->filled('officeCustomer')) {
                foreach ($request->input('officeCustomer') as $key => $office) {
                    $office = new Request($office);

                    if ($office->filled('city_name')) {
                        // validar que la latitud y longitud no estén vacías
                        if (!$office->filled('lat')) {
                            DB::rollback();
                            $this->msgError = 'No se pudo capturar la latitud y longitud de la dirección del cliente, por favor intentalo nuevamente.';

                            return false;
                        }

                        if (!$office->filled('lng')) {
                            DB::rollback();
                            $this->msgError = 'No se pudo capturar la latitud y longitud de la dirección del cliente, por favor intentalo nuevamente.';

                            return false;
                        }

                        if (!is_null($office->input('name'))) {
                            if ($office->input('id')) {
                                $officeCrm = Office::find($office->input('id'));
                                $officeCrm->fill($office->all());
                                $officeCrm->save();
                            } else {
                                $officeCrm = $crm->offices()->create(
                                    [
                                        'city_id' => $office['city_name'],
                                        'headquarter_id' => $office['headquarter_id'],
                                        'name' => $office['name'],
                                        'address' => $office['address'],
                                        'observation' => $office['observation'],
                                        'lat' => $office['lat'],
                                        'lng' => $office['lng'],
                                        'enabled' => $office['enabled'],
                                    ]
                                );
                            }

                            // crear-actualizar bodega de tipo sucursal del cliente
                            Warehouses::setWarehouse($officeCrm, $office['headquarter_id']);

                            // añadir los archivos cargados a la sucursal creada
                            $this->saveFile($request, $officeCrm, 'offices', 'file', true, 'cdocument');

                            // sincronizar los técnicos x servicios
                            if ($request->input('technical')) {
                                $officeCrm->services()->sync($this->makeSyncDataForTechnicals($request->input('technical')));
                            }

                            foreach ($request->input('contactoCustomer') as $key => $contact) {
                                $contact = new Request($contact);

                                if (!is_null($contact->input('name'))) {
                                    if ($contact->input('id')) {
                                        $contacts = Contact::find($contact->input('id'));
                                        $contacts->fill($contact->all());
                                        $contacts->save();
                                    } else {
                                        $contacts = $officeCrm->contacts()->create(
                                            [
                                                'name' => $contact['name'],
                                                'phone' => $contact['phone'],
                                                'position' => $contact['position'],
                                                'email' => $contact['email'],
                                                'enabled' => $contact['enabled'],
                                            ]
                                        );

                                        $contacts->types()->sync($contact['contact_type_id']);

                                        $continue = true;
                                        if ($contact->filled('giveAccess')) {
                                            $continue = $this->_giveAccess($crm, $contacts);
                                        }

                                        if (!$continue) {
                                            DB::rollback();

                                            return false;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // almacenar todos los archivos del cliente
            // $this->saveFile($request, $crm, 'crms', 'file',true,'cdocument');

            // asignar este usuario a este nuevo cliente
            $crm->users()->attach($crm->user_id);

            DB::commit();

            return true;
        } catch (\Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage() . '' . $exc->getTraceAsString();

            return false;
        }
    }

    public function formSimpleCrm(Request $request)
    {
        $data_click = 'saveSimple';
        $form = view('crm.forms.customerSimpleForm', compact(['data_click']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function saveSimple(StoreCrmSimpleRequest $request)
    {
        try {
            DB::beginTransaction();
            // verificar si el cliente es nuevo o ya existe
            $crm = null;
            if ($request->input('crm_id')) {
                if (is_numeric($request->input('crm_id'))) {
                    $crm = Crm::find($request->input('crm_id'));
                    if (!$crm) {
                        $crm = Crm::firstOrCreate(['name' => $request->input('crm_id'), 'enabled' => 1], ['user_id' => Auth::user()->id]);
                    }
                } else {
                    $crm = Crm::firstOrCreate(['name' => $request->input('crm_id'), 'enabled' => 1], ['user_id' => Auth::user()->id]);
                }
            }

            // verificar si la sucursal es nueva o ya existe
            $office = null;
            if ($request->input('office_id')) {
                if (is_numeric($request->input('office_id'))) {
                    $office = Office::find($request->input('office_id'));
                    if (!$office) {
                        $office = Office::firstOrCreate(
                            [
                                'name' => $request->input('office_id'),
                                'crm_id' => $crm->id,
                                'enabled' => 1,
                            ],
                            [
                                'headquarter_id' => session('headquarter_id'),
                                'city_id' => Headquarter::find(session('headquarter_id'))->city_id,
                                'address' => $request->input('office_id'),
                            ]
                        );
                    }
                } else {
                    $office = Office::firstOrCreate(
                        [
                            'name' => $request->input('office_id'),
                            'crm_id' => $crm->id,
                            'enabled' => 1,
                        ],
                        [
                            'headquarter_id' => Auth::user()->headquarter->id,
                            'city_id' => Auth::user()->headquarter->city_id,
                            'address' => $request->input('office_id'),
                        ]
                    );
                }
            }

            DB::commit();

            return response(['success' => 'true', 'message' => 'true' ? $this->msgSuccess : $this->msgError]);
        } catch (\Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage() . '' . $exc->getTraceAsString();

            return false;
        }
    }

    public function show($id)
    {
        $crms = Crm::with(['crmCategory', 'offices'])->headquarterOffice()->find($id);

        $request = new Request(['id' => $id]);

        $firtOfficeCustomer = null;
        $response = $this->officeCustomer($request);
        $responseDecode = json_decode($response->content());
        if (count($responseDecode->results) > 0) {
            $firtOfficeCustomer = json_decode($response->content())->results[0];
        }
        if (isset($crms->id)) {
            return view('crm.show', compact('crms', 'id', 'firtOfficeCustomer'));
        }

        return redirect()->route('crms.index');
    }

    public function validateNit(Request $request)
    {
        // buscar el nit o cèdula
        $crms = Crm::where('document', 'like', '%' . $request->input('document') . '%')
            ->orderBy('customer_national', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
        $view = view('crm.sub.validateNit', compact(['crms']))->render();

        return response(['success' => $crms->count() > 0 ? true : false, 'view' => $view]);
    }

    public function reesamble(Request $request)
    {
        // buscar el nit o cèdula
        $success = Crm::where('id', $request->input('id'))->update(['customer_national' => 1]);

        return response(['success' => true]);
    }

    public function addScheduleFromOffice(Request $request, $id, $prototypeOffice, $office)
    {
        $fromQuote = true;
        $crms = Crm::with(['crmCategory'])->headquarterOffice()->find($id);
        $crm = $crms;
        $prototypeOffice = Office::find($prototypeOffice);
        $office = Office::find($office);
        $isEdit = false;
        $quoteId = null;

        if (!$prototypeOffice) {
            abort('406', 'La sede del cliente donde deseas crear el cronograma ya no se encuentra disponible o no tienes los permisos necesarios para consultarla.');
        }
        if (!$prototypeOffice) {
            abort('406', 'La sede del cliente en la cual se realizó el cronograma ya no se encuentra disponible o no tienes los permisos necesarios para consultarla.');
        }
        if (!$crms) {
            abort('406', 'El cliente del cronograma a realizar no se encuentra disponible o no tienes los permisos necesarios para consultarlo');
        }

        // traer y emular la información del formulario
        $workschedule = Workschedule::with(['services' => function ($services) {
            $services->whereIn('status_id', [1, 2, 3, 6]);
        }])->where('office_id', $prototypeOffice->id)->get();
        if ($workschedule) {
            // $workschedule = $workschedule->last();
        } else {
            abort('406', 'la sede de cliente seleccionada no tiene una cronograma vigente o creado a la fecha, o bien no tiene los permisos neceserio para acceder a esta información.');
        }

        $key = Str::random(10);

        $tempServices = null;
        // obtener los servicios almacenados en la memoria temporal
        $tempServices = $request->session()->get('temp_services');

        try {
            $tempServices = $tempServices[$crm->id][$office->id];
        } catch (\Exception $ex) {
            $tempServices = null;
        }

        $ttss = null;

        foreach ($workschedule->pluck('services')->flatten()->unique(function ($item) {
            return $item->service_id . $item->num;
        })  as $index => $quoteService) {
            $service = new CrmSchedule();
            $service->id = hexdec(uniqid());
            $service->key = hexdec(uniqid());
            $service->service_id = $quoteService->service->id;
            $service->service = $quoteService->service;
            $service->controls = $quoteService->infestations;
            $service->coverage_id = $quoteService->coverage_id;
            $service->coverage = DetailService::find($quoteService->coverage_id);
            $service->price = $quoteService->price;
            $service->periodicity_id = $quoteService->periodicity_id;
            $service->periodicity = Periodicity::find($quoteService->periodicity_id);
            $service->visits = $quoteService->visits;
            $service->current_weeks = $this->getWeeksOfPeriodicityPrivate($quoteService->periodicity_id, Carbon::parse($quoteService->week_start));
            $service->from_session = false;
            $service->periodicity_start = $quoteService->periodicity_start;
            $service->periodicity_end = $quoteService->periodicity_end;
            $service->week_start = $quoteService->week_start;
            $service->weeks = $quoteService->weeks;
            $service->week_data = $quoteService->week_data;
            $service->day = $quoteService->start_day;
            $service->time_value = $quoteService->time_value;
            $service->time_unit = $quoteService->time_unit_id;
            $service->num = $quoteService->num;
            $service->enable_fix_weeks = $quoteService->enable_fix_weeks;

            $params = Office::with(['services' => function ($services) use ($quoteService) {
                $services->where('services.id', $quoteService->service->id);
            }])->whereHas('services', function ($services) use ($quoteService) {
                $services->where('services.id', $quoteService->service->id);
            })->orderBy('id', 'desc')->find($office->id);

            $service->params = !is_null($params) ? $params->services->last()->pivot : null;

            $ttss[] = $service;
        }

        if ($tempServices) {
            foreach ($tempServices  as $key => $servicetemp) {
                $service = null;
                if ($servicetemp) {
                    $serviceD = Service::find($servicetemp['service']);
                }

                $service = new CrmSchedule();
                $service->id = hexdec(uniqid());
                $service->key = $key;
                $service->service_id = $serviceD->id;
                $service->service = $serviceD;
                $service->controls = [];
                $service->coverage_id = null;
                $service->coverage = null;
                $service->price = null;
                $service->periodicity_id = null;
                $service->periodicity = null;
                $service->visits = 1;
                $service->weeks = null;
                $service->from_session = true;
                $service->day = 0;
                $service->unit_value = 1;
                $service->unit_unit = 2;
                $service->num = $servicetemp['num'];

                $ttss[] = $service;
            }
        }

        $services = collect($ttss);

        $timeUnits = TimeUnit::all();
        $schedules = CrmSchedule::with('service', 'controls.infestation', 'controls.periodicity', 'controls.timeUnit', 'controls.products.product', 'controls.products.area', 'controls.products.application', 'status')->whereNull('date');
        $workschedule = null;
        $view = view('crm.forms.scheduleForm', compact(['schedules', 'crm', 'office', 'key', 'services', 'timeUnits', 'fromQuote', 'quoteId', 'isEdit', 'workschedule']))->render();

        return view('crm.show', compact('crms', 'id', 'fromQuote'))->withOpenForm('true')
            ->withAction('create')
            ->withAutocompleteOffice($office)
            ->withClickButton('.btnSendFilter')
            ->withView($view);
    }

    public function addScheduleFromQuote(Request $request, $id, $office, $quoteId)
    {
        $workschedule = null;
        $fromQuote = true;
        $crms = Crm::with(['crmCategory'])->headquarterOffice()->find($id);
        $crm = $crms;
        $office = Office::find($office);
        $isEdit = false;

        if (!$office) {
            abort('406', 'La sede del cliente en la cual se realizó la cotización ya no se encuentra disponible o no tienes los permisos necesarios para consultarla.');
        }
        if (!$crms) {
            abort('406', 'El cliente del cronograma a realizar no se encuentra disponible o no tienes los permisos necesarios para consultarlo');
        }

        // traer y emular la información del formulario
        $quote = Quote::with(['services' => function ($qs) {
            $qs->with('service')
                ->where('approved', 1);
        }, 'services.detailService', 'services.periodicity', 'services.infestations.infestation'])->where('status_id', 2)->find($quoteId);
        $key = Str::random(10);

        if (!$quote) {
            abort('406', 'La cotización desde donde desear hacer el cronograma ya no se encuentra disponible, cambió o tiene  un estado diferente de "Aprobado" o no tienes los permisos necesarios para poder consultarla.');
        }

        $ttss = null;

        foreach ($quote->services  as $index => $quoteService) {
            if (strpos($quoteService->service->name, 'MIP')) {
                $quoteService->service = Service::where('name', 'like', '%Desratización%')->get()->first();

                $service = new CrmSchedule();
                $service->id = hexdec(uniqid());
                $service->service_id = $quoteService->service->id;
                $service->service = $quoteService->service;
                $service->controls = $quoteService->infestations;
                $service->coverage_id = $quoteService->coverage_id;
                $service->coverage = $quoteService->detailService;
                $service->price = $quoteService->price;
                $service->periodicity_id = $quoteService->periodicity_id;
                $service->periodicity = $quoteService->periodicity;
                $service->visits = $quoteService->visits;
                $service->current_weeks = $this->getWeeksOfPeriodicityPrivate($service->periodicity_id, Carbon::now());
                $service->from_session = false;
                $service->day = 0;
                $service->time_value = 1;
                $service->time_unit = 2;
                $service->key = hexdec(uniqid());
                $service->enable_fix_weeks = 0;
                $service->num = null;
                $service->observation = $quoteService->observation;
                $service->public_observation = $quoteService->public_observation;
                $ttss[] = $service;

                $quoteService->service = Service::where('name', 'like', '%Desinsectación%')->get()->first();
                $service = new CrmSchedule();
                $service->id = hexdec(uniqid());
                $service->service_id = $quoteService->service->id;
                $service->service = $quoteService->service;
                $service->controls = $quoteService->infestations;
                $service->coverage_id = $quoteService->coverage_id;
                $service->coverage = $quoteService->detailService;
                $service->price = $quoteService->price;
                $service->periodicity_id = $quoteService->periodicity_id;
                $service->periodicity = $quoteService->periodicity;
                $service->visits = $quoteService->visits;
                $service->current_weeks = $this->getWeeksOfPeriodicityPrivate($service->periodicity_id, Carbon::now());
                $service->from_session = false;
                $service->day = 5;
                $service->time_value = 1;
                $service->time_unit = 2;
                $service->key = hexdec(uniqid());
                $service->enable_fix_weeks = 0;
                $service->num = null;
                $service->observation = $quoteService->observation;
                $service->public_observation = $quoteService->public_observation;
                $ttss[] = $service;

                $params = Office::with(['services' => function ($services) use ($quoteService) {
                    $services->where('services.id', $quoteService->service->id);
                }])->whereHas('services', function ($services) use ($quoteService) {
                    $services->where('services.id', $quoteService->service->id);
                })->orderBy('id', 'desc')->find($office->id);

                $service->params = !is_null($params) ? $params->services->last()->pivot : null;
            } else {
                $service = new CrmSchedule();
                $service->id = hexdec(uniqid());
                $service->service_id = $quoteService->service->id;
                $service->service = $quoteService->service;
                $service->controls = $quoteService->infestations;
                $service->coverage_id = $quoteService->coverage_id;
                $service->coverage = $quoteService->detailService;
                $service->price = $quoteService->price;
                $service->periodicity_id = $quoteService->periodicity_id;
                $service->periodicity = $quoteService->periodicity;
                $service->visits = $quoteService->visits;
                $service->current_weeks = $this->getWeeksOfPeriodicityPrivate($service->periodicity_id, Carbon::now());
                $service->from_session = false;
                $service->day = 0;
                $service->unit_value = 1;
                $service->unit_unit = 2;
                $service->key = hexdec(uniqid());
                $service->enable_fix_weeks = 0;
                $service->num = null;
                $service->observation = $quoteService->observation;
                $service->public_observation = $quoteService->public_observation;
                $ttss[] = $service;
            }
        }

        // obtener los servicios almacenados en la memoria temporal
        $tempServices = $request->session()->get('temp_services');

        try {
            $tempServices = $tempServices[$crm->id][$office->id];
        } catch (\Exception $ex) {
            $tempServices = [];
        }

        foreach ($tempServices  as $key => $servicetemp) {
            $service = null;
            if ($servicetemp) {
                $serviceD = Service::find($servicetemp['service']);
            }

            $service = new CrmSchedule();
            $service->id = hexdec(uniqid());
            $service->service_id = $serviceD->id;
            $service->service = $serviceD;
            $service->controls = [];
            $service->coverage_id = null;
            $service->coverage = null;
            $service->price = null;
            $service->periodicity_id = null;
            $service->periodicity = null;
            $service->visits = 1;
            $service->weeks = null;
            $service->from_session = true;
            $service->day = 0;
            $service->unit_value = 1;
            $service->unit_unit = 2;
            $service->key = hexdec(uniqid());
            $service->enable_fix_weeks = 0;
            $service->num = $servicetemp['num'];

            $ttss[] = $service;
        }

        $services = collect($ttss);

        $timeUnits = TimeUnit::all();
        $schedules = CrmSchedule::with('service', 'controls.infestation', 'controls.periodicity', 'controls.timeUnit', 'controls.products.product', 'controls.products.area', 'controls.products.application', 'status')->whereNull('date');
        $view = view('crm.forms.scheduleForm', compact(['schedules', 'crm', 'office', 'key', 'services', 'timeUnits', 'fromQuote', 'quoteId', 'isEdit', 'workschedule', 'quote']))->render();

        return view('crm.show', compact('crms', 'id', 'fromQuote'))->withOpenForm('true')
            ->withAction('create')
            ->withAutocompleteOffice($office)
            ->withClickButton('.btnSendFilter')
            ->withView($view);
    }

    public function editWorkSchedule(Request $request, $id, $office, $workschedule)
    {
        $from = $request->filled('from') ? $request->input('from') : Carbon::now()->format('Y-m-d');
        $workschedule = Workschedule::with(['services' => function ($services) use ($from) {
            $services->where('date', '>=', $from)
                ->withoutTrashed()
                ->orderBy('date', 'asc');
        }])->find($workschedule);

        if (!$workschedule) {
            abort('406', 'El cronograma  de cliente que intenta modificar/visualizar ya no se encuentra disponible o no tienes los permisos necesarios para consultarla.');
        }
        $fromQuote = $workschedule->quote_id ? true : false;
        $quoteId = $workschedule->quote_id ?? null;
        $crms = Crm::with(['crmCategory'])->headquarterOffice()->find($id);
        $crm = $crms;
        $isEdit = true;

        $office = Office::find($office);

        if (!$office) {
            abort('406', 'La sede del cliente en la cual se realizó el cronograma ya no se encuentra disponible o no tienes los permisos necesarios para consultarla.');
        }
        if (!$crms) {
            abort('406', 'El cliente del cronograma a realizar no se encuentra disponible o no tienes los permisos necesarios para consultarlo');
        }

        // traer y emular la información del formulario
        $quote = Quote::with(['services.service', 'services.detailService', 'services.periodicity', 'services.infestations.infestation'])->find($quoteId);
        $key = Str::random(10);

        if ($quoteId) {
            if (!$quote) {
                abort('406', 'La cotización desde donde desear hacer el cronograma ya no se encuentra disponible, cambió o tiene  un estado diferente de "Aprobado" o no tienes los permisos necesarios para poder consultarla.');
            }
        }

        $tempServices = null;
        // obtener los servicios almacenados en la memoria temporal
        $tempServices = $request->session()->get('temp_services');

        try {
            $tempServices = $tempServices[$crm->id][$office->id];
        } catch (\Exception $ex) {
            $tempServices = null;
        }

        $ttss = null;

        foreach ($workschedule->services
            ->unique(function ($item) {
                return $item->service_id . $item->num;
            })  as $index => $quoteService) {
            $service = new CrmSchedule();
            $service->key = hexdec(uniqid());
            $service->id = $quoteService->id;
            $service->service_id = $quoteService->service->id;
            $service->service = $quoteService->service;
            $service->controls = $quoteService->infestations;
            $service->coverage_id = $quoteService->coverage_id;
            $service->coverage = DetailService::find($quoteService->coverage_id);
            $service->price = $quoteService->price;
            $service->periodicity_id = $quoteService->periodicity_id;
            $service->periodicity = Periodicity::find($quoteService->periodicity_id);
            $service->visits = $quoteService->visits;
            $service->current_weeks = $this->getWeeksOfPeriodicityPrivate($quoteService->periodicity_id, Carbon::parse($quoteService->week_start));
            $service->from_session = false;
            $service->periodicity_start = $quoteService->periodicity_start;
            $service->periodicity_end = $quoteService->periodicity_end;
            $service->week_start = $quoteService->week_start;
            $service->weeks = $quoteService->weeks;
            $service->week_data = $quoteService->week_data;
            $service->day = $quoteService->start_day;
            $service->time_value = $quoteService->time_value;
            $service->time_unit = $quoteService->time_unit_id;
            $service->num = $quoteService->num;
            $service->enable_fix_weeks = $quoteService->enable_fix_weeks;
            $service->start_hour = $quoteService->start_hour;
            $service->init_status = null;
            $service->technical_quantity = $quoteService->technical_quantity;
            $service->users = !is_null($quoteService->technicals) ? $quoteService->technicals : null;
            $ttss[] = $service;
        }

        if ($tempServices) {
            foreach ($tempServices  as $key => $servicetemp) {
                $service = null;
                if ($servicetemp) {
                    $serviceD = Service::find($servicetemp['service']);
                }

                if ($serviceD) {
                    $service = new CrmSchedule();
                    $service->id = hexdec(uniqid());
                    $service->key = $key;
                    $service->service_id = $serviceD->id;
                    $service->service = $serviceD;
                    $service->controls = [];
                    $service->coverage_id = null;
                    $service->coverage = null;
                    $service->price = null;
                    $service->periodicity_id = null;
                    $service->periodicity = null;
                    $service->visits = 1;
                    $service->weeks = null;
                    $service->from_session = true;
                    $service->num = $servicetemp['num'];

                    $ttss[] = $service;
                }
            }
        }

        $services = collect($ttss);

        $timeUnits = TimeUnit::all();
        $schedules = CrmSchedule::with('service', 'controls.infestation', 'controls.periodicity', 'controls.timeUnit', 'controls.products.product', 'controls.products.area', 'controls.products.application', 'status')->whereNull('date');

        $view = view('crm.forms.scheduleForm', compact(['schedules', 'crm', 'office', 'key', 'services', 'timeUnits', 'fromQuote', 'quoteId', 'isEdit', 'workschedule', 'from']))->render();

        return view('crm.show', compact('crms', 'id', 'fromQuote'))->withOpenForm('true')
            ->withAction('create')
            ->withAutocompleteOffice($office)
            ->withClickButton('.btnSendFilter')
            ->withView($view);
    }

    public function createWorkSchedule(Request $request, $id, $office)
    {
        $from = $request->filled('from') ? $request->input('from') : Carbon::now()->format('Y-m-d');
        $workschedule = null;

        $fromQuote = false;
        $quoteId = null;
        $crms = Crm::with(['crmCategory'])->headquarterOffice()->find($id);
        $crm = $crms;
        $isEdit = false;

        $office = Office::find($office);

        if (!$office) {
            abort('406', 'La sede del cliente en la cual se realizó el cronograma ya no se encuentra disponible o no tienes los permisos necesarios para consultarla.');
        }
        if (!$crms) {
            abort('406', 'El cliente del cronograma a realizar no se encuentra disponible o no tienes los permisos necesarios para consultarlo');
        }

        // traer y emular la información del formulario
        $quote = Quote::with(['services.service', 'services.detailService', 'services.periodicity', 'services.infestations.infestation'])->find($quoteId);
        $key = Str::random(10);

        if ($quoteId) {
            if (!$quote) {
                abort('406', 'La cotización desde donde desear hacer el cronograma ya no se encuentra disponible, cambió o tiene  un estado diferente de "Aprobado" o no tienes los permisos necesarios para poder consultarla.');
            }
        }

        $tempServices = null;
        // obtener los servicios almacenados en la memoria temporal
        $tempServices = $request->session()->get('temp_services');

        try {
            $tempServices = $tempServices[$crm->id][$office->id];
        } catch (\Exception $ex) {
            $tempServices = null;
        }

        $ttss = null;

        if ($tempServices) {
            foreach ($tempServices  as $key => $servicetemp) {
                $service = null;
                if ($servicetemp) {
                    $serviceD = Service::find($servicetemp['service']);
                }

                if ($serviceD) {
                    $service = new CrmSchedule();
                    $service->id = hexdec(uniqid());
                    $service->key = $key;
                    $service->service_id = $serviceD->id;
                    $service->service = $serviceD;
                    $service->controls = [];
                    $service->coverage_id = null;
                    $service->coverage = null;
                    $service->price = null;
                    $service->periodicity_id = null;
                    $service->periodicity = null;
                    $service->visits = 1;
                    $service->weeks = null;
                    $service->from_session = true;
                    $service->num = $servicetemp['num'];

                    $ttss[] = $service;
                }
            }
        }

        $services = collect($ttss);

        $timeUnits = TimeUnit::all();
        $schedules = CrmSchedule::with('service', 'controls.infestation', 'controls.periodicity', 'controls.timeUnit', 'controls.products.product', 'controls.products.area', 'controls.products.application', 'status')->whereNull('date');

        $view = view('crm.forms.scheduleForm', compact(['schedules', 'crm', 'office', 'key', 'services', 'timeUnits', 'fromQuote', 'quoteId', 'isEdit', 'workschedule', 'from']))->render();

        return view('crm.show', compact('crms', 'id', 'fromQuote'))->withOpenForm('true')
            ->withAction('create')
            ->withAutocompleteOffice($office)
            ->withClickButton('.btnSendFilter')
            ->withView($view);
    }

    public function gotoSchedule(Request $request, $id, $office)
    {
        $crms = Crm::with(['crmCategory'])->headquarterOffice()->find($id);
        $crm = $crms;
        $isEdit = false;
        $office = Office::find($office);
        $firtOfficeCustomer = $office;
        $fromQuote = false;

        return view('crm.show', compact('crms', 'id', 'fromQuote', 'office', 'firtOfficeCustomer'))
            ->withAutocompleteOffice($office);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $crm = Crm::find($id);
        $success = $this->save($request, $crm);

        return response(['success' => 'true', 'message' => $success ? $this->msgUpdated : $this->msgError]);
    }

    public function deleteForm(Request $request)
    {
        $id = $request->input('id');
        $crm = Crm::find($id);
        $form = view('crm.forms.deleteForm', compact('crm'))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function softDelete(Request $request)
    {
        $id = $request->input('id');
        $note = Crm::destroy($id);

        return response(['success' => 'true', 'message' => $note ? $this->msgDeleted : $this->msgError]);
    }

    public function listCustomerShow(Request $request)
    {
        $id = $request->input('id');
        $crm = Crm::with(['crmCategory', 'meanContact'])->find($id);
        $list = view('crm.sub.listCustomer', compact('crm'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function formCustomer(Request $request)
    {
        $data_click = 'save_customer';
        $id = $request->input('id');
        $title = 'EDITAR CLIENTE';
        $crm = Crm::with(['crmCategory', 'meanContact', 'headquarters'])->find($id);
        $form = view('crm.forms.infoForm', compact(['crm']))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function updateCustomerShow(Request $request)
    {
        $id = $request->input('id');
        $crm = Crm::find($id);
        $crm->fill($request->all());
        $crm->customer_national = $request->input('customer_national') ? true : false;
        $crm->customer_subcontract = $request->input('customer_subcontract') ? true : false;
        $crm->save();

        // sincronizar las sedes que administrarán este cliente
        if ($request->filled('customer_national') && '1' == $request->input('customer_national')) {
            $crm->headquarters()->sync($request->input('headquarters'));
        } else {
            $crm->headquarters()->sync([session('headquarter_id')]);
        }

        // crear-actualizar bodega de tipo cliente
        foreach ($crm->offices()->get() as $key => $office) {
            $name = $crm->name . ' ' . $office->name . ' (' . $office->address . ', ' . $office->city->name . ')';
            Warehouses::updateWarehouseName($office, $name);
        }

        return response(['success' => 'true', 'message' => $crm ? $this->msgUpdated : $this->msgError]);
    }

    public function listContactShow(Request $request)
    {
        $id = $request->input('id');
        $branchOffice = $request->input('branchOffice');

        $contacts = Contact::whereHas('offices', function ($q) use ($branchOffice) {
            $q->where('offices.id', $branchOffice);
        })->orDoesntHave('offices')->where('crm_id', $id)->get();

        $list = view('crm.sub.listContact', compact('contacts'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function setAvatar(Request $request, Crm $crm)
    {
        try {
            DB::beginTransaction();
            $crm->avatar = $this->getAvatar($request);
            $crm->save();
            DB::commit();

            return response(['success' => true, 'message' => 'Imagen actualizada']);
        } catch (\Exception $msg) {
            DB::rollBack();

            return response(['success' => true, 'message' => 'Error ' . $msg->getMessage()]);
        }
    }

    public function formContact(Request $request)
    {
        $seatype = true;
        $data_click = 'save_contact';
        $id = $request->input('id');
        $customerOffice = $request->input('office');
        $office = Office::all()->find($customerOffice);
        $title = $request->input('id') ? 'EDITAR CONTACTO' : 'CREAR CONTACTO';
        $contact = Contact::with('offices', 'types', 'user')->find($id);
        $form = view('crm.forms.contactForm', compact('contact', 'seatype', 'office'))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function storeContact(Request $request)
    {
        $contact = new Contact();
        $success = $this->saveContactShow($request, $contact);

        return response(['success' => $success, 'message' => $success ? $this->msgSuccessContact : $this->msgError]);
    }

    public function updateContactShow(Request $request)
    {
        $id = $request->input('id');
        $contact = Contact::find($id);
        $success = $this->saveContactShow($request, $contact);

        return response(['success' => $success, 'message' => $success ? $this->msgUpdatedContact : $this->msgError]);
    }

    public function deleteFormContact(Request $request)
    {
        $id = $request->input('id');
        $contact = Contact::with('user')->find($id);
        $othersAccess = Contact::where('user_id', $contact->user_id)->whereNotNull('crm_id')->get();

        $form = view('crm.forms.deleteFormContact', compact('contact', 'othersAccess'))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function softDeleteContact(Request $request)
    {
        if ($request->filled('action') && 'delete_and_keep_user' == $request->input('action')) {
            $id = $request->input('id');
            $contact = Contact::find($id);

            // revocar acceso a este cliente
            User::find($contact->user_id)->crms()->detach($contact->crm_id);

            // eliminar el contacto
            $success = Contact::destroy($id);

            return response(['success' => $success, 'message' => $success ? 'Se han eliminado el contacto y solo el acceso a este cliente' : $this->msgError]);
        }
        if ($request->filled('action') && 'delete_all' == $request->input('action')) {
            $id = $request->input('id');
            $contact = Contact::find($id);

            // revocar acceso a este cliente
            User::find($contact->user_id)->crms()->detach();
            User::find($contact->user_id)->destroy();

            // eliminar el contacto
            $success = Contact::destroy($id);

            return response(['success' => $success, 'message' => $success ? 'Se han eliminado el contacto y todos los accesos' : $this->msgError]);
        }
        $id = $request->input('id');
        $contact = Contact::find($id);

        if (is_null($contact->user)) {
            $success = Contact::destroy($id);

            return response(['success' => $success, 'message' => $success ? 'Se ha eliminado el contacto' : $this->msgError]);
        }
        abort('406', 'No seleccionaste una acción a realizar');
    }

    public function giveAccessContact(Request $request, Crm $crm)
    {
        // obtener los datos del contacto
        $contact = Contact::find($request->input('id'));
        $success = $this->_giveAccess($crm, $contact);

        return response(['success' => $success, 'message' => $success ? 'Acceso habilitado y enviado correctamente' : $this->msgError]);
    }

    public function giveAccessContactInSave(Request $request, Crm $crm, Contact $contact)
    {
        // obtener los datos del contacto
        $success = $this->_giveAccess($crm, $contact);
    }

    public function revokeAccessContact(Request $request, Crm $crm)
    {
        // obtener los datos del contacto
        $contact = Contact::find($request->input('id'));

        $crm->users()->detach($contact->user_id);
        $user = User::find($contact->user_id);
        $user->delete();
        $contact->user_id = null;
        $contact->save();

        return response(['success' => 'true', 'message' => 'Acceso revocado correctamente']);
    }

    public function listUserShow(Request $request)
    {
        $id = $request->input('id');
        $office = $request->input('branchOffice');

        $users = User::whereHas('offices', function ($q) use ($office) {
            $q->where('office_id', $office)->headquarter();
        })->get();
        $list = view('crm.sub.listUser', compact('users', 'office'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function formUser(Request $request)
    {
        $id = $request->input('id');
        $data_click = 'save_user';
        $title = $request->input('id') ? 'ASIGNAR COMERCIAL/TECNICO' : 'ASIGNAR COMERCIAL/TECNICO';
        $form = view('crm.forms.userForm', compact('data_click', 'id'))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function formCustomerUser(Request $request)
    {
        $id = $request->input('id');
        $office = Office::find($request->input('office'));
        $data_click = 'save_customer_user';
        $title = $request->input('id') ? 'ASIGNAR USUARIO DE CLIENTE' : 'ASIGNAR USUARIO DE CLIENTE';
        $form = view('crm.forms.userForm', compact('data_click', 'id', 'office'))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function storeUser(Request $request)
    {
        $request->validate(['user_id' => 'required'], [], ['user_id' => 'Usuario']);
        if ($request->filled('office_id')) {
            // Asociar el contacto externo a este cliente
            $contact = Contact::where('user_id', $request->user_id)->first();
            if (!is_null($contact)) {
                try {
                    DB::beginTransaction();
                    // clonar este contacto para el cliente nuevo
                    $newContact = new Contact();
                    $newContact->crm_id = $request->input('crm_id');
                    $newContact->name = $contact->name;
                    $newContact->phone = $contact->phone;
                    $newContact->position = $contact->position;
                    $newContact->email = $contact->email;
                    $newContact->user_id = $contact->user_id;
                    $newContact->enabled = $contact->enabled;
                    $newContact->save();

                    // sincronizar sucursales y temas autorizados
                    $newContact->types()->sync($request->input('contact_type_id'));
                    $newContact->offices()->sync($request->input('office_id'));

                    // sincronizar el nuevo cliente al usuario
                    User::find($request->user_id)->crms()->attach($request->crm_id);

                    DB::commit();

                    return response(['success' => true, 'message' => 'Usuario de cliente asociado correctamente.']);
                } catch (\Exception $ex) {
                    return response(['success' => false, 'message' => $this->msgError . ':' . $ex->getMessage(), 'tarce' => $ex->getTrace()]);
                    DB::rollBack();
                }
            } else {
                abort(406, 'El usuario de cliente seleccionado no tiene asociado un contacto, esto puede deberse a que el contacto se eliminó. no se puede continuar con la operación');
            }
        } else {
            abort(406, 'Debes seleccionar una sucursal del cliente antes de realizar esta acción');
        }
    }

    public function deleteFormUser(Request $request)
    {
        $id = $request->input('id');
        $user = $request->input('user_id');
        $user = User::find($user);
        $form = view('crm.forms.deleteFormUser', compact('user', 'id'))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function fileFormUser(Request $request)
    {
        $id = $request->input('id');
        $user = $request->input('user_id');
        $user = User::withTrashed()->with(['roles', 'files.fileType', 'headquarter', 'availabilities', 'crms', 'services'])->find($user);
        $profile = 'true' == $request->input('profile') ? true : false;
        $filetypes = FileType::where('slug', 'user')->enabled()->get();
        $form = view('crm.forms.fileFormUser', compact(['user', 'id', 'filetypes']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function softDeleteUSer(Request $request)
    {
        $id = $request->input('id');
        $user_id = $request->input('user_id');
        $office_id = $request->input('office_id');
        $success = Office::find($office_id)->users()->detach([$user_id]);

        return response(['success' => $success, 'message' => $success ? $this->msgDeletedUser : $this->msgError]);
    }

    public function listContractShow(Request $request)
    {
        $id = $request->input('id');
        $contracts = Contract::where('crm_id', $id)->enabled()->headquarter()->get();
        $list = view('crm.sub.listContract', compact('contracts'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function listOfficeShow(Request $request)
    {
        // verificar si el usuario actual es cliente
        if (Auth::user()->roles->pluck('slug')->contains('customer')) {
            $id = $request->input('id');
            $offices = Office::with(['cities', 'crm', 'headquarter'])
                ->where('crm_id', $id)
                ->get();
        } else {
            $id = $request->input('id');
            $offices = Office::with(['cities', 'crm', 'headquarter'])
                ->where('crm_id', $id)
                ->headquarter()
                ->get();
        }

        $list = view('crm.sub.listOffice', compact(['offices']))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function formOffice(Request $request)
    {
        $data_click = 'save_office';
        $id = $request->input('id');
        $title = $request->input('id') ? 'EDITAR SUCURSAL' : 'CREAR SUCURSAL';
        $office = Office::with(['city', 'headquarter'])->get()->find($id);
        $form = view('crm.forms.officeForm', compact('office'))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function storeOffice(Request $request)
    {
        $niceNames = [
            'address' => 'Dirección',
            'headquarter_id' => 'Palmera Junior',
            'name' => 'Nombre'
        ];

        $request->validate(
            [
                'address' => 'required',
                'headquarter_id' => 'required',
                'name' => 'required'
            ],
            [],
            $niceNames
        );

        $office = new Office();
        $office->city_id = $request->input('city_name');
        $office->lat = $request->input('lat');
        $office->lng = $request->input('lng');
        $office->address = $request->input('address');
        $office->observation = $request->input('observation');
        $office->name = $request->input('name');
        $office->crm_id = $request->input('crm_id');
        $office->headquarter_id = $request->input('headquarter_id');
        $office->save();
        // crear-actualizar bodega de tipo cliente
        // $crm = Crm::find($office->crm_id);
        Warehouses::setWarehouse($office, $office->headquarter_id);

        return response(['success' => 'true', 'message' => $office ? $this->msgSuccessOffice : $this->msgError]);
    }

    public function updateOfficeShow(Request $request)
    {
        $niceNames = [
            'address' => 'Dirección',
            'headquarter_id' => 'Palmera Junior',
            'name' => 'Nombre'
            
        ];

        $request->validate(
            [
                'address' => 'required',
                'headquarter_id' => 'required',
                'name' => 'required'
            ],
            [],
            $niceNames
        );

        $id = $request->input('id');
        $office = Office::find($id);
        $office->city_id = $request->input('city_name');
        $office->lat = $request->input('lat');
        $office->lng = $request->input('lng');
        $office->address = $request->input('address');
        $office->name = $request->input('name');
        $office->observation = $request->input('observation');
        $office->crm_id = $request->input('crm_id');
        $office->save();

        // verifcar si hay eventos de ruta vigentes
        // creados para esta sucursal y actualizar tambien las
        // coordenas

        $schedulings = Scheduling::where('office_id', $office->id)
            ->where('completed', 0);

        if (!is_null($schedulings)) {
            $schedulings->update([
                'crm_address' => $request->input('address'),
                'crm_lat' => $request->input('lat'),
                'crm_lng' => $request->input('lng'),
            ]);
        }

        // crear-actualizar bodega de tipo cliente
        // $crm = Crm::find($office->crm_id);
        Warehouses::setWarehouse($office, $office->headquarter_id);

        return response(['success' => 'true', 'message' => $office ? $this->msgUpdatedOffice : $this->msgError]);
    }

    public function deleteFormOffice(Request $request)
    {
        $id = $request->input('id');
        $office = Office::find($id);
        $form = view('crm.forms.deleteFormOffice', compact('office'))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function softDeleteOffice(Request $request)
    {
        $id = $request->input('id');
        $success = Office::destroy($id);

        return response(['success' => $success, 'message' => $success ? $this->msgDeletedOffice : $this->msgError]);
    }

    public function listFileShow(Request $request)
    {
        $id = $request->input('id');
        $branchOffice = $request->input('branchOffice');
        $crms = Crm::with(['files'])->find($id);
        $offices = Office::with(['files'])->where('id', $branchOffice)->whereHas('files')->get();

        $list = view('crm.sub.listFile', compact(['crms', 'offices']))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function formDocumento(Request $request)
    {
        $seatype = true;
        $data_click = 'save_document';
        $id = $request->input('id');
        $branchOffice = $request->input('office');
        $title = $request->input('id') ? 'EDITAR DOCUMENTO' : 'CREAR DOCUMENTO';
        $crm = Crm::with(['files'])->find($id);
        if ($crm) {
            $offices = Office::with(['files'])->where('id', $branchOffice)->whereHas('files')->get();
        } else {
            $offices = null;
        }

        $cdocument = null;
        $filetypes = FileType::where('slug', 'crm')->enabled()->get();
        $key = uniqid();
        $form = view('crm.forms.fileForm', compact(['crm', 'filetypes', 'cdocument', 'seatype', 'offices']))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function storeDocumento(Request $request)
    {
        $niceNames = [
            'cdocument.*.file' => 'Archivo',
            'cdocument.*.type_id' => 'Tipo',
        ];

        $request->validate(
            [
                'cdocument.*.file' => 'required',
                'cdocument.*.type_id' => 'required',
            ],
            [],
            $niceNames
        );

        try {
            $crm = Crm::find($request->input('crm_id'));

            $success = false;
            foreach ($request->all()['cdocument'] as $document) {
                $document = new Request($document);
                $office = Office::find($document->input('offices_id'));

                // almacenar todos los archivos del crm
                $success = $this->saveFile($document, $office, 'offices', 'file', false);
            }

            return response(['success' => $success, 'message' => $success ? $this->msgSuccessFile : $this->msgError]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => $this->msgError . ':' . $ex->getMessage(), 'tarce' => $ex->getTrace()]);
        }
    }

    public function updateDocumentShow(Request $request)
    {
        $niceNames = [
            'cdocument.*.office_id' => 'Sucursal',
            'cdocument.*.type_id' => 'Tipo',
        ];

        $request->validate(
            [
                'cdocument.*.office_id' => 'required',
                'cdocument.*.type_id' => 'required',
            ],
            [],
            $niceNames
        );

        try {
            $crm = Crm::find($request->input('crm_id'));

            $success = false;
            foreach ($request->all()['cdocument'] as $document) {
                $document = new Request($document);
                $office = Office::find($document->input('office_id'));

                // almacenar todos los archivos del crm
                $success = $this->saveFile($document, $office, 'offices', 'file', false);
            }

            return response(['success' => $success, 'message' => $success ? $this->msgUpdatedFile : $this->msgError]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => $this->msgError . ':' . $ex->getMessage(), 'tarce' => $ex->getTrace()]);
        }
    }

    public function deleteFormFile(Request $request)
    {
        $id = $request->input('id');
        $document = File::find($id);
        $form = view('crm.forms.deleteFormFile', compact('document'))->render();

        return response(['success' => $document ? true : false, 'view' => $form]);
    }

    public function softDeleteFile(Request $request)
    {
        $id = $request->input('id');
        $success = File::destroy($id);

        return response(['success' => $success, 'message' => $success ? $this->msgDeletedFile : $this->msgError]);
    }

    public function listTechnicalShow(Request $request)
    {
        $office = $request->input('office');
        $id = $request->input('id');
        $technicals = Office::with(['services'])->find($office);
        $technical = view('crm.sub.listTechnical', compact(['technicals']))->render();

        return response(['success' => true, 'view' => $technical]);
    }

    public function formTechnical(Request $request)
    {
        $data_click = 'save_technical';
        $office = $request->input('office');
        $id = $request->input('id');
        $title = $request->input('id') ? 'EDITAR PARÁMETRO' : 'CREAR PARÁMETRO';
        $crm = Office::with(['services'])->find($office);
        $key = uniqid();
        $form = view('crm.forms.technicalForm', compact(['crm', 'key', 'office']))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function storeTechnical(Request $request)
    {
        if ($request->filled('office_id')) {
            $office = Office::find($request->input('office_id'));
            $success = false;

            try {
                DB::beginTransaction();
                $office->services()->sync($this->makeSyncDataForTechnicals($request->input('technical')));

                // aplicar el parametro a los servicios de la oficina del cliente
                // que aun no están en ruta
                $this->applyParametersToSchedulings($request);

                DB::commit();
                $success = true;
            } catch (\Throwable $th) {
                DB::rollBack();
                $success = false;
                $this->msgError .= $th->getMessage();
            }

            return response(['success' => $success, 'message' => $success ? $this->msgSuccessTechnical : $this->msgError, 'trace' => isset($th) ? $th->getTrace() : null]);
        }
        abort('406', 'Debes seleccionar una sucursal del cliente antes de realizar esta acción');
    }

    public function updateTechnical(Request $request)
    {
        if ($request->filled('office_id')) {
            $office = Office::find($request->input('office_id'));
            $success = false;

            try {
                DB::beginTransaction();
                $office->services()->sync($this->makeSyncDataForTechnicals($request->input('technical')));

                // aplicar el parametro a los servicios de la oficina del cliente
                // que aun no están en ruta
                $this->applyParametersToSchedulings($request);

                DB::commit();
                $success = true;
            } catch (\Throwable $th) {
                DB::rollBack();
                $success = false;
                $this->msgError .= $th->getMessage();
            }

            return response(['success' => $success, 'message' => $success ? $this->msgSuccessTechnical : $this->msgError, 'trace' => isset($th) ? $th->getTrace() : null]);
        }
        abort('406', 'Debes seleccionar una sucursal del cliente antes de realizar esta acción');
    }

    public function deleteFormTechnical(Request $request)
    {
        $service_id = $request->input('service_id');
        $id = $request->input('id');
        $service = Service::find($service_id);
        $form = view('crm.forms.deleteFormTechnical', compact(['service', 'id']))->render();

        return response(['success' => $service ? true : false, 'view' => $form]);
    }

    public function softDeleteTechnical(Request $request, $crm_id)
    {
        $id = $request->input('id');
        $success = false;

        try {
            DB::beginTransaction();
            $crm = Crm::find($crm_id);
            $crm->services()->wherePivot('id', '=', $id)->detach();
            DB::commit();
            $success = true;
        } catch (\Exception $ex) {
            DB::rollBack();
            $success = false;
            $this->msgError = $ex->getMessage();
        }

        return response(['success' => $success, 'message' => $success ? $this->msgDeletedTechnical : $this->msgError]);
    }

    public function listQuoteShow(Request $request)
    {
        $id = $request->input('id');
        $office = $request->input('office');
        $quotes = Quote::with(['services.infestations', 'crm', 'coordinator', 'commercial', 'status'])
            ->where('office_id', $office)
            ->get();
        $list = view('crm.sub.listQuote', compact('quotes'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function editQuote($quote)
    {
        $quote = Quote::with('services.infestations', 'crm', 'office', 'contact', 'coordinator', 'commercial', 'status')->find($quote);

        return view('quote.basic', compact('quote'));
    }

    public function storeAreaOffice(Request $request)
    {
        if ($request->filled('office')) {
            $AreaOffice = new AreaOffice();
            list($success, $li, $div) = $this->saveAreaOffice($request, $AreaOffice);

            return response(['success' => $success, 'li' => $li, 'div' => $div, 'message' => $success ? 'Area creada correctamente' : $this->msgError, 'traceError' => $this->traceError]);
        }
        abort('406', 'Debes seleccionar una sucursal del cliente antes de realizar esta acción');
    }

    public function updateAreaOffice(Request $request)
    {
        $area = AreaOffice::find($request->input('id'));
        $area->name = $request->input('name');
        $area->enabled = $request->input('enabled') ? '1' : '0';
        $success = $area->save();

        return response(['success' => $success, 'message' => $success ? 'Area editada correctamente' : $this->msgError, 'traceError' => $this->traceError]);
    }

    public function saveAreaOffice(Request $request, AreaOffice $area)
    {
        try {
            DB::beginTransaction();
            $area->name = $request->input('area');
            $area->office_id = $request->input('office');
            $success = $area->save();
            $crm = $request->input('id');
            $office = $request->input('office');

            $li = view('crm.forms.frames.plan_added_li', compact('area'))->render();
            $div = view('crm.forms.frames.plan_added_div', compact('area', 'crm', 'office'))->render();

            DB::commit();

            return [$success, $li, $div];
        } catch (\Exception $exc) {
            DB::rollback();
            $this->traceError = $exc->getMessage();

            return false;
        }
    }

    public function deleteFormAreas(Request $request)
    {
        $id = $request->input('id');
        $area = AreaOffice::find($id);
        $form = view('crm.forms.deleteFormArea', compact('area'))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function softDeleteArea(Request $request)
    {
        $id = $request->input('id');
        $note = AreaOffice::destroy($id);

        return response(['success' => 'true', 'message' => $note ? $this->msgDeleted : $this->msgError]);
    }

    public function listPlanShow(Request $request)
    {
        $crm = $request->input('id');
        $office = $request->input('office');
        $areas = AreaOffice::with('files')->where('office_id', $office)->get();
        $list = view('crm.sub.listPlan', compact(['areas', 'crm', 'office']))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function listMechanismShow(Request $request)
    {
        $crm = $request->input('id');
        $office = $request->input('office');
        $mechanisms = collect();
        $mechanisms_deleted = collect();
        if (isset($office)) {
            $mechanisms = Qr::with(['extend.extendible', 'extend.lastTechnicalReport.type', 'schedule.service', 'scanBy'])
                ->where('office_id', $request->input('office'))
                ->whereHas('extend', function ($e) {
                    $e->where('extendible_type', 'App\Convection')
                        ->whereNull('deleted_at');
                })
                ->get();

            $mechanisms_deleted = Qr::with(['extend.extendible', 'extend.lastTechnicalReport.type', 'schedule.service', 'scanBy'])
                ->where('office_id', $request->input('office'))
                ->whereHas('extend', function ($e) {
                    $e->where('extendible_type', 'App\Convection')->onlyTrashed();
                })->get();
        }
        if (!is_null($mechanisms)) {
            $mechanisms = $mechanisms->groupBy('extend.extendible.name');
        }
        $list = view('crm.sub.listMechanisms', compact(['mechanisms', 'mechanisms_deleted', 'crm', 'office']))->render();

        return response(['success' => true, 'view' => $list]);
    }

    // NUEVAS FUNCIONES DE AREAS DE CLIENTES

    /** se realizan de nuevo estas funciones dado que se cambia el flujo de como estas funcionan */
    public function areas()
    {
        return view('crm.areas');
    }

    public function areaLists()
    {
        $areas = CrmArea::with('user')->get();
        $crm = '';
        $list = view('crm.sub.listAreas', compact(['areas', 'crm']))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function areaForm(Request $request)
    {
        $id = $request->input('id');
        $area = CrmArea::find($id);
        $title = $request->input('id') ? 'EDITAR AREA DE CLIENTE' : 'CREAR AREA DE CLIENTE';
        $form = view('crm.forms.areaForm', compact('area', 'title'))->render();

        return response(['success' => true, 'view' => $form, 'title' => $title]);
    }

    public function areaStore(Request $request)
    {
        $crmArea = new CrmArea();
        $success = $this->areaSave($request, $crmArea);

        return response(['success' => $success, 'message' => $success ? 'Area creada correctamente' : $this->msgError, 'traceError' => $this->traceError]);
    }

    public function areaUpdate(Request $request)
    {
        $crmArea = CrmArea::find($request->input('id'));
        $success = $this->areaSave($request, $crmArea);

        return response(['success' => $success, 'message' => $success ? 'Area editada correctamente' : $this->msgError, 'traceError' => $this->traceError]);
    }

    public function areaSave(Request $request, CrmArea $area)
    {
        try {
            DB::beginTransaction();
            $area->name = $request->input('name');
            $area->enabled = $request->filled('enabled') ? $request->input('enabled') : ($request->filled('id') ? 0 : 1);
            $area->updated_user = Auth::User()->id;
            $success = $area->save();

            DB::commit();

            return $success;
        } catch (\Exception $exc) {
            DB::rollback();
            $this->traceError = $exc->getMessage();

            return false;
        }
    }

    public function areaDeleteForm(Request $request)
    {
        $id = $request->input('id');
        $area = CrmArea::find($id);
        $form = view('crm.forms.deleteFormArea', compact('area'))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function areaSoftDelete(Request $request)
    {
        $id = $request->input('id');
        $note = CrmArea::destroy($id);

        return response(['success' => 'true', 'message' => $note ? $this->msgDeleted : $this->msgError]);
    }

    public function _getSchedulesCalendar(Request $request, Crm $crm, $strtotime = false, $datePrefix = false)
    {
        $office = Office::with(['contacts' => function ($q) {
            $q->whereHas('types', function ($sq) {
                $sq->where('contact_type_id', 3);
            });
        }])->find($request->input('office_id'));

        if ($strtotime) {
            $dateImput = $datePrefix ? '_date_submit' : '';
            $startDate = date('Y-m-d 00:i:s', strtotime($request->input('start' . $dateImput)));
            $endDate = date('Y-m-d 23:59:59', strtotime($request->input('end' . $dateImput)));
        } else {
            $dateImput = $datePrefix ? '_date_submit' : '';
            $startDate = date('Y-m-d 00:i:s', $request->input('start' . $dateImput));
            $endDate = date('Y-m-d 23:59:59', $request->input('end' . $dateImput));
        }

        $sdate = Carbon::parse($startDate);
        $edate = Carbon::parse($endDate);
        $startDateFormat = $sdate->format('d') . ' de ' . Str::ucfirst($sdate->monthName) . ' de ' . $sdate->format('Y');
        $endDateFormat = $edate->format('d') . ' de ' . Str::ucfirst($edate->monthName) . ' de ' . $edate->format('Y');

        $schedules = CrmSchedule::with([
                'answersSurveys', 
                'userTrackings', 
                'technicalReports', 
                'observations', 
                'controls.infestation', 
                'timeUnit', 
                'controls.product.unit', 
                'controls.area', 
                'controls.application', 
                'controls.dose', 
                'controls.timeUnit', 
                'infestationGrades.pest', 
                'status', 
                'service', 
                'coverage', 
                'workschedule.quote', 
                'workschedule.user',
                'schedulings.routes.user',
                'schedulings.routes.schedulings',
                'schedulings.routes.day_schedulings',
                'schedulings.mechanismReports',
                'schedulings.observations',
                'schedulings.signature',
            ])
            ->where('crm_id', $crm->id)
            ->where('office_id', $request->input('office_id'))
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'ASC')->get()
            ->makeHidden([
                'service_data', 
                'json', 
                'services', 
                'service_data', 
                'week_data', 
                'weeks',
            ]);
        
        $users_ids = collect([]);
        foreach ($schedules as $schedule) {
            $users_ids = $users_ids->merge($schedule->raw_technical);
        }

        CrmSchedule::$rel_users = User::withTrashed()
            ->with(['files'])
            ->whereIn('id', $users_ids->unique())
            ->get()
            ->makeHidden(['full_avatar','full_roles','full_headquarter', 'full_zones','short_name','medium_name','full_customers'])
            ->keyBy('id');

        $sheduleProcess = $schedules->map(function ($item) use ($request) {
            $date = Carbon::parse($item->date);

            // $tempS = $item->services->unique(function ($item) {
            //     return $item->service_id;
            // });
            // $item->_services = $tempS;

            // añadir la url del documento
            $item->url = route('crmschedule.report', [$item->id, 'email' => false]);

            if ('week' == $request->input('visual_type')) {
                $start = $date->copy()->startOfWeek()->format('Y-m-d');
                $end = $date->copy()->endOfWeek()->addDay()->format('Y-m-d');

                $startd = $date->copy()->startOfWeek()->format('d');
                $endd = $date->copy()->endOfWeek()->format('d');

                $item->start = $start;
                $item->end = $end;
                $item->event_title = Str::limit(Str::ucfirst($date->copy()->startOfWeek()->monthName), 3, '') . ' ' . $startd . ' a ' . Str::limit(Str::ucfirst($date->copy()->endOfWeek()->monthName), 3, '') . ' ' . $endd;
            } else {
                $start = $date->copy()->format('Y-m-d');
                $end = $date->copy()->format('Y-m-d');

                $startd = $date->copy()->format('d');

                $item->start = $start;
                $item->end = $end;
                $item->event_title = Str::limit(Str::ucfirst($date->copy()->startOfWeek()->monthName), 3, '') . ' ' . $startd;

                // buscar notas internas del servicio para poner en la description
                $description = '';
                $intNotes = $item->observations->where('private', 0)->sortByDesc('created_at');
                if (!is_null($intNotes) && count($intNotes) > 0) {
                    $description = '[NOTAS INTERNAS]: ';
                    foreach ($intNotes as $note) {
                        $description .= $note->observation;
                        $description .= $note->feedback ? ' [FED]: ' . $note->feedback . ' // ' : ' // ';
                    }
                }
                if (!is_null($intNotes) && $item->observation && count($intNotes) > 0) {
                    $description .= '&#13;&#10;&#13;&#10;';
                }

                if ($item->observation) {
                    $description .= "[NOTA DEL CRONOGRAMA]: {$item->observation}";
                }

                $item->fullObs = $description;
            }

            $controlsProccess = $item->controls->map(function ($control) {
                $control->color_text = Contrast::generate(isset($control->product) && isset($control->product) ? $control->product->color : '#fff');

                return $control;
            });

            $item->controls = $controlsProccess;

            if ($item->workschedule && $item->workschedule->user) {
                $item->workschedule->user->makeHidden(['full_roles','full_headquarter', 'full_zones', 'full_customers']);
            }

            foreach ($item->schedulings as &$scheduling) {
                foreach ($scheduling->routes as &$route) {
                    if ($route && $route->user) {
                        $route->user->makeHidden(['full_roles','full_headquarter', 'full_zones', 'full_customers']);
                    }
                }
            }

            return $item;
        });

        return [$sheduleProcess, $startDate, $endDate, $office, $startDateFormat, $endDateFormat];
    }

    public function listScheduleShow(Request $request, Crm $crm)
    {
        list($schedules, $startDate, $endDate, $office) = $this->_getSchedules($request, $crm);
        $list = view('crm.sub.listSchedule', compact(['schedules', 'crm', 'startDate', 'endDate', 'office']))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function calendarScheduleShow(Request $request, Crm $crm)
    {
        list($schedules, $startDate, $endDate, $office) = $this->_getSchedulesCalendar($request, $crm);
        // $list = view('crm.sub.listSchedule',compact(['schedules','crm', 'startDate', 'endDate','office']))->render();
        return response(['success' => true, 'schedules' => $schedules, 'isCustomer' => Auth::user()->roles->pluck('slug')->contains('customer')]);
    }

    public function scheduleSendForm(Request $request, Crm $crm)
    {
        list($schedules, $startDate, $endDate, $office, $startDateFormat, $endDateFormat) = $this->_getSchedules($request, $crm);
        $contact = $office->contacts->last();
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
        $list = view('crm.forms.sendScheduleForm', compact(['schedules', 'crm', 'startDate', 'endDate', 'office', 'startDateFormat', 'endDateFormat', 'contact', 'greeting']))->render();

        return response(['success' => true, 'view' => $list, 'destinatary' => $contact->email]);
    }

    public function scheduleForm(Request $request, Crm $crm)
    {
        $fromQuote = false;
        $key = Str::random(10);
        $office = Office::find($request->input('office_id'));
        $tempServices = $request->session()->get('temp_services');
        $isEdit = false;

        try {
            $tempServices = $tempServices[$crm->id][$request->input('office_id')];
        } catch (\Exception $ex) {
            $tempServices = null;
        }

        $ttss = null;

        if ($tempServices) {
            foreach ($tempServices  as $key => $servicetemp) {
                $service = null;
                if ($servicetemp) {
                    $serviceD = Service::find($servicetemp['service']);
                }

                if ($serviceD) {
                    $service = new CrmSchedule();
                    $service->id = hexdec(uniqid());
                    $service->key = $key;
                    $service->service_id = $serviceD->id;
                    $service->service = $serviceD;
                    $service->controls = [];
                    $service->coverage_id = null;
                    $service->coverage = null;
                    $service->price = null;
                    $service->periodicity_id = null;
                    $service->periodicity = null;
                    $service->visits = 1;
                    $service->weeks = null;
                    $service->from_session = true;
                    $service->num = $servicetemp['num'];

                    $params = Office::with(['services' => function ($services) use ($serviceD) {
                        $services->where('services.id', $serviceD->id);
                    }])->whereHas('services', function ($services) use ($serviceD) {
                        $services->where('services.id', $serviceD->id);
                    })->orderBy('id', 'desc')->find($office->id);

                    $service->params = !is_null($params) ? $params->services->last()->pivot : null;

                    $ttss[] = $service;
                }
            }
        }

        $services = collect($ttss);

        $timeUnits = TimeUnit::all();
        $schedules = CrmSchedule::with('service', 'controls.infestation', 'controls.periodicity', 'controls.timeUnit', 'controls.products.product', 'controls.products.area', 'controls.products.application', 'status')->whereNull('date');
        $view = view('crm.forms.scheduleForm', compact(['schedules', 'crm', 'office', 'key', 'services', 'timeUnits', 'fromQuote', 'isEdit']))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function newReforce(Request $request, $crm, $date, Office $office, $service)
    {
        $workschedule = new Workschedule();

        $fromQuote = false;
        $isEdit = false;
        $quoteId = null;
        $id = $crm;

        $crms = Crm::with(['crmCategory'])->headquarterOffice()->find($crm);
        $crm = $crms;

        if (!$office) {
            abort('406', 'La sede del cliente en la cual se realizó el cronograma ya no se encuentra disponible o no tienes los permisos necesarios para consultarla.');
        }
        if (!$crm) {
            abort('406', 'El cliente del cronograma a realizar no se encuentra disponible o no tienes los permisos necesarios para consultarlo');
        }

        // traer y emular la información del formulario
        $quote = null;
        $key = Str::random(10);

        $tempServices = null;
        $tempServices[] = ['service' => $service, 'num' => 1];

        $ttss = null;

        foreach ($workschedule->services->unique(function ($item) {
            return $item->service_id . json_encode($item->week_data);
        })  as $index => $quoteService) {
            $service = new CrmSchedule();
            $service->key = hexdec(uniqid());
            $service->id = $quoteService->id;
            $service->service_id = $quoteService->service->id;
            $service->service = $quoteService->service;
            $service->controls = $quoteService->infestations;
            $service->coverage_id = $quoteService->coverage_id;
            $service->coverage = DetailService::find($quoteService->coverage_id);
            $service->price = $quoteService->price;
            $service->periodicity_id = $quoteService->periodicity_id;
            $service->periodicity = Periodicity::find($quoteService->periodicity_id);
            $service->visits = $quoteService->visits;
            $service->current_weeks = $this->getWeeksOfPeriodicityPrivate($quoteService->periodicity_id, Carbon::parse($quoteService->week_start));
            $service->from_session = false;
            $service->periodicity_start = $quoteService->periodicity_start;
            $service->periodicity_end = $quoteService->periodicity_end;
            $service->week_start = $quoteService->week_start;
            $service->weeks = $quoteService->weeks;
            $service->week_data = $quoteService->week_data;
            $service->day = $quoteService->start_day;
            $service->time_value = $quoteService->time_value;
            $service->time_unit = $quoteService->time_unit_id;
            $service->num = $quoteService->num;
            $service->enable_fix_weeks = $quoteService->enable_fix_weeks;
            $service->start_hour = $quoteService->start_hour;
            $service->init_status = !is_null($quoteService->status) ? $quoteService->status : null;
            $service->technical_quantity = $quoteService->technical_quantity;
            $service->users = !is_null($quoteService->technicals) ? $quoteService->technicals : null;
            $ttss[] = $service;
        }

        if ($tempServices) {
            foreach ($tempServices  as $key => $servicetemp) {
                $service = null;
                if ($servicetemp) {
                    $serviceD = Service::find($servicetemp['service']);
                }

                if ($serviceD) {
                    $service = new CrmSchedule();
                    $service->id = hexdec(uniqid());
                    $service->key = $key;
                    $service->service_id = $serviceD->id;
                    $service->service = $serviceD;
                    $service->controls = [];
                    $service->coverage_id = null;
                    $service->coverage = null;
                    $service->price = null;
                    $service->periodicity_id = null;
                    $service->current_weeks = $this->getWeeksOfPeriodicityPrivate(13, Carbon::parse($date));
                    $service->periodicity = null;
                    $service->visits = 1;
                    $service->weeks = null;
                    $service->from_session = true;
                    $service->num = $servicetemp['num'];

                    $ttss[] = $service;
                }
            }
        }

        $services = collect($ttss);

        $timeUnits = TimeUnit::all();
        $schedules = CrmSchedule::with('service', 'controls.infestation', 'controls.periodicity', 'controls.timeUnit', 'controls.products.product', 'controls.products.area', 'controls.products.application', 'status')->whereNull('date');

        $view = view('crm.forms.scheduleForm', compact(['schedules', 'crm', 'office', 'key', 'services', 'timeUnits', 'fromQuote', 'quoteId', 'isEdit', 'workschedule', 'date']))->render();

        return view('crm.show', compact('crms', 'id', 'fromQuote'))->withOpenForm('true')
            ->withAction('create')
            ->withAutocompleteOffice($office)
            ->withClickButton('.btnSendFilter')
            ->withView($view);
    }

    public function ScheduleSend(Request $request, Crm $crm)
    {
        // consultar la información del cronograma
        list($schedules, $startDate, $endDate, $office) = $this->_getSchedulesCalendar($request, $crm, true, true);
        $visual_type = false;
        // generar PDF
        $userFirm = Workschedule::with('user')->where('crm_id', $crm->id)->orderBy('id', 'DESC')->limit(1)->first()->user;
        $view = view('crm.sub.schedulePdf', compact(['schedules', 'crm', 'startDate', 'endDate', 'office', 'visual_type', 'userFirm']))->render();
        // $pdf = PDF::loadView()->setPaper('letter', 'portrait');
        $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');

        // $dompdf->loadHtml($html,'UTF-8');

        // mandar correo
        try {
            Mail::to($request->input('destinatary'))->send(new MailCrmSchedule($request, $pdf));

            return response(['success' => true, 'message' => 'Cronograma enviado correctamente']);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function schedulePdf(Request $request, Crm $crm)
    {
        $visual_type = true;
        $userFirm = null;
        list($schedules, $startDate, $endDate, $office) = $this->_getSchedulesCalendar($request, $crm, true);

        // se obtiene al primer usuario con rol mip, asistente u superadmin que haya realizado un cronograma
        $workschedules = Workschedule::with('user')->where('crm_id', $crm->id)
            ->where('office_id', $office->id)
            ->orderBy('updated_at', 'ASC')
            ->get();

        if (!is_null($workschedules)) {
            $userFirm = $workschedules->pluck('user');
            $userFirm = $userFirm->filter(function ($item) {
                $hasRole = $item->roles->whereIn('slug', ['root', 'mip_assistant', 'mip_coordinator']);

                return $hasRole->count() > 0;
            })->first();
        }

        return view('crm.sub.schedulePdf', compact(['schedules', 'crm', 'startDate', 'endDate', 'office', 'visual_type', 'userFirm']));
        // $pdf = PDF::loadView('crm.sub.schedulePdf',compact(['schedules','crm', 'startDate', 'endDate','office']))->setPaper('letter', 'portrait');
        // return $pdf->stream();
    }

    public function scheduleFullDelete(Request $request, $workschedule)
    {
        try {
            DB::beginTransaction();
            $ws = Workschedule::find($workschedule);
            // cambiar el estado de la cotización relacionada
            if ($ws->quote_id) {
                Quote::find($ws->quote->id)->update(['status_id' => 2]);
            }
            $cs = $ws->services()->get();
            foreach ($cs as $key => $css) {
                $css->controls()->delete();
            }
            $ws->services()->delete();
            $ws->delete();

            DB::commit();
            $success = true;
        } catch (\Exception $ex) {
            DB::rollBack();
            $success = false;
        }

        return response(['success' => $success, 'message' => $success ? 'Control eleminado con éxito' : 'No se eliminó este control, inténtelo nuevamente. ' . $ex->getMessage(), 'trace' => isset($ex) ? $ex->getTrace() : null]);
    }

    public function scheduleDelete(Request $request, $control)
    {
        switch ($request->input('type')) {
            case 'one':
                $success = CrmSchedule::find($control)->delete();

                break;

            case 'all':
                try {
                    DB::beginTransaction();
                    $schedule = CrmSchedule::find($control);

                    // eliminar todos los servicios a partir de este
                    $allShedules = CrmSchedule::where('workschedule_id', $schedule->workschedule_id)
                        ->where('crm_id', $schedule->crm_id)
                        ->where('office_id', $schedule->office_id)
                        ->where('service_id', $schedule->service_id)
                        ->where('date', '>=', $schedule->date)
                        ->where('assigned_week', $schedule->assigned_week)
                        ->where('start_day', $schedule->start_day)
                        ->where('num', $schedule->num)
                        ->where('coverage_id', $schedule->coverage_id)
                        ->where('week_start', $schedule->week_start)
                        ->delete();
                    // ->where('week_data', json_encode($schedule->week_data))

                    DB::commit();
                    $success = true;
                } catch (\Exception $ex) {
                    DB::rollback();
                    $success = false;
                }

                break;

            default:
                // code...
                break;
        }

        return response(['success' => $success, 'message' => $success ? 'Control eleminado con éxito' : 'No se eliminó este control, inténtelo nuevamente.']);
    }

    public function scheduleEdit(Request $request, $crm, $control)
    {
        $schedule = CrmSchedule::with('controls.infestation', 'timeUnit', 'controls.product.unit', 'controls.area', 'controls.application', 'controls.dose', 'status', 'service', 'coverage', 'schedulings')->find($control);
        $timeUnits = TimeUnit::all();
        $view = view('crm/forms/scheduleControlForm', compact(['schedule', 'crm', 'timeUnits']))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function consolidated(Request $request)
    {
        $office = $request->office;
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $start_date = Carbon::parse($request->star_date)->format('Y-m-d');
        $schedules = CrmSchedule::whereDate('date', '>=', $start_date)->WhereDate('date', '<=', $end_date)->where('office_id', $office)->whereHas('status', function ($status) {
            $status->where('slug', 'service-success');
        })->orderBy('date', 'asc')->get();

        if (0 == $schedules->count()) {
            abort(406, 'No existen servicios registrado en este periodo');
        } else {
            $random_name = $request->filled('regenerate') ? '-' . Str::random(4) : '';
            $name = $start_date . '-' . $end_date . '-consolidated-' . $request->input('office') . $random_name;
            $path = storage_path("app/public/crms/{$name}.pdf");
            $path2 = $this->buildConsolidatePath($name);
            $url = URL::signedRoute('consolidated2', $request->only(['office', 'star_date', 'end_date']));
            $issetFile = Storage::disk('public')->exists($path2);
            $urlajax = url('/') . "/storage/app/public/crms/{$name}.pdf";
            if (true === $issetFile) {
                return response(['url' => $urlajax]);
            }

            $command = "google-chrome --headless --disable-gpu --disable-software-rasterizer --no-first-run --no-sandbox --print-to-pdf-no-header --print-to-pdf={$path} \"{$url}\" 2>&1";
            exec($command, $output);

            return response(['url' => $urlajax]);
        }
    }

    public function consolidated2(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $file = null;
        $office = $request->office;
        $end_date = $request->end_date;
        $start_date = $request->star_date;
        $headquarter_office = Office::find($office);
        $headquarter_id = $headquarter_office->headquarter_id ?? null;
        $mechanisms_baits = Qr::where('office_id', $office)->whereHas('extend', function ($extend) use ($end_date) {
            $extend->whereDate('deleted_at', '>', $end_date)->orWhereNull('deleted_at')->withTrashed();
            $extend->whereHasMorph('extendible', ['App\Convection'], function ($extendible) {
                $extendible->whereHas('category', function ($category) {
                    $category->where('slug', 'baits');
                });
            });
            $extend->whereDate('created_at', '<=', $end_date);
        })->get();
        $mechanisms_non_toxic_controls = Qr::where('office_id', $office)->whereHas('extend', function ($extend) use ($end_date) {
            $extend->whereDate('deleted_at', '>', $end_date)->orWhereNull('deleted_at')->withTrashed();
            $extend->whereHasMorph('extendible', ['App\Convection'], function ($extendible) {
                $extendible->whereHas('category', function ($category) {
                    $category->where('slug', 'non-toxic-controls');
                });
            });
            $extend->whereDate('created_at', '<=', $end_date);
        })->orderBy('file_extend_id', 'asc')
            ->get();
        $clock_cages = Qr::where('office_id', $office)->whereHas('extend', function ($extend) use ($end_date) {
            $extend->whereDate('deleted_at', '>', $end_date)->orWhereNull('deleted_at')->withTrashed();
            $extend->whereHasMorph('extendible', ['App\Convection'], function ($extendible) {
                $extendible->whereHas('category', function ($category) {
                    $category->where('slug', 'clock-cages');
                });
            });
            $extend->whereDate('created_at', '<=', $end_date);
        })->orderBy('file_extend_id', 'asc')
            ->count();
        $fly_control_lamps = Qr::where('office_id', $office)->whereHas('extend', function ($extend) use ($end_date) {
            $extend->whereDate('deleted_at', '>', $end_date)->orWhereNull('deleted_at')->withTrashed();
            $extend->whereHasMorph('extendible', ['App\Convection'], function ($extendible) {
                $extendible->whereHas('category', function ($category) {
                    $category->where('slug', 'fly-control-lamps');
                });
            });
            $extend->whereDate('created_at', '<=', $end_date);
        })->orderBy('file_extend_id', 'asc')
            ->get();
        $pennants = Qr::where('office_id', $office)->whereHas('extend', function ($extend) use ($end_date) {
            $extend->whereDate('deleted_at', '>', $end_date)->orWhereNull('deleted_at')->withTrashed();
            $extend->whereHasMorph('extendible', ['App\Convection'], function ($extendible) {
                $extendible->whereHas('category', function ($category) {
                    $category->where('slug', 'pennants');
                });
            });
            $extend->whereDate('created_at', '<=', $end_date);
        })->orderBy('file_extend_id', 'asc')
            ->get();
        $mip_coordinators = User::where('headquarter_id', $headquarter_id)->whereHas('roles', function ($roles) {
            $roles->where('slug', 'mip_coordinator');
        })->get();
        if ($mip_coordinators->count() > 2) {
            $coordinators_mip = $mip_coordinators->random(2);
        } else {
            $coordinators_mip = $mip_coordinators;
        }
        $schedules = CrmSchedule::whereDate('date', '>=', $start_date)->WhereDate('date', '<=', $end_date)->where('office_id', $office)->whereHas('status', function ($status) {
            $status->where('slug', 'service-success');
        })->orderBy('date', 'asc')->get();

        if ($schedules->count() > 0) {
            $name_random = Str::random(5);
            $name = /* $start_date." ".$end_date. */ $name_random . '-consolidated-' . $office;
            $url = url('/') . "/storage/app/public/crms/{$name}.pdf";
            $path = $this->buildConsolidatePath($name);
            $issetFile = Storage::disk('public')->exists($path);
            /* if($issetFile){
                return response(["url"=>$url]);
            } */
            return view('crm.sub.consolidatedReportPdf', compact(['schedules', 'mechanisms_baits', 'start_date', 'end_date', 'mechanisms_non_toxic_controls', 'clock_cages', 'coordinators_mip', 'fly_control_lamps', 'pennants']));
        }
        abort(406, 'No existen servicios registrado en este periodo');
    }

    public function reportSchedule(Request $request, CrmSchedule $schedule)
    {
        // validar si el estado del servicio es finalizado
        if (!in_array($schedule->status_id, [3, 7])) {
            abort('406', 'Este servicio se encuentra en estado "' . $schedule->status->name . '", no es un estado válido para leer/generar este reporte de servicio.');
        }
        $file = null;
        $path = $this->buildSchedulePath($schedule);
        // validar si el archivo ya fue generado en físico
        $issetFile = Storage::disk('public')->exists($path);
        if ($issetFile && !$request->filled('force')) {
            $file = "storage/{$path}";
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $file . '"');
            readfile($file);
        } else {
            $view = view('crm.sub.scheduleReportPdf', compact(['schedule']))->render();
            $pdf = PDF::loadHtml($view)->setPaper('legal', 'portrait');
            $nameFile = $schedule->id;
            // funcion para almacenar el archivo en el storage
            Storage::disk('public')->put($path, PDF::loadHtml($view)->setPaper('legal', 'portrait')->output());

            return $pdf->stream($nameFile);
        }
    }

    public function scheduleNew(Request $request, Crm $crm)
    {
        $id = $request->input('id');
        $date = $request->input('timestamp');

        $workschedules = Workschedule::where('crm_id', $crm->id)->get();

        $schedule = new CrmSchedule();
        $timeUnits = TimeUnit::all();
        $view = view('crm/forms/newscheduleControlForm', compact(['workschedules', 'date', 'schedule', 'crm', 'timeUnits']))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function externalNotes(Request $request, Crm $crm)
    {
        list($schedules, $startDate, $endDate, $office) = $this->_getExternalNotes($request, $crm);
        // $list = view('crm.sub.listSchedule',compact(['schedules','crm', 'startDate', 'endDate','office']))->render();
        return response(['success' => true, 'external_notes' => $schedules, 'isCustomer' => Auth::user()->roles->pluck('slug')->contains('customer')]);
    }

    public function externalNoteForm(Request $request, Crm $crm)
    {
        $id = $request->input('id');
        $date = $request->input('date');
        $type = $request->input('type');
        $office = Office::find($request->input('office'));

        $externalNote = CrmExternalNote::find($id);
        $view = view('crm/forms/externalNoteForm', compact(['date', 'externalNote', 'crm', 'office', 'type']))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function storeExternalNote(Request $request, Crm $crm)
    {
        $crmExternalNote = new CrmExternalNote();
        $success = $this->saveExternalNote($request, $crm, $crmExternalNote);

        return response(['success' => $success, 'message' => $success ? 'Nota Guardada Correctamente' : 'Error guardando la nota: ' . $this->msgError, 'trace' => $this->traceError]);
    }

    public function updateExternalNote(Request $request, Crm $crm)
    {
        $crmExternalNote = CrmExternalNote::find($request->input('id'));
        $success = $this->saveExternalNote($request, $crm, $crmExternalNote);

        return response(['success' => $success, 'message' => $success ? 'Nota Actualizadas Correctamente' : 'Error actualizando la nota: ' . $this->msgError, 'trace' => $this->traceError]);
    }

    public function deleteExternalNote(Request $request, Crm $crm, $id)
    {
        $success = CrmExternalNote::find($id)->delete();

        return response(['success' => $success, 'message' => $success ? 'Nota eliminada correctamente' : 'Error eliminando la nota']);
    }

    public function getGeneralObservation(Request $request, Crm $crm, Office $office)
    {
        return response(['success' => true, 'data' => $office->general_observation]);
    }

    public function saveGeneralObservation(Request $request, Crm $crm, Office $office)
    {
        $office->general_observation = $request->input('observation');
        $success = $office->save();

        return response(['success' => $success]);
    }

    public function workschedules(Request $request, $crm, $office)
    {
        $query = Workschedule::where('crm_id', $crm);

        if ($request->input('term')) {
            $query = Workschedule::selectRaw('*')->with(['services.service'])->where('crm_id', $crm)->where('office_id', $office)->get()->filter(function ($item) use ($request) {
                // replace stristr with your choice of matching function
                return false !== stripos($item->text, $request->input('term'));
            })->values()->all();
        } else {
            $query = Workschedule::selectRaw('*')->with(['services.service'])->where('crm_id', $crm)->where('office_id', $office)->get();
        }

        $query = $query->map(function ($item) {
            // $item->service->name .= ' '. $item->num ?? '';
            $tempS = $item->services->unique(function ($item) {
                return $item->service_id;
            });
            $item->_services = $tempS;

            return $item;
        });

        $query->makeHidden(['service_data', 'json', 'services']);

        return response(['results' => $query]);
    }

    public function getFrameFromService(Request $request, $crmSchedule)
    {
        // $service = Service::find($service);

        $schedule = CrmSchedule::with('controls.infestation', 'timeUnit', 'controls.product.unit', 'controls.area', 'controls.application', 'controls.dose', 'status', 'service', 'coverage')->find($crmSchedule);
        $schedule->datefc = \Jenssegers\Date\Date::parse($request->input('date'));
        $schedule->observation = '';
        $timeUnits = TimeUnit::all();
        $view = view('crm/forms/frames/new_schedule_control', compact(['schedule', 'timeUnits']))->render();

        return response(['success' => true, 'view' => $view]);
    }

    public function saveNewSchedule(Request $request, $crm, $office)
    {
        $workschedule = Workschedule::find($request->input('workschedule'));

        // obteber el servicio de referencia
        $crmSchedule = crmSchedule::find($request->input('service'));

        try {
            DB::beginTransaction();
            // guardar un nuevo servicio
            $date = Carbon::createFromFormat('d/m/Y', $request->input('date_submit'));
            // $date->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
            $schedule = $workschedule->services()->create(
                [
                    'service_id' => $crmSchedule->service_id,
                    'coverage_id' => $crmSchedule->coverage_id,
                    'coverage_text' => $crmSchedule->coverage_text,
                    'price' => $crmSchedule->price,
                    'periodicity_id' => $crmSchedule->periodicity_id,
                    'periodicity_start' => $crmSchedule->periodicity_start,
                    'periodicity_end' => $crmSchedule->periodicity_end,
                    'week_start' => $crmSchedule->week_start,
                    'visits' => 1,
                    'weeks' => $crmSchedule->weeks,
                    'week_data' => ["{$date->weekOfMonth}" => [
                        'end' => $date->copy()->endOfWeek()->format('Y-m-d'),
                        'start' => $date->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'),
                        'control' => $request->input('control'),
                    ]],
                    'date' => $date->format('Y-m-d'),
                    'time_value' => $request->input('time_value'),
                    'time_unit_id' => $request->input('time_unit_id'),
                    'start_day' => $date->dayOfWeek - 1,
                    'status_id' => CrmScheduleStatus::where('slug', 'init')->first()->id,
                    'quote_id' => $crmSchedule->quote_id,
                    'crm_id' => $crmSchedule->crm_id,
                    'office_id' => $crmSchedule->office_id,
                    'assigned_week' => $date->weekOfMonth,
                    'observation' => $request->input('observation'),
                ]
            );

            // sincronizar controles
            $this->_synProductsControlForm($request, $schedule);

            DB::commit();

            return response(['success' => true, 'message' => 'Cambios guardados correctamente.']);
        } catch (\Exception $exc) {
            DB::rollback();

            return response(['success' => false, 'message' => $this->msgError . ': ' . $exc->getMessage(), 'trace' => $exc->getTrace]);
        }
    }

    public function addSupplyRowFormControl(Request $request)
    {
        $key = uniqid();
        $control = null;
        $quoteServiceId = $request->input('serviceId');
        $serviceId = $request->input('serviceId');
        $scheduleId = $request->input('infestationId');

        $row = view('crm.forms.frames.supply_edit_new_row', compact(['key', 'control', 'quoteServiceId', 'serviceId', 'scheduleId']))->render();

        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    public function updateDate(Request $request)
    {
        $id = $request->input('id');
        $schedule = CrmSchedule::find($id);

        try {
            DB::beginTransaction();
            if ($schedule) {
                $start = Carbon::createFromTimestamp($request->input('start'))->addDay()->format('Y-m-d');
                $schedule->date = $start;
                // $schedule->status_id    = CrmScheduleStatus::where('slug','programed')->first()->id;
            }

            $success = $schedule->save();

            DB::commit();

            return response(['success' => $success, 'message' => $success ? 'Cambios guardados correctamente.' : $this->msgError]);
        } catch (\Exception $exc) {
            DB::rollback();

            return response(['success' => false, 'message' => $this->msgError]);
        }
    }

    public function updateNote(Request $request)
    {
        $id = $request->input('id');
        $note = CrmExternalNote::find($id);

        try {
            DB::beginTransaction();
            if ($note) {
                $start = Carbon::createFromTimestamp($request->input('start'))->addDay()->format('Y-m-d');
                $note->date = $start;
                // $schedule->status_id    = CrmScheduleStatus::where('slug','programed')->first()->id;
            }

            $success = $note->save();

            DB::commit();

            return response(['success' => $success, 'message' => $success ? 'Cambios guardados correctamente.' : $this->msgError]);
        } catch (\Exception $exc) {
            DB::rollback();

            return response(['success' => false, 'message' => $this->msgError]);
        }
    }

    public function scheduleControlSave(Request $request, $crm)
    {
        try {
            DB::beginTransaction();
            $control = CrmSchedule::find($request->input('id'));
            $control->start_hour = $request->input('start_hour');
            $control->technical_quantity = $request->input('technical_quantity');
            $control->technicals = $request->input('user');
            $control->observation = $request->input('observation') ?? null;
            $control->note_programmer = $request->input('note_programmer') ?? null;
            if ($request->filled('init_status')) {
                $control->status_id = $request->input('init_status');
            }
            if ($request->filled('time_value')) {
                $control->time_value = $request->input('time_value');
            }
            if ($request->filled('time_unit_id')) {
                $control->time_unit_id = $request->input('time_unit_id');
            }
            if ($request->filled('date_submit')) {
                $control->date = Carbon::createFromFormat('d/m/Y', $request->input('date_submit'));
            }
            $control->save();
            $this->_synProductsControlForm($request, $control);

            // actualiza hora del servicio de la ruta
            if ($control->schedulings()->whereIn('completed', [1])->get()->count() > 0) {
                $sc = $control->schedulings()->get()->last();

                $start = Carbon::createFromFormat('d/m/Y H:i', $request->input('date_submit') . ' ' . $request->input('start_hour'));
                $end = $start->copy()->addSeconds($control->duration);

                if ($request->filled('init_status')) {
                    switch ($request->input('init_status')) {
                        case '1':
                        case '2':
                        case '8':
                            $completed = 0;

                            break;

                        case '3':
                        case '7':
                            $completed = 1;

                            break;

                        case '4':
                            $completed = 3;

                            break;

                        case '5':
                            $completed = 2;

                            break;

                        default:
                            $completed = false;
                    }
                    if (false !== $completed) {
                        $sc->update([
                            'completed' => $completed,
                        ]);
                    }
                }

                $sc->update([
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                ]);

                // obtener la automatización de las rutas y actualizar el pdf
                $routes = Route::whereIn('user_id', $request->input('user'))->where('date', $start->format('Y-m-d'))->get();
                foreach ($routes as $route) {
                    $this->addToRoute($route, $sc);
                }
            }

            DB::commit();

            return response(['success' => true, 'message' => 'Servicio actualizado correctamente']);
        } catch (\Exception $ex) {
            DB::rollback();

            return response(['success' => false, 'message' => 'Error actualizando el servicio: ' . $ex->getMessage()]);
        }
    }

    public function addControlFrame(Request $request)
    {
        $infestation = (object) ['infestation' => [], 'areas' => [], 'periodicity' => []];
        $service = Service::find($request->input('service'));
        $infestation->infestation = Infestation::find($request->input('control'));
        $key = $request->input('control');
        $suplyNewRowFrameKey = uniqid();
        $timeUnits = TimeUnit::all();
        $html = view('crm.forms.frames.control_supply', compact(['service', 'infestation', 'timeUnits', 'suplyNewRowFrameKey']))->render();

        return response(['success' => true, 'html' => $html, 'key' => $key, 'suplyNewRowFrameKey' => $suplyNewRowFrameKey]);
    }

    public function getWeeksOfPeriodicity(Request $request)
    {
        // obtener las semanas de la periodicidad seleccionada
        $periodicity = Periodicity::find($request->input('periodicity'));
        if ($periodicity) {
            $tempWeeks = [];
            $weeksNumber = Periodicity::find($request->input('periodicity'))->weeks;
            $date = Carbon::createFromFormat('d/m/Y', $request->input('start_date'));
            for ($i = 0; $i < $weeksNumber; ++$i) {
                if ($i > 0) {
                    $date->addWeeks(1);
                }
                $carbonWeek = $date->copy();
                $startWeek = $carbonWeek->copy()->startOfWeek(Carbon::MONDAY);
                $endWeek = $carbonWeek->copy()->endOfWeek(Carbon::SUNDAY);
                $tempWeeks[] = (object) [
                    'id' => ($i + 1),
                    'uuid' => uniqid(),
                    'text' => 'Semana ' . ($i + 1) . ' (del ' . $startWeek->format('d/m/Y') . ' al ' . $endWeek->format('d/m/Y') . ')',
                    'week' => ($i + 1),
                    'start' => $startWeek->format('d/m/Y'),
                    'end' => $endWeek->format('d/m/Y'),
                    'parent' => $periodicity,
                    'observations' => '',
                ];
            }
            $data = collect($tempWeeks);
        } else {
            $data = [];
        }

        return response(['results' => $data]);
    }

    public function doses(Request $request)
    {
        if ($request->input('term')) {
            $query = Dose::selectRaw('*')->enabled()->get()->filter(function ($item) use ($request) {
                // replace stristr with your choice of matching function
                return false !== stripos($item->text, $request->input('term'));
            })->values()->all();
        } else {
            $query = Dose::selectRaw('*')->enabled()->get();
        }

        return response(['results' => $query]);
    }

    public function applications(Request $request)
    {
        $query = Application::select(['id', 'name as text']);

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function addWeekFrame(Request $request)
    {
        $isEdit = true;
        $infestation = (object) ['infestation' => [], 'areas' => [], 'periodicity' => []];
        $service = (object) ['id' => $request->input('service'), 'service' => (object) ['name' => $request->input('serviceName')]];
        $infestation->infestation = Infestation::find($request->input('control'));
        $suplyNewRowFrameKey = uniqid();
        $timeUnits = TimeUnit::all();
        $week = (object) $request->input('week');
        $html = view('crm.forms.frames.week', compact(['week', 'service', 'infestation', 'isEdit', 'timeUnits', 'suplyNewRowFrameKey']))->render();

        return response(['success' => true, 'html' => $html, 'service' => $service, 'week' => $week->id, 'suplyNewRowFrameKey' => $suplyNewRowFrameKey]);
    }

    public function scheduleAreas(Request $request, $crm)
    {
        $query = AreaOffice::select('id', 'name as text')
            ->where('crm_id', $crm);

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function addSupplyRow(Request $request)
    {
        $key = uniqid();
        $supply = null;
        $infestationId = $request->input('service');
        $quoteServiceId = $request->input('service');
        $serviceId = $request->input('service');
        $timeUnits = TimeUnit::all();
        $weekId = $request->input('week');

        $row = view('crm.forms.frames.supply_new_row', compact(['key', 'supply', 'quoteServiceId', 'serviceId', 'infestationId', 'weekId', 'timeUnits']))->render();

        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    public function tempServiceSave(Request $request, Crm $crm)
    {
        try {
            $services = $request->session()->get('temp_services');
            $services[$crm->id][$request->input('office_id')][$request->input('key')] = ['service' => $request->input('service_id'), 'num' => $request->input('num')];
            $request->session()->put('temp_services', $services);
            $success = true;
        } catch (\Exception $ex) {
            $success = false;
            $this->msgError = $ex->getMessage();
        }

        return response(['success' => $success, 'message' => $success ? 'Servicio borrador añadido correctamente' : $this->msgError]);
    }

    public function tempServiceRemove(Request $request, Crm $crm)
    {
        try {
            $services = $request->session()->get('temp_services');
            unset($services[$crm->id][$request->input('office_id')][$request->input('service_id')]);
            $request->session()->put('temp_services', $services);
            $request->session()->forget('key');

            return response(['success' => true, 'message' => 'Servicio eliminado']);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => 'Error cuando se intentaba eliminar el servicio. ' . $ex->getMessage()]);
        }
    }

    public function scheduleSave(Request $request, Crm $crm)
    {
        $this->validateSupplies($request);
        $success = CrmSchedules::buildFromCrmRequest($request, $crm);

        return response(['success' => $success, 'message' => $success ? 'Cronograma generado correctamente' : CrmSchedules::$error, 'trace' => CrmSchedules::$trace]);
    }

    public function planTypes(Request $request)
    {
        $query = FileType::select(['id', 'name'])->whereIn('slug', ['plan-init', 'plan-mec', 'plan-risk', 'plan-route', 'plan']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $typeFilePlan = $query->get();

        return response(['options' => $typeFilePlan->toArray()]);
    }

    public function planSave(Request $request)
    {
        $request->validate(['type_id' => 'required'], [], ['type_id' => 'Tipo de plano']);

        try {
            $area = AreaOffice::find($request->input('area_id'));
            DB::beginTransaction();
            $area->file()->create([
                'name' => 'plano_generado_el_' . date('Y-m-d') . '.png',
                'path' => $this->getFile($request, 'image', 'crms/plans'),
                'type_id' => $request->input('type_id'),
            ]);
            DB::commit();

            return response(['success' => true, 'message' => 'Plano generado correctamente, esta pestaña se cerrará para continuar trabajando en mas planos.']);
        } catch (\Exception $exc) {
            DB::rollBack();

            return response(['success' => false, 'message' => 'Se presentó un error guardando el plano: ' . $exc->getMessage()]);
        }
    }

    public function planUpdate(Request $request)
    {
        $request->validate(['type_id' => 'required'], [], ['type_id' => 'Tipo de plano']);

        $file = File::find($request->input('plan_id'));
        $anotherFile = Storage::disk('public')->exists($file->path);

        try {
            DB::beginTransaction();
            if ($anotherFile) {
                Storage::disk('public')->delete($file->path);
            }
            $file->path = $this->getFile($request, 'image', 'crms/plans');
            $file->save();
            DB::commit();

            return response(['success' => true, 'message' => 'Plano actualizado correctamente, esta pestaña se cerrará para continuar trabajando en mas planos.']);
        } catch (\Exception $exc) {
            DB::rollBack();
            return response(['success' => false, 'message' => 'Se presentó un error guardando el plano: ' . $exc->getMessage(), 'trace' => $exc->getTrace()]);
        }
    }

    public function previewPlan(Request $request)
    {
        $file = \App\Models\Api\File::with(['filetype', 'extends' => function ($q) {
            $q->with('extendible');
        }])->find($request->input('id'));

        if ('plan-mec' == $file->fileType->slug) {
            $elements = \App\Convection::enabled()->whereIn('id', $file->extends->pluck('extendible_id'))->get();
            $type = 'Mecanismos';
        } elseif ('plan-risk' == $file->fileType->slug) {
            $elements = \App\ControlInfestation::enabled()->whereIn('id', $file->extends->pluck('extendible_id'))->get();
            $type = 'Riesgos';
        }

        return view('crm.plan', compact(['file', 'elements', 'type']));
    }

    public function previewPlanRoute(Request $request)
    {
        $file = \App\Models\Api\File::with(['fileType'])->find($request->input('id'));

        if ('plan-route' == $file->fileType->slug) {
            $elements = [];

            // añadir las Convecciones

            $element0 = new \App\Convection();
            $element0->name = 'Inicio de desisectación';
            $element0->description = '';
            $element0->slug = 'ID';
            $element0->avatar = '#68af68';
            array_push($elements, $element0);

            $element1 = new \App\Convection();
            $element1->name = 'Ruta de desinsectación';
            $element1->description = '';
            $element1->slug = 'RD';
            $element1->avatar = 'red';
            array_push($elements, $element1);

            $element2 = new \App\Convection();
            $element2->name = 'Fin desinsectación';
            $element2->description = '';
            $element2->slug = 'FD';
            $element2->avatar = 'blue';
            $type = 'Ruta';
            array_push($elements, $element2);
        } elseif ('plan-risk' == $file->fileType->slug) {
            $elements = null;
            $type = '';
        }

        return view('crm.planRoute', compact(['file', 'elements', 'type']));
    }

    public function drawPlan(Request $request)
    {
        $area = AreaOffice::find($request->input('area'));
        $crm = Crm::find($request->input('crm'));
        $office = Office::find($request->input('office'));
        $plan = File::whereHas('fileType', function ($q) {
            $q->where('slug', ['plan-init']);
        })
            ->where('filable_type', 'App\AreaOffice')
            ->where('filable_id', $area->id)
            ->get();

        if ($plan) {
            $plan = $plan->last();
        }
        $mode = 'create';

        return view('crm.editor', compact(['area', 'crm', 'plan', 'mode', 'office']));
    }

    public function editPlan(Request $request)
    {
        $area = AreaOffice::find($request->input('area'));
        $crm = Crm::find($request->input('crm'));
        $plan = File::find($request->input('plan'));
        $office = Office::find($request->input('office'));
        $mode = 'edit';

        return view('crm.editor', compact(['area', 'crm', 'plan', 'mode', 'office']));
    }

    public function deletePlanForm(Request $request)
    {
        $id = $request->input('id');
        $file = File::find($id);
        $form = view('crm.forms.deleteFormPlan', compact('file'))->render();

        return response(['success' => $file ? true : false, 'view' => $form]);
    }

    public function softDeletePlan(Request $request)
    {
        $id = $request->input('id');
        $success = File::destroy($id);

        return response(['success' => $success ? 'true' : 'false', 'message' => $success ? 'Se ha borrado el plano correctamente' : $this->msgError]);
    }

    public function drawMechanisms(Request $request)
    {
        $area = AreaOffice::find($request->input('area'));
        $crm = Crm::find($request->input('crm'));
        $office = Office::find($request->input('office'));
        $file = \App\Models\Api\File::with(['filetype', 'extends' => function ($q) {
            $q->with('extendible')->whereHasMorph('extendible', [\App\Convection::class]);
        }])->find($request->input('plan'));

        $elements = \App\Convection::enabled()->get();
        $type = 'Mecanismos';

        return view('crm.mechanism', compact(['area', 'crm', 'file', 'elements', 'type', 'office']));
    }

    public function listMechanisms(Request $request){
        
        $list  = FileExtend::with('extendible.category')
        ->where('file_id', $request->input('plan'))
        ->get();

        $crm = Crm::find($request->input('crm'));
        $office = Office::find($request->input('office'));
        $area = AreaOffice::find($request->input('area'));

        return view('crm.list-mechanisms', compact(['list','crm', 'office','area']));
    }

    public function printQrs(Request $request)
    {
        $crm = Crm::find($request->input('crmId'));
        $office = Office::find($request->input('officeId'));
        $ids = json_decode(base64_decode($request->input('ids')));
        $fileExtends = FileExtend::with('extendible')->whereIn( 'id', $ids)->get();
        $customSize = [0,0, 269,85];
        $pdf = PDF::loadView('crm.qr-pdf',compact(['fileExtends','crm','office']))->setPaper($customSize, 'portrait');
        return $pdf->stream();

    }

    public function drawRisks(Request $request)
    {
        $area = AreaOffice::find($request->input('area'));
        $crm = Crm::find($request->input('crm'));
        $office = Office::find($request->input('office'));
        $file = \App\Models\Api\File::with(['filetype', 'extends' => function ($q) {
            $q->with('extendible')->whereHasMorph('extendible', [\App\ControlInfestation::class]);
        }])->find($request->input('plan'));

        $elements = \App\ControlInfestation::get();
        $type = 'Plagas';

        return view('crm.mechanism', compact(['area', 'crm', 'file', 'elements', 'type', 'office']));
    }

    public function reCountAutomatic(Request $request, $fileExtend)
    {
        // dd($request->toArray());
        $file_Extend = FileExtend::withTrashed()->find($fileExtend);
        $category = $file_Extend->extendible->category->id;

        $file_ids = [];
        $areas = AreaOffice::with('files')->where('office_id', $request->office_id)->get();
        foreach ($areas as $area) {
            $file_ids[] = $area->files->where('type_id', 22)->pluck('id');
        }
        $files_ids = Arr::flatten($file_ids);

        $filesExtends = FileExtend::whereIn('file_id', $files_ids)->orderBy('id', 'asc')->get();
        $ids = [];
        $num = 1;

        try {
            DB::beginTransaction();
            foreach ($filesExtends as $file) {
                if ($file->extendible->category_conventions_id == $category) {
                    $ids[] = $file->id;
                    $fileExtend = FileExtend::find($file->id);
                    $fileExtend->update([
                        'num' => $num++,
                    ]);
                }
            }
            DB::commit();
            $filesExtends = FileExtend::whereIn('id', $ids)->get();

            return response(['success' => true, 'message' => 'Reconteo correctamente', 'filesExtends' => $filesExtends]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => 'Se presentó un error guardando el elemento. <br><br>' . $ex->getMessage()]);
        }
    }

    public function saveMechanism(Request $request, \App\Convection $convection)
    {
        $num = $this->reCountSave($convection, $request);

        try {
            DB::beginTransaction();
            $fileProduct = $convection->fileExtend()->create([
                'file_id' => $request->input('file_id'),
                'x' => $request->input('x'),
                'y' => $request->input('y'),
                'num' => $num,
                'size' => $request->input('size'),
            ]);

            DB::commit();

            return response(['success' => true, 'fileExtend' => $fileProduct]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => 'Se presentó un error guardando el elemento. <br><br>' . $ex->getMessage()]);
        }
    }

    public function updateReNumber(Request $request, FileExtend $fileExtend)
    {
        $this->validateReNumber($request);

        try {
            DB::beginTransaction();
            if ('Escalar' == $request->title) {
                $fileExtend->update([
                    'size' => $request->input('size'),
                ]);
            }
            if ('Renumerar(Manual)' == $request->title) {
                $fileExtend->update([
                    'num' => $request->input('number'),
                ]);
            }

            DB::commit();
            $fileExtend = FileExtend::with('extendible')->find($fileExtend->id);

            return response(['success' => true, 'fileExtend' => $fileExtend]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => 'Se presentó un error actualizando el elemento. <br><br>' . $ex->getMessage()]);
        }
    }

    public function updatePositionMechanism(Request $request, FileExtend $fileExtend)
    {
        try {
            DB::beginTransaction();
            $fileExtend->update([
                'x' => $request->input('x'),
                'y' => $request->input('y'),
            ]);

            DB::commit();

            return response(['success' => true, 'id' => $fileExtend->id]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => 'Se presentó un error actualizando el elemento. <br><br>' . $ex->getMessage()]);
        }
    }

    public function record($fileExtend)
    {
        $reports = TechnicalReport::with(['pictures', 'pest', 'type', 'user', 'extend.extendible', 'extend.file.filable'])
            ->where('file_extend_id', $fileExtend)
            ->orderBy('id', 'desc')
            ->get();

        $form = view('scheduling.forms.reportInfo', compact(['reports']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function associateQr(FileExtend $fileExtend)
    {
        $list = view('crm.forms.associateQr', compact('fileExtend'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function replaceMechanism(FileExtend $fileExtend)
    {
        $list = view('crm.forms.replaceMechanism', compact('fileExtend'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function selectMechanims(Request $request)
    {
        $query = \App\Convection::select(DB::raw("CONCAT(slug,' > ', name) as text"), 'id')->orderBy('id', 'desc');

        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%')
                ->orWhere('slug', 'LIKE', '%' . $request->input('term') . '%');
        }
        $query->limit(10);
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function replaceMechanismSave(Request $request, FileExtend $oldFile, \App\Convection $newFile)
    {
        try {
            DB::beginTransaction();
            $fileExtends = $newFile->fileExtend()->create([
                'file_id' => $oldFile->file_id,
                'x' => $oldFile->x,
                'y' => $oldFile->y,
                'num' => $oldFile->num,
                'size' => $oldFile->size,
            ]);

            $newFileExtends = $fileExtends;
            $oldFileExtends = $oldFile;

            if (
                \App\Integration::find(1)->enabled
                && in_array(
                    \App\Headquarter::find(
                        session('headquarter_id')
                    )->id,
                    \App\Integration::find(1)->headquarters
                )
            ) {
                $this->requestNewReplaceElement($newFileExtends);
                $this->requestDeleteElement($oldFileExtends);
            }

            $object = QR::whereNull([
                'file_extend_id', 'scan_date',
                'scan_by',
                'crm_id',
                'office_id',
                'crm_schedule_id',
                'scheduling_id'
            ])
                ->whereHas('qrlot', function (Builder $qrlot) {
                    $qrlot->where('headquarter_id', Auth::user()->headquarter_id);
                });
            if (1 == $request->qr_tecnic) {
                $qr = $object->where('use', 1)->first();
            } else {
                $qr = $object->where('use', 2)->first();
            }
            if (!$qr) {
                return response(['success' => false, 'message' => 'No hay QRs disponibles']);
            }
            if (!$oldFile->qr) {
                return response(['success' => false, 'message' => 'No, se puede remplazar el mecanismo, porque no tiene QR']);
            }
            $qr->file_extend_id = $fileExtends->id;
            $qr->scan_date = now();
            $qr->crm_id = $request->crm;
            $qr->office_id = $request->office;
            $qr->scan_by = auth()->user()->id;
            $qr->save();
            $oldFile->delete();
            DB::commit();

            return response(['success' => true, 'fileExtend' => $fileExtends]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => 'Se presentó un error guardando el elemento. <br><br>' . $ex->getMessage()]);
        }
    }

    public function associateQrUpdate(Request $request)
    {
        $request->validate(
            ['qr' => 'required'],
            [],
            ['qr' => 'QR']
        );
        $office = Office::find($request->qr_office);

        try {
            DB::beginTransaction();
            if ($request->qr_old) {
                $qrOld = Qr::find($request->qr_old);
                $qrOld->file_extend_id = null;
                $qrOld->crm_id = null;
                $qrOld->office_id = null;
                $qrOld->scan_date = null;
                $qrOld->scan_by = null;
                $qrOld->save();
            }
            $qr = Qr::find($request->qr);
            $qr->file_extend_id = $request->file_extend_id;
            $qr->crm_id = $request->input('crm');
            $qr->office_id = $request->input('office');
            $qr->scan_date = now();
            $qr->scan_by = Auth::User()->id;

            $qr->save();
            if (1 != $request->update_qr && $request->is_new) {
                $inventoryService = new InventoryService();
                $user = User::find(Auth::user()->id);
                $parent = $user->warehouse->parent;
                $inWarehouse = Warehouse::where('parent', $parent)->where('warehousable_type', 'App\SharedMechasnims')->first();

                $inventoryService->installTransferMechanism(false, $inWarehouse->id, $user->warehouse->parent, $request->file_extend_id, 1, Auth::user());
            }
            DB::commit();

            return response(['success' => true]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => 'Se presentó un error guardando el elemento. <br><br>' . $ex->getMessage(), 'trace' => $ex->getTrace()]);
        }
        $list = view('crm.forms.associateQr', compact('fileExtend'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function selectQr(Request $request)
    {
        if (1 == $request->tecnico) {
            $data = Qr::select('id', 'token as text')->where('token', $request->term)->where('use', 1)->get();
            if (count($data) > 0) {
                $qr = Qr::select('id', 'token as text')->orderBy('token', 'ASC')
                    ->whereNull([
                        'file_extend_id', 'scan_date',
                        'scan_by',
                        'crm_id',
                        'office_id',
                        'crm_schedule_id',
                        'scheduling_id'
                    ])
                    ->whereHas('qrlot', function (Builder $qrlot) {
                        $qrlot->where('headquarter_id', Auth::user()->headquarter_id);
                    })->where('use', 1)->find($data->first()->id);

                if (!isset($qr)) {
                    return response(['results' => [['id' => '', 'text' => 'Este Qr, ya fue asignado']]]);
                }
            }

            return response(['results' => $data]);
        }
        $query = Qr::select('id', 'token as text')->orderBy('token', 'ASC')
            ->whereNull([
                'file_extend_id', 'scan_date',
                'scan_by',
                'crm_id',
                'office_id',
                'crm_schedule_id',
                'scheduling_id'
            ])
            ->whereHas('qrlot', function (Builder $qrlot) {
                $qrlot->where('headquarter_id', Auth::user()->headquarter_id);
            })->where('use', 2)
            ->limit(15);
        if ($request->input('term')) {
            $query->where('token', 'LIKE', '%' . $request->input('term') . '%');
        }

        $data = $query->get();

        return response(['results' => $data]);
    }

    public function reNumber(Request $request, FileExtend $fileExtend)
    {
        $title = $request->input('title');
        $list = view('crm.forms.reNumberForm', compact(['fileExtend', 'title']))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function updateMechanism(Request $request, \App\FileExtend $extend)
    {
        try {
            DB::beginTransaction();
            $extend->x = $request->input('x');
            $extend->y = $request->input('y');
            $extend->size = $request->input('size');
            $extend->num = $request->input('num');
            $extend->save();
            DB::commit();

            return response(['success' => true]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => 'Se presentó un error guardando el elemento. <br><br>' . $ex->getMessage()]);
        }
    }

    public function saveRisk(Request $request, \App\ControlInfestation $infestation)
    {
        try {
            DB::beginTransaction();
            $fileProduct = $infestation->fileExtend()->create([
                'file_id' => $request->input('file_id'),
                'x' => $request->input('x'),
                'y' => $request->input('y'),
                'size' => $request->input('size'),
            ]);

            DB::commit();

            return response(['success' => true, 'id' => $fileProduct->id]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => 'Se presentó un error guardando el elemento. <br><br>' . $ex->getMessage()]);
        }
    }

    public function deleteElement(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $fileExtends = FileExtend::find($id);
            if (isset($fileExtends->qr) && $request->filled('affectInventory') && 1 == $request->input('affectInventory')) {
                $inventoryService = new InventoryService();
                $user = User::find(Auth::user()->id);
                $parent = $user->warehouse->parent;
                $outWarehouse = Warehouse::where('parent', $parent)->where('warehousable_type', 'App\SharedMechasnims')->first();

                $inventoryService->installTransferMechanism(true, $user->warehouse->parent, $outWarehouse->id, $id, 1, Auth::user());
            }
            $fileExtends->update([
                'delete_by' => Auth::user()->id,
            ]);
            $success = FileExtend::destroy($id);
            DB::commit();

            return response(['success' => true, 'message' => 'Elemento eliminado correctamente', 'id' => $id]);
        } catch (\Exception $ex) {
            return response(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function formConfigBilling(Request $request)
    {
        $data_click = 'save_config_billing';
        $id = $request->input('id');
        $title = 'EDITAR CONFIGURACION DE FACTURACION';
        $crm = Crm::find($id);
        $form = view('crm.forms.configBillingForm', compact(['crm']))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function listConfigBillingShow(Request $request)
    {
        $id = $request->input('id');
        $crm = Crm::find($id);
        $list = view('crm.sub.listConfigBilling', compact(['crm']))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function updateConfigBilling(Request $request)
    {
        $id = $request->input('id');
        $crm = Crm::find($id);
        $crm->periodicity = $request->input('periodicity');
        $crm->billing_cycle = $request->input('billing_cycle');
        $crm->pay_method = $request->input('pay_method');
        $crm->save();

        return response(['success' => 'true', 'message' => $crm ? $this->msgUpdatedConfigBilling : $this->msgError]);
    }

    public function listProcessShow(Request $request)
    {
        $crmTrackings = CrmTracking::selectRaw('DATE_FORMAT(date, \'%d-%m-%Y\') as \'group\', crm_trackings.*')
            ->with(['crmTrackingType', 'commercial'])
            ->where('office_id', $request->input('id'))
            ->orderBy('date', 'desc')->get()->sortByDesc('date')->groupBy('group') // ->sortKeysDesc();
        ;

        $list = view('crm.sub.listProcess', compact('crmTrackings'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function listTicketShow(Request $request)
    {
        $ticket = TicketQuestion::find($request->id);
        $this->readBy($ticket);
        $ticket2 = TicketQuestion::find($request->id);
        $ticket = $ticket2;
        $priorities = PriorityTicket::all();
        $list = view('crm.sub.listTicketShow', compact('ticket', 'priorities'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function StateChange(Request $request)
    {
        $ticket = TicketQuestion::find($request->id);
        $state = 1 == $ticket->solved ? null : 1;
        $ticket->update([
            'solved' => $state,
        ]);

        return response(['success' => true]);
    }

    public function PriorityChange(Request $request)
    {
        $ticket = TicketQuestion::find($request->id);
        $ticket->update([
            'priority_id' => $request->priority_id,
        ]);

        return response(['success' => true]);
    }

    public function formTicket(Request $request)
    {
        $data_click = $request->filled('id') ? 'update_ticket' : 'save_ticket';
        $ticket = $request->filled('id') ? TicketQuestion::find($request->id) : new TicketQuestion();
        $title = $request->filled('id') ? 'EDITAR TICKET' : 'CREAR TICKET';
        $id = $request->input('id');
        $office = $request->input('office');
        $categories = CategoryTicket::orderBy('id', 'asc')->get();
        $priorities = PriorityTicket::orderBy('id', 'asc')->get();

        $form = view('crm.forms.ticketForm', compact(['ticket', 'office', 'categories', 'priorities', 'data_click', 'id']))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function listTicketsShow(Request $request)
    {
        $officeTicket = TicketQuestion::with(['office', 'priority', 'registered', 'category', 'read'])
            ->where('office_id', $request->input('id'))
            ->orderBy('created_at', 'desc')->get();

        $categories = CategoryTicket::orderBy('id', 'asc')->get();

        $open_question_count = TicketQuestion::whereNull('solved')->where('office_id', $request->input('id'))->count();
        $close_question_count = TicketQuestion::whereNotNull('solved')->where('office_id', $request->input('id'))->count();
        // dd($cat_billing_count,$mechanisms_report,$cat_service_count,$cat_quotes_count,$cat_pqrs_count,$cat_programming_count,$cat_others_count,$open_question_count,$close_question_count);
        $list = view('crm.sub.ticketIndex', compact('officeTicket', 'categories'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function listTicketsFilter(Request $request)
    {
        $objet = TicketQuestion::with(['office', 'priority', 'registered', 'category', 'read', 'answers'])->withCount('answers')
            ->where('office_id', $request->input('id'));

        if ($request->filled('solved')) {
            if (1 == $request->solved) {
                $objet->whereNotNull('solved');
            } else {
                $objet->whereNull('solved');
            }
        }
        if ($request->filled('category')) {
            $objet->whereHas('category', function (Builder $query) use ($request) {
                $query->where('slug', $request->category);
            });
        }
        $term = '';
        if ($request->filled('search')) {
            $term = $request->input('search');
            $objet->where('subject', 'like', "%{$term}%");
        }

        if ($request->filled('order')) {
            $objet->orderByRaw($request->order);
        } else {
            $objet->orderBy('created_at', 'desc');
        }
        $officeTicket = $objet->paginate($request->filled('perpage') ? $request->input('perpage') : 10);

        $list = view('crm.sub.listTickets', compact(['term', 'officeTicket']))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function storeTicket(Request $request) // funcion para guardar tickets
    {
        $success = $this->saveFormTicket($request);

        return response(['success' => $success, 'message' => $success ? $this->msgSuccessTicket : $this->msgError]);
    }

    public function validateFormTicket(Request $request)
    {
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
                'file' => 'mimes:jpg,png,jpg,jpeg,webp,pdf,mp4,zip,rar,docx,xlsx|file|max:15000',
            ],
            [],
            $niceNames
        );
    }

    public function saveFormTicket(Request $request)
    {
        $this->validateFormTicket($request);
        $office = Office::find($request->input('office_id'));
        $success = $office->ticketQuestions()->create([
            'subject' => $request->input('subject'),
            'category_id' => $request->input('category_id'),
            'message' => $request->input('message'),
            'priority_id' => $request->input('priority_id'),
            'file' => $this->uploadFileTicket($request, 'file'),
            'registered_by_id' => Auth::user()->id,
            'headquarter_id' => $office->headquarter_id,
            'registered_at' => now(),
        ]);
        if ($success) {
            $this->newTicketsNotification($success);
        }

        return $success;
    }

    public function storeTicketAnswer(Request $request) // funcion para guardar tickets
    {
        $success = $this->saveFormTicketAnswer($request);

        return response(['success' => $success, 'message' => $success ? $this->msgSuccessTicket : $this->msgError]);
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
                'file' => 'mimes:jpg,png,jpg,jpeg,webp,pdf,mp4,zip,rar,docx,xlsx|file|max:15000',
            ],
            [],
            $niceNames
        );
    }

    public function saveFormTicketAnswer(Request $request)
    {
        $success = false;
        $this->validateFormTicketAnswer($request);
        $ticket = TicketQuestion::find($request->input('id'));
        $success = $ticket->answers()->create([
            'message' => $request->input('message'),
            'file' => $this->uploadFileTicket($request, 'file'),
            'registered_by' => Auth::user()->id,
        ]);
        if ($success) {
            $count = Auth::user()->id == $ticket->registered_by_id ? $ticket->count_answers : $ticket->count_answers + 1;
            $ticket->update(['updated_at' => now(), 'count_answers' => $count]);
        }
        if ($success) {
            $this->answerTicketsNotification($ticket, $success);
        }

        return $success;
    }

    public function dowloadFile(Request $request)
    {
        $exist = Storage::exists($request->path);
        if ($exist) {
            try {
                $nameFiles = explode('#', $request->path);
                $nameFile = $nameFiles[1];
                return Storage::download($request->path, $nameFile);
            } catch (\Exception $ex) {
                abort(405, 'No se encontró el archivo que deseas descargar en el disco "'.config('filesystems.default').'" del sistema'); 
            }
            
        }
        abort(405, 'El archivo que deseas consultar ha sido eliminado o ya no se encuentra disponible');
    }

    public function updateTicketShow(Request $request)
    {
        $id = $request->input('id');
        $ticket = CrmTracking::find($id);
        $ticket->title = $request->input('title');
        $ticket->detail = $request->input('detail');
        $ticket->crm_tracking_type_id = $request->input('crm_tracking_type_id');
        $ticket->save();

        return response(['success' => 'true', 'message' => $ticket ? $this->msgUpdatedTicket : $this->msgError]);
    }

    public function formProcess(Request $request)
    {
        $data_click = 'save_event';
        $id = $request->input('id');
        $office_id = $request->input('office');
        $crm_id = $request->input('crm');
        $title = $request->input('id') ? 'EDITAR EVENTO' : 'CREAR EVENTO';
        $crmTracking = CrmTracking::find($id);
        $form = view('crm.forms.eventForm', compact(['crmTracking', 'office_id', 'crm_id']))->render();

        return response(['success' => true, 'view' => $form, 'data_click' => $data_click, 'title' => $title]);
    }

    public function crmTrackingType(Request $request)
    {
        $query = CrmTrackingTypes::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $crmTrackingType = $query->get();

        return response(['results' => $crmTrackingType]);
    }

    public function storeEvent(Request $request)
    {
        $crm = new Crm();
        $success = $this->saveFormEvent($request, $crm);

        return response(['success' => $success, 'message' => $success ? $this->msgSuccessEvent : $this->msgError]);
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
                'detail' => 'required',
                'crm_tracking_type_id' => 'required',
            ],
            [],
            $niceNames
        );
    }

    public function saveFormEvent(Request $request, Crm $crm)
    {
        $success = false;
        $this->validateFormEvent($request, $crm);
        $office = Office::find($request->input('office_id'));

        return $office->officeTracking()->create([
            'office_id' => $request->input('office_id'),
            'crm_id' => $request->input('crm_id'),
            'crm_tracking_type_id' => $request->input('crm_tracking_type_id'),
            'user_id' => Auth::user()->id,
            'date' => Carbon::parse($request->input('date'))->format('Y-m-d ' . date('H:i:s')),
            'title' => $request->input('title'),
            'detail' => $request->input('detail'),
            'headquarter_id' => session('headquarter_id'),
            'system' => 0,
        ]);
    }

    public function updateEventShow(Request $request)
    {
        $this->validateFormEvent($request);
        $id = $request->input('id');
        $event = CrmTracking::find($id);
        $event->title = $request->input('title');
        $event->detail = $request->input('detail');
        $event->crm_tracking_type_id = $request->input('crm_tracking_type_id');
        $event->save();

        return response(['success' => 'true', 'message' => $event ? $this->msgUpdatedEvent : $this->msgError]);
    }

    public function filters()
    {
        $filters = view('crm.forms.filter')->render();

        return response(['success' => true, 'view' => $filters]);
    }

    public function crmTrackingDate(Request $request)
    {
        $query = CrmTracking::selectRaw('id, DATE_FORMAT(date, \'%d-%m-%Y\') as text');
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $crmTrackingDate = $query->get();

        return response(['results' => $crmTrackingDate]);
    }

    public function crmTrackingUser(Request $request)
    {
        $query = User::select(['id', 'name as text'])->headquarter(true);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $crmTrackingUser = $query->get();

        return response(['results' => $crmTrackingUser]);
    }

    public function resultFilter(Request $request)
    {
        $query = CrmTracking::selectRaw('DATE_FORMAT(date, \'%d-%m-%Y\') as \'group\', crm_trackings.*')
            ->with(['crmTrackingType', 'commercial']);

        if ($request->filled('filterDateEvent')) {
            $query->whereBetween('date', [$request->input('filterDateEvent') . ' 00:00:00', $request->input('filterDateEvent') . ' 23:59:59']);
        }

        if ($request->filled('filterTypesEvent')) {
            $query->whereIn('crm_tracking_type_id', $request->input('filterTypesEvent'));
        }

        if ($request->filled('filterUser')) {
            $query->whereIn('user_id', $request->input('filterUser'));
        }

        if ($request->filled('GeneratedSystem')) {
            $query->where('system', $request->input('GeneratedSystem'));
        }

        $crmTrackings = $query->headquarter(true)->orderBy('date', 'desc')->get()->sortBy('date')->groupBy('group')->sortKeysDesc();

        $list = view('crm.sub.listProcess', compact('crmTrackings'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    public function storeUserCustomer(Request $request, Crm $crm)
    {
        $category = $request->input('category');
        $id = $request->input('id');
        $crms = Crm::whereHas('users', function ($q) use ($id) {
            $q->where('crm_id', $id);
        })->find($id);

        if (1 == $category && !$crms) {
            $password = uniqid();
            $crm = Crm::with(['offices.contacts'])->find($id);

            if (0 == $crm->offices->first()->contacts->count()) {
                return response(['success' => true, 'message' => 'Categoria actualizada, No se pudo crear un usuario para el cliente por que no tiene contacto resgistrado']);
            }

            if (is_null($crm->offices->first()->contacts->first()->email)) {
                return response(['success' => true, 'message' => 'Categoria actualizada, No se pudo crear un usuario para el cliente debido a que el contacto principal no tiene un correo electronico registrado']);
            }

            $crm->crm_type_id = $category;
            $crm->save();

            $user = User::firstOrCreate([
                'name' => $crm->name,
                'email' => $crm->offices->first()->contacts->first()->email,
                'password' => bcrypt($crm->document ?? $password),
                'avatar' => 'images/default/user.jpg',
                'headquarter_id' => session('headquarter_id'),
                'enabled' => 1,
            ]);

            $success = $user->save();
            $user->roles()->sync([3]);
            $crm->users()->attach($user->id);

            return response(['success' => $success, 'message' => $success ? $this->msgSuccessUser : $this->msgError]);
        }

        $crm->crm_type_id = $category;

        $success = $crm->save();

        return response(['success' => $success, 'message' => $success ? $this->msgSuccessCategoryCrm : $this->msgError]);
    }

    public function storeAvatarCustomer(Request $request, Crm $crm)
    {
        $name = $request->input('name');
        $id = $request->input('id');
        $crms = Crm::whereHas('users', function ($q) use ($id) {
            $q->where('crm_id', $id);
        })->find($id);

        $user = User::firstOrCreate([
            'avatar' => 'images/default/{name}',
        ]);

        $success = $user->save();

        return response(['success' => $success, 'message' => $success ? $this->msgSuccessUser : $this->msgError]);
    }

    public function quotes(Request $request, $crm, $office)
    {
        $query = Quote::selectRaw('*')
            ->where('crm_id', $crm)
            ->where('status_id', 2)
            ->where('office_id', $office);

        if ($request->input('term')) {
            $query->where('consecutive', 'LIKE', '%' . $request->input('term') . '%');
        }

        $crmTrackingType = $query->get();
        $crmTrackingType = $crmTrackingType->map(function ($item) {
            $item->created_at_ = $item->created_at->format('d/m/Y');
            $item->name = $item->code;

            return $item;
        });

        return response(['results' => $crmTrackingType]);
    }

    public function otherOffices(Request $request, $crm, $office)
    {
        $id = $request->input('id');
        $query = Office::where('crm_id', $crm)->where('id', '!=', $office)->headquarter(true);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();

        return response(['results' => $data]);
    }

    public function observationForm(Request $request, $crm)
    {
        $id = $request->input('id');
        $crmSchedule = CrmSchedule::with(['observations' => function ($o) {
            $o->with('user')->orderBy('created_at', 'desc');
        }])->find($id);
        $form = view('crm.forms.observationForm', compact(['crmSchedule']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    public function deleteObservation(Request $request, $crm, $id)
    {
        try {
            DB::beginTransaction();
            $success = CrmScheduleObservation::find($id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $success = false;
        }

        return response(['success' => $success, 'message' => $success ? 'Observación eliminada correctamente' : $this->msgError, 'trace' => $this->traceError]);
    }

    public function canvas(Request $request)
    {
        $area = AreaOffice::find($request->input('area'));
        $crm = Crm::find($request->input('crm'));
        $file = \App\Models\Api\File::with(['filetype', 'extends' => function ($q) {
            $q->with('extendible')->whereHasMorph('extendible', [\App\Models\Api\Product::class]);
        }])->find($request->input('plan'));

        $elements = \App\Convection::enabled()->get();
        $type = 'Mecanismos';

        return view('crm.canvas', compact(['area', 'crm', 'file', 'elements', 'type']));
    }

    public function expiredNotify(Request $request, Notification $notification)
    {
        return view('crm.expiredNotify', compact(['notification']));
    }

    private function getUsersByRole(Request $request, $role = 'technical')
    {
        $query = User::with('headquarter', 'roles')->select(['*'])->whereHas('roles', function ($roles) use ($role) {
            $roles->where('slug', $role);
        })->headquarter()->enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }

        return $query->get();
    }

    private function makeSyncDataForTechnicals($technicals)
    {
        $data = [];
        foreach ($technicals as $key => $technical) {
            $data[$technical['service']] = [
                'technical_quantity' => $technical['quantity'],
                'time' => $technical['time'] ?? null,
                'technicals' => $technical['user'] ?? null,
                'start' => $technical['start'] ?? null,
                'end' => $technical['end'] ?? null,
                'init_status' => $technical['init_status'] ?? CrmScheduleStatus::where('slug', 'init')->first()->id,
            ];
        }

        return $data;
    }

    private function validateForm(Request $request, Crm $crm)
    {
        $niceNames = [
            'document' => 'CC / NIT',
            'name' => 'Nombre',
            'crm_type_id' => 'Categoria CRM',
            'mean_contact_id' => 'Medio de contacto',
            'officeCustomer.*.city_name' => 'Ciudad',
            'officeCustomer.*.name' => 'Nombre de la sucursal',
            'officeCustomer.*.address' => 'Dirección de la sucursal',
            'contactoCustomer.*.email' => 'Email de contacto',
            'technical' => 'una fila de técnicos x servicios',
            'technical.*.service' => 'Servicio',
            'technical.*.quantity' => 'Cantidad',
            'cdocument' => 'una fila de documentos del cliente',
            'cdocument.*.file' => 'Archivo',
            'cdocument.*.type_id' => 'Tipo',
        ];

        $request->validate(
            [
                'name' => 'required',
                // 'document' => 'required',
                // 'crm_type_id' => 'required',
                // 'mean_contact_id' => 'required',
                'officeCustomer.*.name' => 'required_with:officeCustomer.*.city_name',
                'officeCustomer.*.address' => 'required_with:officeCustomer.*.city_name',
                // 'contactoCustomer.*.email' => 'required|email',
                'technical.*.service' => 'required_with:technical',
                'technical.*.quantity' => 'required_with:technical',
                'cdocument.*.file' => 'required_with:cdocument',
                'cdocument.*.type_id' => 'required_with:cdocument',
            ],
            [],
            $niceNames
        );
    }

    private function saveContactShow($request, $contact)
    {
        $niceNames = [
            'name' => 'Nombre',
            'contact_type_id' => 'Tipo de contacto',
        ];

        $request->validate(
            [
                'name' => 'required',
                'contact_type_id' => 'required',
            ],
            [],
            $niceNames
        );

        try {
            DB::beginTransaction();

            $contact->name = $request->input('name');
            $contact->phone = $request->input('phone');
            $contact->position = $request->input('position');
            $contact->email = $request->input('email');
            $contact->crm_id = $request->input('crm_id');

            $contact->save();

            $contact->types()->sync($request->input('contact_type_id'));
            $contact->offices()->sync($request->input('office_id'));

            // verificar si existe el parámetro de giveAccess y dar acceso
            if ($request->filled('giveAccess')) {
                $crm = Crm::find($request->input('crm_id'));
                $this->giveAccessContactInSave($request, $crm, $contact);
            }

            DB::commit();

            return true;
        } catch (\Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();

            return false;
        }
    }

    private function _giveAccess($crm, $contact)
    {
        try {
            DB::beginTransaction();
            $password = Str::random(8);

            $user = User::withTrashed()->updateOrCreate(
                [
                    'email' => $contact->email,
                ],
                [
                    'name' => $contact->name,
                    'password' => bcrypt($password),
                    'avatar' => 'images/default/user.jpg',
                    'headquarter_id' => session('headquarter_id'),
                    'enabled' => 1,
                ]
            );

            $contact->user_id = $user->id;
            $contact->save();

            $user->restore();
            $user->roles()->sync([3]);
            $crm->users()->attach($user->id);

            // enviar datos de acceso al cliente
            Mail::to($contact->email)->send(new CrmAccess($crm, $contact, $password));

            DB::commit();

            return true;
        } catch (\Exception $ex) {
            $this->msgError = $ex->getMessage();
            DB::rollBack();

            return false;
        }
    }

    private function applyParametersToSchedulings(Request $request)
    {
        foreach ($request->input('technical') as $key => $technical) {
            $technical = new Request($technical);

            // validar si se especificó una hora o un técnico
            if ($technical->filled('time') || $technical->filled('user') || $technical->filled('quantity')) {
                $crmSchedules = CrmSchedule::where('office_id', $request->input('office_id'))
                    ->where('service_id', $technical->input('service'));

                // condicionales acorde a las fechas de validez
                if ($technical->filled('start') && $technical->filled('end')) {
                    $crmSchedules->whereBetween('date', [$technical->input('start'), $technical->input('end')]);
                } elseif ($technical->filled('start')) {
                    $crmSchedules->where('date', '>=', $technical->input('start'));
                } elseif ($technical->filled('end')) {
                    $crmSchedules->where('date', '<=', $technical->input('end'));
                }

                // condicional de estado
                $crmSchedules->whereIn(
                    'status_id',
                    [
                        CrmScheduleStatus::where('slug', 'init')->first()->id,
                        CrmScheduleStatus::where('slug', 'to-be-confirm')->first()->id,
                        CrmScheduleStatus::where('slug', 'programed')->first()->id,
                    ]
                );

                // bindear parametros
                $bind = [];
                if ($technical->filled('time')) {
                    $bind = array_merge($bind, ['start_hour' => $technical->input('time')]);
                }
                if ($technical->filled('quantity')) {
                    $bind = array_merge($bind, ['technical_quantity' => $technical->input('quantity')]);
                }
                if ($technical->filled('user')) {
                    $bind = array_merge($bind, ['technicals' => json_encode($technical->input('user'))]);
                }
                if ($technical->filled('init_status')) {
                    $bind = array_merge($bind, ['status_id' => $technical->input('init_status')]);
                }

                // ejecutar update
                $crmSchedules->update($bind);
            }
        }
    }

    private function _getSchedules(Request $request, Crm $crm)
    {
        $office = Office::with(['contacts' => function ($q) {
            $q->whereHas('types', function ($sq) {
                $sq->where('contact_type_id', 3);
            });
        }])->find($request->input('office_id'));

        $startDate = $request->input('start_date_submit');
        $endDate = $request->input('end_date_submit');

        $sdate = Carbon::parse($startDate);
        $edate = Carbon::parse($endDate);
        $startDateFormat = $sdate->format('d') . ' de ' . Str::ucfirst($sdate->monthName) . ' de ' . $sdate->format('Y');
        $endDateFormat = $edate->format('d') . ' de ' . Str::ucfirst($edate->monthName) . ' de ' . $edate->format('Y');

        $schedules = CrmSchedule::whereHas('workschedule', function ($wk) use ($request, $crm) {
            $wk->where('crm_id', $crm->id)
                ->where('office_id', $request->input('office_id'));
        })->with('controls.infestation', 'timeUnit', 'controls.product.unit', 'controls.area', 'controls.application', 'controls.dose', 'status', 'service', 'coverage', 'workschedule')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'ASC')->get();

        return [$schedules, $startDate, $endDate, $office, $startDateFormat, $endDateFormat];
    }

    private function buildConsolidatePath($office = 'test')
    {
        return 'crms/' . $office . '.pdf';
    }

    private function deleteFileC($name)
    {
        sleep(20);
        unlink(storage_path("app/public/crms/{$name}.pdf"));
    }

    private function buildSchedulePath(CrmSchedule $schedule)
    {
        return 'crms/schedules/' . $schedule->crm_id . '/' . $schedule->office_id . '/' . $schedule->id . '.pdf';
    }

    private function _getExternalNotes(Request $request, Crm $crm, $strtotime = false, $datePrefix = false)
    {
        $office = Office::with(['contacts' => function ($q) {
            $q->whereHas('types', function ($sq) {
                $sq->where('contact_type_id', 3);
            });
        }])->find($request->input('office_id'));

        if ($strtotime) {
            $dateImput = $datePrefix ? '_date_submit' : '';
            $startDate = date('Y-m-d 00:i:s', strtotime($request->input('start' . $dateImput)));
            $endDate = date('Y-m-d 23:59:59', strtotime($request->input('end' . $dateImput)));
        } else {
            $dateImput = $datePrefix ? '_date_submit' : '';
            $startDate = date('Y-m-d 00:i:s', $request->input('start' . $dateImput));
            $endDate = date('Y-m-d 23:59:59', $request->input('end' . $dateImput));
        }

        $sdate = Carbon::parse($startDate);
        $edate = Carbon::parse($endDate);
        $startDateFormat = $sdate->format('d') . ' de ' . Str::ucfirst($sdate->monthName) . ' de ' . $sdate->format('Y');
        $endDateFormat = $edate->format('d') . ' de ' . Str::ucfirst($edate->monthName) . ' de ' . $edate->format('Y');

        $externalNotes = CrmExternalNote::where('crm_id', $crm->id)
            ->where('office_id', $request->input('office_id'))
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'ASC')->get();

        return [$externalNotes, $startDate, $endDate, $office, $startDateFormat, $endDateFormat];
    }

    private function saveExternalNote(Request $request, Crm $crm, CrmExternalNote $crmExternalNote)
    {
        $request->validate(['observation' => 'required'], [], ['observation' => 'observacion']);

        try {
            DB::beginTransaction();
            if (2 == $request->input('type')) {
                $crmExternalNote->number = $request->input('number');
                $crmExternalNote->value = $request->input('value');
                $crmExternalNote->commercial_id = $request->input('commercial');
                $crmExternalNote->invoice_date = $request->input('invoice_date');
                $crmExternalNote->new_service = $request->input('new_service');
            }
            $crmExternalNote->crm_id = $request->input('crm');
            $crmExternalNote->office_id = $request->input('office');
            $crmExternalNote->user_id = Auth::user()->id;
            $crmExternalNote->date = $request->input('date');
            $crmExternalNote->type = $request->input('type');
            $crmExternalNote->observation = $request->input('observation');
            $crmExternalNote->save();
            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->msgError = $th->getMessage();
            $this->traceError = $th->getTrace();

            return false;
        }
    }

    private function addToRoute($riginalroute = null, Scheduling $scheduling)
    {
        $success = false;
        foreach ($scheduling->users as $user) {
            $route = Route::routeUser($user->id, $scheduling->start)->get()->last();

            if ($route) {
                // eliminar el pdf existente
                $route->schedulings()->syncWithOutDetaching($scheduling->id);
                $filename = 'r-' . Carbon::parse($route->date)->format('dmY') . '-' . $route->user->name;
                $path = 'routes/' . $filename . '.pdf';
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
            'schedulings.crmSchedule.controls.application',
        )
            ->find($route->id);

        // almacenar archivo en físico
        $route = $nroute;
        $subject = 'Reporte de ruta ' . Date::parse($nroute->date)->format('l') . '(' . Carbon::parse($nroute->date)->format('d-m-Y') . ') - ' . $nroute->user->name;
        $view = view('scheduling.sub.routePdf', compact(['route', 'subject']))->render();
        $pdf = PDF::loadHtml($view)->setPaper('letter', 'portrait');

        $nfilename = 'r-' . Carbon::parse($nroute->date)->format('dmY') . '-' . $nroute->user->name;
        $npath = 'routes/' . $nfilename . '.pdf';

        Storage::disk('public')->put($npath, $pdf->output());
    }

    private function _synProductsControlForm(Request $request, CrmSchedule $schedule)
    {
        if ($request->filled('control')) {
            foreach ($request->input('control') as $key => $product) {
                $product = new Request($product);

                // verificar si el area es nueva o ya existe
                $area = null;
                if ($product->filled('area')) {
                    if (is_numeric($product->input('area'))) {
                        $area = CrmArea::find($product->input('area'));
                        if (!$area) {
                            $area = CrmArea::firstOrCreate(['name' => $product->input('area'), 'enabled' => 1]);
                        }
                    } else {
                        $area = CrmArea::firstOrCreate(['name' => $product->input('area'), 'enabled' => 1]);
                    }
                }

                // verificar si la aplicacion es nueva o ya existe
                $application = null;
                if ($product->filled('application')) {
                    if (is_numeric($product->input('application'))) {
                        $application = Application::find($product->input('application'));
                        if (!$application) {
                            $application = Application::firstOrCreate(['name' => $product->input('application'), 'enabled' => 1]);
                        }
                    } else {
                        $application = Application::firstOrCreate(['name' => $product->input('application'), 'enabled' => 1]);
                    }
                }

                if ($product->filled('id')) {
                    $result = $schedule->controls()->where('id', $product->input('id'))->update(
                        [
                            'infestation_id' => $product->input('infestation'),
                            'crm_area_id' => !is_null($area) ? $area->id : null,
                            'product_id' => $product->input('product'),
                            'dose_id' => $product->input('dose'),
                            'application_id' => !is_null($application) ? $application->id : null,
                        ]
                    );
                } else {
                    $result = $schedule->controls()->create([
                        'infestation_id' => $product->input('infestation'),
                        'crm_area_id' => !is_null($area) ? $area->id : null,
                        'product_id' => $product->input('product'),
                        'dose_id' => $product->input('dose'),
                        'application_id' => !is_null($application) ? $application->id : null,
                    ]);
                }

                // eliminar los controles que no están en los ids del request
                // es decir los que han sido borrados del formulario
                $schedule->controls()->whereNotIn('id', collect($request->control)->pluck('id'))->delete();
            }
        } else {
            // eliminar los controles
            $schedule->controls()->delete();
        }
    }

    private function getWeeksOfPeriodicityPrivate($periodicity, $startDate)
    {
        // obtener las semanas de la periodicidad seleccionada
        $periodicity = Periodicity::find($periodicity);
        if ($periodicity) {
            $weeksNumber = $periodicity->weeks;
            for ($i = 0; $i < $weeksNumber; ++$i) {
                if ($i > 0) {
                    $startDate->addWeeks(1);
                }
                $carbonWeek = $startDate->copy();
                $startWeek = $carbonWeek->copy()->startOfWeek(Carbon::MONDAY);
                $endWeek = $carbonWeek->copy()->endOfWeek(Carbon::SUNDAY);
                $tempWeeks[] = (object) [
                    'id' => ($i + 1),
                    'uuid' => uniqid(),
                    'text' => 'Semana ' . ($i + 1) . ' (del ' . $startWeek->format('d/m/Y') . ' al ' . $endWeek->format('d/m/Y') . ')',
                    'week' => ($i + 1),
                    'start' => $startWeek->format('d/m/Y'),
                    'end' => $endWeek->format('d/m/Y'),
                    'parent' => $periodicity,
                    'observations' => '',
                ];
            }
            $data = collect($tempWeeks);
        } else {
            $data = [];
        }

        return $data;
    }

    private function validateSupplies(Request $request)
    {
        $niceNames = [
            'services.*.start_hour' => 'Hora fija',
            'services.*.init_status' => 'Estado inicial',
            'services.*.routes' => 'una o mas rutas están seleccionadas',
            'services.*.detail_service_id' => 'cobertura establecida',
            'services.*.price' => 'precio',
            'services.*.periodicity_id' => 'periodicidad',
            'services.*.start_date' => 'inicio periodo',
            'services.*.end_date' => 'fin periodo',
            'services.*.visits' => 'visitas',
            'services.*.weeks' => 'semanas a programar',
            'services.*.time_value' => 'duración',
            'services.*.time_unit' => 'duración',
            'services.*.week.*.control.*.infestation' => 'control',
            'services.*.week.*.control.*.area' => 'area',
            'services.*.week.*.control.*.product' => 'producto',
            'services.*.week.*.control.*.dose' => 'dosis',
            'services.*.week.*.control.*.application' => 'aplicación',
        ];

        $rules = [
            'services.*.detail_service_id' => 'required',
            'services.*.price' => 'required',
            'services.*.periodicity_id' => 'required',
            'services.*.start_date' => 'required',
            'services.*.end_date' => 'required',
            'services.*.visits' => 'required',
            'services.*.weeks' => 'required',
            'services.*.time_value' => 'required',
            'services.*.time_unit' => 'required',
            'services.*.week.*.control.*.infestation' => 'required',
            'services.*.week.*.control.*.area' => 'required',
            'services.*.week.*.control.*.product' => 'required',
            'services.*.week.*.control.*.dose' => 'required',
            'services.*.week.*.control.*.application' => 'required',
            'services.*.start_hour' => 'required_with:services.*.routes',
            'services.*.init_status' => 'required_with:services.*.routes',
        ];

        return $request->validate($rules, [], $niceNames);
    }

    private function reCountSave(\App\Convection $convection, Request $request)
    {
        $category = $convection->category->id ?? null;
        $file_ids = [];
        $areas = AreaOffice::with('files')->where('office_id', $request->office)->get();
        foreach ($areas as $area) {
            $file_ids[] = $area->files->where('type_id', 22)->pluck('id');
        }
        $files_ids = Arr::flatten($file_ids);

        $filesExtends = FileExtend::whereIn('file_id', $files_ids)->where('extendible_type', 'App\Convection')->orderBy('id', 'asc')->get();
        $num = 1;
        foreach ($filesExtends as $file) {
            if ($file->extendible->category_conventions_id == $category) {
                ++$num;
            }
        }

        return $num;
    }

    private function validateReNumber(Request $request)
    {
        if ('Escalar' == $request->title) {
            return true;
        }
        $category = $request->category_id;
        $filesExtends = FileExtend::where('file_id', $request->file_id)->orderBy('id', 'asc')->get();
        foreach ($filesExtends as $file) {
            if ($file->extendible->category_conventions_id == $category) {
                $numbers[] = $file->num;
            }
        }
        $unique = in_array($request->number, $numbers) ? 'url' : '';

        $niceNames = [
            'number' => 'Numero',
        ];
        $request->validate(
            [
                'number' => 'required|min:1|integer|' . $unique,
            ],
            [
                'number.url' => 'Este Numero ya esta registrado',
            ],
            $niceNames
        );
    }

    private function requestAsociateQr(Request $request, QR $qr)
    {
        $user = User::find(Auth::user()->id);
        $parent = $user->warehouse->parent;
        $warehouseShare = Warehouse::where('parent', $parent)->where('warehousable_type', 'App\SharedMechasnims')->first();
        $products = $qr->extend->extendible->products;

        foreach ($products as $product) {
            $detailInventory = optional(optional(optional($user)->warehouse)->parentt->inventory->where('product_id', $product->id)->first())->detailInventory;
            if (!isset($detailInventory)) {
                abort(406, 'Operacion fallida, porque la bodega ' . optional(optional($user)->warehouse)->parentt->name . ', no tiene stock de ' . $product->name);
            }
            $transferProd[] = [
                'product_id' => $product->id,
                'plot' => optional($detailInventory)->plot,
                'code' => $product->code,
                'cost' => $product->cost,
                'quantity' => optional($product->pivot)->quantity,
                'transfer_quantity' => optional($product->pivot)->quantity,
                'facturable' => false,
                'date_expiration' => optional($detailInventory)->date_expiration,
            ];
        }
        $req = [
            'warehouse_id' => $warehouseShare->parent,
            'warehouse_transfer' => $warehouseShare->id,
            'transferInventory' => $transferProd,
            'is_new' => $request->is_new,
            'file_extend_id' => $request->file_extend_id,
        ];
        $newReq = new Request($req);
    }

    private function requestDeleteElement(FileExtend $fileExtend)
    {
        $inventories = $fileExtend->inventories;
        foreach ($inventories as $inventory) {
            $warehouseId = $inventory->warehouse_id;
            $warehouseTransfer = $inventory->warehouse->parent;
            foreach ($inventory->detailInventories as $detail) {
                if (!isset($detail)) {
                    abort(406, 'Operacion fallida, porque la bodega ' . optional(optional($inventory)->warehouse)->parentt->name . ', no tiene stock de ' . $detail->product->name);
                }
                $transferProd[] = [
                    'product_id' => $detail->product_id,
                    'plot' => optional($detail)->plot,
                    'code' => optional($detail)->code,
                    'cost' => optional($detail)->cost,
                    'quantity' => optional($detail)->stock,
                    'transfer_quantity' => optional($detail)->stock,
                    'facturable' => false,
                    'date_expiration' => optional($detail)->date_expiration,
                ];
            }
        }
        if (!isset($warehouseId)) {
            abort(406, 'Este mecanismo no se encuentra, en la bodega de mecanismos prestados');
        }
        $req = [
            'warehouse_id' => $warehouseId,
            'warehouse_transfer' => $warehouseTransfer,
            'transferInventory' => $transferProd,
            'file_extend_id' => $fileExtend->id,
        ];
        $newReq = new Request($req);
        $this->saveTranferInventory($newReq);
    }

    private function requestNewReplaceElement(FileExtend $fileExtend)
    {
        $user = User::find(Auth::user()->id);
        $parent = $user->warehouse->parent;
        $warehouseShare = Warehouse::where('parent', $parent)->where('warehousable_type', 'App\SharedMechasnims')->first();
        $products = $fileExtend->extendible->products;
        foreach ($products as $product) {
            $detailInventory = optional(optional(optional($user)->warehouse)->parentt->inventory->where('product_id', $product->id)->first())->detailInventory;
            if (!isset($detailInventory)) {
                abort(406, 'Operacion fallida, porque la bodega ' . optional(optional($user)->warehouse)->parentt->name . ', no tiene stock de ' . $product->name);
            }
            $transferProd[] = [
                'product_id' => $product->id,
                'plot' => optional($detailInventory)->plot,
                'code' => $product->code,
                'cost' => $product->cost,
                'quantity' => optional($product->pivot)->quantity,
                'transfer_quantity' => optional($product->pivot)->quantity,
                'date_expiration' => optional($detailInventory)->date_expiration,
            ];
        }
        $req = [
            'warehouse_id' => $warehouseShare->parent,
            'warehouse_transfer' => $warehouseShare->id,
            'transferInventory' => $transferProd,
            'is_new' => 1,
            'file_extend_id' => $fileExtend->id,
        ];
        $newReq = new Request($req);

        $this->saveTranferInventory($newReq);
    }

    private function getDocumentFoxconTraWarehouse($id)
    {
        $warehouse = Warehouse::find($id);
        if ('App\\SharedMechasnims' == $warehouse->warehousable_type || 'App\\User' == $warehouse->warehousable_type) {
            $warehouse = $warehouse->parentt;
        }
        $result = optional(optional(optional($warehouse)->configurationFoxcon)->where('document_type_id', 2))->last();
        if (!$result) {
            abort(406, 'Para registrar el Traslado en Foxcon, debe configurar los Traslados de documentos de foxcon, para la bodega > ' . optional($warehouse)->name ?? '');
        }

        return $result->id;
    }

    private function getDocumentFoxconTrasladWarehouse($id)
    {
        $warehouse = Warehouse::find($id);
        if ('App\\SharedMechasnims' == $warehouse->warehousable_type || 'App\\User' == $warehouse->warehousable_type) {
            $warehouse = $warehouse->parentt;
        }
        $result = optional(optional(optional($warehouse)->configurationFoxcon)->where('document_type_id', 2))->last();
        if (!$result) {
            abort(406, 'Para registrar el Traslado en Foxcon, debe configurar los Traslados de documentos de foxcon, para la bodega > ' . optional($warehouse)->name ?? '');
        }

        return $result;
    }

    private function saveTranferInventory(Request $request)
    {
        $transferInventory = TransferInventory::create([
            'date_transfer' => now()->format('Y-m-d H:i:s'),
            'document_type_id' => $this->getDocumentFoxconTraWarehouse($request->input('warehouse_id')),
            'headquarter_id' => session('headquarter_id'),
            'warehouse_id' => $request->warehouse_id,
            'warehouse_transfer' => $request->warehouse_transfer,
            'user_id' => Auth::user()->id,
            'file_extend_id' => $request->file_extend_id,
        ]);

        // esta variable se envia a la funcion deductQuantityInventory para localizar la bodega saliente y desminuir
        $warehouse_transfer_type = Warehouse::find($request->input('warehouse_transfer'));
        if ('App\\SharedMechasnims' === $warehouse_transfer_type->warehousable_type) {
            $this->saveInventaryDirectly($request, $transferInventory);
        } else {
            if ($request->has('transferInventory')) {
                foreach ($request->input('transferInventory') as $indx => $content) {
                    $content = new Request($content);

                    $collection = collect($request->input('transferInventory'));
                    $sum_value = $collection->where('product_id', $content->input('product_id'))->where('plot', $content->input('plot'))->pluck('transfer_quantity')->sum();
                    if ($request->filled('file_extend_id')) {
                        $productInventorysDeduct = Inventory::where('warehouse_id', $request->input('warehouse_id'))
                            ->where('product_id', $content->input('product_id'))
                            ->where('file_extend_id', $request->file_extend_id)
                            ->get();
                    } else {
                        $productInventorysDeduct = Inventory::where('warehouse_id', $request->input('warehouse_id'))
                            ->where('product_id', $content->input('product_id'))
                            ->get();
                    }
                    $product = Product::find($content->product_id);
                    if (0 === count($productInventorysDeduct)) {
                        abort(406, "Stock insuficiente del producto {$product->name}");
                    }
                    foreach ($productInventorysDeduct as $productInventoryDeduct) {
                        $detailsInventori = $productInventoryDeduct->detailInventories->where('plot', $content->input('plot'));
                        foreach ($detailsInventori as $inventorydet) {
                            $result = $inventorydet->stock - $sum_value;
                            if ($result < 0) {
                                abort('406', "La cantidad a tranferir del producto '" . $productInventoryDeduct->product->name . "', NO debe ser mayor al Stock del lote '" . $inventorydet->plot . "'");
                            }
                        }
                    }
                    $date_expiration = Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00');

                    // guardar
                    $transferInventory->detailTranferInventory()->create([
                        'product_id' => $content->input('product_id'),
                        'rental' => $content->input('rental'),
                        'facturable' => $content->input('facturable'),
                        'cost' => $content->cost,
                        'plot' => $content->input('plot'),
                        'foxcon' => $content->input('foxcon'),
                        'date_expiration' => $date_expiration,
                        'quantity' => $content->input('quantity'),
                        'transfer_quantity' => $content->input('transfer_quantity'),
                    ]);
                }
            }
        }
    }

    // PERMITE AGREGAR LA TRANFERENCIA DIRECTAMENTE EN EL INVENTARIO, SIN NECESIDAD DE COMPROBACION DEL USURIO A RECIBIR LA TRANFERENCIA
    private function saveInventaryDirectly(Request $request, TransferInventory $transferInventory)
    {
        $transferInventory->enabled = 0;
        $transferInventory->save();
        if ($request->has('transferInventory')) {
            foreach ($request->input('transferInventory') as $indx => $content) {
                $content = new Request($content);
                $productInventory = null;

                $productInventorys = Inventory::where('warehouse_id', $request->input('warehouse_transfer'))
                    ->where('product_id', $content->input('product_id'))
                    ->where('file_extend_id', $request->input('file_extend_id'))
                    ->get();

                $productInventorysDeduct = Inventory::where('warehouse_id', $request->input('warehouse_id'))
                    ->where('product_id', $content->input('product_id'))
                    ->get();
                $product = Product::find($content->product_id);
                if (isset($request->is_new)) {
                    if (0 === count($productInventorysDeduct)) {
                        abort(406, "Stock insuficiente del producto {$product->name}");
                    }
                    foreach ($productInventorysDeduct as $productInventoryDeduct) {
                        $decreseValue = 0;
                        $decreseValue = $content->input('transfer_quantity');
                        $productInventoryDeduct->stock -= $decreseValue;
                        $productInventoryDeduct->save();
                        $detailsInventori = $productInventoryDeduct->detailInventories->where('plot', $content->input('plot'));
                        foreach ($detailsInventori as $inventorydet) {
                            $stock_old = $inventorydet->stock - $decreseValue;
                            $inventorydet->stock = $stock_old;
                            $inventorydet->save();
                            if ($inventorydet->stock < 0) {
                                abort('406', "La cantidad a tranferir del producto '" . $productInventoryDeduct->product->name . "', NO debe ser mayor al Stock del lote '" . $inventorydet->plot . "'");
                            }
                        }
                    }
                }

                foreach ($productInventorys as $productInventory) {
                    $productInventory->stock += $content->input('transfer_quantity');
                    $productInventory->save();
                }

                if (count($productInventorys) > 0) {
                    $productInventory->detailInventory()->create(
                        [
                            'warehouse_id' => $request->input('warehouse_transfer'),
                            'product_id' => $content->input('product_id'),
                            'plot' => $content->input('plot'),
                            'code' => $content->input('foxcon'),
                            'date_expiration' => Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00'),
                            'stock' => $content->input('transfer_quantity'),
                        ]
                    );
                } else {
                    $inventory = Inventory::create([
                        'headquarter_id' => Auth::user()->headquarter_id,
                        'warehouse_id' => $request->input('warehouse_transfer'),
                        'product_id' => $content->input('product_id'),
                        'stock' => $content->input('transfer_quantity'),
                        'file_extend_id' => $request->input('file_extend_id'),
                    ]);

                    $inventory->detailInventory()->create(
                        [
                            'warehouse_id' => $request->input('warehouse_transfer'),
                            'product_id' => $content->input('product_id'),
                            'plot' => $content->input('plot'),
                            'code' => $content->input('foxcon'),
                            'date_expiration' => Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00'),
                            'stock' => $content->input('transfer_quantity'),
                        ]
                    );
                }
                $this->registerTransference($content, $transferInventory);
            }
        }
        // Validar si la integración con foxcon está activa
        if (
            \App\Integration::find(1)->enabled
            && in_array(
                \App\Headquarter::find(
                    session('headquarter_id')
                )->id,
                \App\Integration::find(1)->headquarters
            )
        ) {
            $this->saveFoxconDocumentsTechnical($request);
        }
    }

    private function registerTransference(Request $content, TransferInventory $transferInventory)
    {
        // guardar
        $transferInventory->detailTranferInventory()->create([
            'product_id' => $content->input('product_id'),
            'rental' => $content->input('rental'),
            'facturable' => $content->input('facturable'),
            'cost' => $content->cost,
            'plot' => $content->input('plot'),
            'foxcon' => $content->input('foxcon'),
            'date_expiration' => Carbon::parse($content->input('date_expiration'))->format('Y-m-d 00:00:00'),
            'quantity' => $content->input('quantity'),
            'transfer_quantity' => $content->input('transfer_quantity'),
        ]);
    }

    private function getNitFoxcon($id)
    {
        $idd = 1 == $id ? 2 : $id;
        $warehouse = Warehouse::find($idd);
        $nit = null;
        if ('App\Headquarter' == $warehouse->warehousable_type) {
            $nit = $warehouse->warehousable->nit;
        }
        if ('App\User' == $warehouse->warehousable_type || 'App\SharedMechasnims' == $warehouse->warehousable_type) {
            $nit = $warehouse->parentt->warehousable->nit;
        }
        if (!$nit) {
            abort(406, "Para registrar la transacción en Foxcon, es necesario configurar el NIT para la sede {$warehouse->name}");
        }

        return $nit;
    }

    private function getProductFoxcon($id)
    {
        $product = Product::find($id);
        $code = $product->code;
        if (!$code) {
            abort(406, "Por favor, configure el codigo Foxcon para el producto {$product->name}");
        }

        return $code;
    }

    private function getUserIdFoxcon($id = null)
    {
        if (isset($id)) {
            $user = User::find($id);
            $id_foxcon = $user->headquarter->foxcon_user_id;
            $name = $user->headquarter->name;
        } else {
            $id_foxcon = Auth::user()->headquarter->foxcon_user_id;
            $name = Auth::user()->headquarter->name;
        }
        if (!$id_foxcon) {
            abort(406, 'Por favor, configure el id usuario foxcon de compras para la sede > ' . $name);
        }

        return $id_foxcon;
    }

    private function getWarehouseFoxcon($id)
    {
        $warehouse = Warehouse::find($id);
        $code_foxcon = $warehouse->code_foxcon;
        if (!$code_foxcon) {
            abort(406, "Por favor, configure el codigo foxcon para bodega {$warehouse->name}");
        }

        return $code_foxcon;
    }

    private function getNumberFoxcon($number)
    {
        $lenght = strlen($number);
        $rest = 6 - $lenght;
        $val = '';
        for ($i = 0; $i < $rest; ++$i) {
            $val .= '0';
        }

        return $val . $number;
    }

    // PERMITE GUARDAR LA TRANSFERENCIA EN LA TABLA DE FOXCON CUANDO SE REALIZA LA TRANFERENCIA DESDE UNA BODEGA A UN TECNICO LA CUAL NO NECESITA DE APROBACION
    private function saveFoxconDocumentsTechnical(Request $request)
    {
        $configFoxcon = $this->getDocumentFoxconTrasladWarehouse($request->warehouse_id);
        $numero = $this->getNumberFoxcon($configFoxcon->consecutive + 1);
        $foxconDocuments = FoxconDocument::create([
            'documento' => $configFoxcon->nomenclature,
            'numero' => $numero,
            'timestamp' => now(),
            'grabacion' => now(),
            'sync' => 0,
            'usuario' => $this->getUserIdFoxcon(),
            'nit' => $this->getNitFoxcon($request->warehouse_id),
        ]);
        $configFoxcon->consecutive = $numero;
        $configFoxcon->save();

        if ($request->has('transferInventory')) {
            foreach ($request->input('transferInventory') as $indx => $content) {
                $content = new Request($content);
                $foxconDocuments->details()->create([
                    'documento' => $configFoxcon->nomenclature,
                    'numero' => $numero,
                    'timestamp' => now(),
                    'producto' => $this->getProductFoxcon($content->product_id),
                    'bodega' => $this->getWarehouseFoxcon($request->warehouse_id),
                    'cantidad' => $content->transfer_quantity,
                    'nit' => $this->getNitFoxcon($request->warehouse_id),
                    'tipoes' => 'S',
                    'plot' => strtoupper($content->input('plot')),
                ]);
                $foxconDocuments->details()->create([
                    'documento' => $configFoxcon->nomenclature,
                    'numero' => $numero,
                    'timestamp' => now(),
                    'producto' => $this->getProductFoxcon($content->product_id),
                    'bodega' => $this->getWarehouseFoxcon($request->warehouse_transfer),
                    'cantidad' => $content->transfer_quantity,
                    'nit' => $this->getNitFoxcon($request->warehouse_transfer),
                    'tipoes' => 'E',
                    'plot' => strtoupper($content->input('plot')),
                ]);
            }
        }
    }

    private function readBy(TicketQuestion $ticket)
    {
        if (Auth::user()->id != $ticket->registered_by_id) {
            $ticket->update([
                'read_by_id' => Auth::user()->id,
                'read_at' => now(),
            ]);
        }
    }

    private function newTicketsNotification($ticket)
    {
        $slug_roles = $ticket->category->roles->pluck('slug');
        if ('quotes' == $ticket->category->slug) {
            $user = $ticket->office->crm->user;
            if ($user) {
                $user->notify(new NewTicket($ticket, $user->email));
            }
        } else {
            $users = User::where('id', '!=', auth()->user()->id)->whereHas('roles', function ($roles) use ($slug_roles) {
                $roles->whereIn('slug', $slug_roles);
            })->headquarter('true')->get();
            foreach ($users as $user) {
                $delay = now()->addSeconds(2);
                $user->notify((new NewTicket($ticket, $user->email))->delay($delay));
            }
        }
    }

    private function answerTicketsNotification($ticket, \App\TicketAnswer $answer = null)
    {
        if (auth()->user()->roles->pluck('slug')->contains('customer')) {
            if ('quotes' == $ticket->category->slug) {
                $user = $ticket->office->crm->user;
                if ($user) {
                    $user->notify(new AnswerTicket($ticket, $user->email, $answer));
                }
            } else {
                $slug_roles = $ticket->category->roles->pluck('slug');
                $users = User::whereHas('roles', function ($roles) use ($slug_roles) {
                    $roles->whereIn('slug', $slug_roles);
                })->where('headquarter_id', $ticket->office->headquarter_id)->get();
                foreach ($users as $user) {
                    $delay = now()->addSeconds(2);
                    $user->notify((new AnswerTicket($ticket, $user->email, $answer))->delay($delay));
                }
            }
        } else {
            $user = $ticket->registered;
            $user->notify(new AnswerTicket($ticket, $user->email, $answer));
        }
    }

    private function uploadFileTicket(Request $request, $name)
    {
        $path = null;
        $file = $request->file($name);
        // obtenemos el nombre del archivo
        if ($request->hasFile($name)) {
            $nameFile = Str::random(20) . '#' . $file->getClientOriginalName();
            $path = Storage::putFileAs('public/tickets', $request->file($name), $nameFile);
        }

        return $path;
    }

    private function getCityByName($city)
    {
        return City::where('name', 'like', '%' . $city . '%')->first()->id;
    }
}
