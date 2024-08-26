@extends('layouts.app')

@section('title', 'Events Map')

@section('additionalstyles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        /* Override Bootstrap styles to ensure the height is set correctly for the map */
        .map {
            height: 500px;
        }

        #suggestions {
            position: absolute;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
        }

        #suggestions .list-group-item {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <!-- Jumbotron Section -->
    <div class="jumbotron jumbotron-fluid mb-4">
        <div class="container text-center">
            <h1 class="display-3">Welcome to BernEvents</h1>
            <p class="lead">Join the Fun with Bernevents – Where Every Event is Just a Click Away!</p>
        </div>
    </div>

    <!-- Search Field -->
    <div class="container mb-4">
        <form id="search-form" class="d-flex">
            <input id="search-input" class="form-control me-2" type="text" placeholder="Search for a location"
                aria-label="Search" autocomplete="off">
            <button id="search-button" class="btn btn-search" type="button">Search</button>
        </form>
        <ul id="suggestions" class="list-group mt-2"></ul> <!-- Suggestions will appear here -->
    </div>

    <div class="row">
        <div class="col-md-8">
            <div id="map" class="map">
            </div>
        </div>
        <div class="col-md-4">
            <h2>Passed Events
                @guest
                    <i class="fas fa-info-circle" id="infoIcon" style="cursor: pointer;" data-toggle="tooltip"
                        title="Click for more info"></i>
                @endguest
            </h2>
            <div id="cards-container">

            </div>
        </div>
    </div>
    @guest
        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoModalLabel">More Information</h5>
                    </div>
                    <div class="modal-body">
                        The map displays events that have already taken place. Click on the markers to see more information
                        about the event.
                        To view events that are happening in the future, please signup or login.
                    </div>
                    <div class="modal-footer">
                        <button id="btn-close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const infoIcon = document.getElementById('infoIcon');
                const infoModal = new bootstrap.Modal(document.getElementById('infoModal'));

                infoIcon.addEventListener('click', function() {
                    infoModal.show();
                });

                const buttonClose = document.getElementById('btn-close');
                buttonClose.addEventListener('click', function() {
                    infoModal.hide();
                });
            });
        </script>
    @endguest
@endsection

@section('additionalscripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const suggestionsList = document.getElementById('suggestions');

            searchInput.addEventListener('input', function() {
                const query = searchInput.value;

                if (query.length > 2) {
                    fetch(
                            `https://nominatim.openstreetmap.org/search?q=${query}&format=json&addressdetails=1&limit=5`
                        )
                        .then(response => response.json())
                        .then(data => {
                            suggestionsList.innerHTML = '';

                            data.forEach(location => {
                                const suggestionItem = document.createElement('li');
                                suggestionItem.classList.add('list-group-item',
                                    'list-group-item-action');
                                suggestionItem.textContent = `${location.display_name}`;
                                suggestionItem.addEventListener('click', () => {
                                    searchInput.value = location.display_name;
                                    suggestionsList.innerHTML = '';
                                });
                                suggestionsList.appendChild(suggestionItem);
                            });
                        });
                } else {
                    suggestionsList.innerHTML = '';
                }
            });
        });
    </script>
@endsection
