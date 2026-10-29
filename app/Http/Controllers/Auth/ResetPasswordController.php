<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Headquarter;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */

     
    protected $redirectTo = 'scheduling';

    public function redirectTo(){

        
    
        if(is_null(session('headquarter_id'))){
            $headquarter = Headquarter::find(Auth::user()->headquarter->id);
            session(['headquarter_id' => $headquarter->id]);
            session(['headquarter_name' => $headquarter->name]);
        }
        
        if(Auth::user()->roles->pluck('slug')->contains('customer')){
            $crm = Auth::user()->crms()->first()->id;
            return 'crm/'.$crm;
        }else{
            return 'scheduling';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
