@extends('components.layout')

@section('title', $studio->name)

@section('content')
<div class="page-background"></div>
<div class="single-studio">
    <div class="hero-wrapper">
        <div class="hero-section">
            <div class="slider">
                <div class="slider-wrapper" id="slider-wrapper">
                    @foreach ($studio->images->isNotEmpty() ? $studio->images : collect([(object)['img_path' => 'images/studios/placeholder.jpg']]) as $image)
                        <div class="slider-item">
                            <img src="{{ asset('storage/' . $image->img_path) }}" alt="{{ $studio->name }}">
                        </div>
                    @endforeach
                </div>
                @if($studio->images->count() > 1)
                    <button class="slider-button left" id="slider-prev">❮</button>
                    <button class="slider-button right" id="slider-next">❯</button>
                @endif
            </div>
            
            <div class="studio-quick-info">
                <h1>{{ $studio->name }}</h1>
                <div class="quick-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $studio->formatted_address }}</span>
                </div>
                <div class="quick-info-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ $studio->phone }}</span>
                </div>
                <div class="quick-info-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:{{ $studio->email }}">{{ $studio->email }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content-container">
        <div class="content-box fade-in">
            <h2>About the Studio</h2>
            <p>{{ $studio->description ?: 'No description available.' }}</p>
        </div>
        
        <div class="content-box fade-in">
            <h2>Our Instructors</h2>
            @if ($instructors->isNotEmpty())
                <div class="instructors-grid">
                    @foreach ($instructors as $instructor)
                        <a href="{{ route('instructors.show', $instructor->id) }}" class="instructor-card">
                            @if($instructor->profile_image)
                                <div class="instructor-image">
                                    <img src="{{ asset('storage/' . $instructor->profile_image) }}" 
                                         alt="{{ $instructor->user->first_name }} {{ $instructor->user->last_name }}">
                                </div>
                            @endif
                            <h3>{{ $instructor->user->first_name }} {{ $instructor->user->last_name }}</h3>
                            <p><strong>Experience:</strong> {{ $instructor->experience }} years</p>
                            <p><strong>Expertise:</strong> {{ $instructor->dance_expertise }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="no-data-message">No instructors available.</p>
            @endif
        </div>

        @php
            $userBookings = auth()->check() ? auth()->user()->bookings->pluck('classes_id')->toArray() : [];
            $availableClasses = $classes->whereNotIn('id', $userBookings)->where('is_active', true);
        @endphp

        <div class="content-box fade-in">
            <h2>Available Classes</h2>
            @if($availableClasses->isNotEmpty())
                <div class="classes-slider">
                    <div class="classes-wrapper {{ count($availableClasses) > 3 ? 'many-classes' : '' }}">
                        @foreach ($availableClasses as $class)
                            <div class="class-card">
                                <h3>{{ $class->name }}</h3>
                                <p class="class-genre">{{ $class->genre }}</p>
                                <p class="class-description">{{ $class->description }}</p>
                                <div class="class-details">
                                    <p class="class-price">
                                        <i class="fas fa-tag"></i>
                                        {{ $class->price }} lv
                                    </p>
                                    <p class="class-time">
                                        <i class="far fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($class->time_start)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($class->time_ends)->format('H:i') }}
                                    </p>
                                    @if($class->availability > 0)
                                        <p class="class-availability">
                                            <i class="fas fa-users"></i>
                                            {{ $class->availability }} spots left
                                        </p>
                                    @else
                                        <p class="class-availability full">
                                            <i class="fas fa-users-slash"></i>
                                            Class Full
                                        </p>
                                    @endif
                                </div>
                                
                                @auth
                                    @if(auth()->user()->role === 'user' && $class->availability > 0)
                                        <form action="{{ route('class.book', $class->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="book-button">
                                                <i class="fas fa-bookmark"></i>
                                                Book Now
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="book-button">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Login to Book
                                    </a>
                                @endauth
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="no-data-message">No available classes.</p>
            @endif
        </div>

        @if($relatedStudios->isNotEmpty())
            <div class="content-box fade-in">
                <h2>Recommended Studios</h2>
                <div class="classes-slider">
                    <div class="classes-wrapper">
                        @foreach ($relatedStudios as $relatedStudio)
                            <div class="class-card">
                                <h3>{{ $relatedStudio->name }}</h3>
                                <p class="class-genre">
                                    Genres: {{ implode(', ', $relatedStudio->classes->pluck('genre')->unique()->toArray()) }}
                                </p>
                                <p class="studio-location">
                                    {{ $relatedStudio->address }}, {{ $relatedStudio->city }}
                                </p>
                                <p class="studio-distance">
                                    Distance: {{ number_format($relatedStudio->distance, 1) }} km
                                </p>
                                <a href="{{ route('studios.single', $relatedStudio->id) }}" class="book-button">
                                    View Studio
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const mainSlider = {
        wrapper: document.getElementById('slider-wrapper'),
        items: document.querySelectorAll('.slider-item'),
        prevBtn: document.getElementById('slider-prev'),
        nextBtn: document.getElementById('slider-next'),
        currentIndex: 0,
        autoPlayInterval: null,

        init() {
            if (!this.wrapper || this.items.length <= 1) return;
            this.update();
            this.addEventListeners();
            this.startAutoPlay();
        },

        update() {
            const transformValue = -(this.currentIndex * 100);
            this.wrapper.style.transform = `translateX(${transformValue}%)`;
        },

        next() {
            this.currentIndex = (this.currentIndex < this.items.length - 1) ? this.currentIndex + 1 : 0;
            this.update();
        },

        prev() {
            this.currentIndex = (this.currentIndex > 0) ? this.currentIndex - 1 : this.items.length - 1;
            this.update();
        },

        addEventListeners() {
            this.prevBtn?.addEventListener('click', () => {
                this.prev();
                this.stopAutoPlay();
            });

            this.nextBtn?.addEventListener('click', () => {
                this.next();
                this.stopAutoPlay();
            });

            let touchStartX = 0;
            let touchEndX = 0;

            this.wrapper.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            this.wrapper.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 50) {
                    this.next();
                } else if (touchEndX - touchStartX > 50) {
                    this.prev();
                }
            }, { passive: true });
        },

        startAutoPlay() {
            this.autoPlayInterval = setInterval(() => this.next(), 5000);
        },

        stopAutoPlay() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
                this.autoPlayInterval = null;
            }
        }
    };

    const classesSlider = {
        wrapper: document.querySelector('.classes-wrapper'),
        cards: document.querySelectorAll('.class-card'),
        
        init() {
            if (!this.wrapper || this.cards.length <= 3) return;
            
            this.wrapper.classList.add('many-classes');
            this.addNavigation();
            this.addScrollListeners();
        },

        addNavigation() {
            const sliderContainer = this.wrapper.closest('.classes-slider');
            
            const prevBtn = document.createElement('button');
            const nextBtn = document.createElement('button');
            
            prevBtn.className = 'slider-button left classes-prev';
            nextBtn.className = 'slider-button right classes-next';
            prevBtn.innerHTML = '❮';
            nextBtn.innerHTML = '❯';

            sliderContainer.appendChild(prevBtn);
            sliderContainer.appendChild(nextBtn);

            prevBtn.addEventListener('click', () => this.scroll(-1));
            nextBtn.addEventListener('click', () => this.scroll(1));
        },

        scroll(direction) {
            const cardWidth = this.cards[0].offsetWidth + parseInt(getComputedStyle(this.cards[0]).marginRight);
            const visibleCards = 3;
            const scrollAmount = cardWidth * visibleCards;
            
            const currentScroll = this.wrapper.scrollLeft;
            const maxScroll = this.wrapper.scrollWidth - this.wrapper.clientWidth;
            
            if (direction > 0) {
                const nextScroll = Math.min(currentScroll + scrollAmount, maxScroll);
                this.wrapper.scrollTo({
                    left: nextScroll,
                    behavior: 'smooth'
                });
            } else {
                const nextScroll = Math.max(currentScroll - scrollAmount, 0);
                this.wrapper.scrollTo({
                    left: nextScroll,
                    behavior: 'smooth'
                });
            }
        },

        addScrollListeners() {
            this.wrapper.addEventListener('scroll', () => {
                const scrollLeft = this.wrapper.scrollLeft;
                const maxScroll = this.wrapper.scrollWidth - this.wrapper.clientWidth;
                
                const prevBtn = document.querySelector('.classes-prev');
                const nextBtn = document.querySelector('.classes-next');
                
                if (prevBtn && nextBtn) {
                    prevBtn.style.display = scrollLeft > 0 ? 'block' : 'none';
                    nextBtn.style.display = scrollLeft < maxScroll - 3 ? 'block' : 'none';
                }
            });
        }
    };

    const animations = {
        init() {
            this.animateOnScroll();
            this.addHoverEffects();
        },

        animateOnScroll() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.class-card, .instructor-card, .content-box')
                .forEach(el => observer.observe(el));
        },

        addHoverEffects() {
            document.querySelectorAll('.slider-item').forEach(item => {
                item.addEventListener('mousemove', (e) => {
                    const { left, top, width, height } = item.getBoundingClientRect();
                    const x = (e.clientX - left) / width - 0.5;
                    const y = (e.clientY - top) / height - 0.5;

                    const img = item.querySelector('img');
                    if (img) {
                        img.style.transform = `scale(1.1) translate(${x * 10}px, ${y * 10}px)`;
                    }
                });

                item.addEventListener('mouseleave', () => {
                    const img = item.querySelector('img');
                    if (img) {
                        img.style.transform = 'scale(1) translate(0, 0)';
                    }
                });
            });
        }
    };

    mainSlider.init();
    classesSlider.init();
    
    const studiosSlider = Object.create(classesSlider);
    studiosSlider.wrapper = document.querySelectorAll('.classes-wrapper')[1];
    studiosSlider.cards = studiosSlider.wrapper ? studiosSlider.wrapper.querySelectorAll('.class-card') : [];
    studiosSlider.init();
    
    animations.init();
});
</script>