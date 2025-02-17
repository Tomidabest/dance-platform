@extends('components.layout')

@section('content')
    <div class="results">
        @if (count($studios) === 0)
            <p>No results found.</p>
        @else
            @foreach ($studios as $studio)
                <div class="studio">
                    <h2>
                        <a href="{{ route('studios.single', $studio->id) }}">{{ $studio->name }}</a>
                    </h2>
                    <p>{{ $studio->address }}</p>
                    <p>Genres:
                        @foreach ($studio->classes as $class)
                            {{ $class->genre }}
                        @endforeach
                    </p>
                </div>
            @endforeach
            <div class="pagination">
                {{ $studios->links() }}
            </div>
        @endif
    </div>
@endsection