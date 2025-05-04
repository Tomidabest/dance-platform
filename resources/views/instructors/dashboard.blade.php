@extends('components.layout')

@section('title', 'My Classes')

@section('content')
<div class="dashboard-container">
    <h1 class="dashboard-title">My Classes</h1>

    @if ($instructor->studio)
        <p class="dashboard-subtitle"><strong>Studio:</strong> {{ $instructor->studio->name }}</p>
    @endif

    <div class="classes-grid">
        @forelse ($classes as $class)
            <div class="class-card">
                <div class="class-info">
                    <h2 class="class-name">{{ $class->name }}</h2>
                    <p class="class-genre">Genre: {{ $class->genre }}</p>
                    <p class="class-studio">Studio: {{ $class->studio?->name ?? 'Studio Not Assigned' }}</p>
                    <p class="class-time">Start Time: {{ \Carbon\Carbon::parse($class->time_start)->format('d M Y, H:i') }}</p>
                    <p class="class-time">End Time: {{ \Carbon\Carbon::parse($class->time_ends)->format('d M Y, H:i') }}</p>
                </div>
            </div>
        @empty
            <p class="no-bookings">You are not assigned to any classes.</p>
        @endforelse
    </div>
</div>
@endsection
