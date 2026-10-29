<?php

namespace App\Http\Controllers\Administrative;

use App\Billing;
use App\BillingDetail;
use App\BillingState;
use App\Scheduling;
use App\Crm;
use App\CrmSchedule;
use App\CrmScheduleStatus;
use App\Headquarter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use App\General\DateTranslator;

class BillingController extends Controller
{

    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Proforma agregada correctamente";
    public $msgSuccessState = "Estado de la proforma actualizado correctamente";
    public $msgUpdated = "Proforma actualizada satisfactoriamente";
    public $msgDeleted = "Proforma eliminada satisfactoriamente";

    public function listProforma()
    {

        $schedules = CrmSchedule::with(['crm','office','service','controls.area'])
        ->where('status_id',CrmScheduleStatus::where('slug','service-success')->first()->id)
        ->whereHas('workschedule',function($workschedule){
            $workschedule->where('headquarter_id', session('headquarter_id'));
        })
        ->where('enabled',1)
        ->get();
        $list = view('billing.sub.listProforma',compact('schedules'))->render();
        return response(['success' => true, 'view' => $list]);
    }


    public function formProforma(Request $request)
    {
        $id= $request->input('id');
        $bill = Billing::find($id);
        $ids = $request->input('ids');
        $schedules = CrmSchedule::with(['crm','office','service','controls.area'])->find($ids);
        $numberSetting = DB::table('settings')->max('invoice')+1;
        $numberBIlling = DB::table('billings')->max('number_billing')+1;
        if($numberSetting <= $numberBIlling){
            $numberProforma = $numberBIlling;
        }else{
            $numberProforma = $numberSetting;
        }
        $headquearter = Headquarter::with(['warehouse'])->find(session('headquarter_id'));
        $form = view ('billing.forms.proformaForm',compact(['headquearter','numberProforma','schedules','bill']))->render();
        return response (['success'=>true, 'view' => $form]);
    }

    public function customer (Request $request){

        $query = Crm::with('crmCategory')->commercialCrm()->headquarterOffice()->enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $customer = $query->get();
        return response(['results' => $customer]);
    }

    public function list() {
        $billings = Billing::with(['detailBillings','crm'])
        ->orderBy('id', 'desc')
        ->get();
        $list = view('billing.sub.list', compact('billings'))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    public function index()
    {
        return view('billing.index');
    }

    /**Informe ejecutados vs facturados */
    public function evsf()
    {
        return view('billing.evsf');
    }

    public function evsfList(Request $request)
    {
        $startDate =  $request->filled('start') ? $request->input('start') : date('Y-m-01');
        $endDate =     $request->filled('end') ? $request->input('end') : \Carbon\Carbon::now()->format('Y-m-t');

        $crms = Crm::with(['offices'=> function($offices) use ($startDate, $endDate){
            $offices->whereHas('schedules', function($schedules) use ($startDate, $endDate){
                 $schedules->whereBetween('date', [$startDate, $endDate])
                 ->whereIn('status_id', [3,7]);
            })->headquarter()->with(['externalNotes'=> function($en) use ($startDate, $endDate){
                $en->whereBetween('date', [$startDate, $endDate])->where('type',2);
            }]);
        }])
        ->whereHas('schedules', function ($schedules) use ($startDate, $endDate){
            $schedules->whereBetween('date', [$startDate, $endDate])
            ->whereIn('status_id', [3,7]);
        })->whereHas('offices',function($offices) use ($startDate, $endDate){
            $offices->whereHas('schedules', function($schedules) use ($startDate, $endDate) {
                $schedules->whereBetween('date', [$startDate, $endDate])
            ->whereIn('status_id', [3,7]);
            })->headquarter();
        })
        ->orderBy('name','asc')
        ->orderBy('commercial_name','asc')
        ->get();

        $view = view('billing.sub.listEvsf',compact(['crms','startDate']))->render();
        return response(['success' => true, 'view' => $view]);
    }

    public function state(Request $request){
        $query = BillingState::select(['id', 'name as text']);
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $user = $query->get();
        return response(['results' => $user]);
    }



    public function create()
    {
        return view ('billing.create');
    }

    public function setting()
    {
        return view ('billing.show');
    }

    public function filters(Request $request)
    {
        $filters = view('billing.forms.filter')->render();
        return response(['success' => true, 'view' => $filters]);
    }

    public function crms(Request $request)
    {
        $query = Crm::select(['id', 'name as text'])->enabled()->headquarterOffice();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $crm = $query->get();
        return response(['results' => $crm]);
    }

    //AGREGAR UN FILA NUEVA DE DETALLES DE ORDEN DE COMPRA
    public function addRow(Request $request){
        $key       = uniqid();
        $row       = view('billing.forms.frames.billing_new_row',compact(['key']))->render();
        return response(['success' => true, 'row' => $row, 'key' => $key]);
    }

    //FORMULARIO PARA ELIMINAR UN REGISTRO DE DETALLE DE BILLING
    public function deleteRow(Request $request){
        $id        = $request->input('id');
        $billing   = Billing::find($id);
        $form      = view('billing.forms.deleteFormDetail', compact('billing'))->render();

        return response(['success' => $billing? true: false, 'view' => $form]);
    }

    public function storeState(Request $request, Billing $billing, $states){
        $billing = Billing::find($states);
        $billing->billing_state_id = $request->input('state');
        $success=$billing->save();
        

        return response(["success" => $success, "message" => $success ? $this->msgSuccessState : $this->msgError]); 
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $billing =  new Billing();
        $success =  $this->save($request, $billing);

        return response(["success" => 'true', "message" => $success ? $this->msgSuccess : $this->msgError]);
    }


    //TRANSACCION DEL GUARDADO DE COMPRAS
    public function save(Request $request, Billing $billing){
    
        $this->validateForm($request,$billing);

        try {
            DB::beginTransaction();

            $billing->headquarter_id = session('headquarter_id');
            $billing->crm_id = $request->input('crm_id');
            if(!empty($request->input('id')) == false){

                $billing->billing_state_id = 1;
            }
            $billing->number_billing = $request->input('number_proforma');
            $billing->date_billing = $request->input('date_billing');
            $billing->total = $this->clear_format($request->input('total'));
            $billing->save();
            
            if($request->has('billing')){
                foreach ($request->input('billing') as $indx => $content) {
                    $content = new Request($content);

    
                        //actualizar detalle de proforma
                        if($content->filled('id')){
                            $billingDetails = BillingDetail::find($content->input('id'));
                            $billingDetails->name = $content->input('name');
                            $billingDetails->quantity = $content->input('quantity');
                            $billingDetails->valor = $this->clear_format($content['valor']);
                            $billingDetails->save();
                        }else{
                            //guardar
                            $billing->detailBillings()->create([
                                'name'        => $content->input('name'),
                                'quantity'            => $content->input('quantity'),
                                'valor'              => $this->clear_format($content['valor']),
                            ]);
                            
                        }

                        //actualizar estado del servicio
                        if($content->filled('schedule_id')){
                            CrmSchedule::find($content->input('schedule_id'))->update(['status_id'=> CrmScheduleStatus::where('slug','invoiced')->first()->id]);
                        }
                }
            }
                    
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollback();
            return false;
        }

    }

    //VALIDACION DEL GUARDADO DE COMPRAS
    private function validateForm(Request $request){

        $niceNames = [
            'crm_id' => 'Cliente',
            'billing.*.name' => 'Servicio',
            'billing.*.quantity' => 'Cantidad',
            'billing.*.valor' => 'Valor',
        ];
                    
        $request->validate([
            'crm_id' => 'required',
            'billing.*.name' => 'required',
            'billing.*.quantity' => 'required|numeric',
            'billing.*.valor' => 'required',
        ],
        [], $niceNames);
    }

    public function update(Request $request)
    {
        $id        = $request->input('id');
        $billing   = Billing::find($id);
        $success   = $this->save($request, $billing);

    
        return response(["success" => 'true', "message" => $success ? $this->msgUpdated : $this->msgError]);
    }


    public function formatPdf($id)
    {

        $billing = Billing::with(['detailBillings','crm'])
            ->find($id);

        // echo view('quote.sub.formatPdf',compact('quote'))->render();
        // die();

        $pdf = PDF::loadView('billing.sub.formatPdf',compact('billing'))->setPaper('letter', 'portrait');
        return $pdf->stream();
        
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit(Billing $billing)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        //
    }
}
