<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;

class CrmDelete
{
    public function handle($request, Closure $next)
    {
        
        if(Auth::user()->roles->pluck('slug')->contains('commercial') || Auth::user()->roles->pluck('slug')->contains('boss_commercial')){
            $crm = \App\Crm::with('offices.workschedules')->find($request->input('id'));
            $workschedules = $crm->offices->pluck('workschedules')->flatten();
            if($crm->user_id == Auth::user()->id && $workschedules->count() == 0){
                return $next($request); //allow
            }
            return false;
            
        }else if(Auth::user()->can('crms.destroy')){
            return $next($request); //allow
        }

        return false; // deny
    }
}