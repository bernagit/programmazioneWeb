@extends('layouts.app')

@section('title', 'Event Details')

@section('additionalstyles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endsection

@section('content')
    <div class="container mt-5">
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="display-4">{{ $event->name }}</h1>
                    <p class="lead">{{ $event->description }}</p>
                    @if ($event->image_path != null)
                        <div class="col-md-4">
                            <img src="{{ Storage::url('images/' . $event->image_path) }}">
                        </div>
                    @endif
                    <hr class="my-4">
                    <p><strong>Location:</strong> {{ $event->location }}</p>
                    <p><strong>Date:</strong> {{ $event->date }}</p>
                    <p><strong>Time:</strong> {{ $event->time }}</p>
                    <p><strong>Price:</strong> {{ $event->price }}</p>
                    <p><strong>Capacity:</strong> {{ $event->capacity }}</p>
                </div>
            </div>
        </div>

    @endsection

    @section('additionalscripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js" crossorigin="anonymous"></script>
        <script src="js/app.js"></script>
    @endsection
