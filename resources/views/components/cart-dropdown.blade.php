<div x-data="{ open: false }" class="relative inline-block text-left">
  <div>
    <!-- Cart Icon with hover functionality -->
    <div
      @mouseenter="open = true"
      @mouseleave="open = false"
      class="relative"
    >
      <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-green-500 transition-colors relative block">
        <i class="fas fa-shopping-cart text-xl"></i>
        @if(auth()->check() && auth()->user()->cart_item_count > 0)
          <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
            {{ auth()->user()->cart_item_count }}
          </span>
        @endif
      </a>
    </div>
  </div>

  <!-- Cart Dropdown -->
  <div
    x-show="open"
    x-transition
    @mouseenter="open = true"
    @mouseleave="open = false"
    class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
    role="menu"
    aria-orientation="vertical"
    style="display: none;"
  >
    @auth
      @php
        $cartItems = auth()->user()->cart()->with('product')->take(3)->get();
        $totalItems = auth()->user()->cart_item_count;
        $totalPrice = auth()->user()->cart->sum(function ($item) {
          return $item->quantity * $item->product->current_price;
        });
      @endphp

      @if($cartItems->count() > 0)
        <div class="py-1" role="none">
          <!-- Cart Header -->
          <div class="px-4 py-2 border-b border-gray-100">
            <div class="flex items-center justify-between">
              <h3 class="text-sm font-medium text-gray-900">Your Cart</h3>
              <span class="text-xs text-gray-500">{{ $totalItems }} item{{ $totalItems > 1 ? 's' : '' }}</span>
            </div>
          </div>

          <!-- Cart Items -->
          <div class="max-h-64 overflow-y-auto">
            @foreach($cartItems as $item)
              <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50">
                <div class="flex items-center space-x-3">
                  <!-- Product Image Placeholder -->
                  <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                    <i class="fas fa-pills text-gray-400 text-sm"></i>
                  </div>

                  <!-- Product Details -->
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                      {{ $item->product->name }}
                    </p>
                    <p class="text-xs text-gray-500">
                      Qty: {{ $item->quantity }} × ${{ number_format($item->product->current_price, 2) }}
                    </p>
                  </div>

                  <!-- Item Total -->
                  <div class="text-sm font-medium text-gray-900">
                    ${{ number_format($item->quantity * $item->product->current_price, 2) }}
                  </div>
                </div>
              </div>
            @endforeach

            @if($totalItems > 3)
              <div class="px-4 py-2 text-center text-xs text-gray-500 border-b border-gray-50">
                +{{ $totalItems - 3 }} more item{{ $totalItems - 3 > 1 ? 's' : '' }}
              </div>
            @endif
          </div>

          <!-- Cart Footer -->
          <div class="px-4 py-3 border-t border-gray-100">
            <div class="flex items-center justify-between mb-3">
              <span class="text-sm font-medium text-gray-900">Total:</span>
              <span class="text-sm font-bold text-gray-900">${{ number_format($totalPrice, 2) }}</span>
            </div>

            <a
              href="{{ route('cart.index') }}"
              class="w-full bg-green-600 text-white text-sm font-medium py-2 px-4 rounded-md hover:bg-green-700 transition-colors flex items-center justify-center space-x-2"
            >
              <i class="fas fa-shopping-cart"></i>
              <span>View Cart & Order</span>
            </a>
          </div>
        </div>
      @else
        <div class="py-6 px-4 text-center" role="none">
          <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-shopping-cart text-gray-400 text-lg"></i>
          </div>
          <p class="text-sm text-gray-500 mb-3">Your cart is empty</p>
          <a
            href="{{ route('products.index') }}"
            class="inline-flex items-center space-x-2 text-green-600 hover:text-green-700 text-sm font-medium"
          >
            <i class="fas fa-plus"></i>
            <span>Browse Products</span>
          </a>
        </div>
      @endif
    @else
      <div class="py-6 px-4 text-center" role="none">
        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
          <i class="fas fa-shopping-cart text-gray-400 text-lg"></i>
        </div>
        <p class="text-sm text-gray-500 mb-3">Please log in to view your cart</p>
        <a
          href="{{ route('login') }}"
          class="inline-flex items-center space-x-2 text-green-600 hover:text-green-700 text-sm font-medium"
        >
          <i class="fas fa-sign-in-alt"></i>
          <span>Login</span>
        </a>
      </div>
    @endauth
  </div>
</div>
