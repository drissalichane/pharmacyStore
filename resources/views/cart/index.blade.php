<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Pharmacy Store</title>
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
                    <a href="{{ route('cart.index') }}" class="text-blue-600 font-semibold">Cart</a>
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600">Orders</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Cart Items ({{ $cartItems->count() }})</h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                            <div class="p-6">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                            @if($item->product->image)
                                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                            @else
                                                <div class="text-2xl text-gray-400">💊</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                                @if($item->product->manufacturer)
                                                    <p class="text-xs text-gray-400">By {{ $item->product->manufacturer }}</p>
                                                @endif
                                            </div>
                                            
                                            <!-- Price -->
                                            <div class="text-right">
                                                @if($item->product->sale_price)
                                                    <span class="text-lg font-bold text-green-600">${{ number_format($item->product->sale_price, 2) }}</span>
                                                    <span class="text-sm text-gray-500 line-through block">${{ number_format($item->product->price, 2) }}</span>
                                                @else
                                                    <span class="text-lg font-bold text-gray-900">${{ number_format($item->product->price, 2) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Quantity and Actions -->
                                        <div class="flex items-center justify-between mt-4">
                                            <div class="flex items-center space-x-4">
                                                <label class="text-sm font-medium text-gray-700">Quantity:</label>
                                                <form id="updateForm-{{ $item->id }}" action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" 
                                                           class="w-16 px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <button type="button" onclick="showUpdateConfirmation({{ $item->id }}, '{{ $item->product->name }}')" class="text-blue-600 hover:text-blue-800 text-sm">Update</button>
                                                </form>
                                            </div>
                                            
                                            <div class="flex items-center space-x-4">
                                                <span class="text-lg font-semibold text-gray-900">
                                                    Total: ${{ number_format($item->total_price, 2) }}
                                                </span>
                                                
                                                <form id="removeForm-{{ $item->id }}" action="{{ route('cart.remove', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="showRemoveConfirmation({{ $item->id }}, '{{ $item->product->name }}')" class="text-red-600 hover:text-red-800 text-sm">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold">${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping:</span>
                                <span class="font-semibold">Free</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax:</span>
                                <span class="font-semibold">$0.00</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('orders.create') }}" class="w-full bg-green-600 text-white py-3 px-6 rounded-md font-semibold hover:bg-green-700 transition-colors text-center block">
                            Proceed to Checkout
                        </a>
                        
                        <div class="mt-4 text-center">
                            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
                <p class="text-gray-600 mb-8">Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700 transition-colors">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    <!-- Update Quantity Confirmation Modal -->
    <div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Quantity Update</h3>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to update the quantity for <strong id="updateProductName"></strong>?
                </p>
                <div class="flex space-x-3">
                    <button onclick="hideUpdateConfirmation()" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button onclick="confirmUpdate()" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                        Proceed
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove Item Confirmation Modal -->
    <div id="removeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Item Removal</h3>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to remove <strong id="removeProductName"></strong> from your cart?
                </p>
                <div class="flex space-x-3">
                    <button onclick="hideRemoveConfirmation()" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button onclick="confirmRemove()" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                        Remove
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
        let currentUpdateForm = null;
        let currentRemoveForm = null;

        function showUpdateConfirmation(itemId, productName) {
            currentUpdateForm = document.getElementById('updateForm-' + itemId);
            document.getElementById('updateProductName').textContent = productName;
            document.getElementById('updateModal').classList.remove('hidden');
        }

        function hideUpdateConfirmation() {
            document.getElementById('updateModal').classList.add('hidden');
            currentUpdateForm = null;
        }

        function confirmUpdate() {
            if (currentUpdateForm) {
                currentUpdateForm.submit();
            }
        }

        function showRemoveConfirmation(itemId, productName) {
            currentRemoveForm = document.getElementById('removeForm-' + itemId);
            document.getElementById('removeProductName').textContent = productName;
            document.getElementById('removeModal').classList.remove('hidden');
        }

        function hideRemoveConfirmation() {
            document.getElementById('removeModal').classList.add('hidden');
            currentRemoveForm = null;
        }

        function confirmRemove() {
            if (currentRemoveForm) {
                currentRemoveForm.submit();
            }
        }

        // Close modals when clicking outside
        document.getElementById('updateModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideUpdateConfirmation();
            }
        });

        document.getElementById('removeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRemoveConfirmation();
            }
        });
    </script>
</body>
</html> 