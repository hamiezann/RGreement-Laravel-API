<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserAccessController extends Controller
{
    public function register(Request $request){
        $registerUserData = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|min:8',
            'role' => 'required|in:renter,landlord',
          
        ]);
        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
            'role' => $registerUserData['role'],
        ]);
        return response()->json([
            'message' => 'User Created ',
        ]);
    }

    public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken', ['*'], Carbon::now()->addHour(1))->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'message' => 'You are logged in',
        ]);
    }
    
    
    public function logout(Request $request){

    $request -> user();
    auth()->user()->tokens()->delete();
       

       // auth()->user()->tokens()->revoke();
    
        return response()->json([
          "message"=>"Logged out"
        ]);
    }
}
