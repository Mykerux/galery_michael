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

    // public function postLogin(Request $request)
    // {
    //     $login = $request->only('username', 'password');

    //     if (Auth::attempt($login)) {
    //         // Authentication passed
    //         return redirect()->intended('/gallery');
    //     }

    //     // Authentication failed
    //     return redirect()->route('login')->with('error', 'Invalid username or password');
    // }

    public function postLogin(Request $request)
    {
        $login = $request->validate([
            'username'=>'required',
            'password'=>'required',
        ]);
        if (Auth::attempt($login))
        {
            Session::put('user_id', auth()->User()->id);
            Session::put('name', auth()->user()->name);

            return redirect()->intended('/gallery');
        }
        
        return back()->withErrors([
            'errors' => 'Username atau Password salah'
        ]);
    }
    
    public function postRegister(Request $request)
    {
        // echo $request->username;
        $register = $request->validate([
            'username'=>'required',
            'password'=>'required',
            'terms'=>'required',
            'repassword'=>'required|same:password',
            'email'=>'required',
            'name'=>'required',
        ]);
        
        if ($request->password == $request->repassword)
        {
        
            $ins = User::create(
                [
                    'username'=>$request->username,
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'password'=>bcrypt($request->password),
                ]
            );
            
            $login = $request->validate([
                'username'=>'required',
                'password'=>'required',
            ]);
            
            if (Auth::attempt($login))
            {
                Session::put('user_id', auth()->User()->id);
                Session::put('name', auth()->user()->name);

                return redirect()->intended('/gallery');
            }

            
        }
        

        return redirect('/login');
    }
    
    public function logout()
    {
        Auth::logout();
        Session::forget('user_id');
        Session::forget('name');

        return redirect('/');
    }

    // public function postLogin(Request $request)
    // {
    //     $login = $request->validate([
    //         'username' => 'required',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::attempt($login)) 
    //     {
    //         Session::put('user_id', auth()->user()->id);

    //         return redirect('/galery');
    //     }
    // }

    // public function postRegister(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'username' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required',
    //         'repassword' => 'required|same:password', // Memastikan 'repassword' sama dengan 'password'
    //         'terms' => 'required',
    //     ]);

    //     $ins = User::create([
    //         'name' => $request->name,
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password),
    //     ]);

    //     return redirect('/login');
    // }
}