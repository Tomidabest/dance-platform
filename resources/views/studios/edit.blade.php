@extends('components.layout')

@section('title', 'Edit Studio')

@section('content')
<div class="studio-form-background"></div>

<div class="studio-form-container">
    <div class="studio-form-box">
        <h1>Edit Studio</h1>

        <form action="{{ route('studios.update', $studio->id) }}" method="POST" class="studio-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Studio Name:</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $studio->name) }}" required>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $studio->phone) }}">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $studio->email) }}">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description (Optional):</label>
                <textarea id="description" name="description" class="form-textarea">{{ old('description', $studio->description) }}</textarea>
            </div>

           <div class="form-group">
                <label class="form-label">Studio Images:</label>
                <div class="image-upload-container">
                    <div class="current-images">
                        @foreach($studio->images as $image)
                            <div class="image-item" data-image-id="{{ $image->id }}">
                                <img src="{{ asset('storage/' . $image->img_path) }}" alt="Studio image">
                                <button type="button" class="delete-image" onclick="deleteImage({{ $image->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <div class="upload-new-images">
                        <label for="images" class="upload-label">
                            <i class="fas fa-plus"></i>
                            <span>Add Images</span>
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                        </label>
                        <div id="image-preview" class="image-preview"></div>
                    </div>
                </div>
                <p class="help-text">You can upload up to 20 images. Each image must be less than 2MB.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Select Location:</label>
                <div id="map" style="height: 400px; width: 100%; margin-top: 10px; border-radius: 8px;"></div>
            </div>

            <input type="hidden" id="address" name="address" value="{{ old('address', $studio->address) }}">
            <input type="hidden" id="city" name="city" value="{{ old('city', $studio->city) }}">
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $studio->latitude) }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $studio->longitude) }}">

            <button type="submit" class="submit-button">Update Studio</button>
            <a href="{{ route('admin.dashboard') }}" class="back-button">Back to Dashboard</a>
        </form>
    </div>
</div>

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

<script>

document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("images");
});
function previewImages(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    const files = input.files;

    if (document.querySelectorAll('.image-item').length + files.length > 20) {
        alert('You can only upload up to 20 images.');
        input.value = '';
        return;
    }

    for (const file of files) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Each image must be less than 2MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    }
}

async function deleteImage(imageId) {
    if (!confirm('Are you sure you want to delete this image?')) return;

    try {
        const response = await fetch(`/studios/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok) {
            document.querySelector(`[data-image-id="${imageId}"]`).remove();
        } else {
            alert(data.error || "Failed to delete image.");
        }
    } catch (error) {
        alert("Error deleting image. Please try again.");
    }
}
</script>
@endsection