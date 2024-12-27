<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        //dd($request->all());

        $validated = $request->validate(
            [
                'first_name' => 'required|string|max:255|',
                'last_name' => 'required|string|max:255|',
                'username' => 'required|string|max:255|regex:/^(?!.*\.\.)(?!.*\.$)[a-zA-Z0-9.]+$/|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed'
            ],
            [
                'first_name.required' => 'The first name is required.',
                'first_name.string' => 'The first name must be a valid string.',
                'first_name.max' => 'The first name may not be greater than 255 characters.',

                'last_name.required' => 'The last name is required.',
                'last_name.string' => 'The last name must be a valid string.',
                'last_name.max' => 'The last name may not be greater than 255 characters.',

                'username.required' => 'The username is required.',
                'username.string' => 'The username must be a valid string.',
                'username.max' => 'The username may not be greater than 255 characters.',
                'username.regex' => 'The username can only contain letters, numbers, and periods. It cannot have consecutive periods or end with a period.',
                'username.unique' => 'The username is already taken. Please choose another one.',

                'email.required' => 'The email address is required.',
                'email.email' => 'The email address must be a valid email format.',
                'email.max' => 'The email address may not be greater than 255 characters.',
                'email.unique' => 'The email address is already registered. Please use another email.',

                'password.required' => 'The password is required.',
                'password.string' => 'The password must be a valid string.',
                'password.min' => 'The password must be at least 8 characters long.',
                'password.confirmed' => 'The passwords do not match.',
            ]
        );

        User::create(
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password'])
            ]
        );

        return redirect()->route('landing')->with('success', 'You are now registered!');
    }
}
