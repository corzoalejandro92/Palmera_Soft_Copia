<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Crm;
use App\Office;
use App\Contact;
use App\Headquarter;
use App\General\CryptoAES;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'scheduling';

    protected function authenticated(Request $request, $user)
    {
            Session::put('recent_logged','true');
            session(['headquarter_id' => $user->headquarter->id]);
            session(['headquarter_name' => $user->headquarter->name]);

            if(Auth::user()->roles->pluck('slug')->contains('customer')){
                $crm = Auth::user()->crms()->first();
                session(['crm_id' => $crm->id]);
                session(['crm_name' => $crm->name]);
                $this->redirectTo ='crm/'.$crm->id;
            }else{
                $this->redirectTo ='scheduling';
            }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout','logoutAPI');
    }

    public function loginAPI(Request $request){
        $password = CryptoAES::cryptoJsAesDecrypt($request->input('password'));

        $user = User::with('roles')->where('email',$request->input('username'))->get()->last();
        if($user){
            $user->makeVisible(['password']);
            $this->destroyToken($user->id);
            $token = $this->generateToken($request,$user);
            if(!$user->roles->pluck('slug')->contains('technical')) return $this->error(["logged"=>false, "msg" => 'Debes tener un rol de técnico para poder acceder']);
            $match = Hash::check($password, $user->password);
            return $this->success(["logged"=> $match, "user"=>$user->makeHidden(['password','roles']), "token"=>$token,"msg" => $match ? 'Iniciando sesión...': 'La contraseña de acceso es incorrecta']);
        }else{
            return $this->error(["logged"=>false, "msg" => 'Usuario o contraseña son incorrectos'],200);
        }
    }

    public function loginAPICustomer(Request $request){
        $password = CryptoAES::cryptoJsAesDecrypt($request->input('password'));

        $user = User::with(['roles','crms'])->where('email',$request->input('username'))->get()->last();
        if($user){

            //anexar las offices_id
            //obtener el id del usuario
            //$user = User::find($this->getUserIDToken($request));

            //obtener el id del contacto por medio del usuario
            $contact = Contact::where('user_id',$user->id)->get();
            $user->crms = $user->crms->map(function($c) use($contact){

                $offices = Office::where('crm_id',$c->id)->whereHas('contacts', function($c)use($contact){
                    $c->whereIn('contact_id',$contact->pluck('id'));
                })->get();

                $c->offices = $offices;
                return $c;
            });

            

            $user->makeVisible(['password']);
            $this->destroyToken($user->id);
            $token = $this->generateToken($request,$user);
            if(!$user->roles->pluck('slug')->contains('customer')) return $this->error('Debes tener un rol de cliente para poder acceder', 401, ["logged"=>false]);
            $match = Hash::check($password, $user->password);
            return $this->success(["logged"=> $match, "user"=>$user->makeHidden(['password','roles']), "token"=>$token,"msg" => $match ? 'Iniciando sesión...': 'La contraseña de acceso es incorrecta']);
        }else{
            return $this->error('Usuario o contraseña son incorrectos', 401, ["logged"=>false]);
        }
    }

    public function userDataCustomer(Request $request){
        $user = User::with(['roles','crms'])->find($this->getUserIDToken($request));
        if($user){

            //anexar las offices_id
            //obtener el id del usuario
            $user = User::find($this->getUserIDToken($request));

            //obtener el id del contacto por medio del usuario
            $contact = Contact::where('user_id',$user->id)->get();

            $user->crms = $user->crms->map(function($c) use($contact){

                $offices = Office::where('crm_id',$c->id)->whereHas('contacts', function($c)use($contact){
                    $c->whereIn('contact_id',$contact->pluck('id'));
                })->get();

                $c->offices = $offices;
                return $c;
            });

        
            $match = true;
            return $this->success(["logged"=> $match, "user"=>$user->makeHidden(['password','roles']), "msg" => $match ? 'Obteniendo información...': 'La contraseña de acceso es incorrecta']);
        }else{
            return $this->error('Este usuario ya no se encuentra disponible', 401, ["logged"=>false]);
        }
    }

    public function logoutAPI(Request $request){

        try{
            $id = $this->getUserIDToken($request);
            $success = $this->destroyToken($id);
            return $this->success(['logout' =>true]);
        }catch(Exception $ex){
            return $this->success(['logout' =>false, 'message'=> $ex->getMessage()]);
        }
    }
}
