@extends('components.layout')

@section('title', 'Log In')

@section('content')
<div class="auth-background"></div>
<div class="auth-container">
    <div class="auth-box">
        <h1>Login</h1>

        <form method="POST" action="{{ route('login.auth') }}">
            @csrf

            <input name="email" placeholder="Email" type="email" class="auth-input" required value="{{ old('email') }}">
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror

            <input name="password" placeholder="Password" type="password" class="auth-input" required minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}">
            @error('password')
                <p class="auth-error">{{ $message }}</p>
            @enderror

            <button type="submit" class="auth-button">Login</button>
        </form>
    </div>
</div>
@endsection
