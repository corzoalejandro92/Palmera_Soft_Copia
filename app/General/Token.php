<?php

namespace App\General;
use \App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Crypt;
use App\Token as TokenModel;

trait Token
{

    
    public static function generateToken(Request $request, $user){
        
        $data = ["sessionID" =>$request->header('SessionID'),"userID"=>$user->id];
        $hash = Crypt::encryptString(json_encode($data));
        $token = new TokenModel();
        $token->token = $hash;
        $token->user_id = $user->id;
        $continue = $token->save();

        //actalizar e incluir el id del token dentro de este
        $data = ["sessionID" =>$request->header('SessionID'),"userID"=>$user->id, "token_id" => $token->id];

        $hash = Crypt::encryptString(json_encode($data));
        $token->token = $hash;
        $continue = $token->save();
        return $continue ? $hash: false;
    }
    
    public static function getUserIDToken(Request $request){
        return json_decode(Crypt::decryptString($request->bearerToken()))->userID;
    }
    
    static function getUserToken($token){
        return TokenModel::where('token',$token)->first();
    }

    static function destroyToken($id){
        return TokenModel::where('user_id',$id)->delete();
    }

}
