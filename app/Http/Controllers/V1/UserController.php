<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RegisterUserRequest;
use App\Http\Requests\V1\UserLoginRequest;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(UserLoginRequest $request) 
    {   
        $user=User::where(['email'=>$request->email])->first();
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'status'=>'failed',
                'message'=>'Invalid Credentials'
            ],401);
        }
        $data['token']=$user->createToken($request->email,['role:user'])->plainTextToken;
        $data['user']=$user;
        $response=[
            'status'=>'success',
            'message'=>'User Logged in Successfully',
            'data'=>$data
        ];
       return response()->json($response,200);
    }
    public function register(RegisterUserRequest $request) 
    {
        $user=User::create($request->all());
        $data['token']=$user->createToken($request->email,['role:user'])->plainTextToken;
        $data['user']=$user;
        $response = [
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => $data,
        ];
        return response()->json($response,201);
    }

    public function logout(Request $request) {
        
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'Logout Successfully'
        ],200);
    }

    public function index()  {
        return new UserCollection(User::all());
    }

    public function show(string $id)  {
        // return User::findOrFail($id);
        return new UserResource(User::findOrFail($id));
    }
    public function getUserData()  {
        $user=Auth::user();
        // return User::findOrFail($id);
        return new UserResource($user);
    }
}
