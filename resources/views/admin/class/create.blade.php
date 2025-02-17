@extends('components.layout')

@section('title', 'Create Class')

@section('content')
<div class="class-form-background"></div>
<div class="class-form-container">
    <div class="class-form-box">
        <h1>Create New Class</h1>

        <form action="{{ route('class.store') }}" method="POST" class="class-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Class Name</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" name="genre" id="genre" class="form-input" value="{{ old('genre') }}" required>
                @error('genre')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-textarea">{{ old('description') }}</textarea>
                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="instructor_id" class="form-label">Instructor</label>
                <select name="instructor_id" id="instructor_id" class="form-select">
                    <option value="">Select Instructor (Optional)</option>
                    @foreach ($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->user->first_name }} {{ $instructor->user->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('instructor_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="availability" class="form-label">Available Slots</label>
                <input type="number" name="availability" id="availability" class="form-input" 
                       value="{{ old('availability') }}" min="0" required>
                @error('availability')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="time_start" class="form-label">Start Time</label>
                <input type="datetime-local" name="time_start" id="time_start" class="form-input" 
                       value="{{ old('time_start') }}" required>
                @error('time_start')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="time_ends" class="form-label">End Time</label>
                <input type="datetime-local" name="time_ends" id="time_ends" class="form-input" 
                       value="{{ old('time_ends') }}" required>
                @error('time_ends')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" name="price" id="price" class="form-input" 
                       value="{{ old('price') }}" required>
                @error('price')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="submit-button">Create Class</button>
            <a href="{{ route('admin.dashboard') }}" class="back-button">Back to Dashboard</a>
        </form>
    </div>
</div>
@endsection