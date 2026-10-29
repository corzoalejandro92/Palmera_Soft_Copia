<?php

namespace App\Http\Controllers\Administrative;

use App\Audit;
use App\Crm;
use App\Headquarter;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function audits(Request $request)
    {
        $headquarter=Auth::user()->headquarter_id;
        if($request->filled('perheadquarter')){
            $headquarter =$request->input('perheadquarter');
        }
        $models=Audit::select('auditable_type')->groupBy('auditable_type')->get();
        $events=Audit::select('event')->groupBy('event')->get();
        if(Auth::user()->roles->pluck('slug')->contains('root'))
            $headquarters=Headquarter::all();
        else{
            $headquarters=Headquarter::where('id',$headquarter)->get();
        }
        $object = Audit::orderBy('created_at', 'desc');

                
                $model=null;
                if($request->filled('permodel')){
                    $model =$request->input('permodel');
                }
                $term = '';
                if($request->filled('search')){
                    $term =$request->input('search');
                    
                            if($model == 'App\User' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('users', function($u) use($term){
                                        $u->where('name', 'LIKE',"%$term%");
                                        $u->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                    });
                                }); 
                            }
                            if($model == 'App\Crm' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('crms', function($crm) use($term){
                                        $crm->where('name', 'LIKE',"%$term%");
                                        $crm->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                    });
                                }); 
                            }
                            if($model == 'App\Headquarter' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('headquarters', function($headquarters) use($term){
                                        $headquarters->where('name', 'LIKE',"%$term%");
                                        $headquarters->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                    });
                                }); 
                            }
                            if($model == 'App\Office' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('offices', function($offices) use($term){
                                        $offices->where('name', 'LIKE',"%$term%");
                                        $offices->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                        $offices->orWhereHas('crm', function($crm) use($term){
                                            $crm->where('name', 'LIKE',"%$term%");
                                            $crm->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                        });
                                    });
                                }); 
                            }
                            if($model == 'App\Availability' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('availabilities', function($availabilities) use($term){
                                        $availabilities->where('name', 'LIKE',"%$term%");
                                        $availabilities->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                    });
                                }); 
                            }
                            if($model == 'App\Route' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('routes', function($routes) use($term){
                                        $routes->whereHas('user', function($user) use($term){
                                            $user->where('name', 'LIKE',"%$term%");
                                            $user->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                        });
                                    });
                                }); 
                            }
                            if($model == 'App\CrmSchedule' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('crmSchedules', function($crmSchedules) use($term){
                                        $crmSchedules->whereHas('crm', function($crms) use($term){
                                            $crms->where('name', 'LIKE',"%$term%");
                                            $crms->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                        });
                                        $crmSchedules->orWhereHas('office', function($office) use($term){
                                            $office->where('name', 'LIKE',"%$term%");
                                            $office->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                        });
                                    });
                                }); 
                            }
                            if($model == 'App\Automation' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('automations', function($automations) use($term){
                                        $automations->whereHas('user', function($user) use($term){
                                            $user->where('name', 'LIKE',"%$term%");
                                            $user->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                        });
                                    });
                                });
                            }
                            if($model == 'App\CrmScheduleControl' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('crmScheduleControl', function($CrmScheduleControl) use($term){
                                        $CrmScheduleControl->whereHas('service', function($service) use($term){
                                            $service->whereHas('crm', function($crm) use($term){
                                                $crm->where('name', 'LIKE',"%$term%");
                                                $crm->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                            });
                                            $service->orWhereHas('office', function($office) use($term){
                                                $office->where('name', 'LIKE',"%$term%");
                                                $office->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                            });
                                        });
                                    });
                                });
                            }
                            if($model == 'App\Quote' || !isset($model)){
                                $object->orWhere(function($w) use ($term){
                                    $w->whereHas('quote', function($quote) use($term){
                                        $quote->whereHas('crm', function($crm) use($term){
                                            $crm->where('name', 'LIKE',"%$term%");
                                            $crm->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                        });
                                        $quote->orWhereHas('office', function($office) use($term){
                                            $office->where('name', 'LIKE',"%$term%");
                                            $office->orWhere('name', 'LIKE',"%".strtoupper($term)."%");
                                        });
                                    });
                                });
                            }
                        }

                if($request->filled('permodel')){
                    $model =$request->input('permodel');
                    $object->where('auditable_type',$model);
                }

                $event=null;
                if($request->filled('peraction')){
                    $event =$request->input('peraction');
                    $object->where('event',$event);
                }

                $users=Array();
                if($request->filled('perusers')){
                    $users =$request->input('perusers');
                    $object->whereIn('user_id',$users);
                }


                if($request->filled('perheadquarter')){
                    $headquarter =$request->input('perheadquarter');
                    $object->where('headquarter_id',$headquarter);
                }else{
                    $object->where('headquarter_id',$headquarter);
                }

                $date=null;
                if($request->filled('perstartdate')){
                    $date =$request->input('perstartdate');
                    $date =Carbon::parse($date)->format('Y-m-d');
                    $object->whereDate('created_at','>=', $date);
                }

                $date2=null;
                if($request->filled('perenddate')){
                    $date2 =$request->input('perenddate');
                    $date2 =Carbon::parse($date2)->format('Y-m-d');
                    $object->whereDate('created_at','<=', $date2);
                }
                $audits = $object->paginate($request->filled('perpage') ? $request->input('perpage') : 10);

        if($request->ajax()){
            $list = view('auditing.sub.list', compact('audits','models','model','events','event','headquarters','headquarter','users','date','date2'))->render();
            return response(['success' => true, 'view' => $list]);
        }else{
            return view('auditing.index',compact('audits','models','model','events','event','headquarters','headquarter','users','date','date2'));
        }
    }

    public function show (Request $request){
        $id          = $request->input('id');
        $audit       = Audit::find($id);
        $form        = view('auditing.form.viewForm', compact('audit'))->render();
    
        return response(['success' => true, 'view' => $form]);
    }
    public function users(Request $request)
    {
        $query = User::select(['id', 'name as text'])->enabled()->headquarter();
        if ($request->input('term')) {
            $query->where('name', 'LIKE', '%' . $request->input('term') . '%')->orWhere('name', 'LIKE', '%' . strtoupper($request->input('term')) . '%');
        }

        $query->whereHas('roles',function($roles){
            $roles->whereNotIn('role_id', [3]);
        });

        $data = $query->get();
        return response(['results' => $data]);
    }
    
    public function restore(Request $request){
        $model=$request->model::withTrashed()->find($request->id_model);
        $model_no_deleted=$request->model::find($request->id_model);
        $audit = $model->audits()->find($request->id_audit);
        if(!isset($model_no_deleted)){
            $model->restore();
        }
        if($audit->event == "updated" || $audit->event == "deleted"){
            $model->update($audit->old_values);
            $model->save();
        }
        return response(['success' => true,"message" =>"Restauracion Exitosa"]);

    }
}