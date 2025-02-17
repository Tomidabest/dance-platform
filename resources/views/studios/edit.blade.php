@extends('components.layout')

@section('title', 'Edit Studio')

@section('content')
<div class="studio-form-background"></div>

<div class="studio-form-container">
    <div class="studio-form-box">
        <h1>Edit Studio</h1>

        <form action="{{ route('studios.update', $studio->id) }}" method="POST" class="studio-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Studio Name:</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $studio->name) }}" required>
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Address:</label>
                <input type="text" id="address" name="address" class="form-input" value="{{ old('address', $studio->address) }}" required>
            </div>

            <div class="form-group">
                <label for="city" class="form-label">City:</label>
                <input type="text" id="city" name="city" class="form-input" value="{{ old('city', $studio->city) }}" required>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone (Optional):</label>
                <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $studio->phone) }}">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email (Optional):</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $studio->email) }}">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description (Optional):</label>
                <textarea id="description" name="description" class="form-textarea">{{ old('description', $studio->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Select Location:</label>
                <div id="map" style="height: 400px; width: 100%; margin-top: 10px; border-radius: 8px;"></div>
            </div>

            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $studio->latitude) }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $studio->longitude) }}">

            <button type="submit" class="submit-button">Update Studio</button>

            <a href="{{ route('admin.dashboard') }}" class="back-button">Back to Dashboard</a>
        </form>
    </div>
</div>

<!-- Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const currentLocation = [{{ old('latitude', $studio->latitude) }}, {{ old('longitude', $studio->longitude) }}];
        const map = L.map("map").setView(currentLocation, 12);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
        }).addTo(map);

        const marker = L.marker(currentLocation, { draggable: true }).addTo(map);

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
@endsection
