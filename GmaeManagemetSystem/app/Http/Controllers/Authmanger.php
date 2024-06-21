<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
class Authmanger extends Controller
{
    function register()
    {
        if(Auth::check())
        {
            return redirect(route(name: 'game'));
        }else
        {
            return view('register');
        }
    }
    function registerPost(Request $request){
       $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|string|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed'
       ]);
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $user = user::create($data);
        if(!$user)
        {
            return redirect(route(name: 'register'))->with("error", 'Registration filad!!.');
        }else{
            return redirect(route(name: 'game'));
        }
    }
    function login()
    {
        if(Auth::check())
        {
            return redirect(route(name: 'game'));
        }else
        {
            return view('index');
        }
    }

    function loginPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);
        $data = $request->only('name','password');
        if(Auth::attempt($data)){
            return redirect()->intended(route(name: 'game'));//->with('token', $token);
        }else{
            return redirect(route(name: 'login'))->with("error", 'Login filad!!.');
        }
    }
    function logout()
    {
        if(Auth::check())
        {
            session::flush();
            Auth::logout();
            return redirect(route(name: 'login'))->with("suc", 'logedout suc');
        }else
        {
            return redirect(route(name: 'login'))->with("error", 'you are not logedin!');
        }
    }
}