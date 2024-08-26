@extends('layouts.app')

@section('title', 'Event Details')

@section('additionalstyles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        /* General page styling */
        .container {
            font-family: 'Arial', sans-serif;
        }

        .jumbotron {
            background-color: #f9f9f9;
            padding: 2rem 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .display-4 {
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .lead {
            font-size: 1.2rem;
            color: #666;
        }

        /* Map container styling */
        #map {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            margin-top: 20px;
        }

        /* Image styling */
        .event-image-container {
            position: relative;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .event-image-container img {
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .event-image-container img:hover {
            transform: scale(1.05);
        }

        /* Fullscreen icon styling */
        #fullscreenButton {
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.9);
            border: none;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            color: #c5c5c5;
        }

        #fullscreenButton i {
            font-size: 18px;
        }

        /* Heart icon styling */
        .heart-icon {
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .heart-icon.liked {
            color: #e74c3c;
        }

        /* Modal styling */
        .modal-content {
            border-radius: 10px;
            overflow: hidden;
        }

        .modal-body img {
            border-radius: 0;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .jumbotron {
                padding: 1.5rem;
            }

            .display-4 {
                font-size: 1.8rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="jumbotron">
            <div class="row">
                <div class="col-lg-6 order-1 order-lg-1">
                    <h1 class="display-4">{{ $event->name }}</h1>
                    <p class="lead">{{ $event->description }}</p>
                    @if ($event->image_path != null)
                        <div class="event-image-container position-relative">
                            <img class="img-fluid" src="{{ Storage::url('images/' . $event->image_path) }}"
                                alt="Event Image" id="eventImage">

                            <!-- Fullscreen icon -->
                            <button class="btn btn-secondary position-absolute" id="fullscreenButton"
                                style="top: 10px; right: 10px;">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    @endif
                    <hr class="my-4">
                    <p><strong>Location:</strong> {{ $event->location }}</p>
                    <p><strong>Date:</strong> {{ $event->date }}</p>
                    <p><strong>Time:</strong> {{ $event->time }}</p>
                    <p><strong>Price:</strong> {{ $event->price }}</p>
                    <p><strong>Additional info:</strong> {{ $event->additional_info }}</p>
                    @if ($event->creator_id == auth()->id() || auth()->user()->isSuperAdmin())
                        <a href="{{ route('events.edit', $event->id) }}" class="btn light-2 mt-3">Edit Event</a>
                        <!-- Show user that likes the event in a modal -->
                        <button type="button" class="btn light-2 mt-3" data-bs-toggle="modal" data-bs-target="#likesModal">
                            Show Likes
                        </button>
                        <div class="modal fade" id="likesModal" tabindex="-1" aria-labelledby="likesModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="likesModalLabel">Users that liked the event</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($event->likes as $like)
                                                    <tr>
                                                        <td>{{ $like->user->name }}</td>
                                                        <td>{{ $like->user->email }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-6 order-2 order-lg-2 mb-3 text-center">
                    <i class="fas fa-heart heart-icon {{ $event->isLikedByUser() ? 'liked' : '' }}"
                        data-id="{{ $event->id }}"></i>
                    <span id="like-count-{{ $event->id }}" class="d-block">{{ $event->likesCount() ?? 0 }}</span>
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

        document.addEventListener('DOMContentLoaded', function() {
            const likeButtons = document.querySelectorAll('.heart-icon');

            likeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const eventId = this.getAttribute('data-id');
                    const isLiked = this.classList.contains('liked');
                    const url = isLiked ? `/unlikeEvent/${eventId}` : `/likeEvent/${eventId}`;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const likeCountElement = document.getElementById(
                                    `like-count-${eventId}`);
                                likeCountElement.textContent = data.likes_count;

                                if (data.liked) {
                                    this.classList.add('liked');
                                } else {
                                    this.classList.remove('liked');
                                }
                            } else {
                                console.error('Error:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });
    </script>
@endsection
