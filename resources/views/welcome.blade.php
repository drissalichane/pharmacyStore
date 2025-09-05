<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Store - Your Health, Our Priority</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" style="font-family: 'Ubuntu', sans-serif;">
    <!-- Navigation -->
    @include('components.navbar')

    <!-- Hero Section -->
    <section class="bg-green-900 text-white py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-4xl mx-auto">
                <div class="scroll-animate">
                    <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                        Your Health, 
                        <span class="text-gradient">Our Priority</span>
                    </h1>
                </div>
                <div class="scroll-animate delay-100">
                    <p class="text-xl mb-8 text-green-100 max-w-3xl mx-auto">
                        Discover a wide range of healthcare products, from prescription medications to wellness supplements. 
                        Fast, reliable, and secure online pharmacy services.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 justify-center scroll-animate delay-200">
                    <a href="{{ route('products.index') }}" class="btn-custom btn-custom-secondary text-lg">
                        Shop Now
                    </a>
                    <a href="#categories" class="btn-custom btn-custom-outline text-lg">
                        Browse Categories
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center scroll-animate hover-lift">
                    <div class="text-4xl mb-4">🚚</div>
                    <h3 class="text-xl font-semibold mb-2">Fast Delivery</h3>
                    <p class="text-gray-600">Same-day delivery for urgent medications</p>
                </div>
                <div class="text-center scroll-animate delay-100 hover-lift">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="text-xl font-semibold mb-2">Secure & Safe</h3>
                    <p class="text-gray-600">Licensed pharmacy with secure transactions</p>
                </div>
                <div class="text-center scroll-animate delay-200 hover-lift">
                    <div class="text-4xl mb-4">👨‍⚕️</div>
                    <h3 class="text-xl font-semibold mb-2">Expert Support</h3>
                    <p class="text-gray-600">Professional consultation available 24/7</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12 scroll-animate">
                <h2 class="text-3xl font-bold mb-4">Shop by Category</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Browse our comprehensive selection of healthcare products organized by category for easy navigation
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="card-custom bg-white rounded-lg p-6 hover-lift scroll-animate">
                    <div class="text-center">
                        <div class="text-4xl mb-4">💊</div>
                        <h3 class="text-lg font-semibold mb-2">Medications</h3>
                        <p class="text-sm text-gray-600 mb-4">Licensed prescription medications</p>
                        <a href="{{ route('products.index', ['category' => 1]) }}" class="btn-custom btn-custom-primary">Browse</a>
                    </div>
                </div>
                
                <div class="card-custom bg-white rounded-lg p-6 hover-lift scroll-animate delay-100">
                    <div class="text-center">
                        <div class="text-4xl mb-4">🌿</div>
                        <h3 class="text-lg font-semibold mb-2">Vitamins & Supplements</h3>
                        <p class="text-sm text-gray-600 mb-4">Natural health supplements</p>
                        <a href="{{ route('products.index', ['category' => 4]) }}" class="btn-custom btn-custom-primary">Browse</a>
                    </div>
                </div>
                
                <div class="card-custom bg-white rounded-lg p-6 hover-lift scroll-animate delay-200">
                    <div class="text-center">
                        <div class="text-4xl mb-4">🩹</div>
                        <h3 class="text-lg font-semibold mb-2">Medical Supplies</h3>
                        <p class="text-sm text-gray-600 mb-4">Emergency and medical supplies</p>
                        <a href="{{ route('products.index', ['category' => 3]) }}" class="btn-custom btn-custom-primary">Browse</a>
                    </div>
                </div>
                
                <div class="card-custom bg-white rounded-lg p-6 hover-lift scroll-animate delay-300">
                    <div class="text-center">
                        <div class="text-4xl mb-4">🧴</div>
                        <h3 class="text-lg font-semibold mb-2">Personal Care</h3>
                        <p class="text-sm text-gray-600 mb-4">Hygiene and personal care items</p>
                        <a href="{{ route('products.index', ['category' => 2]) }}" class="btn-custom btn-custom-primary">Browse</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12 scroll-animate">
                <h2 class="text-3xl font-bold mb-4">Featured Products</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Discover our most popular healthcare products trusted by thousands of customers
                </p>
            </div>

            @if($featuredProducts->count() > 0)
            <div class="relative">
                <!-- Navigation Arrows -->
                <button id="prev-btn" class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-3 shadow-lg hover:shadow-xl transition-shadow duration-300 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <button id="next-btn" class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-3 shadow-lg hover:shadow-xl transition-shadow duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Products Container -->
                <div id="products-container" class="overflow-hidden min-h-[500px]">
                    <div id="products-slider" class="flex transition-transform duration-300 ease-in-out">
                        @foreach($featuredProducts as $index => $product)
                        <div class="flex-none w-full md:w-1/2 lg:w-1/3 xl:w-1/4 px-3">
                            <div class="card-custom bg-white rounded-lg overflow-hidden hover-lift scroll-animate">
                                <div class="h-48 bg-gray-200 flex items-center justify-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="text-4xl text-gray-400">
                                            @if($product->category && $product->category->name)
                                                @switch($product->category->name)
                                                    @case('Medications')
                                                        💊
                                                        @break
                                                    @case('Vitamins & Supplements')
                                                        🌿
                                                        @break
                                                    @case('Medical Supplies')
                                                        🩹
                                                        @break
                                                    @case('Personal Care')
                                                        🧴
                                                        @break
                                                    @default
                                                        📦
                                                @endswitch
                                            @else
                                                📦
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        @if($product->category)
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">{{ $product->category->name }}</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-medium">General</span>
                                        @endif
                                    </div>
                                    <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 60) }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-gray-900">${{ number_format($product->current_price, 2) }}</span>
                                        <span class="text-sm {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                        </span>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('products.show', $product) }}" class="btn-custom btn-custom-primary w-full block text-center">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-xl font-semibold mb-2">No Products Available</h3>
                <p class="text-gray-600">Check back soon for our featured products!</p>
            </div>
            @endif

            <div class="text-center mt-8 scroll-animate delay-400">
                <a href="{{ route('products.index') }}" class="btn-custom btn-custom-ghost text-lg">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Brands Section -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12 scroll-animate">
                <h2 class="text-3xl font-bold mb-4">Featured Brands</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Discover trusted brands we proudly feature
                </p>
            </div>

            @if($featuredBrands->count() > 0)
            <div class="relative">
                <!-- Navigation Arrows -->
                <button id="brands-prev-btn" class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-3 shadow-lg hover:shadow-xl transition-shadow duration-300 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <button id="brands-next-btn" class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-3 shadow-lg hover:shadow-xl transition-shadow duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Brands Container -->
                <div id="brands-container" class="overflow-hidden min-h-[300px]">
                    <div id="brands-slider" class="flex flex-wrap transition-transform duration-300 ease-in-out">
                        @foreach($featuredBrands as $index => $brand)
                        <div class="flex-none w-1/4 p-4 cursor-pointer">
                            <a href="#" class="block">
                                <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" class="mx-auto h-24 object-contain">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🏷️</div>
                <h3 class="text-xl font-semibold mb-2">No Brands Available</h3>
                <p class="text-gray-600">Check back soon for our featured brands!</p>
            </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 bg-green-900 text-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="scroll-animate">
                <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
                <p class="text-xl mb-8 text-green-100">
                    Join thousands of satisfied customers who trust us with their healthcare needs
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 justify-center scroll-animate delay-100">
                <a href="{{ route('register') }}" class="btn-custom btn-custom-secondary text-lg">
                    Create Account
                </a>
                <a href="{{ route('products.index') }}" class="btn-custom btn-custom-outline text-lg">
                    Browse Products
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="scroll-animate">
                    <h3 class="text-xl font-bold mb-4">Pharmacy Store</h3>
                    <p class="text-gray-300">
                        Your trusted partner for all healthcare needs. Professional, reliable, and secure.
                    </p>
                </div>
                <div class="scroll-animate delay-100">
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white">Products</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contact</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div class="scroll-animate delay-200">
                    <h4 class="font-semibold mb-4">Customer Service</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Shipping Info</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Returns</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="scroll-animate delay-300">
                    <h4 class="font-semibold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li>📞 (555) 123-4567</li>
                        <li>📧 info@pharmacystore.com</li>
                        <li>📍 123 Health St, Medical City</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center scroll-animate delay-400">
                <p>&copy; 2024 Pharmacy Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @include('components.chat-popup')

    <!-- Scroll Animation JavaScript -->
    <script>
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        // Observe all elements with scroll-animate class
        document.addEventListener('DOMContentLoaded', () => {
            const animatedElements = document.querySelectorAll('.scroll-animate');
            animatedElements.forEach(el => observer.observe(el));
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Featured Products Slideshow
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.getElementById('products-slider');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const container = document.getElementById('products-container');

            if (!slider || !prevBtn || !nextBtn || !container) return;

            let currentIndex = 0;
            const totalProducts = {{ $featuredProducts->count() }};
            const productsPerView = window.innerWidth >= 1280 ? 4 : window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
            const maxIndex = Math.max(0, totalProducts - productsPerView);

            function updateButtons() {
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= maxIndex;
            }

            function updateSlider() {
                const translateX = -currentIndex * (100 / productsPerView);
                slider.style.transform = `translateX(${translateX}%)`;
                updateButtons();
            }

            prevBtn.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            });

            nextBtn.addEventListener('click', () => {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateSlider();
                }
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                const newProductsPerView = window.innerWidth >= 1280 ? 4 : window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
                if (newProductsPerView !== productsPerView) {
                    location.reload(); // Simple solution for responsive changes
                }
            });

            updateButtons();
        });

        // Featured Brands Slideshow
        document.addEventListener('DOMContentLoaded', () => {
            const brandsSlider = document.getElementById('brands-slider');
            const brandsPrevBtn = document.getElementById('brands-prev-btn');
            const brandsNextBtn = document.getElementById('brands-next-btn');
            const brandsContainer = document.getElementById('brands-container');

            if (!brandsSlider || !brandsPrevBtn || !brandsNextBtn || !brandsContainer) return;

            let brandsCurrentIndex = 0;
            const brandsTotal = {{ $featuredBrands->count() }};
            const brandsPerView = 8; // 4 columns x 2 rows = 8 brands per view
            const brandsMaxIndex = Math.max(0, Math.ceil(brandsTotal / brandsPerView) - 1);

            function updateBrandsButtons() {
                brandsPrevBtn.disabled = brandsCurrentIndex === 0;
                brandsNextBtn.disabled = brandsCurrentIndex >= brandsMaxIndex;
            }

            function updateBrandsSlider() {
                const translateX = -brandsCurrentIndex * 100;
                brandsSlider.style.transform = `translateX(${translateX}%)`;
                updateBrandsButtons();
            }

            brandsPrevBtn.addEventListener('click', () => {
                if (brandsCurrentIndex > 0) {
                    brandsCurrentIndex--;
                    updateBrandsSlider();
                }
            });

            brandsNextBtn.addEventListener('click', () => {
                if (brandsCurrentIndex < brandsMaxIndex) {
                    brandsCurrentIndex++;
                    updateBrandsSlider();
                }
            });

            updateBrandsButtons();
        });
    </script>
</body>
</html>