<?php

namespace App\Http\Controllers;


use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
       $user = new User();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->password = Hash::make($request->password);
       $user->save();

    return response()->json([
        'message' => 'User created successfully',
        'user' => $user
    ], 201);
    }

    public function login (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if(!$token=auth()->attempt($validator->validated())) {
            return response()->json(['errors' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_toke' =>$token,
            'token_type'=>'bearer',
            'expires_in' =>auth()->factory()->getTTL()* 60,
            'user' =>auth()->user(),
        ]);
    }

    public function profile()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Please log in to access your profile.'], 401);
        }
    
        // Return the authenticated user's profile
        return response()->json(auth()->user());
        return response()->json(auth()->user());
    }
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


}