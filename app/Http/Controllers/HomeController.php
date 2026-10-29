<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function report()
    {
        return view('reports');
    }

    public function integrations(){
        $integrations = \App\Integration::all();
        return view('integrations.index', compact('integrations'));
    }

    public function integrationSwitch(Request $request){
        $success = \App\Integration::where('id',$request->input('id'))->update(['enabled' => $request->input('enabled')]);
        return response(['success' => $success, "message" => !$success ? 'Error '.($request->input('enabled') == 1 ? 'habilitando' : 'deshabilitando').' la integración': 'Integración '.($request->input('enabled') == 1 ? 'habilitada' : 'deshabilitada').' correctamente']);
    }

    public function integrationSwitchHeadquarter(Request $request){

        $integration = \App\Integration::find($request->input('id'));

        if($request->input('enabled')){

            if($integration->headquarters != null){
                $integration->headquarters = collect($integration->headquarters)->push($request->input('headquarter_id'));
            }else{
                $integration->headquarters = [$request->input('headquarter_id')];
            }
            
        }else{
            if($integration->headquarters != null){
                $integration->headquarters = collect($integration->headquarters)->filter(function (int $value, int $key) use($request) {
                    return $value != $request->input('headquarter_id');
                });
            }else{
                $integration->headquarters = [];
            }
        }

        
        $success = $integration->save();
        return response(['success' => $success, "message" => !$success ? 'Error '.($request->input('enabled') == 1 ? 'habilitando' : 'deshabilitando').' la integración': 'Integración '.($request->input('enabled') == 1 ? 'habilitada' : 'deshabilitada').' correctamente']);
    }

    
}
