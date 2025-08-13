<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Pharmacy Store</title>
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
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Shipping & Payment -->
                <div class="space-y-8">
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Shipping Information</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="shipping_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" id="shipping_name" name="shipping_name" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('shipping_name') border-red-500 @enderror"
                                       value="{{ old('shipping_name', auth()->user()->name) }}">
                                @error('shipping_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="shipping_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" id="shipping_email" name="shipping_email" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('shipping_email') border-red-500 @enderror"
                                       value="{{ old('shipping_email', auth()->user()->email) }}">
                                @error('shipping_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="shipping_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel" id="shipping_phone" name="shipping_phone" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('shipping_phone') border-red-500 @enderror"
                                       value="{{ old('shipping_phone') }}">
                                @error('shipping_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                <textarea id="shipping_address" name="shipping_address" rows="3" required
                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('shipping_address') border-red-500 @enderror"
                                          placeholder="Enter your complete shipping address">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" id="shipping_city" name="shipping_city" required
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('shipping_city') border-red-500 @enderror"
                                           value="{{ old('shipping_city') }}">
                                    @error('shipping_city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_state" class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" id="shipping_state" name="shipping_state" required
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('shipping_state') border-red-500 @enderror"
                                           value="{{ old('shipping_state') }}">
                                    @error('shipping_state')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_zip" class="block text-sm font-medium text-gray-700">ZIP Code</label>
                                    <input type="text" id="shipping_zip" name="shipping_zip" required
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('shipping_zip') border-red-500 @enderror"
                                           value="{{ old('shipping_zip') }}">
                                    @error('shipping_zip')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country</label>
                                <select id="shipping_country" name="shipping_country" required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('shipping_country') border-red-500 @enderror">
                                    <option value="">Select Country</option>
                                    <option value="US" {{ old('shipping_country') == 'US' ? 'selected' : '' }}>United States</option>
                                    <option value="CA" {{ old('shipping_country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                    <option value="UK" {{ old('shipping_country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="AU" {{ old('shipping_country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                </select>
                                @error('shipping_country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Order Notes (Optional)</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Any special instructions for your order">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Payment Method</h2>
                        
                        <div class="space-y-4">
                            <!-- Payment Method Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Select Payment Method</label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="payment_method" value="credit_card" class="mr-3" checked>
                                        <div class="flex items-center">
                                            <span class="text-lg mr-2">💳</span>
                                            <span class="font-medium">Credit/Debit Card</span>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="payment_method" value="paypal" class="mr-3">
                                        <div class="flex items-center">
                                            <span class="text-lg mr-2">📱</span>
                                            <span class="font-medium">PayPal</span>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="payment_method" value="apple_pay" class="mr-3">
                                        <div class="flex items-center">
                                            <span class="text-lg mr-2">🍎</span>
                                            <span class="font-medium">Apple Pay</span>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="payment_method" value="google_pay" class="mr-3">
                                        <div class="flex items-center">
                                            <span class="text-lg mr-2">📱</span>
                                            <span class="font-medium">Google Pay</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Credit Card Details (shown by default) -->
                            <div id="creditCardDetails" class="space-y-4">
                                <div>
                                    <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                                    <input type="text" id="card_number" name="card_number" 
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="1234 5678 9012 3456">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="card_expiry" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                        <input type="text" id="card_expiry" name="card_expiry" 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="MM/YY">
                                    </div>
                                    <div>
                                        <label for="card_cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                        <input type="text" id="card_cvv" name="card_cvv" 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="123">
                                    </div>
                                </div>

                                <div>
                                    <label for="card_name" class="block text-sm font-medium text-gray-700">Name on Card</label>
                                    <input type="text" id="card_name" name="card_name" 
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="John Doe">
                                </div>
                            </div>

                            <!-- PayPal Message -->
                            <div id="paypalMessage" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <p class="text-blue-800 text-sm">
                                    You will be redirected to PayPal to complete your payment after placing the order.
                                </p>
                            </div>

                            <!-- Apple Pay Message -->
                            <div id="applePayMessage" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <p class="text-blue-800 text-sm">
                                    Apple Pay will be available during the payment process.
                                </p>
                            </div>

                            <!-- Google Pay Message -->
                            <div id="googlePayMessage" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <p class="text-blue-800 text-sm">
                                    Google Pay will be available during the payment process.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="bg-white rounded-lg shadow-md p-6 h-fit">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Summary</h2>
                    
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-4 py-4 border-b border-gray-200">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    @if($item->product->image)
                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <div class="text-xl text-gray-400">💊</div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900">${{ number_format($item->total_price, 2) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="font-semibold text-green-600">Free</span>
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

                    <div class="mt-6 p-4 bg-blue-50 rounded-md">
                        <h3 class="font-semibold text-blue-900 mb-2">Shipping Information</h3>
                        <p class="text-sm text-blue-700">
                            • Free standard shipping on all orders<br>
                            • Estimated delivery: 3-5 business days<br>
                            • Tracking number will be provided via email
                        </p>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                                class="w-full bg-green-600 text-white py-3 px-6 rounded-md font-semibold hover:bg-green-700 transition-colors">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Pharmacy Store. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Handle payment method selection
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const creditCardDetails = document.getElementById('creditCardDetails');
        const paypalMessage = document.getElementById('paypalMessage');
        const applePayMessage = document.getElementById('applePayMessage');
        const googlePayMessage = document.getElementById('googlePayMessage');

        function showPaymentDetails(method) {
            // Hide all payment details
            creditCardDetails.classList.add('hidden');
            paypalMessage.classList.add('hidden');
            applePayMessage.classList.add('hidden');
            googlePayMessage.classList.add('hidden');

            // Show relevant payment details
            switch(method) {
                case 'credit_card':
                    creditCardDetails.classList.remove('hidden');
                    break;
                case 'paypal':
                    paypalMessage.classList.remove('hidden');
                    break;
                case 'apple_pay':
                    applePayMessage.classList.remove('hidden');
                    break;
                case 'google_pay':
                    googlePayMessage.classList.remove('hidden');
                    break;
            }
        }

        paymentMethods.forEach(method => {
            method.addEventListener('change', (e) => {
                showPaymentDetails(e.target.value);
            });
        });

        // Initialize with credit card selected
        showPaymentDetails('credit_card');
    </script>
</body>
</html> 