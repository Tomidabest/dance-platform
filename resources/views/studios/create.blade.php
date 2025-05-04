@extends('components.layout')

@section('title', 'Create a New Studio')

@section('content')
<div class="studio-form-background"></div>

<div class="studio-form-container">
    <div class="studio-form-box">
        <h1>Create a New Studio</h1>

        <form action="{{ route('studios.store') }}" method="POST" class="studio-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Studio Name:</label>
                <input type="text" id="name" name="name" class="form-input" required>
            </div>


            <div class="form-group">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-input">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description (Optional):</label>
                <textarea id="description" name="description" class="form-textarea"></textarea>
            </div>

             <div class="form-group">
                <label class="form-label">Select Location:</label>
                <div id="map" style="height: 400px; width: 100%; margin-top: 10px; border-radius: 8px;"></div>
            </div>

            <input type="hidden" id="address" name="address">
            <input type="hidden" id="city" name="city">
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <button type="submit" class="submit-button">Create Studio</button>
        </form>
    </div>
</div>

<!-- Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const defaultLocation = [42.6977, 23.3242]; // Sofia
    const map = L.map("map").setView(defaultLocation, 12);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
    }).addTo(map);

    const marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

    async function updateAddress(lat, lng) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
            const data = await response.json();
            
            const address = data.display_name;
            const city = data.address.city || data.address.town || data.address.village || '';
            
            document.getElementById("address").value = address;
            document.getElementById("city").value = city;
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
        } catch (error) {
            console.error("Error fetching address:", error);
        }
    }

    updateAddress(defaultLocation[0], defaultLocation[1]);

    marker.on("dragend", (event) => {
        const latLng = marker.getLatLng();
        updateAddress(latLng.lat, latLng.lng);
    });

    map.on("click", (event) => {
        marker.setLatLng(event.latlng);
        updateAddress(event.latlng.lat, event.latlng.lng);
    });
});
</script>
@endsection