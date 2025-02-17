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
                <label for="address" class="form-label">Address:</label>
                <input type="text" id="address" name="address" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="city" class="form-label">City:</label>
                <input type="text" id="city" name="city" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone (Optional):</label>
                <input type="text" id="phone" name="phone" class="form-input">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email (Optional):</label>
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

            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <button type="submit" class="submit-button">Create Studio</button>

        </form>
    </div>
</div>
@endsection
<!-- Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const defaultLocation = [42.6977, 23.3242]; //Sofia
        const map = L.map("map").setView(defaultLocation, 12);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
        }).addTo(map);

        const marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

        document.getElementById("latitude").value = defaultLocation[0];
        document.getElementById("longitude").value = defaultLocation[1];

        marker.on("dragend", (event) => {
            const latLng = marker.getLatLng();
            document.getElementById("latitude").value = latLng.lat;
            document.getElementById("longitude").value = latLng.lng;
        });

        map.on("click", (event) => {
            marker.setLatLng(event.latlng);
            document.getElementById("latitude").value = event.latlng.lat;
            document.getElementById("longitude").value = event.latlng.lng;
        });
    });
</script>