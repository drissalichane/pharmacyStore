<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Pharmacy Store</title>
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
                    <a href="{{ route('products.index') }}" class="text-blue-600 font-semibold">Products</a>
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
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Our Products</h1>
            <p class="text-gray-600">Browse our wide selection of healthcare products</p>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                    <input type="text" name="search" placeholder="Search products..." 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Apply Filters
                    </button>
                </div>
            </form>

            <!-- Clear Filters -->
            @if(request('search') || request('category') || request('sort') != 'newest')
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    Clear all filters
                </a>
            </div>
            @endif
        </div>

        <!-- Results Count -->
        @if(request('search') || request('category'))
        <div class="mb-4">
            <p class="text-gray-600">
                Showing {{ $products->total() }} result{{ $products->total() != 1 ? 's' : '' }}
                @if(request('search'))
                    for "{{ request('search') }}"
                @endif
                @if(request('category'))
                    in {{ $categories->find(request('category'))->name ?? 'selected category' }}
                @endif
            </p>
        </div>
        @endif

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <!-- Product Image -->
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-4xl text-gray-400">💊</div>
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $product->category->name }}</span>
                        @if($product->requires_prescription)
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Prescription Required</span>
                        @endif
                    </div>
                    
                    <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                    
                    @if($product->manufacturer)
                        <p class="text-xs text-gray-500 mb-2">By {{ $product->manufacturer }}</p>
                    @endif
                    
                    @if($product->dosage_form && $product->strength)
                        <p class="text-xs text-gray-500 mb-3">{{ $product->dosage_form }} - {{ $product->strength }}</p>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->sale_price)
                                <span class="text-lg font-bold text-green-600">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        
                        <div class="text-sm text-gray-500">
                            @if($product->stock_quantity > 0)
                                <span class="text-green-600">In Stock ({{ $product->stock_quantity }})</span>
                            @else
                                <span class="text-red-600">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('products.show', $product) }}" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md text-center hover:bg-blue-700 transition-colors">
                            View Details
                        </a>
                        @auth
                            @if($product->stock_quantity > 0)
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                                        Add to Cart
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        @if($products->count() == 0)
        <div class="text-center py-12">
            <div class="text-6xl mb-4">🔍</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">No products found</h2>
            <p class="text-gray-600 mb-8">
                @if(request('search'))
                    No products match your search for "{{ request('search') }}".
                @else
                    No products available in the selected category.
                @endif
                Try adjusting your search criteria.
            </p>
            <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700 transition-colors">
                View All Products
            </a>
        </div>
        @endif

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Pharmacy Store. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 