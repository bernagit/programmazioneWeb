@extends('layouts.app')

@section('title', 'Create Event')

@section('additionalstyles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Create Event</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('events.update', $event->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Event Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ $event->name }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ $event->description }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="datetime" class="form-label">Date and Time</label>
                                <input type="datetime-local" name="datetime"
                                    class="form-control @error('datetime') is-invalid @enderror"
                                    value="{{ $event->datetime }}" required>
                                @error('datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <p>Select the coordinates from map</p>
                                    <div id="map" style="height: 300px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input id="latitude" type="text" name="latitude"
                                            class="form-control @error('latitude') is-invalid @enderror"
                                            value="{{ $event->latitude }}">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input id="longitude" type="text" name="longitude"
                                            class="form-control @error('longitude') is-invalid @enderror"
                                            value="{{ $event->longitude }}">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" name="price"
                                    class="form-control @error('price') is-invalid @enderror" value="{{ $event->price }}"
                                    step="0.01">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Event Image</label>
                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="additional_info" class="form-label">Additional Info</label>
                                <input type="text" name="additional_info"
                                    class="form-control @error('additional_info') is-invalid @enderror"
                                    value="{{ $event->additional_info }}">
                                @error('additional_info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary w-100">Update Event</button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary w-100">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additionalscripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([{{ $event->latitude }}, {{ $event->longitude }}], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            var marker = L.marker([{{ $event->latitude }}, {{ $event->longitude }}], {
                draggable: true
            }).addTo(map);

            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                map.panTo(new L.LatLng(position.lat, position.lng));
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;
            });

            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                if (marker) {
                    map.removeLayer(marker); // Remove the previous marker
                }

                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                // Update the latitude and longitude input fields
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });
        });
    </script>
@endsection
