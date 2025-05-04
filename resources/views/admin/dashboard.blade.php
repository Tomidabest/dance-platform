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

    <div class="add-instructor-container">
        <form action="{{ route('instructors.assign') }}" method="POST">
            @csrf
            <label for="instructor_id">Add Instructor to Studio:</label>
            <select name="instructor_id" id="instructor_id" required>
                <option value="">Select an Instructor</option>
                @foreach($availableInstructors as $instructor)
                    <option value="{{ $instructor->id }}">{{ $instructor->user->first_name }} {{ $instructor->user->last_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="add-instructor-button">Add</button>
        </form>
    </div>

    <div class="classes-grid">
        @if($classes->isNotEmpty())
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

                            <form action="{{ route('class.delete', $class->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this class?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-class-button action-button">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>

                            <form action="{{ route('class.toggle', $class->id) }}" method="POST" class="toggle-form">
                                @csrf
                                @method('PATCH')
                                <label class="toggle-switch">
                                    <input type="checkbox" class="status-toggle" data-class-id="{{ $class->id }}" {{ $class->is_active ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="no-classes-message">No classes available. Create a new class to get started.</p>
        @endif
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleSwitches = document.querySelectorAll('.status-toggle');

    toggleSwitches.forEach(toggle => {
        toggle.addEventListener('change', async function () {
            const classId = this.dataset.classId;
            const card = this.closest('.class-card');
            const isChecked = this.checked;

            try {
                const response = await fetch(`/class/${classId}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ is_active: isChecked })
                });

                if (!response.ok) {
                    throw new Error(`Request failed with status ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    card.classList.toggle('active', data.is_active);
                    card.classList.toggle('inactive', !data.is_active);
                } else {
                    throw new Error('Class update failed');
                }
            } catch (error) {
                console.error('Error:', error);
                this.checked = !isChecked;
            }
        });
    });
});
</script>