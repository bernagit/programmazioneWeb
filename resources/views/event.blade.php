@extends('layouts.app')

@section('title', 'Event Details')

@section('additionalstyles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        /* Ensure the map container has a height */
        #map {
            height: 400px;
            /* Adjust as needed */
            width: 100%;
            /* Ensure it takes up the full width */
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="jumbotron">
            <div class="row">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h1 class="display-4">{{ $event->name }}</h1>
                    <p class="lead">{{ $event->description }}</p>
                    @if ($event->image_path != null)
                        <div class="mb-3 position-relative">
                            <img class="img-fluid" src="{{ Storage::url('images/' . $event->image_path) }}"
                                alt="Event Image" id="eventImage">

                            <!-- Fullscreen icon -->
                            <button class="btn btn-secondary position-absolute" style="top: 10px; right: 10px;"
                                id="fullscreenButton">
                                <i class="fas fa-expand"></i> <!-- FontAwesome fullscreen icon -->
                            </button>
                        </div>
                    @endif
                    <hr class="my-4">
                    <p><strong>Location:</strong> {{ $event->location }}</p>
                    <p><strong>Date:</strong> {{ $event->date }}</p>
                    <p><strong>Time:</strong> {{ $event->time }}</p>
                    <p><strong>Price:</strong> {{ $event->price }}</p>
                    <p><strong>Additional info:</strong> {{ $event->additional_info }}</p>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 mb-3">
                    <div id="map" class="map-small"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Image Fullscreen Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img id="modalImage" class="img-fluid" src="" alt="Full Screen Event Image">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('additionalscripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([{{ $event->latitude }}, {{ $event->longitude }}], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            var geocoder = L.Control.Geocoder.nominatim();

            geocoder.reverse(
                L.latLng({{ $event->latitude }}, {{ $event->longitude }}),
                map.getZoom(),
                function(results) {
                    var r = results[0];
                    if (r) {
                        L.marker([{{ $event->latitude }}, {{ $event->longitude }}])
                            .addTo(map)
                            .bindPopup('<b>{{ $event->name }}</b><br>' + r.name)
                            .openPopup();
                    }
                }
            );
        });

        document.addEventListener('DOMContentLoaded', function() {
            const fullscreenButton = document.getElementById('fullscreenButton');
            const eventImage = document.getElementById('eventImage');
            const modalImage = document.getElementById('modalImage');
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));

            fullscreenButton.addEventListener('click', function() {
                // Set the image source in the modal
                modalImage.src = eventImage.src;

                // Show the modal
                imageModal.show();
            });
        });
    </script>
@endsection
