<?php

/*
| Description: Creating methods which can be used for CRUD, return view, API and more
*/

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
            // check if the email is valid
            $user = User::where('email', $request->email)->first();
            // no matched email
            if (!$user) {
                // redirect back to previous page with error message and user input
                return back()
                    ->withErrors(['email' => 'Email not found.'])
                    ->withInput();
            }

            // email is valid, attempt to login
            $is_logged_in = Auth::attempt(['email' => request('email'), 'password' => request('password')]);
            if (!$is_logged_in) {
                // password not match
                return back()
                    ->withErrors(['password' => 'Password is incorrect.'])
                    ->withInput();
            } else {
                // attempt successful
                Auth::login($user);
                if (Auth::user()->role == "admin") {
                    return redirect('/admin');
                }
                return redirect('/');
            }
        }

        // if a user is logged, the user will be logged out
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

    public function getProfile(Request $request)
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    // get apcard balance
    public function getApcardBalance(Request $request)
    {
        $balance = Auth::user()->apcard_balance;
        return response()->json($balance, 200);
    }

    // topup
    public function topupApcard(Request $request)
    {
        $user = Auth::user();
        $topup_amount = $request->amount;

        $user->apcard_balance = $user->apcard_balance + $topup_amount;
        $user->update();

        return response()->json("topup successful", 200);
    }
}
