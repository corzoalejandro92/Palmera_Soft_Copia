<?php

namespace App\General;

use App\Usuario;

trait Roles
{
    
    protected function isAdmin($id,$modulo){
        if(is_null($id)) return false;
        $user = Usuario::
        with(['permisos'=> function($q) use ($modulo){
            $q->where('id_modulo', $modulo)
            ->whereIn('id_grupo',[0,1,4])->first();
        }])->find($id);
        
        if($user){
            if($user->permisos) return true; else return false;
        }else{
            return false;
        }
        
    }

    protected function userToFind($isAdmin,$user,$request){
        if($request->input('user') == 'null') return $user;
        else return  $isAdmin? $request->input('user') : $userId;
    }
}