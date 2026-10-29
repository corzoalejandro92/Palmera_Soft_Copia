<?php

namespace App\Http\Controllers\Administrative;

use Carbon\Carbon;
use App\Setting;
use App\Headquarter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class SettingController extends Controller
{


    public $msgError = "No se pudo realizar la acción solicitada";
    public $msgSuccess = "Configuración agregada correctamente";
    public $msgUpdated = "Configuración actualizada satisfctoriamente";
    public $msgDeleted = "Configuración eliminado satisfctoriamente";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('setting.index');
    }

    public function list()
     {
        $setting = Setting::headquarter()->enabled()
        ->get();
        $list = view('setting.sub.list',compact(['setting']))->render();
        
        return response(['success' => true, 'view' => $list]);
    }

    public function form(Request $request){
        $id             = $request->input('id');
        $setting        = Setting::find($id);
        $form           = view('setting.forms.settingForm', compact(['setting']))->render();

        return response(['success' => true, 'view' => $form]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $setting = new Setting;
        $success = $this->save($request, $setting);

        return response(["success" => $success, "message" => $success ? $this->msgSuccess : $this->msgError]);  
    }

    public function save(Request $request, Setting $setting){
        
        
         try {
            DB::beginTransaction();

            $setting->headquarter_id = $request->input('headquarter_id');
            $setting->invoice = $request->input('invoice');
            $setting->quote = $request->input('quote');
            $setting->proof_income = $request->input('proof_income');
            $setting->proof_discharge = $request->input('proof_discharge');
            $setting->save();

                
            DB::commit();
                return true;
        } catch (Exception $exc) {
            DB::rollback();
            $this->msgError = $exc->getMessage();
            return false;
        } 
    }

    public function update(Request $request)
    {
        $id        = $request->input('id');
        $setting   = Setting::find($id);
        $success   = $this->save($request, $setting);


        return response([ "success" =>  $success, "message" => $success ? $this->msgUpdated : $this->msgError]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
