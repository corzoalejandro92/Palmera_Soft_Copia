<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->roles->pluck('slug')->contains('customer')){
                $crm = Auth::user()->crms()->first()->id;
                return redirect('crm/'.$crm);
            }else{
                return redirect('/scheduling');
            }
        }
        
        return $next($request);
    }
}
