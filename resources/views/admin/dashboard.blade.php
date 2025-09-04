<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Pharmacy Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('build/assets/ocr-ChomjTqJ.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
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

                    <!-- Modal Popup for Image Cropping -->
                    <div id="cropModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden overflow-auto">
                        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl relative max-h-[90vh] overflow-y-auto">
                            <button onclick="closeCropModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl z-10">&times;</button>
        <h3 class="text-lg font-bold mb-4">Crop Image for OCR</h3>
        <div class="mb-4">
                    <img id="cropImage" src="" alt="Image to crop" class="max-w-full max-h-96">
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button onclick="closeCropModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button onclick="performCroppedOCR()" class="bg-blue-600 text-white px-4 py-2 rounded">Scan Cropped Area</button>
                </div>
            </div>
        </div>

        <!-- Separate Modal for OCR Image Chunks -->
        <div id="chunksModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden overflow-auto">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-6xl relative max-h-[95vh] overflow-y-auto">
                <button onclick="closeChunksModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl z-10">&times;</button>
                <h3 class="text-lg font-bold mb-4">OCR Image Chunks Debug View</h3>
                <p class="text-sm text-gray-600 mb-4">Each chunk below represents a portion of the cropped image being processed by OCR.</p>
                <div id="chunksContainer" class="grid grid-cols-1 gap-4">
                    <!-- Chunks will be dynamically added here -->
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button onclick="closeChunksModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Close</button>
                </div>
            </div>
        </div>
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

            <!-- Brands Management -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Brands Management</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.brands.index') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-md text-center hover:bg-blue-700 transition-colors">
                        View All Brands
                    </a>
                    <a href="{{ route('admin.brands.create') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded-md text-center hover:bg-green-700 transition-colors">
                        Add New Brand
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
                    <div id="emergencyInfoImageModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-40 overflow-auto hidden">
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
                                    <h4 class="text-sm font-semibold mb-2">OCR Comparison:</h4>
                                    <div class="space-y-4">
                                        <!-- Client-Side OCR -->
                                        <div class="bg-blue-50 rounded-lg p-4">
                                            <h5 class="font-semibold text-blue-800 mb-2">Client-Side OCR (Tesseract.js)</h5>
                                            <button onclick="openCropModalForClient('{{ $emergencyImageUrl }}')" class="bg-blue-600 text-white px-3 py-2 rounded text-sm mb-2">Scan Cropped Area (Client)</button>
                                            <div id="clientScannedWords" class="bg-white rounded p-3 text-gray-700 text-sm min-h-[60px]">
                                               Client-side results will appear here...
                                            </div>
                                        </div>

                                        <!-- Server-Side OCR -->
                                        <div class="bg-green-50 rounded-lg p-4">
                                            <h5 class="font-semibold text-green-800 mb-2">Server-Side OCR (Tesseract CLI)</h5>
                                            <div class="flex gap-2 mb-2">
                                                <button onclick="openCropModalForServer('{{ $emergencyImageUrl }}')" class="bg-green-600 text-white px-3 py-2 rounded text-sm">Scan Cropped Area (Server)</button>
                                                <button onclick="showChunksModal('{{ $emergencyImageUrl }}')" class="bg-purple-600 text-white px-3 py-2 rounded text-sm">Show Chunks</button>
                                            </div>
                                            <div id="serverScannedWords" class="bg-white rounded p-3 text-gray-700 text-sm min-h-[60px]">
                                               Server-side results will appear here...
                                            </div>
                                        </div>

                                        <!-- OCR Image Chunks Debug View -->
                                        <div id="chunkDebugContainer" style="padding: 10px; border: 1px solid #ddd; max-width: 100%; overflow-x: auto; white-space: nowrap; margin-top: 10px;">
                                            <h4 class="font-semibold mb-2">OCR Image Chunks Debug View</h4>
                                            <p style="font-size: 0.875rem; color: #555;">Each chunk below represents a portion of the cropped image being processed by OCR.</p>
                                        </div>
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

            <!-- Settings Management -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Settings Management</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.settings.index') }}" class="block w-full bg-purple-600 text-white py-2 px-4 rounded-md text-center hover:bg-purple-700 transition-colors">
                        Site Settings
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
    let cropper = null;
    let lastProcessedChunks = null; // Store chunks from the last OCR operation

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

    function openCropModal(imageUrl) {
        const cropModal = document.getElementById('cropModal');
        const cropImage = document.getElementById('cropImage');

        cropImage.src = imageUrl;
        cropModal.classList.remove('hidden');

        // Initialize Cropper.js
        cropImage.onload = function() {
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(cropImage, {
                aspectRatio: NaN, // Allow free cropping
                viewMode: 1,
                responsive: true,
                restore: false,
                checkCrossOrigin: false,
                checkOrientation: false,
                modal: true,
                guides: true,
                center: true,
                highlight: true,
                background: false,
                autoCrop: true,
                autoCropArea: 0.8,
                movable: true,
                rotatable: false,
                scalable: true,
                zoomable: true,
                zoomOnTouch: true,
                zoomOnWheel: true,
                wheelZoomRatio: 0.1,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: true,
            });
        };
    }

    // Image chunking function for better OCR accuracy
    async function processImageChunks(canvas, chunkSize = 300) {
        const chunks = [];
        const results = [];
        const width = canvas.width;
        const height = canvas.height;

        // Calculate number of chunks needed
        const cols = Math.ceil(width / chunkSize);
        const rows = Math.ceil(height / chunkSize);

        // Create chunks
        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < cols; col++) {
                const chunkCanvas = document.createElement('canvas');
                const chunkCtx = chunkCanvas.getContext('2d');

                const chunkWidth = Math.min(chunkSize, width - col * chunkSize);
                const chunkHeight = Math.min(chunkSize, height - row * chunkSize);

                chunkCanvas.width = chunkWidth;
                chunkCanvas.height = chunkHeight;

                // Enable high-quality rendering for better OCR accuracy
                chunkCtx.imageSmoothingEnabled = true;
                chunkCtx.imageSmoothingQuality = 'high';

                // Copy chunk from original canvas
                chunkCtx.drawImage(
                    canvas,
                    col * chunkSize, row * chunkSize, chunkWidth, chunkHeight,
                    0, 0, chunkWidth, chunkHeight
                );

                // For debugging: append chunk canvas to a container to visualize chunks
                const debugContainer = document.getElementById('chunkDebugContainer');
                if (debugContainer) {
                    const chunkWrapper = document.createElement('div');
                    chunkWrapper.style.border = '1px solid #ccc';
                    chunkWrapper.style.margin = '2px';
                    chunkWrapper.style.display = 'inline-block';
                    chunkWrapper.style.width = chunkWidth + 'px';
                    chunkWrapper.style.height = chunkHeight + 'px';
                    chunkWrapper.title = `Chunk (${col}, ${row}) - ${chunkWidth}x${chunkHeight}`;
                    chunkWrapper.appendChild(chunkCanvas);
                    debugContainer.appendChild(chunkWrapper);
                }

                chunks.push({
                    canvas: chunkCanvas,
                    x: col * chunkSize,
                    y: row * chunkSize,
                    width: chunkWidth,
                    height: chunkHeight
                });
            }
        }

        // Process each chunk with OCR
        for (let i = 0; i < chunks.length; i++) {
            const chunk = chunks[i];
            const dataUrl = chunk.canvas.toDataURL('image/png');

            try {
                const result = await Tesseract.recognize(dataUrl, 'fra', {
                    logger: info => console.log(`Chunk ${i + 1}/${chunks.length}:`, info.progress)
                });

                if (result.data.text && result.data.text.trim()) {
                    results.push({
                        text: result.data.text.trim(),
                        position: { x: chunk.x, y: chunk.y }
                    });
                }
            } catch (error) {
                console.error(`Error processing chunk ${i + 1}:`, error);
            }
        }

        // Sort results by position (top to bottom, left to right)
        results.sort((a, b) => {
            if (Math.abs(a.position.y - b.position.y) < 50) {
                return a.position.x - b.position.x;
            }
            return a.position.y - b.position.y;
        });

        // Combine all text results
        return results.map(r => r.text).join('\n');
    }

    // New function to open crop modal for client-side OCR
    function openCropModalForClient(imageUrl) {
        openCropModal(imageUrl);
        // Override performCroppedOCR to use client-side OCR
        window.performCroppedOCR = function() {
            if (!cropper) {
                alert('Please select an area to crop first.');
                return;
            }

            const canvas = cropper.getCroppedCanvas({
                width: 1200,
                height: 900,
                minWidth: 256,
                minHeight: 256,
                maxWidth: 4096,
                maxHeight: 4096,
                fillColor: '#fff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            if (!canvas) {
                alert('Failed to crop image. Please try again.');
                return;
            }

            // Use image chunking for better OCR accuracy
            const clientScannedWords = document.getElementById('clientScannedWords');
            clientScannedWords.innerHTML = '<p>Scanning cropped area...</p>';

            // Clear chunk debug container before new chunks
            const debugContainer = document.getElementById('chunkDebugContainer');
            if (debugContainer) {
                debugContainer.innerHTML = '<h4 class="font-semibold mb-2">OCR Image Chunks Debug View</h4><p style="font-size: 0.875rem; color: #555;">Each chunk below represents a portion of the cropped image being processed by OCR.</p>';
            }

            processImageChunks(canvas).then(combinedText => {
                clientScannedWords.innerHTML = `<p>${combinedText || 'No text detected'}</p>`;
                // Close crop modal after processing completes
                closeCropModal();
            }).catch(error => {
                console.error('OCR Error:', error);
                clientScannedWords.innerHTML = '<p>Error during image scanning.</p>';
                // Close crop modal even on error
                closeCropModal();
            });

            // Do NOT closeCropModal() here immediately
        };
    }

    // New function to open crop modal for server-side OCR
    function openCropModalForServer(imageUrl) {
        openCropModal(imageUrl);
        // Override performCroppedOCR to use server-side OCR
        window.performCroppedOCR = function() {
            if (!cropper) {
                alert('Please select an area to crop first.');
                return;
            }

            const canvas = cropper.getCroppedCanvas({
                width: 1200,
                height: 900,
                minWidth: 256,
                minHeight: 256,
                maxWidth: 4096,
                maxHeight: 4096,
                fillColor: '#fff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            if (!canvas) {
                alert('Failed to crop image. Please try again.');
                return;
            }

            // Store chunks for later display
            lastProcessedChunks = generateChunks(canvas);

            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('image', blob, 'cropped-image.png');

                const serverScannedWords = document.getElementById('serverScannedWords');
                serverScannedWords.innerHTML = '<p>Processing cropped image...</p>';

                closeCropModal();

                fetch('/admin/process-cropped-image', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        serverScannedWords.innerHTML = `<p>${data.text}</p>`;
                    } else {
                        serverScannedWords.innerHTML = '<p>Error processing image.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    serverScannedWords.innerHTML = '<p>Error processing image.</p>';
                });
            }, 'image/png');
        };
    }

    function closeCropModal() {
        const cropModal = document.getElementById('cropModal');
        cropModal.classList.add('hidden');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    }

    function performCroppedOCR() {
        if (!cropper) {
            alert('Please select an area to crop first.');
            return;
        }

        // Get cropped canvas
            const canvas = cropper.getCroppedCanvas({
                width: 1200,
                height: 900,
                minWidth: 256,
                minHeight: 256,
                maxWidth: 4096,
                maxHeight: 4096,
                fillColor: '#fff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

        if (!canvas) {
            alert('Failed to crop image. Please try again.');
            return;
        }

        // Convert canvas to blob
        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('image', blob, 'cropped-image.png');

            // Show loading state
            const serverScannedWords = document.getElementById('serverScannedWords');
            serverScannedWords.innerHTML = '<p>Processing cropped image...</p>';

            // Close crop modal
            closeCropModal();

            // Send cropped image to OCR processing
            fetch('/admin/process-cropped-image', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    serverScannedWords.innerHTML = `<p>${data.text}</p>`;
                } else {
                    serverScannedWords.innerHTML = '<p>Error processing image.</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                serverScannedWords.innerHTML = '<p>Error processing image.</p>';
            });
        }, 'image/png');
    }

    // Override the performOCR function to use the client container
    window.performOCR = async function performOCR(imageUrl) {
        console.log('performOCR function triggered with imageUrl:', imageUrl);
        const clientScannedWords = document.getElementById('clientScannedWords');
        if (!clientScannedWords) {
            console.error('Client scanned words container not found');
            return;
        }

        clientScannedWords.innerHTML = '<p>Scanning...</p>';

        try {
            const result = await Tesseract.recognize(
                imageUrl,
                'fra',
                {
                    logger: info => console.log('Tesseract progress:', info)
                }
            );

            console.log('OCR Result:', result.data.text);
            clientScannedWords.innerHTML = `<p>${result.data.text || 'No text detected'}</p>`;
        } catch (error) {
            console.error('OCR Error:', error);
            clientScannedWords.innerHTML = '<p>Error during image scanning.</p>';
        }
    }

    // Function to show chunks modal
    function showChunksModal(imageUrl) {
        const chunksModal = document.getElementById('chunksModal');
        const chunksContainer = document.getElementById('chunksContainer');

        // Clear previous chunks
        chunksContainer.innerHTML = '<p class="text-center text-gray-500">Loading chunks...</p>';

        // If we have stored chunks from a previous scan, use them directly
        if (lastProcessedChunks) {
            displayChunks(lastProcessedChunks, chunksContainer);
            chunksModal.classList.remove('hidden');
            chunksContainer.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        // If no stored chunks, open crop modal to generate new ones
        openCropModal(imageUrl);

        // Override performCroppedOCR to show chunks instead of processing OCR
        window.performCroppedOCR = function() {
            if (!cropper) {
                alert('Please select an area to crop first.');
                return;
            }

            const canvas = cropper.getCroppedCanvas({
                width: 1200,  // Higher resolution for better quality
                height: 900,
                minWidth: 256,
                minHeight: 256,
                maxWidth: 4096,
                maxHeight: 4096,
                fillColor: '#fff',
                imageSmoothingEnabled: true,  // Enable smoothing for better quality
                imageSmoothingQuality: 'high',
            });

            if (!canvas) {
                alert('Failed to crop image. Please try again.');
                return;
            }

            // Generate new chunks and display them
            const chunks = generateChunks(canvas);
            displayChunks(chunks, chunksContainer);

            // Close crop modal and show chunks modal
            closeCropModal();
            chunksModal.classList.remove('hidden');

            // Automatically scroll chunks container to show chunks immediately
            chunksContainer.scrollIntoView({ behavior: 'smooth' });
        };
    }



    // Generate chunks and return array of chunk canvases and metadata
    function generateChunks(canvas, chunkSize = 300) {
        const chunks = [];
        const width = canvas.width;
        const height = canvas.height;

        const cols = Math.ceil(width / chunkSize);
        const rows = Math.ceil(height / chunkSize);

        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < cols; col++) {
                const chunkCanvas = document.createElement('canvas');
                const chunkCtx = chunkCanvas.getContext('2d');

                const chunkWidth = Math.min(chunkSize, width - col * chunkSize);
                const chunkHeight = Math.min(chunkSize, height - row * chunkSize);

                chunkCanvas.width = chunkWidth;
                chunkCanvas.height = chunkHeight;

                chunkCtx.imageSmoothingEnabled = true;
                chunkCtx.imageSmoothingQuality = 'high';
                chunkCtx.drawImage(
                    canvas,
                    col * chunkSize, row * chunkSize, chunkWidth, chunkHeight,
                    0, 0, chunkWidth, chunkHeight
                );

                chunks.push({
                    canvas: chunkCanvas,
                    x: col * chunkSize,
                    y: row * chunkSize,
                    width: chunkWidth,
                    height: chunkHeight
                });
            }
        }
        return chunks;
    }

    // Display chunks in container
    function displayChunks(chunks, container) {
        container.innerHTML = ''; // Clear container
        chunks.forEach((chunk, index) => {
            const chunkWrapper = document.createElement('div');
            chunkWrapper.className = 'bg-white border border-gray-300 rounded-lg p-3 shadow-sm';
            chunkWrapper.innerHTML = `
                <div class="text-xs text-gray-600 mb-2">Chunk ${index + 1} - ${chunk.width}x${chunk.height}</div>
                <div class="flex justify-center">
                    <img src="${chunk.canvas.toDataURL('image/png')}" alt="Chunk ${index + 1}" class="max-w-full max-h-48 border border-gray-200 rounded">
                </div>
            `;
            container.appendChild(chunkWrapper);
        });
    }

    // Function to close chunks modal
    function closeChunksModal() {
        const chunksModal = document.getElementById('chunksModal');
        chunksModal.classList.add('hidden');
    }

    </script>
</body>
</html>