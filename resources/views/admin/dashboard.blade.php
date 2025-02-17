@extends('components.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="dashboard-container">
    <h1 class="dashboard-title">Dashboard</h1>

    <a href="{{ route('class.create') }}" class="create-class-button action-button">
        <i class="fas fa-plus"></i> Create New Class
    </a>

    @if(auth()->user()->studio_id)
            <a href="{{ route('studios.edit', auth()->user()->studio_id) }}" class="edit-studio-button action-button">
                <i class="fas fa-edit"></i> Edit Studio
            </a>
    @endif

    <div class="classes-grid">
        @foreach($classes as $class)
            <div class="class-card {{ $class->is_active ? 'active' : 'inactive' }}">
                <div class="class-info">
                    <h2 class="class-name">{{ $class->name }}</h2>
                    <p class="class-genre">Genre: {{ $class->genre }}</p>
                    <p class="class-description">{{ $class->description }}</p>
                    <p class="class-instructor">
                        Instructor: {{ $class->instructor?->user?->first_name }} {{ $class->instructor?->user?->last_name ?? 'Not Assigned' }}
                    </p>
                    
                    <div class="class-actions">
                        <a href="{{ route('class.bookings', $class->id) }}" class="view-bookings-button action-button">
                            <i class="fas fa-calendar-check"></i> View Bookings
                        </a>
                        <a href="{{ route('class.edit', $class->id) }}" class="edit-class-button action-button">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        
                        <form action="{{ route('class.toggle', $class->id) }}" method="POST" class="toggle-form">
                            @csrf
                            @method('PATCH')
                            <label class="toggle-switch">
                                <input type="checkbox" 
                                    class="status-toggle" 
                                    data-class-id="{{ $class->id }}"
                                    {{ $class->is_active ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleSwitches = document.querySelectorAll('.status-toggle');
    
    toggleSwitches.forEach(toggle => {
        toggle.addEventListener('change', async function() {
            const classId = this.dataset.classId;
            const card = this.closest('.class-card');
            
            try {
                const response = await fetch(`/class/${classId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                
                this.checked = data.is_active;
                card.classList.toggle('active', data.is_active);
                card.classList.toggle('inactive', !data.is_active);

                const successMessage = document.createElement('div');
                successMessage.className = 'success-message';
                successMessage.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    ${data.message}
                `;
                card.insertBefore(successMessage, card.firstChild);

                setTimeout(() => {
                    successMessage.remove();
                }, 3000);

            } catch (error) {
                console.error('Error:', error);
                
                this.checked = !this.checked;
                
                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                errorMessage.innerHTML = `
                    <i class="fas fa-exclamation-circle"></i>
                    Failed to update class status
                `;
                card.insertBefore(errorMessage, card.firstChild);

                setTimeout(() => {
                    errorMessage.remove();
                }, 3000);
            }
        });
    });
});
</script>
@endpush