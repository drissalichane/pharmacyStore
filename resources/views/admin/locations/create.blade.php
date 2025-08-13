<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Location - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- OpenStreetMap -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600">Pharmacy Store</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="text-gray-700 hover:text-blue-600">Products</a>
                    <a href="{{ route('admin.locations.index') }}" class="text-blue-600 font-semibold">Locations</a>
                    <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-blue-600">Orders</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Add New Location</h1>
            <p class="text-gray-600">Add a new pharmacy location with map coordinates</p>
        </div>

        <form method="POST" action="{{ route('admin.locations.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form Fields -->
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Location Name *</label>
                                <input type="text" id="name" name="name" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                                <input type="text" id="address" name="address" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('address') }}" placeholder="Enter address or search below">
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Search Address</label>
                                <div class="flex space-x-2">
                                    <input type="text" id="addressSearch" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Search for address in Marrakech">
                                    <button type="button" id="searchBtn" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Search
                                    </button>
                                </div>
                                <div id="searchResults" class="mt-2 space-y-1"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude *</label>
                                    <input type="number" id="latitude" name="latitude" step="any" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           value="{{ old('latitude', '31.6295') }}">
                                    @error('latitude')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude *</label>
                                    <input type="number" id="longitude" name="longitude" step="any" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           value="{{ old('longitude', '-7.9811') }}">
                                    @error('longitude')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="text" id="phone" name="phone" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('phone') }}">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('email') }}">
                            </div>

                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                <input type="url" id="website" name="website" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('website') }}">
                            </div>

                            <div>
                                <label for="hours" class="block text-sm font-medium text-gray-700 mb-2">Operating Hours</label>
                                <textarea id="hours" name="hours" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('hours') }}</textarea>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea id="description" name="description" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Location Type -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Location Type</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="is_our_pharmacy" name="is_our_pharmacy" value="1" 
                                       {{ old('is_our_pharmacy') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_our_pharmacy" class="ml-2 block text-sm text-gray-900">Our Pharmacy</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="is_emergency_pharmacy" name="is_emergency_pharmacy" value="1" 
                                       {{ old('is_emergency_pharmacy') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_emergency_pharmacy" class="ml-2 block text-sm text-gray-900">Emergency Pharmacy (Pharmacie de Garde)</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="is_24h" name="is_24h" value="1" 
                                       {{ old('is_24h') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_24h" class="ml-2 block text-sm text-gray-900">24-Hour Pharmacy</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Map Location</h3>
                        <p class="text-sm text-gray-600 mb-4">Click on the map to set the location coordinates</p>
                        
                        <div id="map" class="w-full h-96 rounded-lg border"></div>
                        
                        <div class="mt-4 text-sm text-gray-600">
                            <p><strong>Instructions:</strong></p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Click on the map to set the exact location</li>
                                <li>Use the search box to find addresses</li>
                                <li>Coordinates will be automatically filled</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.locations.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create Location
                </button>
            </div>
        </form>
    </div>

    <script>
    let map;
    let marker;

    function initMap() {
        // Initialize map centered on Marrakech
        map = L.map('map').setView([31.6295, -7.9811], 12);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add initial marker
        marker = L.marker([31.6295, -7.9811]).addTo(map);

        // Handle map clicks
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            // Update marker position
            marker.setLatLng([lat, lng]);
            
            // Update form fields
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        });
    }

    // Address search functionality
    document.getElementById('searchBtn').addEventListener('click', function() {
        const query = document.getElementById('addressSearch').value;
        const resultsDiv = document.getElementById('searchResults');
        
        if (query.trim() === '') return;

        // Simulate address search (in real implementation, use a geocoding service)
        const suggestions = [
            {
                address: 'Place Jemaa el-Fna, Medina, Marrakech',
                lat: 31.6255,
                lng: -7.9891
            },
            {
                address: 'Avenue Mohammed V, Gueliz, Marrakech',
                lat: 31.6415,
                lng: -7.9991
            },
            {
                address: 'Boulevard Mohammed VI, Hivernage, Marrakech',
                lat: 31.6355,
                lng: -7.9891
            }
        ];

        resultsDiv.innerHTML = '';
        suggestions.forEach(suggestion => {
            const div = document.createElement('div');
            div.className = 'p-2 hover:bg-gray-100 cursor-pointer border rounded';
            div.textContent = suggestion.address;
            div.onclick = function() {
                document.getElementById('address').value = suggestion.address;
                document.getElementById('latitude').value = suggestion.lat;
                document.getElementById('longitude').value = suggestion.lng;
                marker.setLatLng([suggestion.lat, suggestion.lng]);
                map.setView([suggestion.lat, suggestion.lng], 15);
                resultsDiv.innerHTML = '';
            };
            resultsDiv.appendChild(div);
        });
    });

    // Initialize map when page loads
    window.addEventListener('load', initMap);
    </script>
</body>
</html> 