<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // login
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $is_logged_in = Auth::attempt(['email' => request('email'), 'password' => request('password')]);

            if ($is_logged_in) {
                $user = User::where('email', $request->email)->first();
                Auth::login($user);

                return redirect('/');
            } else {
                return back()->withErrors(['Login failed']);
            }
        }

        if (Auth::check()) {
            Auth::logout();
        }

        return view('auth.login');
    }

    // logout
    public function logout(Request $request)
    {
        if (Auth::user()) {
            Auth::logout();
        }

        return redirect('/login');
    }
}
