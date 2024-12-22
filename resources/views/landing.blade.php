<x-layout>
    <!-- Featured Dance Section -->
    <div class="featured-dance">
        <div class="conteiner">

            <img src="{{ asset('images/landing_img.jpg') }}" alt="Graceful Image" class="featured-dance__image" />

        </div>
        <div class="featured-dance__content">
                <h1 class="featured-dance__title">Discover A Variety of Dance Experiences</h1>
                <p class="featured-dance__description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.
                </p>
                <button class="featured-dance__button">Buy Now</button>
        </div>
    </div>

    <!-- Search and Register Section -->
    <div class="search-register">
        <div class = "search-register__form">
            <form action="{{ route('search') }}" method="GET">
                <input name="Location Field" placeholder="Location" type="text" class="search-register__input" />
                <input name="Genre Field" placeholder="Genre" type="text" class="search-register__input" />
                <input name="Date Field" placeholder="Date" type="text" class="search-register__input" />
                <button class="search-register__button">Search</button>
            </form>
        </div>
        <h2 class="search-register__prompt">
            Want to become a member of DanceIgnite? Register now!
        </h2>
        <button class="search-register__register-button">Register</button>
    </div>
</x-layout>
