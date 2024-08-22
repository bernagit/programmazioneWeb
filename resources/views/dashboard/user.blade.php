@extends('layouts.app')

@section('title', 'Dashboard')

@section('additionalstyles')
    <style>
        .scrollable-container {
            max-height: 600px;
            /* Set your desired height */
            overflow-y: auto;
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
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="scrollable-container">
                <div class="row">
                    @foreach ($events as $event)
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3>{{ $event->name }}</h3>
                                </div>
                                <img src="miao.jpg" class="card-img-top">
                                <div class="card-body">
                                    <p>{{ $event->description }}</p>
                                    <p><strong>Date:</strong> {{ $event->date }}</p>
                                    <p><strong>Time:</strong> {{ $event->time }}</p>
                                    <p><strong>Venue:</strong> {{ $event->venue }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additionalscripts')

@endsection
