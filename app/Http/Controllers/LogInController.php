<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LogInController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => 'Incorrect email or password',
                'email.email' => 'Incorrect email or password',
                'password.required' => 'Incorrect email or password',
            ]
        );

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            $cookieName = 'personal_token_' . $user->id;
            Cookie::queue($cookieName, $token, 60 * 24 * 30);

            return redirect()->route('landing');
        }

        return back()->withInput()->withErrors(['email' => 'Incorrect email or password']);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $cookieName = 'personal_token_' . $user->id;
        Cookie::queue(Cookie::forget($cookieName));

        return redirect()->route('landing');
    }
}
