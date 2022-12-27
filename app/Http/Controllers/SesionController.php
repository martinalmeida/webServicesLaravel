<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SesionController extends Controller
{
    public function index()
    {
        return view('login.login');
    }

    public function check(Request $request)
    {
        $correo = $request->email;
        $pass  = $request->password;

        if (auth()->attempt(array('email' => $correo, 'password' => $pass))) {
            return response()->json([[1]]);
        } else {
            return response()->json([[3]]);
        }
    }

    public function home()
    {
        return view('home.home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        return redirect('/');
    }
}
