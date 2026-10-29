<?php

namespace App\General;

trait APIResponse
{
    protected function success($data=[]){
        $response= [
            "success"=>true,
            "code"=>200,
            "data"=>$data
        ];
        
        return response()->json($response,200);
    }
    
    protected function error($msg,$code=401, $data= []){
        $response= [
            "success"=>false,
            "code"=>$code,
            "message"=>$msg,
            "data"=>$data
        ];
        
        return response()->json($response,$code);
    }
}
