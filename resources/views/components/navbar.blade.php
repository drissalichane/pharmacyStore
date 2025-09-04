<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PharmaCare - Your Trusted Pharmacy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f0f9f0 0%, #e6f4e6 100%);
      min-height: 100vh;
    }

    .dropdown {
      opacity: 0;
      visibility: hidden;
      transform: translateY(10px);
      transition: all 0.3s ease;
    }

    .nav-item:hover .dropdown {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .nav-item {
      position: relative;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">
  <!-- Floating Navbar Component -->
  <header class="bg-green-900 py-4 w-full">
    <div class="container mx-auto px-4 bg-white rounded-lg shadow-lg border border-gray-200">
      <!-- Top Row: Logo, Search + Icons -->
      <div class="flex flex-col md:flex-row items-center justify-between p-4 space-y-4 md:space-y-0">
        <!-- Logo Section -->
        <div class="flex-shrink-0">
          <a href="/" class="text-2xl font-bold text-gray-800 hover:text-green-600 transition-colors flex items-center">
            @if(\App\Models\Setting::get('site_logo'))
              <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="{{ \App\Models\Setting::getSiteName() }}" class="h-11 w-auto">
            @else
              <div class="bg-green-100 p-2 rounded-lg mr-2">
                <i class="fas fa-mortar-pestle text-green-600"></i>
              </div>
              <span class="hidden sm:inline">{{ \App\Models\Setting::getSiteName() }}</span>
            @endif
          </a>
        </div>

        <!-- Search + Icons Section -->
        <div class="flex items-center space-x-4 flex-1 max-w-md mx-4">
          <!-- Search Bar -->
          <form action="{{ route('products.index') }}" method="GET" class="flex-grow max-w-lg">
            <div class="relative w-full">
              <input
                type="text"
                name="search"
                placeholder="Search products..."
                class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                value="{{ request('search') }}"
              >
              <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 flex items-center justify-center">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </form>

          <!-- Icons -->
          <div class="flex items-center space-x-3">
            <!-- Heart Icon -->
            <a href="#" class="text-gray-600 hover:text-red-500 transition-colors">
              <i class="fas fa-heart text-xl"></i>
            </a>

            <!-- Cart Icon -->
            <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-green-500 transition-colors relative">
              <i class="fas fa-shopping-cart text-xl"></i>
              @if(auth()->check() && auth()->user()->cart_item_count > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                  {{ auth()->user()->cart_item_count }}
                </span>
              @endif
            </a>

            <!-- Profile Icon Dropdown -->
            @include('components.profile-dropdown')
          </div>
        </div>
      </div>
      </div>

      <!-- Navigation Links Section with Green Background -->
      <div class="bg-green-900 rounded-b-lg">
        <nav class="px-4 py-3">
          <div class="flex flex-wrap justify-center gap-1 md:gap-2">
            <!-- Dynamic Categories -->
            @php
              $rootCategories = \App\Models\Category::root()->where('is_active', true)->orderBy('sort_order')->get();
            @endphp
            @foreach($rootCategories as $category)
              <div class="nav-item group">
                <a
                  href="{{ route('products.category', $category->slug) }}"
                  class="button-glow text-white hover:bg-green-800 font-medium transition-colors whitespace-nowrap px-4 py-2 inline-block"
                >
                  {{ $category->name }}
                </a>
                <!-- Category Tree Dropdown -->
                <div class="dropdown-container relative">
                  <div class="dropdown absolute left-0 bg-white shadow-xl border border-gray-200 rounded-sm mt-1 z-10 w-64 max-h-96 overflow-y-auto">
                    <div class="p-1">
                      @php
                        $level1Categories = $category->children()->where('is_active', true)->orderBy('sort_order')->get();
                      @endphp
                      @foreach($level1Categories as $level1Category)
                        <div class="level1-item relative" data-category-id="{{ $level1Category->id }}">
                          <a href="{{ route('products.category', $level1Category->slug) }}" class="block px-3 py-2 text-gray-800 hover:bg-green-50 hover:text-green-600 rounded text-sm transition-colors">
                            <span class="font-medium">{{ $level1Category->name }}</span>
                            @if($level1Category->children()->where('is_active', true)->count() > 0)
                              <span class="float-right text-gray-400 ml-2">›</span>
                            @endif
                          </a>
                        </div>
                      @endforeach
                    </div>
                  </div>

                  <!-- Level 2 Dropdowns (completely independent) -->
                  @foreach($level1Categories as $level1Category)
                    @php
                      $level2Categories = $level1Category->children()->where('is_active', true)->orderBy('sort_order')->get();
                    @endphp
                    @if($level2Categories->count() > 0)
                      <div class="level2-dropdown level2-dropdown-{{ $level1Category->id }} fixed bg-white shadow-xl border border-gray-200 rounded-sm w-56 max-h-80 overflow-y-auto opacity-0 invisible transition-all duration-200 z-30 pointer-events-none">
                        <div class="p-2">
                          <div class="font-semibold text-gray-700 px-2 py-1 border-b border-gray-100 text-sm">{{ $level1Category->name }}</div>
                          @foreach($level2Categories as $level2Category)
                            <a href="{{ route('products.category', $level2Category->slug) }}" class="block px-2 py-1 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors pointer-events-auto">
                              {{ $level2Category->name }}
                            </a>
                          @endforeach
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            @endforeach

            <!-- Static Categories -->
            <div class="nav-item group">
              <a href="#" class="button-glow text-white hover:bg-green-800 font-medium transition-colors whitespace-nowrap px-4 py-2 inline-block">
                Services
              </a>
              <div class="dropdown absolute left-0 bg-white shadow-xl border border-gray-200 rounded-sm mt-1 w-48 z-10">
                <a href="#" class="block px-4 py-3 text-gray-800 hover:bg-green-50 border-b border-gray-100">Health Consultations</a>
                <a href="#" class="block px-4 py-3 text-gray-800 hover:bg-green-50 border-b border-gray-100">Prescription Refills</a>
                <a href="#" class="block px-4 py-3 text-gray-800 hover:bg-green-50 border-b border-gray-100">Home Delivery</a>
                <a href="#" class="block px-4 py-3 text-gray-800 hover:bg-green-50">Health Screenings</a>
              </div>
            </div>

            <div class="nav-item group">
              <a href="#" class="button-glow text-white hover:bg-green-800 font-medium transition-colors whitespace-nowrap px-4 py-2 inline-block">
                Contact
              </a>
              <div class="dropdown absolute left-0 bg-white shadow-xl border border-gray-200 rounded-sm mt-1 w-48 z-10">
                <a href="#" class="block px-4 py-3 text-gray-800 hover:bg-green-50 border-b border-gray-100">Store Locator</a>
                <a href="#" class="block px-4 py-3 text-gray-800 hover:bg-green-50 border-b border-gray-100">Customer Service</a>
                <a href="#" class="block px-4 py-3 text-gray-800 hover:bg-green-50 border-b border-gray-100">Pharmacist Chat</a>
                <a href="#" class="block px-4 py-3 text-gray-800 hover:bg-green-50">Feedback</a>
              </div>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </header>

  <script>
    // Simple dropdown functionality with improved hover handling
    document.querySelectorAll('.nav-item').forEach(item => {
      const dropdown = item.querySelector('.dropdown');
      let hideTimeout = null;

      if (dropdown) {
        item.addEventListener('mouseenter', () => {
          // Clear any pending hide timeout
          if (hideTimeout) {
            clearTimeout(hideTimeout);
            hideTimeout = null;
          }
          dropdown.style.display = 'block';
        });

        item.addEventListener('mouseleave', (e) => {
          // Check if mouse is moving towards the dropdown
          const dropdownRect = dropdown.getBoundingClientRect();
          const mouseX = e.clientX;
          const mouseY = e.clientY;

          // If mouse is moving towards dropdown area, don't hide immediately
          if (mouseX >= dropdownRect.left && mouseX <= dropdownRect.right &&
              mouseY >= dropdownRect.top && mouseY <= dropdownRect.bottom) {
            return;
          }

          // Add delay before hiding to allow mouse movement
          hideTimeout = setTimeout(() => {
            if (!dropdown.matches(':hover') && !item.matches(':hover')) {
              dropdown.style.display = 'none';
            }
          }, 200);
        });

        dropdown.addEventListener('mouseenter', () => {
          if (hideTimeout) {
            clearTimeout(hideTimeout);
            hideTimeout = null;
          }
        });

        dropdown.addEventListener('mouseleave', () => {
          hideTimeout = setTimeout(() => {
            if (!dropdown.matches(':hover') && !item.matches(':hover')) {
              dropdown.style.display = 'none';
            }
          }, 200);
        });
      }
    });

    // Level 2 dropdown functionality
    let currentLevel2Dropdown = null;
    let hideTimeout = null;

    function hideAllLevel2Dropdowns() {
      document.querySelectorAll('.level2-dropdown').forEach(dropdown => {
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
        dropdown.style.pointerEvents = 'none';
      });
      currentLevel2Dropdown = null;
    }

    function showLevel2Dropdown(dropdown, item) {
      // Hide any currently visible level 2 dropdown
      hideAllLevel2Dropdowns();

      // Calculate position
      const itemRect = item.getBoundingClientRect();
      const navbarRect = document.querySelector('header').getBoundingClientRect();

      // Position the dropdown to the right of the hovered item
      const left = itemRect.right + 8; // 8px gap
      const top = itemRect.top;

      dropdown.style.left = `${left}px`;
      dropdown.style.top = `${top}px`;
      dropdown.style.opacity = '1';
      dropdown.style.visibility = 'visible';
      dropdown.style.pointerEvents = 'auto';

      currentLevel2Dropdown = dropdown;
    }

    document.querySelectorAll('.level1-item').forEach(item => {
      const categoryId = item.getAttribute('data-category-id');
      const level2Dropdown = document.querySelector(`.level2-dropdown-${categoryId}`);

      if (level2Dropdown) {
        item.addEventListener('mouseenter', () => {
          // Clear any pending hide timeout
          if (hideTimeout) {
            clearTimeout(hideTimeout);
            hideTimeout = null;
          }

          showLevel2Dropdown(level2Dropdown, item);
        });

        item.addEventListener('mouseleave', () => {
          hideTimeout = setTimeout(() => {
            if (!level2Dropdown.matches(':hover')) {
              hideAllLevel2Dropdowns();
            }
          }, 150);
        });

        level2Dropdown.addEventListener('mouseenter', () => {
          if (hideTimeout) {
            clearTimeout(hideTimeout);
            hideTimeout = null;
          }
        });

        level2Dropdown.addEventListener('mouseleave', () => {
          hideTimeout = setTimeout(() => {
            hideAllLevel2Dropdowns();
          }, 150);
        });
      }
    });

    // Hide all level 2 dropdowns when mouse leaves the entire nav area
    document.querySelectorAll('.nav-item').forEach(navItem => {
      navItem.addEventListener('mouseleave', () => {
        setTimeout(() => {
          const relatedDropdown = navItem.querySelector('.dropdown');
          if (relatedDropdown && !relatedDropdown.matches(':hover')) {
            hideAllLevel2Dropdowns();
          }
        }, 200);
      });
    });

    // Cursor-based glow pulse effect
    document.querySelectorAll('.button-glow').forEach(button => {
      button.addEventListener('mousemove', (e) => {
        const rect = button.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        button.style.setProperty('--x', `${x}px`);
        button.style.setProperty('--y', `${y}px`);
      });
    });
  </script>
</body>
</html>
