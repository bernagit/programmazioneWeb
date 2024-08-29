@extends('layouts.app')

@section('title', 'Dashboard')

@section('additionalstyles')
    <style>
        .nav-item .nav-link.active {
            background-color: #FCEC52;
            color: black;
            border: 0;
        }

        .nav-item .nav-link {
            color: black;
            border: 0;
        }

        .nav-item .nav-link:hover {
            background-color: #ADE8F4;
            color: black;
        }

        .scrollable-container {
            max-height: 600px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 10px;
        }

        .img-event {
            height: 200px;
            overflow: hidden;
        }

        .heart-icon {
            font-size: 24px;
            cursor: pointer;
        }

        .heart-icon.liked {
            color: red;
        }

        @media (max-width: 768px) {
            .nav-pills {
                flex-direction: column;
            }

            .nav-item {
                margin-bottom: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-3 col-md-4">
                <ul class="nav nav-pills flex-column mb-4" id="sa-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard" role="tab"
                            aria-controls="dashboard" aria-selected="true">Dashboard</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="events-tab" data-bs-toggle="tab" href="#events" role="tab"
                            aria-controls="events" aria-selected="false">Events</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab"
                            aria-controls="settings" aria-selected="false">Settings</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="tab-content" id="sa-tabContent">
                    <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                        <h1 class="tab-content-title">Dashboard</h1>
                        <p>Welcome to the dashboard, {{ Auth::user()->name }}!</p>
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="tab-content-title">Change password</h2>
                                @include('user.change_pwd')
                            </div>
                            <div class="col-md-6">
                                <h4 class="tab-content-title">Manage profile</h4>
                                <button class="btn btn-danger">Delete Account</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="events" role="tabpanel" aria-labelledby="events-tab">
                        <h1 class="tab-content-title">Events</h1>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="filterEvents" role="switch">
                            <label class="form-check-label" for="filterEvents" id="filterEventsLabel">Show events filtered
                                by preferences</label>
                        </div>
                        <br>
                        <div class="scrollable-container">
                            <div class="row" id="events-container">
                                <!-- Events will be dynamically added here -->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                        <div class="row">
                            <div class="col-md-8 order-1">
                                <h2 class="tab-content-title">Settings</h2>
                                @include('user.settings', [
                                    'latitude' => $latitude,
                                    'longitude' => $longitude,
                                    'price' => $price,
                                    'radius' => $radius,
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additionalscripts')
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const slider = document.getElementById('radiusSlider');
            const output = document.getElementById('radiusValue');
            const filter = document.getElementById('filterEvents');
            const eventsContainer = document.getElementById('events-container');

            // Update slider value display
            if (slider && output) {
                slider.addEventListener('input', function() {
                    output.textContent = `${this.value} km`;
                });
            }

            // Fetch and update events
            async function fetchAndUpdateEvents() {
                const isFiltered = filter && filter.checked;
                const endpoint = isFiltered ? '/filteredEvents' : '/events';
                try {
                    const response = await fetch(endpoint);
                    let data = await response.json();
                    console.log(JSON.stringify(data));

                    if (data.length === 0) {
                        eventsContainer.innerHTML = '<p>No events found</p>';
                        return;
                    }
                    if (!Array.isArray(data)) {
                        data = Object.values(data);
                    }
                    updateEvents(data);
                } catch (error) {
                    console.error(`Error fetching ${isFiltered ? 'filtered' : 'all'} events:`, error);
                }
            }

            // Update events in the container
            function updateEvents(events) {
                eventsContainer.innerHTML = '';
                events.forEach(event => {
                    const eventHTML = `
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h3>${event.name}</h3>
                            </div>
                            <div class="img-event">
                                <img src="${event.image_path}" class="card-img-top img-fluid img-event">
                            </div>
                            <div class="card-body">
                                <p>${event.description}</p>
                                <p><strong>Date:</strong> ${event.date}</p>
                                <p><strong>Time:</strong> ${event.time}</p>
                                <p><strong>Price:</strong> ${event.price}</p>
                                <div class="row">
                                    <div class="col-6">
                                        <a href="/showEvent/${event.id}" class="btn light-2">View</a>
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-heart heart-icon ${event.liked ? 'liked' : ''}" data-id="${event.id}"></i>
                                        <span id="like-count-${event.id}">${event.likes_count}</span> Likes
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    eventsContainer.insertAdjacentHTML('beforeend', eventHTML);
                });
            }

            // Initial fetch and update
            await fetchAndUpdateEvents();

            // Set up filter change listener
            if (filter) {
                filter.addEventListener('change', fetchAndUpdateEvents);
            }

            // Handle like/unlike functionality
            async function handleLike(event) {
                if (event.target.classList.contains('heart-icon')) {
                    const button = event.target;
                    const eventId = button.getAttribute('data-id');
                    const isLiked = button.classList.contains('liked');
                    const url = isLiked ? `/unlikeEvent/${eventId}` : `/likeEvent/${eventId}`;

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();

                        if (data.success) {
                            const likeCountElement = document.getElementById(`like-count-${eventId}`);
                            if (likeCountElement) {
                                likeCountElement.textContent = data.likes_count;
                            }

                            if (data.liked) {
                                button.classList.add('liked');
                            } else {
                                button.classList.remove('liked');
                            }
                        } else {
                            console.error('Error:', data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            }

            // Attach the click handler to the document
            document.addEventListener('click', handleLike);
        });
    </script>
@endsection
