@extends('components.layout')

@section('title', 'Studios')

@section('content')
    <div class="all-studios-grid" id="studios-container">
        @if ($studios->isEmpty())
            <p>No results found.</p>
        @else
            @foreach ($studios as $studio)
                <div class="all-studios-item">
                    <a href="{{ route('studios.single', $studio->id) }}" class="all-studios-link">
                        <img 
                            src="{{ $studio->firstImage() ? asset('storage/' . $studio->firstImage()) : asset('storage/images/studios/placeholder.jpg') }}" 
                            alt="{{ $studio->name }}" 
                            class="all-studios-image">
                    </a>
                    <div class="all-studios-info">
                        <h1 class="all-studios-name">
                            <a href="{{ route('studios.single', $studio->id) }}">Studio {{ $studio->name }}</a>
                        </h1>
                        <h2 class="all-studios-location">Location: {{ $studio->formatted_address }}</h2>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if(!$studios->isEmpty())
        <div id="load-more" data-next-page="{{ $studios->nextPageUrl() }}"></div>
    @endif
@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
    let container = document.querySelector("#studios-container");
    let loadMoreDiv = document.querySelector("#load-more");
    let nextPageUrl = loadMoreDiv ? loadMoreDiv.dataset.nextPage : null;
    let isLoading = false;

    function fetchStudios() {
        if (!nextPageUrl || isLoading) return;

        isLoading = true;

        fetch(nextPageUrl)
            .then(response => response.text())
            .then(data => {
                let tempDiv = document.createElement("div");
                tempDiv.innerHTML = data;

                let newStudios = tempDiv.querySelector("#studios-container").innerHTML;
                container.insertAdjacentHTML("beforeend", newStudios);

                let newNextPageDiv = tempDiv.querySelector("#load-more");
                if (newNextPageDiv) {
                    nextPageUrl = newNextPageDiv.dataset.nextPage;
                } else {
                    nextPageUrl = null;
                    loadMoreDiv.remove();
                }

                isLoading = false;
            })
            .catch(error => {
                console.error("Error loading more studios:", error);
                isLoading = false;
            });
    }

    function handleScroll() {
        if (isLoading || !nextPageUrl) return;

        let scrollPosition = window.innerHeight + window.scrollY;
        let documentHeight = document.documentElement.offsetHeight;

        if (scrollPosition >= documentHeight - 200) {
            fetchStudios();
        }
    }

    window.addEventListener("scroll", handleScroll);
});
</script>