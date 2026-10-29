<?php

namespace App\Http\Controllers\Administrative;

use App\CategoryTicket;
use App\Crm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Office;
use App\PriorityTicket;
use App\TicketQuestion;
use App\User;
use Illuminate\Database\Eloquent\Builder;

class TicketController extends Controller
{
    public function tickets(Request $request)
    {
        $objet  = TicketQuestion::with(['office','office.crm','priority','registered','category','read','answers'])->withCount('answers')->viewTicket();
        if($request->filled('solved')) {
            if($request->solved == 1){
                $objet->whereNotNull('solved');
            }else{
                $objet->whereNull('solved');
            }
        }
        if($request->filled('read')) {
            if($request->read == 1){
                $objet->whereNotNull('read_by_id');
            }else{
                $objet->whereNull('read_by_id');
            }
        }
        $category='';
        if($request->filled('category')) {
            $category=$request->category;
            $objet->whereHas('category', function (Builder $query) use($category) {
                $query->where('slug', $category);
            });

        }
        if($request->filled('users')){
            $objet->whereIn('registered_by_id',$request->users);
        }
        if($request->filled('date')){
            $objet->whereDate('created_at',$request->date);
        }
        if($request->filled('customer')){
            $offices=Office::where('crm_id',$request->customer)->get();
            $offfices_ids=$offices->pluck('id')->toArray();
            $objet->whereIn('office_id',$offfices_ids);
        }

        if($request->filled('priority')) {
            $priority=$request->priority;
            $objet->whereHas('priority', function (Builder $query) use($priority) {
                $query->where('slug', $priority);
            });

        }

        if($request->filled('order') ) {
            $objet->orderByRaw($request->order)->orderBy('id', 'desc');
        }else{
            $objet->orderBy('created_at', 'desc');
        }
        $tickets=$objet->paginate($request->filled('paginate') ? $request->input('paginate') : 10);
        $categories=CategoryTicket::all();
        $priorities=PriorityTicket::all();
        if($request->ajax()){
            $list = view('tickets.sub.list', compact('tickets','categories','category','priorities'))->render();
            return response(['success' => true, 'view' => $list]);
        }else{
            return view('tickets.index',compact('tickets','categories','category','priorities'));
        }
    }

    public function users(Request $request)
    {
        $query = User::with('headquarter', 'roles')->headquarter()->enabled();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%');
        }
        $data = $query->get();
        return response(['results' => $data]);
    }
    public function customer (Request $request){

        $query = Crm::enabled();
        if ($request->input('term')) {
            $query->where(function($w) use($request){
                $w->where('name', 'LIKE', '%' . $request->input('term') . '%')
                ->orWhere('commercial_name', 'LIKE', '%' . $request->input('term') . '%');
            });
        }
        $customer = $query->limit(30)->orderBy('created_at','desc')->get();
        return response(['results' => $customer]);
    }
}
