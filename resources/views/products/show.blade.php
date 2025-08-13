<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Pharmacy Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600">Products</a>
                    @auth
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600">Cart</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600">Orders</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-blue-600">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="/" class="hover:text-blue-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-blue-600">Products</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('products.category', $product->category) }}" class="hover:text-blue-600">{{ $product->category->name }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                    @else
                        <div class="text-8xl text-gray-400">💊</div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Category and Badges -->
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $product->category->name }}</span>
                    @if($product->requires_prescription)
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Prescription Required</span>
                    @endif
                </div>

                <!-- Product Name -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="mb-6">
                    @if($product->sale_price)
                        <div class="flex items-center space-x-3">
                            <span class="text-3xl font-bold text-green-600">${{ number_format($product->sale_price, 2) }}</span>
                            <span class="text-xl text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">
                                {{ $product->discount_percentage }}% OFF
                            </span>
                        </div>
                    @else
                        <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Stock Status -->
                <div class="mb-6">
                    @if($product->stock_quantity > 0)
                        <span class="text-green-600 font-semibold">✓ In Stock ({{ $product->stock_quantity }} available)</span>
                    @else
                        <span class="text-red-600 font-semibold">✗ Out of Stock</span>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-4 mb-6">
                    @if($product->manufacturer)
                        <div>
                            <span class="font-semibold text-gray-700">Manufacturer:</span>
                            <span class="text-gray-600">{{ $product->manufacturer }}</span>
                        </div>
                    @endif

                    @if($product->dosage_form && $product->strength)
                        <div>
                            <span class="font-semibold text-gray-700">Dosage:</span>
                            <span class="text-gray-600">{{ $product->dosage_form }} - {{ $product->strength }}</span>
                        </div>
                    @endif

                    @if($product->ingredients)
                        <div>
                            <span class="font-semibold text-gray-700">Ingredients:</span>
                            <p class="text-gray-600 mt-1">{{ $product->ingredients }}</p>
                        </div>
                    @endif

                    @if($product->usage_instructions)
                        <div>
                            <span class="font-semibold text-gray-700">Usage Instructions:</span>
                            <p class="text-gray-600 mt-1">{{ $product->usage_instructions }}</p>
                        </div>
                    @endif

                    @if($product->side_effects)
                        <div>
                            <span class="font-semibold text-gray-700">Side Effects:</span>
                            <p class="text-gray-600 mt-1">{{ $product->side_effects }}</p>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                @if($product->description)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">Description</h3>
                        <p class="text-gray-600">{{ $product->description }}</p>
                    </div>
                @endif

                <!-- Add to Cart -->
                @auth
                    @if($product->stock_quantity > 0)
                        <form id="addToCartForm" action="{{ route('cart.add', $product) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" 
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="button" onclick="showConfirmation()" class="w-full bg-green-600 text-white py-3 px-6 rounded-md font-semibold hover:bg-green-700 transition-colors">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-400 text-white py-3 px-6 rounded-md font-semibold cursor-not-allowed">
                            Out of Stock
                        </button>
                    @endif
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <p class="text-blue-800 text-center">
                            <a href="{{ route('login') }}" class="font-semibold hover:underline">Login</a> or 
                            <a href="{{ route('register') }}" class="font-semibold hover:underline">Register</a> to add items to cart
                        </p>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-32 bg-gray-200 flex items-center justify-center">
                        @if($relatedProduct->image)
                            <img src="{{ $relatedProduct->image }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-3xl text-gray-400">💊</div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-sm mb-2">{{ $relatedProduct->name }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900">${{ number_format($relatedProduct->current_price, 2) }}</span>
                            <a href="{{ route('products.show', $relatedProduct) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Add to Cart</h3>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to add <span id="confirmQuantity">1</span> x <strong>{{ $product->name }}</strong> to your cart?
                </p>
                <div class="flex space-x-3">
                    <button onclick="hideConfirmation()" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button onclick="confirmAddToCart()" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                        Proceed
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Pharmacy Store. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function showConfirmation() {
            const quantity = document.getElementById('quantity').value;
            document.getElementById('confirmQuantity').textContent = quantity;
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        function hideConfirmation() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        function confirmAddToCart() {
            document.getElementById('addToCartForm').submit();
        }

        // Close modal when clicking outside
        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideConfirmation();
            }
        });
    </script>
</body>
</html> 