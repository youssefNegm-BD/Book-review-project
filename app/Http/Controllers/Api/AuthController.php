<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request):JsonResponse{
        $request->validate([
            'email'=>'required|email|max:255',
            'password'=>'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message'=>'The Provided credentials are incorrect'
                ],401);
        }
        
        $token = $user->createToken($user->name.'Auth-Token')->plainTextToken;
        return response()->json([
            'message'=> 'login Successfully',
            'token_type'=> 'Barer',
            'token'=> $token

        ],201);
    }

    public function register(Request $request):JsonResponse{
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email|max:255',
            'password'=> 'required|string'
        ]);

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);


        if ($user) {
            $token = $user->createToken($user->name.'Auth-Token')->plainTextToken;

            return response()->json([
                'message'=> 'register Successfully',
                'token_type'=> 'Barer',
                'token'=> $token
            ],201);
        }else{
            return response()->json([
                'message'=>'Something went wrong!'
            ],500);
        }


    }
        public function profile(Request $request){
            if ($request->user()) {
                return response()->json([
                    'message'=>'Profile Fetched.',
                    "data"=>$request->user()
                ],200);


    }else{
            return response()->json([
                'message'=>'Not Authenticated'
            ],401);
        }
    }
    public function logout(Request $request){
        // $user = User::where('id',$request->user()->id)->first();
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json([
                'message'=>'Loged out successfully'
            ],200);
        }else{
            return response()->json([
                'message'=>'User Not Found'
            ],404);
        }
    }
    

}
