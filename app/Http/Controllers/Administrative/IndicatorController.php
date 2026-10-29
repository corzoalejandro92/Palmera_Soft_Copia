<?php

namespace App\Http\Controllers\Administrative;

use App\Audit;
use App\Crm;
use App\Headquarter;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Objetive;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indicatorsMonth(Request $request)
    {
        $start = $request->filled('start') ? \Carbon\Carbon::createFromFormat('Y-m',$request->input('start'))->startOfMonth(): \Carbon\Carbon::now()->startOfMonth();
        $end  = $request->filled('start') ? \Carbon\Carbon::createFromFormat('Y-m',$request->input('start'))->endOfMonth(): \Carbon\Carbon::now()->endOfMonth();
        
        $new_sales_objetive=Objetive::where('year',$start->format('Y'))->whereHas('type', function ($type) {
            $type->where('slug','new-objetive-month');
        })->first();
        
        
        $users = $query = User::with('sales')->whereHas('roles', function ($roles) {
            $roles->where('slug', 'indicators_commercial_graphics');
        })
        ->enabled()->get();
        if($request->ajax()){
            $list = view('indicatorReport.sub.listMonth', compact(['users','start','end','new_sales_objetive']))->render();
            return response(['success' => true, 'view' => $list]);
        }else{
            return view('indicatorReport.index',compact(['users','start','end','new_sales_objetive']));
        }
    }
    public function indicatorsYear(Request $request)
    {
        $start = $request->filled('start') ? \Carbon\Carbon::createFromFormat('Y-m',$request->input('start'))->startOfMonth(): \Carbon\Carbon::now()->startOfMonth();
        $end  = $request->filled('start') ? \Carbon\Carbon::createFromFormat('Y-m',$request->input('start'))->endOfMonth(): \Carbon\Carbon::now()->endOfMonth();

        $total_sales_objetive=Objetive::where('year',$start->format('Y'))->whereHas('type', function ($type) {
            $type->where('slug','total-objetive-month');
        })->first();
        
        $usersYear = $query = User::with('sales')->whereHas('roles', function ($roles) {
            $roles->where('slug', 'indicators_commercial_graphics');
        })
        ->enabled()->get();

        if($request->ajax()){
            $list = view('indicatorReport.sub.listYear', compact(['start','end','total_sales_objetive','usersYear']))->render();
            return response(['success' => true, 'view' => $list]);
        }else{
            return view('indicatorReport.index',compact(['start','end','total_sales_objetive','usersYear']));
        }
    }

}