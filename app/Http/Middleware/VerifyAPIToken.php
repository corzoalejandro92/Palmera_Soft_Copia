<?php

namespace App\Http\Middleware;

use Closure;
use App\General\APIResponse;
use \Illuminate\Support\Facades\Crypt;
use App\General\Token;

class VerifyAPIToken
{
    use APIResponse;
    use Token;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = json_decode(Crypt::decryptString($request->bearerToken()));
        $sessionID = $request->header("SessionID");
        if($token->sessionID == $sessionID){

            //verificar si el token está activo
            $dbtoken = $this->getUserToken($request->bearerToken());
            if(!empty($dbtoken)){
                if($dbtoken->user_id == $token->userID){
                    return $next($request);
                }else{
                    return $this->error('Token de acceso inválido',401);
                }
            }  
        }
        
        return $this->error('Token de acceso inválido',401);
        
 
    }
    
    
}
