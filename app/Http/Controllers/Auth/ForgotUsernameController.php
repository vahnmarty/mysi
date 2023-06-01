<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ForgotUsernameController extends Controller
{
    public function create()
    {
        return view('auth.forgot-username');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        
        $user  = User::where('email', $request->email)->first();

        if($user){
            $user->sendForgotUsernameNotification();

            return back()->with('status', 'Email sent to ' . $user->email);
        }
        

        return back()->withErrors(['email' => 'Email Address not found.']);
        
    }
}
