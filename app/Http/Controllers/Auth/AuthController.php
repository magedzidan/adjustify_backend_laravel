<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  public function register(Request $request)
    {
        // 1. Validate input
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // 2. Create user
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

    // 3. Generate JWT
   // $token = JWTAuth::fromUser($user);

    // 4. Login user and returning jwt token
    $token = Auth::login($user);

        // 4. Return response
        return response()->json([
            'status'=>'success',
            'message' => 'User registered successfully',
            'user'    => $user,
            'authorisation'=>[
                'token'   => $token,
                'type'    => 'bearer',
            ]
        ], 201);
    }


    public function Login(Request $request){
        $validated= $request->validate([
            'email'=>'string|required|email',
            'password'=>'required|string'
        ]);


        // 2. login with credtianls
        $token = JWTAuth::attempt($validated);

        if(!$token){
            return response()->json([
                'status'=>'error',
                'message'=>'unauthorized'
            ],401);
        }

        $user = Auth::user();

        return response()->json([
            'status'=>'success',
            'user'=>$user,
            'authorisation'=>[
                'token'=>$token,
                'type'=>'bearer'
            ]
            ]);
    }

    public function logout(Request $request){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json([
            'status'=>'success',
            'message'=>'Successfully logged out'
        ]);
    }
}
