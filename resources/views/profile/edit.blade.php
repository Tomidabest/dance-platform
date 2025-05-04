@extends('components.layout')

@section('title', 'Edit Profile')

@section('content')
<div class="profile-edit-background"></div>
<div class="profile-edit-container">
    <div class="profile-edit-box">
        <h1>Edit Profile</h1>

        <form action="{{ route('profile.update') }}" method="POST" class="profile-edit-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-input" 
                       value="{{ old('first_name', $user->first_name) }}" required>
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-input" 
                       value="{{ old('last_name', $user->last_name) }}" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-input" 
                       value="{{ old('email', $user->email) }}" required>
            </div>

            @if ($user->role === 'instructor')
                <div class="form-group">
                    <label for="experience" class="form-label">Experience (Years)</label>
                    <input type="number" name="experience" id="experience" class="form-input" 
                           value="{{ old('experience', $instructor->experience) }}">
                </div>

                <div class="form-group">
                    <label for="dance_expertise" class="form-label">Dance Expertise</label>
                    <input type="text" name="dance_expertise" id="dance_expertise" class="form-input" 
                           value="{{ old('dance_expertise', $instructor->dance_expertise) }}">
                </div>
            @endif

            <button type="submit" class="submit-button">Update Profile</button>
            <a href="{{ route('profile.view') }}" class="back-button">Back to Profile</a>
        </form>
    </div>
</div>
@endsection