        <script>
        let emergencyImageZoomed = false;
        let emergencyImageModalImg = null;
        let dragStart = null, dragOffset = {x:0, y:0};
        function openEmergencyImageModal(url) {
            emergencyImageModalImg = document.getElementById('emergencyImageModalImg');
            emergencyImageModalImg.src = url;
            emergencyImageModalImg.style.transform = 'scale(1)';
            emergencyImageModalImg.style.position = 'static';
            emergencyImageModalImg.style.left = emergencyImageModalImg.style.top = '';
            emergencyImageZoomed = false;
            emergencyImageModalImg.classList.remove('cursor-move');
            document.getElementById('emergencyImageModal').classList.remove('hidden');
        }
        function closeEmergencyImageModal() {
            document.getElementById('emergencyImageModal').classList.add('hidden');
            if (emergencyImageModalImg) emergencyImageModalImg.src = '';
        }
        function toggleZoomEmergencyImage() {
            if (!emergencyImageModalImg) return;
            emergencyImageZoomed = !emergencyImageZoomed;
            if (emergencyImageZoomed) {
                emergencyImageModalImg.style.transform = 'scale(2)';
                emergencyImageModalImg.classList.add('cursor-move');
                emergencyImageModalImg.style.position = 'relative';
            } else {
                emergencyImageModalImg.style.transform = 'scale(1)';
                emergencyImageModalImg.classList.remove('cursor-move');
                emergencyImageModalImg.style.left = emergencyImageModalImg.style.top = '';
                emergencyImageModalImg.style.position = 'static';
            }
        }
        // Drag to move when zoomed
        document.addEventListener('mousedown', function(e) {
            if (!emergencyImageZoomed || !emergencyImageModalImg || e.target !== emergencyImageModalImg) return;
            // Get current left/top or default to 0
            let left = parseInt(emergencyImageModalImg.style.left || 0);
            let top = parseInt(emergencyImageModalImg.style.top || 0);
            dragStart = {x: e.clientX, y: e.clientY, left: left, top: top};
            emergencyImageModalImg.style.position = 'relative';
            document.body.style.cursor = 'grabbing';
        });
        document.addEventListener('mousemove', function(e) {
            if (!dragStart || !emergencyImageZoomed || !emergencyImageModalImg) return;
            let dx = e.clientX - dragStart.x;
            let dy = e.clientY - dragStart.y;
            emergencyImageModalImg.style.left = (dragOffset.x + dx) + 'px';
            emergencyImageModalImg.style.top = (dragOffset.y + dy) + 'px';
        });
        document.addEventListener('mouseup', function() {
            dragStart = null;
            document.body.style.cursor = '';
        });
        // Optional: Close modal on background click
        document.getElementById('emergencyImageModal').addEventListener('click', function(e) {
            if (e.target === this) closeEmergencyImageModal();
        });
        </script>
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

         <!-- Quick Access Buttons -->
         <div class="bg-white rounded-lg shadow-md p-6 mb-6">
             <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Access</h2>
             <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                 <button onclick="scrollToMap()" 
                         class="bg-blue-600 text-white px-4 py-3 rounded-md hover:bg-blue-700 transition-colors">
                     <i class="fas fa-map-marker-alt mr-2"></i>View Map
                 </button>
                 <button onclick="scrollToSection('day-pharmacies')" 
                         class="bg-orange-600 text-white px-4 py-3 rounded-md hover:bg-orange-700 transition-colors">
                     <i class="fas fa-sun mr-2"></i>Day Service
                 </button>
                 <button onclick="scrollToSection('night-pharmacies')" 
                         class="bg-purple-600 text-white px-4 py-3 rounded-md hover:bg-purple-700 transition-colors">
                     <i class="fas fa-moon mr-2"></i>Night Service
                 </button>
                 <a href="https://www.syndicat-pharmaciens-marrakech.com/pharmacies-de-garde-marrakech" 
                    target="_blank" 
                    class="bg-red-600 text-white px-4 py-3 rounded-md hover:bg-red-700 transition-colors text-center">
                     <i class="fas fa-external-link-alt mr-2"></i>More Info
                 </a>
             </div>

         </div>
                    @php
                        $emergencyInfoImageUrl = \App\Http\Controllers\Admin\EmergencyInfoImageController::getLatestImageUrl();
                    @endphp
                    @if($emergencyInfoImageUrl)
                    <div class="mb-8 flex flex-col items-center">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Emergency Info</h3>
                        <img src="{{ $emergencyInfoImageUrl }}" alt="Emergency Info" class="rounded-lg shadow-md mb-2 cursor-pointer transition duration-200 hover:scale-105" style="max-width:500px; max-height:350px; object-fit:contain;" onclick="openEmergencyImageModal('{{ $emergencyInfoImageUrl }}')">
                    </div>

                    <!-- Emergency Image Modal -->
                    <div id="emergencyImageModal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-[1000] hidden">
                        <div class="relative flex flex-col items-center justify-center w-full h-full">
                            <button onclick="closeEmergencyImageModal()" class="absolute top-6 right-8 text-white text-4xl font-bold z-20 bg-black bg-opacity-40 rounded-full px-3 py-1 hover:bg-opacity-80 transition">&times;</button>
                            <div id="emergencyImageModalImgWrapper" class="flex items-center justify-center w-full h-full">
                                <img id="emergencyImageModalImg" src="" alt="Emergency Info Zoomed" class="rounded-lg shadow-2xl object-contain cursor-zoom-in bg-white" style="max-width:90vw; max-height:80vh; transition: transform 0.2s; display:block; margin:auto;" onclick="toggleZoomEmergencyImage()">
                            </div>
                        </div>
                    </div>

                    <script>
                    let emergencyImageZoomed = false;
                    let emergencyImageModalImg = null;
                    function openEmergencyImageModal(url) {
                        emergencyImageModalImg = document.getElementById('emergencyImageModalImg');
                        emergencyImageModalImg.src = url;
                        emergencyImageModalImg.style.transform = 'scale(1)';
                        emergencyImageZoomed = false;
                        emergencyImageModalImg.classList.remove('cursor-move');
                        document.getElementById('emergencyImageModal').classList.remove('hidden');
                    }
                    function closeEmergencyImageModal() {
                        document.getElementById('emergencyImageModal').classList.add('hidden');
                        if (emergencyImageModalImg) emergencyImageModalImg.src = '';
                    }
                    function toggleZoomEmergencyImage() {
                        if (!emergencyImageModalImg) return;
                        emergencyImageZoomed = !emergencyImageZoomed;
                        if (emergencyImageZoomed) {
                            emergencyImageModalImg.style.transform = 'scale(2)';
                            emergencyImageModalImg.classList.add('cursor-move');
                        } else {
                            emergencyImageModalImg.style.transform = 'scale(1)';
                            emergencyImageModalImg.classList.remove('cursor-move');
                        }
                    }
                    // Optional: Close modal on background click
                    document.getElementById('emergencyImageModal').addEventListener('click', function(e) {
                        if (e.target === this) closeEmergencyImageModal();
                    });
                    </script>
                    @endif

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
                         <option value="emergency-day">Emergency (Day)</option>
                         <option value="emergency-night">Emergency (Night)</option>
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
         <div id="map-container" class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
             <div id="map" class="w-full h-[600px]" style="position:relative; z-index:10;"></div>
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
                             onclick="showOnMapAndScroll({{ $pharmacy->latitude }}, {{ $pharmacy->longitude }}, '{{ $pharmacy->name }}')">
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
                 <div class="flex space-x-2">
                     <select id="regionSelector" class="bg-gray-100 border border-gray-300 rounded-md px-4 py-2">
                         <option value="">All Regions</option>
                         @php
                             $regions = $emergencyPharmacies->pluck('description')->map(function($desc) {
                                 if (preg_match('/Region: ([^\.]+)/i', $desc, $match)) {
                                     return trim($match[1]);
                                 }
                                 return null;
                             })->filter()->unique()->sort()->values();
                         @endphp
                         @foreach($regions as $region)
                             <option value="{{ $region }}">{{ $region }}</option>
                         @endforeach
                     </select>
                     <button onclick="scrollToSection('day-pharmacies')" 
                             class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors">
                         <i class="fas fa-sun mr-2"></i>Day Service
                     </button>
                     <button onclick="scrollToSection('night-pharmacies')" 
                             class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors">
                         <i class="fas fa-moon mr-2"></i>Night Service
                     </button>
                     <a href="https://www.syndicat-pharmaciens-marrakech.com/pharmacies-de-garde-marrakech" 
                        target="_blank" 
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                         <i class="fas fa-external-link-alt mr-2"></i>View All Emergency Pharmacies
                     </a>
                 </div>
             </div>
             @php
                 $selectedRegion = request('region') ?? '';
                 $dayPharmacies = $emergencyPharmacies->filter(function($pharmacy) use ($selectedRegion) {
                     $isDay = trim(strtolower($pharmacy->hours)) === 'de 09h a 23h sans interruption';
                     $region = trim(explode(',', $pharmacy->address)[0]);
                     return $isDay && ($selectedRegion === '' || $region === $selectedRegion);
                 });
                 $nightPharmacies = $emergencyPharmacies->filter(function($pharmacy) use ($selectedRegion) {
                     $isNight = trim(strtolower($pharmacy->hours)) === 'de 23h a 09h sans interruption';
                     $region = trim(explode(',', $pharmacy->address)[0]);
                     return $isNight && ($selectedRegion === '' || $region === $selectedRegion);
                 });
             @endphp
             @if($dayPharmacies->count() > 0)
             <div id="day-pharmacies" class="mb-6">
                 <h3 class="text-xl font-semibold text-gray-800 mb-4">Garde du Jour (Day Service)</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                     @foreach($dayPharmacies as $pharmacy)
                     @php
                         $region = null;
                         if (preg_match('/Region: ([^\.]+)/i', $pharmacy->description, $match)) {
                             $region = trim($match[1]);
                         }
                     @endphp
                     <div class="bg-orange-50 border border-orange-200 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow" data-region="{{ $region }}">
                         <div class="flex items-start justify-between mb-4">
                             <h4 class="text-lg font-semibold text-gray-900">{{ $pharmacy->name }}</h4>
                             <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2 py-1 rounded-full">Day</span>
                         </div>
                         <p class="text-gray-600 mb-2">{{ html_entity_decode($pharmacy->address) }}</p>
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
                         <button class="mt-4 bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors w-full"
                                 onclick="showOnMapAndScroll({{ $pharmacy->latitude }}, {{ $pharmacy->longitude }}, '{{ $pharmacy->name }}')">
                             Show on Map
                         </button>
                     </div>
                     @endforeach
                 </div>
             </div>
             @endif
             @if($nightPharmacies->count() > 0)
             <div id="night-pharmacies" class="mb-6">
                 <h3 class="text-xl font-semibold text-gray-800 mb-4">Garde de Nuit (Night Service)</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                     @foreach($nightPharmacies as $pharmacy)
                     @php
                         $region = null;
                         if (preg_match('/Region: ([^\.]+)/i', $pharmacy->description, $match)) {
                             $region = trim($match[1]);
                         }
                     @endphp
                     <div class="bg-purple-50 border border-purple-200 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow" data-region="{{ $region }}">
                         <div class="flex items-start justify-between mb-4">
                             <h4 class="text-lg font-semibold text-gray-900">{{ $pharmacy->name }}</h4>
                             <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded-full">Night</span>
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
                         <button class="mt-4 bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors w-full"
                                 onclick="showOnMapAndScroll({{ $pharmacy->latitude }}, {{ $pharmacy->longitude }}, '{{ $pharmacy->name }}')">
                             Show on Map
                         </button>
                     </div>
                     @endforeach
                 </div>
             </div>
             @endif
         </div>
         @endif

     <!-- OpenStreetMap Implementation -->
    <script>
    // Emergency region selector instant filtering
    document.addEventListener('DOMContentLoaded', function() {
        const regionSelector = document.getElementById('regionSelector');
        regionSelector.addEventListener('change', function() {
            const selected = regionSelector.value;
            document.querySelectorAll('[data-region]').forEach(function(card) {
                if (!selected || card.getAttribute('data-region') === selected) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
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

     function showOnMapAndScroll(lat, lng, name) {
         // First scroll to the map
         scrollToMap();
         
         // Then show the location on map after a short delay
         setTimeout(() => {
             showOnMap(lat, lng, name);
         }, 500);
     }

     function scrollToMap() {
         const mapContainer = document.getElementById('map-container');
         mapContainer.scrollIntoView({ 
             behavior: 'smooth', 
             block: 'start' 
         });
     }

     function scrollToSection(sectionId) {
         const section = document.getElementById(sectionId);
         if (section) {
             section.scrollIntoView({ 
                 behavior: 'smooth', 
                 block: 'start' 
             });
         }
     }

     function scrollToTop() {
         window.scrollTo({
             top: 0,
             behavior: 'smooth'
         });
     }

     // Show/hide back to top button based on scroll position
     window.addEventListener('scroll', function() {
         const backToTopButton = document.getElementById('backToTop');
         if (window.scrollY > 300) {
             backToTopButton.classList.remove('opacity-0', 'invisible');
             backToTopButton.classList.add('opacity-100', 'visible');
         } else {
             backToTopButton.classList.add('opacity-0', 'invisible');
             backToTopButton.classList.remove('opacity-100', 'visible');
         }
     });

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