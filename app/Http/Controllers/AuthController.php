<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index() 
    {
        return view('login');    
    }

    public function register()
    {
        return view('register');
    }

    public function postLogin(Request $request)
    {
        $login = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($login)) 
        {
            Session::put('user_id', auth()->user()->id);

            return redirect('/galery');
        }
    }

    public function postRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'repassword' => 'required|same:password', // Memastikan 'repassword' sama dengan 'password'
            'terms' => 'required',
        ]);

        $ins = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect('/login');
    }
}