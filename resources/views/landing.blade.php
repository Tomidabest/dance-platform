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
            @auth
                <h2 class="featured-dance__description"> Welcome, {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}! </h2>
            @else
                <h2 class="featured-dance__description"> Want to become a member of DanceIgnite? </h2>
                <p class="featured-dance__title">REGISTER NOW!</p>
                <a href="/register" class="featured-dance__button">Register</a>
            @endauth
        </div>
    </div>

    <div class="search-register">
        <div class="search-register__form">
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="address" id="address" placeholder="Enter Address" class="search-register__input">

                <select name="genre" id="genre-select" class="search-register__input">
                    <option value="">Select Genre</option>
                    <option value="Ballet">Ballet</option>
                    <option value="Hip-hop">Hip-hop</option>
                    <option value="Salsa">Salsa</option>
                    <option value="Tango">Tango</option>
                    <option value="Contemporary">Contemporary</option>
                    <option value="Jazz">Jazz</option>
                    <option value="Breakdance">Breakdance</option>
                    <option value="Ballroom">Ballroom</option>
                    <option value="Kizomba">Kizomba</option>
                    <option value="House">House</option>
                    <option value="Krump">Krump</option>
                    <option value="Swing">Swing</option>
                    <option value="Popping">Popping</option>
                    <option value="Locking">Locking</option>
                    <option value="Bachata">Bachata</option>
                    <option value="Flamenco">Flamenco</option>
                    <option value="Tap Dance">Tap Dance</option>
                </select>

                <input name="date" type="date" class="search-register__input">

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
