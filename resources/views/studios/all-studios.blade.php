@extends('components.layout')

@section('title', 'Studios')

@section('content')
    <div class="all-studios-grid">
        @foreach ($studios as $studio)
            <div class="all-studios-item">
                <a href="{{route('studios.single', $studio->id)}}" class="all-studios-link">
                    <img 
                        src="{{$studio->firstImage() ? asset('storage/' . $studio->firstImage()) : asset('storage/images/studios/placeholder.jpg')}}"
                        alt="{{ $studio->name }}" 
                        class="all-studios-image">
                </a>
                <div class="all-studios-info">
                    <h1 class="all-studios-name">
                        <a href="{{route('studios.single', $studio->id)}}">Studio {{$studio->name}}</a>
                    </h1>
                    <h2 class="all-studios-location">Location: {{$studio->address}}</h2>
                </div>
            </div>
        @endforeach
    </div>
@endsection


<script>
    // Плавно появяване при скролване
const cards = document.querySelectorAll('.studio-card');

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

cards.forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'all 0.6s ease';
    observer.observe(card);
});

// Паралакс ефект за изображенията
cards.forEach(card => {
    const image = card.querySelector('.studio-image');
    
    card.addEventListener('mousemove', (e) => {
        const { left, top, width, height } = card.getBoundingClientRect();
        const x = (e.clientX - left) / width - 0.5;
        const y = (e.clientY - top) / height - 0.5;
        
        image.style.transform = `
            scale(1.1)
            rotateY(${x * 10}deg)
            rotateX(${y * -10}deg)
        `;
    });
    
    card.addEventListener('mouseleave', () => {
        image.style.transform = 'scale(1) rotateY(0) rotateX(0)';
    });
    });
</script>