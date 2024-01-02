<?php

namespace App\Http\Controllers;

use App\Events\UserLoggedIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    
    public function index(){
        return view('Authorization.Login.index');
    }

    public function login(Request $request){
        $validated=$request->validate([
            'email'=>'required|email',
            'password'=>'required|min:3'
        ]);
        if(Auth::attempt($validated)){
            event(new UserLoggedIn($request->email));
           return redirect()->intended(route('test'));
        } 
        else{
            return redirect()->back()->with('fail','Failed');
        }
    }

    public function logout(){
        Auth::guard('web')->logout();
        return redirect(route('login'));
    }

    
}
