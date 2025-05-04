@extends('components.layout')

@section('title', 'My Bookings')

@section('content')
<div class="dashboard-container">
    <h1 class="dashboard-title">My Bookings</h1>

    <div class="classes-grid">
        @forelse ($bookings as $booking)
            <div class="class-card">
                <div class="class-info">
                    <h2 class="class-name">{{ $booking->classes->name }}</h2>
                    <p class="class-genre">Genre: {{ $booking->classes->genre }}</p>
                    <p class="class-studio">Studio: {{ $booking->classes->studio->name }}</p>
                    <p class="class-instructor">
                        Instructor: {{ $booking->classes->instructor?->user?->first_name }} {{ $booking->classes->instructor?->user?->last_name ?? 'Not Assigned' }}
                    </p>
                    <p class="booking-date">Booked on: {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}</p>

                    <div class="booking-actions">
                        <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cancel-booking-button action-button">
                                <i class="fas fa-times"></i> Cancel Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="no-bookings">You have no bookings.</p>
        @endforelse
    </div>
</div>
@endsection