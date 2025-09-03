<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold">Admin Dashboard</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white">View Store</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
            <p class="text-gray-600 mt-2">Create a new product for your store</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" id="name" name="name" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                                   value="{{ old('name') }}" placeholder="Enter product name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cascading Category Selection -->
                        <div id="category-section">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>

                            <!-- Root Category -->
                            <div class="mb-3">
                                <label for="root_category" class="block text-xs font-medium text-gray-600 mb-1">Root Category</label>
                                <select id="root_category" name="root_category"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('category_id') border-red-500 @enderror">
                                    <option value="">Select Root Category</option>
                                    @foreach($rootCategories as $category)
                                <option value="{{ $category->id }}" data-root-type="{{ $category->root_type }}" {{ old('root_category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                            </div>

                            <!-- Level 1 Category -->
                            <div id="level1-container" class="mb-3 hidden">
                                <label for="level1_category" class="block text-xs font-medium text-gray-600 mb-1">Subcategory Level 1</label>
                                <select id="level1_category" name="level1_category"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Select Subcategory</option>
                                </select>
                            </div>

                            <!-- Level 2 Category -->
                            <div id="level2-container" class="mb-3 hidden">
                                <label for="level2_category" class="block text-xs font-medium text-gray-600 mb-1">Subcategory Level 2</label>
                                <select id="level2_category" name="level2_category"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Select Subcategory</option>
                                </select>
                            </div>

                            <!-- Level 3 Category -->
                            <div id="level3-container" class="mb-3 hidden">
                                <label for="level3_category" class="block text-xs font-medium text-gray-600 mb-1">Subcategory Level 3</label>
                                <select id="level3_category" name="level3_category"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Select Subcategory (Optional)</option>
                                </select>
                            </div>

                            <!-- Hidden field for final category selection -->
                            <input type="hidden" id="category_id" name="category_id" value="{{ old('category_id') }}">
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand</label>
                            <select id="brand_id" name="brand_id"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('brand_id') border-red-500 @enderror">
                                <option value="">Select Brand (Optional)</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Choose an existing brand or leave empty to create a new one later</p>
                            @error('brand_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-500 @enderror"
                                      placeholder="Enter product description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                            <input type="text" id="manufacturer" name="manufacturer"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('manufacturer') border-red-500 @enderror"
                                   value="{{ old('manufacturer') }}" placeholder="Enter manufacturer name">
                            @error('manufacturer')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Pricing and Stock -->
                    <div class="space-y-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Regular Price</label>
                            <input type="number" id="price" name="price" step="0.01" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('price') border-red-500 @enderror"
                                   value="{{ old('price') }}" placeholder="0.00">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price (Optional)</label>
                            <input type="number" id="sale_price" name="sale_price" step="0.01"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('sale_price') border-red-500 @enderror"
                                   value="{{ old('sale_price') }}" placeholder="0.00">
                            @error('sale_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                            <input type="number" id="stock_quantity" name="stock_quantity" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('stock_quantity') border-red-500 @enderror"
                                   value="{{ old('stock_quantity', 0) }}" min="0">
                            @error('stock_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Upload a product image (JPEG, PNG, GIF, max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-8 space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="dosage_form" class="block text-sm font-medium text-gray-700">Dosage Form</label>
                            <input type="text" id="dosage_form" name="dosage_form"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="{{ old('dosage_form') }}" placeholder="e.g., Tablet, Capsule, Liquid">
                        </div>

                        <div>
                            <label for="strength" class="block text-sm font-medium text-gray-700">Strength</label>
                            <input type="text" id="strength" name="strength"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="{{ old('strength') }}" placeholder="e.g., 500mg, 10mg">
                        </div>
                    </div>

                    <div>
                        <label for="ingredients" class="block text-sm font-medium text-gray-700">Ingredients</label>
                        <textarea id="ingredients" name="ingredients" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="List the main ingredients">{{ old('ingredients') }}</textarea>
                    </div>

                    <div>
                        <label for="usage_instructions" class="block text-sm font-medium text-gray-700">Usage Instructions</label>
                        <textarea id="usage_instructions" name="usage_instructions" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="How to use this product">{{ old('usage_instructions') }}</textarea>
                    </div>

                    <div>
                        <label for="side_effects" class="block text-sm font-medium text-gray-700">Side Effects</label>
                        <textarea id="side_effects" name="side_effects" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Common side effects">{{ old('side_effects') }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Create Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Pharmacy Store Admin. All rights reserved.</p>
        </div>
    </footer>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const rootCategory = document.getElementById('root_category');
                    const level1Category = document.getElementById('level1_category');
                    const level2Category = document.getElementById('level2_category');
                    const level3Category = document.getElementById('level3_category');
                    const categoryId = document.getElementById('category_id');

                    const level1Container = document.getElementById('level1-container');
                    const level2Container = document.getElementById('level2-container');
                    const level3Container = document.getElementById('level3-container');

                    // Function to reset dropdowns
                    function resetDropdowns(fromLevel = 1) {
                        if (fromLevel <= 1) {
                            level1Category.innerHTML = '<option value="">Select Subcategory</option>';
                            level1Container.classList.add('hidden');
                        }
                        if (fromLevel <= 2) {
                            level2Category.innerHTML = '<option value="">Select Subcategory</option>';
                            level2Container.classList.add('hidden');
                        }
                        if (fromLevel <= 3) {
                            level3Category.innerHTML = '<option value="">Select Subcategory (Optional)</option>';
                            level3Container.classList.add('hidden');
                        }
                        categoryId.value = '';
                    }

                    // Function to load subcategories
                    async function loadSubcategories(parentId, targetSelect, level) {
                        try {
                            const response = await fetch(`/admin/api/categories/subcategories/${parentId}`);
                            const data = await response.json();

                            targetSelect.innerHTML = level === 3
                                ? '<option value="">Select Subcategory (Optional)</option>'
                                : '<option value="">Select Subcategory</option>';

                            data.forEach(category => {
                                const option = document.createElement('option');
                                option.value = category.id;
                                option.textContent = category.name;
                                targetSelect.appendChild(option);
                            });

                            return data.length > 0;
                        } catch (error) {
                            console.error('Error loading subcategories:', error);
                            return false;
                        }
                    }

                    // Initialize dropdowns on page load if old category_id exists
                    async function initializeDropdowns() {
                        const oldCategoryId = "{{ old('category_id') }}";
                        if (!oldCategoryId) {
                            return;
                        }

                        // Fetch category hierarchy for oldCategoryId
                        try {
                            const response = await fetch(`/admin/api/categories/hierarchy/${oldCategoryId}`);
                            const hierarchy = await response.json();

                            if (hierarchy.length > 0) {
                                rootCategory.value = hierarchy[0].id;
                                const hasLevel1 = await loadSubcategories(hierarchy[0].id, level1Category, 1);
                                if (hasLevel1) {
                                    level1Container.classList.remove('hidden');
                                }

                                if (hierarchy.length > 1) {
                                    level1Category.value = hierarchy[1].id;
                                    const hasLevel2 = await loadSubcategories(hierarchy[1].id, level2Category, 2);
                                    if (hasLevel2) {
                                        level2Container.classList.remove('hidden');
                                    }
                                }

                                if (hierarchy.length > 2) {
                                    level2Category.value = hierarchy[2].id;
                                    const hasLevel3 = await loadSubcategories(hierarchy[2].id, level3Category, 3);
                                    if (hasLevel3) {
                                        level3Container.classList.remove('hidden');
                                    }
                                }

                                if (hierarchy.length > 3) {
                                    level3Category.value = hierarchy[3].id;
                                    categoryId.value = hierarchy[3].id;
                                } else if (hierarchy.length > 2) {
                                    categoryId.value = hierarchy[2].id;
                                } else if (hierarchy.length > 1) {
                                    categoryId.value = hierarchy[1].id;
                                } else {
                                    categoryId.value = hierarchy[0].id;
                                }
                            }
                        } catch (error) {
                            console.error('Error fetching category hierarchy:', error);
                        }
                    }

                    // Root category change handler
                    rootCategory.addEventListener('change', async function() {
                        const selectedValue = this.value;

                        if (!selectedValue) {
                            resetDropdowns();
                            categoryId.value = '';
                            return;
                        }

                        resetDropdowns();

                        // Set category_id to root category initially
                        categoryId.value = selectedValue;

                        // Load level 1 categories
                        const hasLevel1 = await loadSubcategories(selectedValue, level1Category, 1);
                        if (hasLevel1) {
                            level1Container.classList.remove('hidden');
                        }
                    });

                    // Level 1 category change handler
                    level1Category.addEventListener('change', async function() {
                        const selectedValue = this.value;

                        if (!selectedValue) {
                            resetDropdowns(2);
                            categoryId.value = rootCategory.value;
                            return;
                        }

                        resetDropdowns(2);

                        // Always set category_id to the selected level 1 category
                        categoryId.value = selectedValue;

                        // Load level 2 categories (optional)
                        const hasLevel2 = await loadSubcategories(selectedValue, level2Category, 2);
                        if (hasLevel2) {
                            level2Container.classList.remove('hidden');
                        } else {
                            // No level 2 categories, keep category_id as level 1
                            level2Container.classList.add('hidden');
                        }
                    });

                    // Level 2 category change handler
                    level2Category.addEventListener('change', async function() {
                        const selectedValue = this.value;

                        if (!selectedValue) {
                            resetDropdowns(3);
                            categoryId.value = level1Category.value;
                            return;
                        }

                        resetDropdowns(3);

                        // Load level 3 categories
                        const hasLevel3 = await loadSubcategories(selectedValue, level3Category, 3);
                        if (hasLevel3) {
                            level3Container.classList.remove('hidden');
                        } else {
                            categoryId.value = selectedValue;
                        }
                    });

                    // Level 3 category change handler
                    level3Category.addEventListener('change', function() {
                        const selectedValue = this.value;
                        categoryId.value = selectedValue || level2Category.value;
                    });

                    // Initialize dropdowns on page load
                    initializeDropdowns();
                });
            </script>
</body>
</html>
