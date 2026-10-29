<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{

    public function getToken (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails())
        {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $this->makeToken($user);
            return response()->json(["success"=>true, "api_token" => $token],200);
        } else {
            return response()->json(['errors' => [__('Credenciales incorrectas')]], 422);
        }
    }

    public function makeToken( User $user)
    {
        $token = Str::random(60);
 
        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();
 
        return $token;
    }


  public function test()
    {
        return response()->json(["success"=>true, "message" => "Conexión correctamente configurada", "data" => null],200);
    }
}