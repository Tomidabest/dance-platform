@extends('components.layout')

@section('title', 'User Profile')

@section('content')
<div class="page-background"></div>
<div class="profile-page">
    <div class="profile-hero">
        <div class="profile-container">
            <div class="profile-image-container fade-in">
                <img src="{{ asset($user->image ?? 'storage/images/studios/placeholder.jpg') }}" alt="{{ $user->first_name }}">
                <form action="{{ route('profile.uploadImage') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*">
                    <button type="button" class="upload-button" onclick="document.getElementById('profile_image').click();">
                        <i class="fas fa-camera"></i> Change Profile Picture
                    </button>
                    <button type="submit" class="save-button">Save</button>
                </form>
            </div>
            <div class="profile-info fade-in">
                <h1>{{ $user->first_name }} {{ $user->last_name }}</h1>
                <p><strong>Email:</strong> {{ $user->email }}</p>

                @if ($user->role === 'admin')
                    <h3>Studio Owner</h3>
                    @if ($studio)
                        <p><strong>Studio Name:</strong> {{ $studio->name }}</p>
                        <p><strong>Location:</strong> {{ $studio->address }}</p>
                    @else
                        <p class="no-data-message">No studio assigned.</p>
                    @endif

                @elseif ($user->role === 'instructor')
                    <h3>Instructor Profile</h3>
                    <p><strong>Experience:</strong> {{ $instructor->experience ?? 'N/A' }} years</p>
                    <p><strong>Dance Expertise:</strong> {{ $instructor->dance_expertise ?? 'N/A' }}</p>
                    <p><strong>Description:</strong> {{ $instructor->description ?? 'No description provided.' }}</p>
                    <p><strong>Studio:</strong> {{ $instructor->studio?->name ?? 'Not Assigned' }}</p>

                @else
                    <h3>Regular User</h3>
                    <p class="no-data-message">No additional information available.</p>
                @endif

                <a href="{{ route('profile.edit') }}" class="edit-profile-button action-button">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection