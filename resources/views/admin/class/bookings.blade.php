@extends('components.layout')

@section('title', 'Class Bookings')

@section('content')
<div class="booking-background"></div>
<div class="booking-container">
    <div class="booking-content">
        <h1 class="booking-title">Bookings for {{ $class->name }}</h1>

        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bookings-list">
            @forelse ($bookings as $booking)
                <div class="booking-item">
                    <div class="booking-info">
                        <div class="booking-user">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</div>
                        <div class="booking-date">
                            <i class="far fa-calendar-alt"></i>
                            Booked on: {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}
                        </div>
                    </div>
                    <form action="{{ route('booking.destroy', $booking->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to remove this booking?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-button">
                            <i class="fas fa-times"></i>
                            Remove
                        </button>
                    </form>
                </div>
            @empty
                <p class="no-bookings">No bookings found for this class.</p>
            @endforelse
        </div>

        <a href="{{ route('admin.dashboard') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection