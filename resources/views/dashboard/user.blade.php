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

        .scrollable-container::-webkit-scrollbar {
            width: 8px;
        }

        .scrollable-container::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }

        .scrollable-container::-webkit-scrollbar-thumb:hover {
            background-color: #555;
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
                        </div>
                    </div>
                    <div class="tab-pane fade" id="events" role="tabpanel" aria-labelledby="events-tab">
                        <h1 class="tab-content-title">Events</h1>
                        <div class="scrollable-container">
                            <div class="row">
                                @foreach ($events as $event)
                                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3>{{ $event->name }}</h3>
                                            </div>
                                            @isset($event->image_path)
                                                <div class="img-event">
                                                    <img src="{{ Storage::url('images/' . $event->image_path) }}"
                                                        class="card-img-top img-fluid img-event">
                                                </div>
                                            @endisset
                                            <div class="card-body">
                                                <p>{{ $event->description }}</p>
                                                <p><strong>Date:</strong> {{ $event->date }}</p>
                                                <p><strong>Time:</strong> {{ $event->time }}</p>
                                                <p><strong>Price:</strong> {{ $event->price }}</p>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <a href="{{ route('showEvent', $event->id) }}"
                                                            class="btn light-2">View</a>
                                                    </div>
                                                    <div class="col-6">
                                                        <i class="fas fa-heart heart-icon {{ $event->isLikedByUser() ? 'liked' : '' }}"
                                                            data-id="{{ $event->id }}"></i>
                                                        <span
                                                            id="like-count-{{ $event->id }}">{{ $event->likesCount() ?? 0 }}</span>
                                                        Likes
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
