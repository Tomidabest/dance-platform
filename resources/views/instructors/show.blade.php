@extends('components.layout')

@section('content')
<div class="page-background"></div>
<div class="instructor-page">
    <div class="instructor-hero">
        <div class="instructor-profile">
            <div class="profile-image-container fade-in">
                <img 
                    src="{{ $instructor->profile_image ? asset('storage/' . $instructor->profile_image) : asset('storage/images/studios/placeholder.jpg') }}" 
                    alt="{{ $instructor->user->first_name }}" 
                >
            </div>
            <div class="instructor-info fade-in">
                <h1>{{ $instructor->user->first_name }} {{ $instructor->user->last_name }}</h1>
                <h3>About {{ $instructor->user->first_name }}</h3>
                <p>{{ $instructor->description }}</p>
                
                @if($instructor->experience)
                    <p><strong>Experience:</strong> {{ $instructor->experience }} years</p>
                @endif
                
                @if($instructor->dance_expertise)
                    <p><strong>Expertise:</strong> {{ $instructor->dance_expertise }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection