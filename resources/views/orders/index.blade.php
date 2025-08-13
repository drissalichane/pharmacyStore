<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Pharmacy Store</title>
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
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600">Cart</a>
                    <a href="{{ route('orders.index') }}" class="text-blue-600 font-semibold">Orders</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status_badge_class }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->payment_status_badge_class }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Order Items -->
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Items</h4>
                                <div class="space-y-3">
                                    @foreach($order->items as $item)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <div class="text-lg text-gray-400">💊</div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">${{ number_format($item->total_price, 2) }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Order Details -->
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Order Details</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total:</span>
                                        <span class="font-semibold">${{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Shipping Address:</span>
                                        <span class="text-right">{{ $order->shipping_address }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Phone:</span>
                                        <span>{{ $order->shipping_phone }}</span>
                                    </div>
                                    @if($order->notes)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Notes:</span>
                                        <span class="text-right">{{ $order->notes }}</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $orders->links() }}
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📦</div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">No orders yet</h2>
                <p class="text-gray-600 mb-8">You haven't placed any orders yet. Start shopping to see your order history here.</p>
                <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700 transition-colors">
                    Start Shopping
                </a>
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