<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Instructor;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(Request $request)
    {

        $validated = $request->validate(
            [
                'first_name' => 'required|string|max:255|',
                'last_name' => 'required|string|max:255|',
                'username' => 'required|string|max:255|regex:/^(?!.*\.\.)(?!.*\.$)[a-zA-Z0-9.]+$/|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:user,admin,instructor'
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

        $user = User::create(
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role']
            ]
        );

        Auth::login($user);

        if ($validated['role'] === 'instructor') {
            $instructor = Instructor::create([
                'users_id' => $user->id,
                'studios_id' => null,
                'experience' => null,
                'dance_expertise' => null,
                'description' => null,
            ]);

            return redirect()->route('instructor.setup', ['id' => $instructor->id]);
        }

        return redirect()->route('landing');
    }
}
