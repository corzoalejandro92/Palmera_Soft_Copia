<?php

namespace App\Http\Controllers\Administrative;
use Illuminate\Support\Collection;
use App\Programming;
use App\User;
use App\Service;
use App\Crm;
use App\Quote;
use App\CrmSchedule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->roles->pluck('slug')->contains('customer')){
            $crm = Auth::user()->crms()->first()->id;
            return redirect('crm/'.$crm);
        }else{
            return view('home.index');
        }
        
    }

    public function filter(){
        $form      = view('home.forms.filter')->render();
        return response(['success' => true, 'view' => $form]);
    }

    public function userFilter(Request $request){
        $query = User::select(['id', 'name as text'])->enabled()->headquarter()->whereHas('roles',function($q){
            $q->where('slug','technical');
        });
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $user = $query->get();
        return response(['results' => $user]);
    }

    public function serviceFilter(Request $request){
        $query = Service::select(['id', 'name as text'])->enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $service = $query->get();
        return response(['results' => $service]);
    }

    public function customerFilter(Request $request){
        $query = Crm::select(['id', 'name as text'])->enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $crm = $query->limit(100)->get();
        return response(['results' => $crm]);
    }

    public function programmingFilter(Request $request){
        $query = User::select(['id', 'name as text'])->enabled()->headquarter()->whereHas('roles',function($q){
            $q->where('slug','programming');
        });
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $programming = $query->get();
        return response(['results' => $programming]);
    }

    public function commercial(Request $request)
    {

        $start = $request->filled('start') ? \Carbon\Carbon::createFromFormat('Y-m',$request->input('start'))->startOfMonth(): \Carbon\Carbon::now()->startOfMonth();
        $end  = $request->filled('start') ? \Carbon\Carbon::createFromFormat('Y-m',$request->input('start'))->endOfMonth(): \Carbon\Carbon::now()->endOfMonth();

        $users = User::with(['quotes'])->withCount(['visits', 'visits as commercials_visits' => function ($query2) use($start,$end) {
                $query2->whereYear('created_at',$start->format('Y'))->whereMonth('created_at',$end->format('m'))->whereHas('crmTrackingType', function ($query) {
                    $query->where('slug','commercial_visits');
                });
        },'visits', 'visits as follow_visits' => function ($query2) use($start,$end) {
                $query2->whereYear('created_at',$start->format('Y'))->whereMonth('created_at',$end->format('m'))->whereHas('crmTrackingType', function ($query) {
                    $query->where('slug','visit');
                });
        }])
        ->whereHas('roles', function ($roles) {
            $roles->where('slug', 'indicators_commercial');
        }) 
        ->whereHas('quotes')
        ->headquarter()
        ->withTrashed()
        ->get();

        $form = view('home.sub.listCommercial', compact(['users','start','end']))->render();
        return response(['success' => true, 'view' => $form]);
        }

    public function programming(Request $request){

        $start = $request->filled('start') ? \Carbon\Carbon::createFromFormat('Y-m',$request->input('start'))->startOfMonth(): \Carbon\Carbon::now()->startOfMonth();
        $end  = $request->filled('start') ? \Carbon\Carbon::createFromFormat('Y-m',$request->input('start'))->endOfMonth(): \Carbon\Carbon::now()->endOfMonth();

        $schedulings = CrmSchedule::with('service','status')
        ->whereHas('workschedule', function($w){
            $w->where('headquarter_id', session('headquarter_id'))->whereNull('deleted_at');
        })
        ->whereHas('crm', function($w){
            $w->whereNull('deleted_at');
        })
        ->whereHas('office', function($w){
            $w->whereNull('deleted_at');
        })
        ->where(function($wf) use($start, $end){
            $wf->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
        })->whereNull('deleted_at')
        ->get();

        $services = null;

        if(!is_null($schedulings)){
            $services = $schedulings->groupBy('service_id')->flatMap(function ($items) {
                $count_c = $items->whereIn('status_id',[3,7])->count();
                $count_cc = $items->whereIn('status_id',[5])->count();
                $count_i = $items->whereIn('status_id',[4])->count();
                $count_r = $items->whereIn('status_id',[2])->count();
                $count_sp = $items->whereIn('status_id',[1])->count();
                $count_pc = $items->whereIn('status_id',[8])->count();
                $count_pr = $items->whereIn('status_id',[6])->count();
                $total  = $items->count();

                return $items->map(function ($item) use($count_c, $count_i, $count_r, $count_pr, $count_sp, $count_cc, $count_pc, $total){
                    $item->completed_count = $count_c;
                    $item->incompleted_count = $count_i;
                    $item->cancelled_count = $count_cc;
                    $item->in_route = $count_r;
                    $item->programmed = $count_pr;
                    $item->init = $count_sp;
                    $item->to_be_confirm = $count_pc;
                    $item->total = $total;
                    return $item;
                });

            })->unique('service_id');
        }

        $form      = view('home.sub.listProgramming',compact(['services','start','end']))->render();
        return response(['success' => true, 'view' => $form]);
    }

    public function technical(){
        $form      = view('home.sub.listTechnical')->render();
        return response(['success' => true, 'view' => $form]);
    }

    public function report()
    {
        return view('reports');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function show(Programming $programming)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function edit(Programming $programming)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programming $programming)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function destroy(Programming $programming)
    {
        //
    }
}
