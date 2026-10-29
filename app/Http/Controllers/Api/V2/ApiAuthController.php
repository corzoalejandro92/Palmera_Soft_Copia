<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    public function login (Request $request) {
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

            return response()->json(
                [
                    "success" => true,
                    "data" => [
                        'id' => $user->id,
                        'email' => $user->email, 
                        'name' => $user->name,
                        'photo' => $user->full_avatar,  
                        'token' => $token,
                        'headquarter' => $user->headquarter->name,
                         
                    ]
                ]
            );
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

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'Has cerrado sesión, vuelve pronto!'];
        return response($response, 200);
    }
}
