<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Store - Your Health, Our Priority</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" style="font-family: 'Ubuntu', sans-serif;">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-gradient">Pharmacy Store</a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Products</a>
                    <a href="/map" class="text-gray-700 hover:text-blue-600 transition-colors">Locations</a>
                    @auth
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Cart</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Orders</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-blue-600 transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="btn-custom btn-custom-primary">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gray-800 text-white py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-4xl mx-auto">
                <div class="scroll-animate">
                    <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                        Your Health, 
                        <span class="text-gradient">Our Priority</span>
                    </h1>
                </div>
                <div class="scroll-animate delay-100">
                    <p class="text-xl mb-8 text-blue-100 max-w-3xl mx-auto">
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
                        <h3 class="text-lg font-semibold mb-2">Prescription Drugs</h3>
                        <p class="text-sm text-gray-600 mb-4">Licensed prescription medications</p>
                        <a href="{{ route('products.index') }}" class="btn-custom btn-custom-primary">Browse</a>
                    </div>
                </div>
                
                <div class="card-custom bg-white rounded-lg p-6 hover-lift scroll-animate delay-100">
                    <div class="text-center">
                        <div class="text-4xl mb-4">🌿</div>
                        <h3 class="text-lg font-semibold mb-2">Vitamins & Supplements</h3>
                        <p class="text-sm text-gray-600 mb-4">Natural health supplements</p>
                        <a href="{{ route('products.index') }}" class="btn-custom btn-custom-primary">Browse</a>
                    </div>
                </div>
                
                <div class="card-custom bg-white rounded-lg p-6 hover-lift scroll-animate delay-200">
                    <div class="text-center">
                        <div class="text-4xl mb-4">🩹</div>
                        <h3 class="text-lg font-semibold mb-2">First Aid</h3>
                        <p class="text-sm text-gray-600 mb-4">Emergency and first aid supplies</p>
                        <a href="{{ route('products.index') }}" class="btn-custom btn-custom-primary">Browse</a>
                    </div>
                </div>
                
                <div class="card-custom bg-white rounded-lg p-6 hover-lift scroll-animate delay-300">
                    <div class="text-center">
                        <div class="text-4xl mb-4">🧴</div>
                        <h3 class="text-lg font-semibold mb-2">Personal Care</h3>
                        <p class="text-sm text-gray-600 mb-4">Hygiene and personal care items</p>
                        <a href="{{ route('products.index') }}" class="btn-custom btn-custom-primary">Browse</a>
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
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Sample Featured Products -->
                <div class="card-custom bg-white rounded-lg overflow-hidden hover-lift scroll-animate">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl text-gray-400">💊</div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">Pain Relief</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Aspirin 500mg</h3>
                        <p class="text-gray-600 text-sm mb-3">Effective pain relief and fever reduction</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">$8.99</span>
                            <span class="text-sm text-green-600">In Stock</span>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('products.index') }}" class="btn-custom btn-custom-primary w-full block text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-custom bg-white rounded-lg overflow-hidden hover-lift scroll-animate delay-100">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl text-gray-400">🌿</div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Vitamins</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Vitamin C 1000mg</h3>
                        <p class="text-gray-600 text-sm mb-3">Immune system support and antioxidant</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">$12.99</span>
                            <span class="text-sm text-green-600">In Stock</span>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('products.index') }}" class="btn-custom btn-custom-primary w-full block text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-custom bg-white rounded-lg overflow-hidden hover-lift scroll-animate delay-200">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl text-gray-400">🩹</div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">First Aid</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Band-Aid Assorted</h3>
                        <p class="text-gray-600 text-sm mb-3">Sterile adhesive bandages for minor cuts</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">$5.99</span>
                            <span class="text-sm text-green-600">In Stock</span>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('products.index') }}" class="btn-custom btn-custom-primary w-full block text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-custom bg-white rounded-lg overflow-hidden hover-lift scroll-animate delay-300">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl text-gray-400">🧴</div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">Personal Care</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Hand Sanitizer</h3>
                        <p class="text-gray-600 text-sm mb-3">Kills 99.9% of germs and bacteria</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">$4.99</span>
                            <span class="text-sm text-green-600">In Stock</span>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('products.index') }}" class="btn-custom btn-custom-primary w-full block text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8 scroll-animate delay-400">
                <a href="{{ route('products.index') }}" class="btn-custom btn-custom-ghost text-lg">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="scroll-animate">
                <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
                <p class="text-xl mb-8 text-blue-100">
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
    </script>
</body>
</html>