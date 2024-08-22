$ = jQuery;

var map;

// Function to fetch passed events
async function getPassedEvents() {
    try {
        const response = await axios.get('/api/passedevents');
        return response.data;
    } catch (error) {
        console.error('Error fetching passed events:', error);
        return [];
    }
}

// Function to initialize the map with latitude and longitude
function initializeMap(latitude, longitude) {
    if (!latitude || !longitude) {
        console.error('Invalid latitude or longitude:', latitude, longitude);
        return;
    }

    // Initialize the map and set its view to the provided latitude and longitude
    map = L.map('map').setView([latitude, longitude], 12);

    // Add a tile layer to the map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
}

// Function to add markers to the map
function addMarker(map, events) {
    events.forEach(function (event) {
        if (event.latitude && event.longitude) {
            var marker = L.marker([event.latitude, event.longitude]).addTo(map);
            marker.on('click', function () {
                // Optionally, pan the map to the marker location
                map.setView([event.latitude, event.longitude], 14);
            });
            marker.bindPopup('<b>' + event.name + '</b><br><a href="#' + event.id + '">Get more info</a>');
        }
    });
}

// Success callback for geolocation
function success(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

    console.log(latitude, longitude);

    initializeMap(latitude, longitude);
    addMarker(window.map, window.events);
}

// Error callback for geolocation
function error(err) {
    console.error('Geolocation error:', err);

    // Initialize the map with default coordinates
    initializeMap(45.54, 10.21);
    addMarker(window.map, window.events);
}

// Function to create an event card
function createEventCard(event) {
    var card = document.createElement('div');
    card.className = 'card';
    card.id = event.id;

    var cardBody = document.createElement('div');
    cardBody.className = 'card-body';

    var cardTitle = document.createElement('h5');
    cardTitle.className = 'card-title';
    cardTitle.textContent = event.name;

    var cardText = document.createElement('p');
    cardText.className = 'card-text';
    cardText.textContent = event.description;

    var cardButton = document.createElement('a');
    cardButton.className = 'btn light-2';
    cardButton.href = '/passedevents/' + event.id;
    cardButton.textContent = 'Show event info';
    // cardButton.onclick = function () {
    //     alert('Pan to location: ' + event.latitude + ', ' + event.longitude);
    // };

    cardBody.appendChild(cardTitle);
    cardBody.appendChild(cardText);
    cardBody.appendChild(cardButton);
    card.appendChild(cardBody);

    return card;
}

// Function to display event cards
async function displayEventCards(events) {
    var cardsContainer = document.getElementById('cards-container');

    cardsContainer.innerHTML = ''

    events.forEach(function (event) {
        var card = createEventCard(event);
        cardsContainer.appendChild(card);
    });
}

// Initialize geocoder
var geocoder = L.Control.Geocoder.nominatim();

// Handle search functionality
document.getElementById('search-button').addEventListener('click', function () {
    var query = document.getElementById('search-input').value;
    geocoder.geocode(query, function (results) {
        if (results.length > 0) {
            var result = results[0];
            var latlng = result.center;
            map.setView(latlng, 13); // Zoom level of 13
        } else {
            alert('No results found');
        }
    });
});

function showPlaceholders() {
    var cardsContainer = document.getElementById('cards-container');
    for (var i = 0; i < 5; i++) { // Show 5 placeholders
        var placeholderCard = document.createElement('div');
        placeholderCard.className = 'placeholder-card';

        var placeholderTitle = document.createElement('div');
        placeholderTitle.className = 'placeholder-title';

        var placeholderText1 = document.createElement('div');
        placeholderText1.className = 'placeholder-text';

        var placeholderText2 = document.createElement('div');
        placeholderText2.className = 'placeholder-text';

        var placeholderButton = document.createElement('div');
        placeholderButton.className = 'placeholder-btn';

        placeholderCard.appendChild(placeholderTitle);
        placeholderCard.appendChild(placeholderText1);
        placeholderCard.appendChild(placeholderText2);
        placeholderCard.appendChild(placeholderButton);

        cardsContainer.appendChild(placeholderCard);
    }
}

// Document ready event
$(document).ready(async function () {
    showPlaceholders();

    window.events = await getPassedEvents();
    await displayEventCards(window.events);

    // Request geolocation
    navigator.geolocation.getCurrentPosition(success, error);
});
