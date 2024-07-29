@extends('layouts.app')

@section('title', 'Events Map')

@section('additionalstyles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        /* Override Bootstrap styles to ensure the height is set correctly for the map */
        .map {
            height: 500px;
        }
    </style>
@endsection

@section('content')
    <!-- Jumbotron Section -->
    <div class="jumbotron jumbotron-fluid mb-4">
        <div class="container text-center">
            <h1 class="display-3">Welcome to BernEvents</h1>
            <p class="lead">Join the Fun with Bernevents – Where Every Event is Just a Click Away!</p>
            {{-- <a class="btn btn-light btn-lg" href="{{ route('events.index') }}" role="button">Find Events</a> --}}
        </div>
    </div>

    <!-- Search Field -->
    <div class="container mb-4">
        <form id="search-form" class="d-flex">
            <input id="search-input" class="form-control me-2" type="text" placeholder="Search for a location"
                aria-label="Search">
            <button id="search-button" class="btn btn-primary" type="button">Search</button>
        </form>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div id="map" class="map">
            </div>
        </div>
        <div class="col-md-4">
            <h2>Passed Events</h2>
            <div id="cards-container">
            </div>
        </div>
    </div>
@endsection

@section('additionalscripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
@endsection
