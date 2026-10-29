<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserTracking;
use App\SchedulingService;
use App\User;
use Illuminate\Support\Facades\DB;
use Exception;

class UserTrackingController extends Controller
{

	public function index(Request $request)
    {  
    	

    }

     public function create()
    {

    }


    public function store(Request $request)
    {
    	try{
            DB::beginTransaction();
	    	// almacenar las coordenadas enviadas
	    	$user= User::find($request->input('user_id'));
	        $coords = $user->coords()->create([
	            'lat'  => $request->input('lat'),
	            'lng'  => $request->input('lng'),
	            'date' =>  $request->input('date'),
	        ]);

	    	$schedulingService = SchedulingService::find($request->input('id'));
	    	if($schedulingService){
	    		$schedulingService->userTracking()->create([
	    			'user_id' 			 => $request->input('user_id'),
	    			'user_coord_id'      => $coords->id,
	    			'user_tracking_type' => $request->input('user_tracking_type'),
	    			'observation'        => $request->input('observation'),
	    			'date' =>  $request->input('date')
	    		]);
	    	}
	    	DB::commit();
	        return $this->success(true);
        }catch(Exception $ex){
            DB::rollback();
            $this->error($ex->getMessage(),500);
        }
    }


    public function show( $userTracking)
    {
        dd("función Show");
    }


    public function edit( $userTracking)
    {
        //
    }


    public function update(Request $request,  $userTracking)
    {
        //
    }


    public function destroy( $userTracking)
    {
        //
    }
}
