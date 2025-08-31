<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Pharmacy Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('build/assets/ocr-ChomjTqJ.js') }}"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold">Admin Dashboard</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white">View Store</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="text-3xl text-blue-600 mr-4">📦</div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ \App\Models\Product::count() }}</h3>
                        <p class="text-gray-600">Total Products</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="text-3xl text-green-600 mr-4">🏷️</div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ \App\Models\Category::count() }}</h3>
                        <p class="text-gray-600">Categories</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="text-3xl text-purple-600 mr-4">📋</div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ \App\Models\Order::count() }}</h3>
                        <p class="text-gray-600">Total Orders</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="text-3xl text-orange-600 mr-4">👥</div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ \App\Models\User::count() }}</h3>
                        <p class="text-gray-600">Users</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Products Management -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Products Management</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.products.index') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-md text-center hover:bg-blue-700 transition-colors">
                        View All Products
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded-md text-center hover:bg-green-700 transition-colors">
                        Add New Product
                    </a>
                </div>
            </div>

            <!-- Categories Management -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Categories Management</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.categories.index') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-md text-center hover:bg-blue-700 transition-colors">
                        View All Categories
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded-md text-center hover:bg-green-700 transition-colors">
                        Add New Category
                    </a>
                </div>
            </div>

            <!-- Locations Management -->
            <div class="bg-white rounded-lg shadow-md p-6 relative">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Locations Management</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.locations.index') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-md text-center hover:bg-blue-700 transition-colors">
                        View All Locations
                    </a>
                    <a href="{{ route('admin.locations.create') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded-md text-center hover:bg-green-700 transition-colors">
                        Add New Location
                    </a>
                    <button onclick="runScraping()" class="block w-full bg-orange-600 text-white py-2 px-4 rounded-md text-center hover:bg-orange-700 transition-colors">
                        Update Emergency Pharmacies
                    </button>
                    {{-- Removed Upload Pharmacy Info Image button --}}
                        <button onclick="document.getElementById('emergencyInfoImageModal').classList.remove('hidden')" class="block w-full bg-red-600 text-white py-2 px-4 rounded-md text-center hover:bg-red-700 transition-colors">
                            Upload Emergency Info Image
                        </button>
                </div>
                <!-- Modal Popup for Image Upload -->
                    <!-- Modal Popup for Pharmacy Info Image Upload -->
                    <div id="pharmacyImageModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
                            <button onclick="document.getElementById('pharmacyImageModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                            <h3 class="text-lg font-bold mb-4">Upload Pharmacy Info Image</h3>
                            <form method="POST" action="{{ route('admin.pharmacy-image.upload') }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <input type="file" name="pharmacy_image" accept="image/*" required class="block w-full border rounded p-2">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded w-full">Upload</button>
                            </form>
                            @php
                                $imageUrl = \App\Http\Controllers\Admin\AdminPharmacyImageController::getLatestImageUrl();
                            @endphp
                            @if($imageUrl)
                                <div class="mt-4">
                                    <h4 class="text-sm font-semibold mb-2">Latest Uploaded Image:</h4>
                                    <img src="{{ $imageUrl }}" alt="Pharmacy Info" class="rounded shadow w-full">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Modal Popup for Emergency Info Image Upload -->
                    <div id="emergencyInfoImageModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 overflow-auto hidden">
                        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative overflow-y-auto max-h-[90vh]">
                            <button onclick="document.getElementById('emergencyInfoImageModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                            <h3 class="text-lg font-bold mb-4">Upload Emergency Info Image</h3>
                            <form method="POST" action="{{ route('admin.emergency-info-image.upload') }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <input type="file" name="emergency_info_image" accept="image/*" required class="block w-full border rounded p-2">
                                <input type="text" name="title" placeholder="Title (optional)" class="block w-full border rounded p-2">
                                <textarea name="description" placeholder="Description (optional)" class="block w-full border rounded p-2"></textarea>
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded w-full">Upload</button>
                            </form>
                            @php
                                $emergencyImageUrl = \App\Http\Controllers\Admin\EmergencyInfoImageController::getLatestImageUrl();
                            @endphp
                            @if($emergencyImageUrl)
                                <div class="mt-4">
                                    <h4 class="text-sm font-semibold mb-2">Latest Emergency Info Image:</h4>
                                    <img src="{{ $emergencyImageUrl }}" alt="Emergency Info" class="rounded shadow w-full">
                                </div>
                                <div class="mt-4">
                                    <h4 class="text-sm font-semibold mb-2">Scanned Words:</h4>
@php
    $emergencyImageUrl = \App\Http\Controllers\Admin\EmergencyInfoImageController::getLatestImageUrl();
@endphp
<button onclick="performOCR('{{ $emergencyImageUrl }}')" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">Scan</button>
                                    <div id="scannedWords" class="bg-gray-100 rounded-lg shadow-md p-4 w-full max-w-md text-gray-700">
                                       <!-- Scanned words will be dynamically inserted here -->
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
            </div>

            <!-- Orders Management -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Orders Management</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.orders.index') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-md text-center hover:bg-blue-700 transition-colors">
                        View All Orders
                    </a>
                    <a href="{{ route('admin.orders.index') }}?status=pending" class="block w-full bg-yellow-600 text-white py-2 px-4 rounded-md text-center hover:bg-yellow-700 transition-colors">
                        Pending Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Recent Orders</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(\App\Models\Order::with('user')->latest()->take(5)->get() as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- Removed Upload Pharmacy Info Image button below recent orders --}}
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Pharmacy Store Admin. All rights reserved.</p>
        </div>
    </footer>

    <script>
    function runScraping() {
        if (confirm('This will update emergency pharmacies from the syndicat website. This may take a few minutes. Continue?')) {
            // Show loading state
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Updating...';
            button.disabled = true;
            
            // Add loading spinner
            const spinner = document.createElement('span');
            spinner.className = 'ml-2 animate-spin';
            spinner.innerHTML = '⏳';
            button.appendChild(spinner);
            
            // Make AJAX call to run the scraping command with longer timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 300000); // 5 minute timeout
            
            fetch('/admin/scrape-pharmacie-de-garde', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                signal: controller.signal
            })
            .then(response => {
                clearTimeout(timeoutId);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Emergency pharmacies updated successfully! Visit the map to see the changes.');
                } else {
                    alert('Error updating pharmacies: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.name === 'AbortError') {
                    alert('Request timed out. The scraping process may still be running in the background. Please check back later.');
                } else {
                    alert('Error updating pharmacies. Please try again or check the console for details.');
                }
            })
            .finally(() => {
                // Reset button
                button.textContent = originalText;
                button.disabled = false;
                button.removeChild(spinner);
            });
        }
    }

    </script>
</body>
</html>