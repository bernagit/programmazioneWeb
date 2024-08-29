<form action="{{ route('user.update_settings') }}" method="POST" id="settings-form"
    class="p-4 border rounded bg-light shadow-sm">
    @csrf
    <p class="h5 mb-4">Select the preferred radius, coordinates, and price.</p>

    <!-- Radius Slider -->
    <div class="mb-3">
        <label for="Radius" class="form-label">Radius</label>
        <div class="row align-items-center">
            <div class="col-10">
                <input type="range" class="form-range" id="radiusSlider" name="Radius" min="20" max="100"
                    step="1" value="{{ $radius ?? 50 }}">
            </div>
            <div class="col-2 text-end">
                <span id="radiusValue">{{ $radius ?? 50 }} km</span>
            </div>
        </div>
    </div>

    <!-- Latitude Input -->
    <div class="mb-3">
        <label for="Latitude" class="form-label">Latitude</label>
        <input type="number" step="any" class="form-control" id="latitudeInput" name="Latitude"
            placeholder="Enter latitude" required value="{{ $latitude ?? '' }}">
    </div>

    <!-- Longitude Input -->
    <div class="mb-3">
        <label for="Longitude" class="form-label">Longitude</label>
        <input type="number" step="any" class="form-control" id="longitudeInput" name="Longitude"
            placeholder="Enter longitude" required value="{{ $longitude ?? '' }}">
    </div>

    <!-- Preferred Price Input -->
    <div class="mb-3">
        <label for="preferredPrice" class="form-label">Preferred Price</label>
        <input type="number" class="form-control" id="preferredPriceInput" name="PreferredPrice"
            placeholder="Enter preferred price" required value="{{ $price ?? '10' }}">
    </div>

    <button type="submit" class="btn light-2 w-100" id="submit-button">Save Changes</button>
</form>
