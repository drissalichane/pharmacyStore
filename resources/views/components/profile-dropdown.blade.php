<div x-data="{ open: false }" class="relative inline-block text-left">
  <div>
    <button @click="open = !open" type="button" class="inline-flex justify-center items-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none" id="menu-button" :aria-expanded="open.toString()" aria-haspopup="true">
      <i class="fas fa-user text-xl"></i>
    </button>
  </div>

  <div x-show="open" x-transition @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" style="display: none;">
    @auth
      <div class="py-1" role="none">
        <div class="px-4 py-2 border-b border-gray-100">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold">
              {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
              <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
              <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
            </div>
          </div>
        </div>
        <a href="/orders" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 flex items-center space-x-2" role="menuitem" tabindex="-1" id="menu-item-0">
          <i class="fas fa-user-circle"></i>
          <span>My Account</span>
        </a>
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 flex items-center space-x-2" role="menuitem" tabindex="-1" id="menu-item-admin">
            <i class="fas fa-tachometer-alt"></i>
            <span>Admin Dashboard</span>
          </a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full text-left text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 flex items-center space-x-2" role="menuitem" tabindex="-1" id="menu-item-1">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
          </button>
        </form>
      </div>
    @else
      <div class="py-1" role="none">
        <div class="px-4 py-2 border-b border-gray-100">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white font-semibold">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.88 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <div class="text-sm font-medium text-gray-900">Guest</div>
              <div class="text-xs text-gray-500">Not logged in</div>
            </div>
          </div>
        </div>
        <a href="{{ route('login') }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">Login / Register</a>
      </div>
    @endauth
  </div>
</div>
