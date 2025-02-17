@extends('components.layout')

@section('title', 'Home')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="featured-dance">
        <div class="conteiner">
            <img src="{{ asset('storage/images/landing_img.jpg') }}" alt="Graceful Image" class="featured-dance__image" />
        </div>
        <div class="featured-dance__content">
            <h2 class="featured-dance__description"> Want to become a member of DanceIgnite? </h2>
            <p class="featured-dance__title">REGISTER NOW!</p>
            <a href="/register" class="featured-dance__button">Register</a>
        </div>
    </div>

    <div class="search-register">
        <div class="search-register__form">
            <form action="{{ route('search') }}" method="GET">
                <input name="location" placeholder="Location" type="text" class="search-register__input" />
                <input name="genre" placeholder="Genre" type="text" class="search-register__input" />
                <input name="date" placeholder="Date" type="text" class="search-register__input" />
                <button class="search-register__button">Search</button>
            </form>
        </div>
    </div>

    <div class="featured-studios">
    <div class="featured-studios__container">
        <h2 class="featured-studios__title">Featured Studios</h2>
        <div class="featured-studios__grid">
            @foreach ($studios as $studio)
                <div class="studio-card">
                    <img 
                        src="{{ $studio->firstImage() ? asset('storage/' . $studio->firstImage()) : asset('storage/images/studios/placeholder.jpg') }}" 
                        alt="{{ $studio->name }}" 
                        class="studio-card__image"
                    />
                    <div class="studio-card__content">
                        <h3 class="studio-card__name">
                            <a href="{{ route('studios.single', $studio->id) }}">{{ $studio->name }}</a>
                        </h3>
                        <p class="studio-card__address">{{ $studio->address }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection



<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroImage = document.querySelector('.featured-dance__image');
    
    function animate() {
        const time = Date.now() * 0.001;
        
        const xMovement = Math.sin(time * 0.5) * 20;
        const yMovement = Math.cos(time * 0.3) * 15;
        const scaleEffect = 1 + Math.sin(time * 0.5) * 0.02;
        
        heroImage.style.transform = `
            translate3d(${xMovement}px, ${yMovement}px, 0)
            scale(${scaleEffect})
        `;
        
        requestAnimationFrame(animate);
    }

    animate();
});
</script>