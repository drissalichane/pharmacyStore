<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Product Details</h1>
                    <p class="text-gray-600 mt-2">View complete product information</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Edit Product
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                        Back to Products
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Product Image and Basic Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mb-6">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg">
                        @else
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">No Image</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600">ID: {{ $product->id }}</p>
                        </div>

                        <div>
                            <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                            @if($product->sale_price)
                                <span class="text-lg text-gray-500 line-through ml-2">${{ number_format($product->sale_price, 2) }}</span>
                            @endif
                        </div>

                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $product->stock_quantity > 10 ? 'bg-green-100 text-green-800' :
                                   ($product->stock_quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $product->stock_quantity > 10 ? 'In Stock' :
                                   ($product->stock_quantity > 0 ? 'Low Stock' : 'Out of Stock') }}
                                ({{ $product->stock_quantity }})
                            </span>
                        </div>

                        @if($product->brand)
                            <div>
                                <p class="text-sm text-gray-600">Brand</p>
                                <p class="font-medium">{{ $product->brand->name }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-sm text-gray-600">Category</p>
                            <p class="font-medium">{{ $product->category->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Manufacturer</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->manufacturer ?: 'Not specified' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Regular Price</label>
                            <p class="mt-1 text-sm text-gray-900">${{ number_format($product->price, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sale Price</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->sale_price ? '$' . number_format($product->sale_price, 2) : 'Not on sale' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->stock_quantity }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($product->description)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                    <p class="text-gray-700">{{ $product->description }}</p>
                </div>
                @endif

                <!-- Medical Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Medical Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dosage Form</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->dosage_form ?: 'Not specified' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Strength</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->strength ?: 'Not specified' }}</p>
                        </div>
                    </div>

                    @if($product->ingredients)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ingredients</label>
                        <p class="text-gray-700">{{ $product->ingredients }}</p>
                    </div>
                    @endif

                    @if($product->usage_instructions)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Usage Instructions</label>
                        <p class="text-gray-700">{{ $product->usage_instructions }}</p>
                    </div>
                    @endif

                    @if($product->side_effects)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Side Effects</label>
                        <p class="text-gray-700">{{ $product->side_effects }}</p>
                    </div>
                    @endif
                </div>

                <!-- Category Hierarchy -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Category Information</h2>
                    <div class="space-y-2">
                        @if(isset($categoryHierarchy) && count($categoryHierarchy) > 0)
                            @foreach($categoryHierarchy as $index => $category)
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 w-20">Level {{ $index + 1 }}:</span>
                                    <span class="text-sm font-medium">{{ $category->name }}</span>
                                    @if($category->root_type)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $category->root_type }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-600">No category hierarchy available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Pharmacy Store Admin. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
