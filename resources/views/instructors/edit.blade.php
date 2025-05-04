@extends('components.layout')

@section('title', 'Setup Instructor Profile')

@section('content')
<link rel="stylesheet" href="{{ asset('css/class-form.css') }}">

<div class="class-form-background"></div>
<div class="class-form-container">
    <div class="class-form-box">
        <h1>Complete Your Instructor Profile</h1>

        <form action="{{ route('instructor.storeSetup', $instructor->id) }}" method="POST" class="class-form">
            @csrf

            <div class="form-group">
                <label for="experience" class="form-label">Years of Experience</label>
                <input type="number" name="experience" id="experience" class="form-input" 
                       value="{{ old('experience', $instructor->experience) }}" min="0" required>
            </div>

            <div class="form-group">
                <label for="dance_expertise" class="form-label">Dance Expertise</label>
                <input type="text" name="dance_expertise" id="dance_expertise" class="form-input" 
                       value="{{ old('dance_expertise', $instructor->dance_expertise) }}" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Bio</label>
                <textarea name="description" id="description" class="auth-textarea">{{ old('description', $instructor->description) }}</textarea>
            </div>

            <button class="submit-button" type="submit">Save & Continue</button>
        </form>
    </div>
</div>
@endsection