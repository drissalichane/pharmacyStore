<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Locations - Pharmacy Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- OpenStreetMap (Free Alternative) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-50" style="font-family: 'Ubuntu', sans-serif;">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-gradient">Pharmacy Store</a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Products</a>
                    <a href="{{ route('map.index') }}" class="text-blue-600 font-semibold">Locations</a>
                    @auth
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Cart</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Orders</a>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Admin</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-blue-600 transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="btn-custom btn-custom-primary">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Find Our Pharmacies</h1>
            <p class="text-gray-600">Locate our pharmacy branches and find nearby emergency pharmacies in Marrakech.</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Pharmacies</label>
                    <input type="text" id="search" placeholder="Search by name or address..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="filter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                    <select id="filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Pharmacies</option>
                        <option value="our">Our Pharmacies</option>
                        <option value="emergency">Emergency Pharmacies</option>
                        <option value="24h">24-Hour Pharmacies</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button id="searchBtn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Search
                    </button>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div id="map" class="w-full h-96"></div>
        </div>

        <!-- Pharmacy List -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Pharmacy Locations</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="pharmacyList">
                @foreach($ourPharmacies as $pharmacy)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $pharmacy->name }}</h3>
                        @if($pharmacy->is_24h)
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">24h</span>
                        @endif
                    </div>
                    <p class="text-gray-600 mb-2">{{ $pharmacy->address }}</p>
                    @if($pharmacy->phone)
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-phone mr-2"></i>{{ $pharmacy->phone }}
                        </p>
                    @endif
                    @if($pharmacy->hours)
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-clock mr-2"></i>{{ $pharmacy->hours }}
                        </p>
                    @endif
                    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors w-full"
                            onclick="showOnMap({{ $pharmacy->latitude }}, {{ $pharmacy->longitude }}, '{{ $pharmacy->name }}')">
                        Show on Map
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Emergency Pharmacies Section -->
        @if($emergencyPharmacies->count() > 0)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Emergency Pharmacies (Pharmacie de Garde)</h2>
                <a href="https://www.syndicat-pharmaciens-marrakech.com/pharmacies-de-garde-marrakech" 
                   target="_blank" 
                   class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i>View All Emergency Pharmacies
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($emergencyPharmacies as $pharmacy)
                <div class="bg-red-50 border border-red-200 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $pharmacy->name }}</h3>
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">Emergency</span>
                    </div>
                    <p class="text-gray-600 mb-2">{{ $pharmacy->address }}</p>
                    @if($pharmacy->phone)
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-phone mr-2"></i>{{ $pharmacy->phone }}
                        </p>
                    @endif
                    @if($pharmacy->hours)
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-clock mr-2"></i>{{ $pharmacy->hours }}
                        </p>
                    @endif
                    <button class="mt-4 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors w-full"
                            onclick="showOnMap({{ $pharmacy->latitude }}, {{ $pharmacy->longitude }}, '{{ $pharmacy->name }}')">
                        Show on Map
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- OpenStreetMap Implementation -->
    <script>
    let map;
    let markers = [];

    function initMap() {
        // Initialize OpenStreetMap
        map = L.map('map').setView([31.6295, -7.9811], 12); // Marrakech center
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add our pharmacy markers
        @foreach($ourPharmacies as $pharmacy)
            addMarker(
                {{ $pharmacy->latitude }}, 
                {{ $pharmacy->longitude }}, 
                '{{ $pharmacy->name }}', 
                '{{ $pharmacy->address }}',
                '{{ $pharmacy->phone }}',
                'our'
            );
        @endforeach

        // Add emergency pharmacy markers
        @foreach($emergencyPharmacies as $pharmacy)
            addMarker(
                {{ $pharmacy->latitude }}, 
                {{ $pharmacy->longitude }}, 
                '{{ $pharmacy->name }}', 
                '{{ $pharmacy->address }}',
                '{{ $pharmacy->phone }}',
                'emergency'
            );
        @endforeach
    }

    function addMarker(lat, lng, name, address, phone, type) {
        // Create custom icon
        const icon = L.divIcon({
            className: 'custom-marker',
            html: `<div style="background-color: ${type === 'our' ? '#3B82F6' : '#EF4444'}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        const marker = L.marker([lat, lng], { icon: icon }).addTo(map);

        const content = `
            <div class="p-4">
                <h3 class="font-bold text-lg mb-2">${name}</h3>
                <p class="text-gray-600 mb-2">${address}</p>
                ${phone ? `<p class="text-gray-600"><i class="fas fa-phone mr-2"></i>${phone}</p>` : ''}
                <div class="mt-3">
                    <a href="https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}&zoom=15" 
                       target="_blank" class="text-blue-600 hover:text-blue-800">
                        Get Directions
                    </a>
                </div>
            </div>
        `;

        marker.bindPopup(content);
        markers.push(marker);
    }

    function showOnMap(lat, lng, name) {
        map.setView([lat, lng], 15);
        
        // Find and open popup for this marker
        markers.forEach(marker => {
            if (marker.getLatLng().lat === lat && marker.getLatLng().lng === lng) {
                marker.openPopup();
            }
        });
    }

    function searchPharmacies() {
        const query = document.getElementById('search').value;
        const filter = document.getElementById('filter').value;
        
        fetch(`/map/search?query=${encodeURIComponent(query)}&type=${filter}`)
            .then(response => response.json())
            .then(data => {
                // Clear existing markers
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];
                
                // Add new markers
                data.forEach(pharmacy => {
                    addMarker(
                        pharmacy.latitude,
                        pharmacy.longitude,
                        pharmacy.name,
                        pharmacy.address,
                        pharmacy.phone,
                        pharmacy.is_our_pharmacy ? 'our' : 'emergency'
                    );
                });
            });
    }

    // Event listeners
    document.getElementById('searchBtn').addEventListener('click', searchPharmacies);
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchPharmacies();
        }
    });

    // Initialize map when page loads
    window.addEventListener('load', initMap);
    </script>
</body>
</html> 