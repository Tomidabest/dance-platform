@extends('components.layout')

@section('title', 'Register')

@section('content')
<div class="auth-background"></div>
<div class="auth-container">
    <div class="auth-box">
        <h1>Sign Up</h1>

        <form action="{{ route('register.store') }}" method="POST" id="registerForm">
            @csrf

            <div class="input-group">
                <input name="first_name" placeholder="First Name" type="text" class="auth-input" required minlength="2" maxlength="255" pattern="[A-Za-z\s]+" value="{{ old('first_name') }}">
                @error('first_name')
                    <p class="auth-error">{{ $message }}</p>
                @enderror

                <input name="last_name" placeholder="Last Name" type="text" class="auth-input" required minlength="2" maxlength="255" pattern="[A-Za-z\s]+" value="{{ old('last_name') }}">
                @error('last_name')
                    <p class="auth-error">{{ $message }}</p>
                @enderror

                <input name="username" placeholder="Username" type="text" class="auth-input" required minlength="3" maxlength="255" value="{{ old('username') }}">
                @error('username')
                    <p class="auth-error">{{ $message }}</p>
                @enderror

                <input name="email" placeholder="Email" type="email" class="auth-input" required value="{{ old('email') }}">
                @error('email')
                    <p class="auth-error">{{ $message }}</p>
                @enderror

                <input name="password" placeholder="Password" type="password" class="auth-input" required minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}">
                @error('password')
                    <p class="auth-error">{{ $message }}</p>
                @enderror

                <input name="password_confirmation" placeholder="Confirm Password" type="password" class="auth-input" required minlength="8">
                @error('password_confirmation')
                    <p class="auth-error">{{ $message }}</p>
                @enderror

                <label for="role">Register as</label>
                <select name="role" id="role" class="auth-select" required>
                    <option value="user">Dancer</option>
                    <option value="admin">Studio Owner</option>
                </select>
            </div>

            <button class="auth-button" type="submit">Sign Up</button>
        </form>
    </div>
</div>
@endsection