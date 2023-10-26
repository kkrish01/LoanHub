<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function show(){
        $user = User::all();
        if($user->count() > 0){
            return response()->json([
                'status' => 200,
                'message' => $user
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No users found.'
            ],404);
        }
    }

    public function signup(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => $validator->messages()
            ],422);
        }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if($user){
                return response()->json([
                    'status' => 200,
                    'message' => "User account created successfully."
                ],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => 'Something went wrong.'
                ],500);
            }
        }
        
    }
}
