<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
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
            <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
            <p class="text-gray-600 mt-2">Update product information</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" id="name" name="name" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                                   value="{{ old('name', $product->name) }}" placeholder="Enter product name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dynamic Cascading Category Selection -->
                        <div id="category-section">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <div id="category-dropdowns">
                                <!-- Root Category -->
                                <div class="mb-3 category-level" data-level="0">
                                    <label for="category_0" class="block text-xs font-medium text-gray-600 mb-1">Root Category</label>
                                    <select id="category_0" name="category_0"
                                            class="category-select block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('category_id') border-red-500 @enderror">
                                        <option value="">Select Root Category</option>
                                        @foreach($rootCategories as $category)
                                            <option value="{{ $category->id }}" data-root-type="{{ $category->root_type }}"
                                                    {{ old('category_0', (isset($categoryHierarchy[0]) ? $categoryHierarchy[0]->id : '')) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Hidden field for final category selection -->
                            <input type="hidden" id="category_id" name="category_id" value="{{ old('category_id', $product->category_id) }}">
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
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
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
                                      placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                            <input type="text" id="manufacturer" name="manufacturer"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('manufacturer') border-red-500 @enderror"
                                   value="{{ old('manufacturer', $product->manufacturer) }}" placeholder="Enter manufacturer name">
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
                                   value="{{ old('price', $product->price) }}" placeholder="0.00">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price (Optional)</label>
                            <input type="number" id="sale_price" name="sale_price" step="0.01"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('sale_price') border-red-500 @enderror"
                                   value="{{ old('sale_price', $product->sale_price) }}" placeholder="0.00">
                            @error('sale_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                            <input type="number" id="stock_quantity" name="stock_quantity" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('stock_quantity') border-red-500 @enderror"
                                   value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                            @error('stock_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-lg">
                                    <p class="text-sm text-gray-500 mt-1">Current image</p>
                                </div>
                            @endif
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Upload a new image (JPEG, PNG, GIF, max 2MB)</p>
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
                                   value="{{ old('dosage_form', $product->dosage_form) }}" placeholder="e.g., Tablet, Capsule, Liquid">
                        </div>

                        <div>
                            <label for="strength" class="block text-sm font-medium text-gray-700">Strength</label>
                            <input type="text" id="strength" name="strength"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="{{ old('strength', $product->strength) }}" placeholder="e.g., 500mg, 10mg">
                        </div>
                    </div>

                    <div>
                        <label for="ingredients" class="block text-sm font-medium text-gray-700">Ingredients</label>
                        <textarea id="ingredients" name="ingredients" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="List the main ingredients">{{ old('ingredients', $product->ingredients) }}</textarea>
                    </div>

                    <div>
                        <label for="usage_instructions" class="block text-sm font-medium text-gray-700">Usage Instructions</label>
                        <textarea id="usage_instructions" name="usage_instructions" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="How to use this product">{{ old('usage_instructions', $product->usage_instructions) }}</textarea>
                    </div>

                    <div>
                        <label for="side_effects" class="block text-sm font-medium text-gray-700">Side Effects</label>
                        <textarea id="side_effects" name="side_effects" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Common side effects">{{ old('side_effects', $product->side_effects) }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Update Product
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
            const categoryDropdowns = document.getElementById('category-dropdowns');
            const categoryId = document.getElementById('category_id');

            // Store original hierarchy for restoration
            const originalHierarchy = @json($categoryHierarchy ?? []);

            // Function to create a category dropdown
            function createCategoryDropdown(level, labelText, selectedValue = '') {
                const container = document.createElement('div');
                container.className = 'mb-3 category-level';
                container.setAttribute('data-level', level);

                const label = document.createElement('label');
                label.setAttribute('for', `category_${level}`);
                label.className = 'block text-xs font-medium text-gray-600 mb-1';
                label.textContent = labelText;

                const select = document.createElement('select');
                select.id = `category_${level}`;
                select.name = `category_${level}`;
                select.className = 'category-select block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm';

                if (selectedValue) {
                    select.value = selectedValue;
                }

                container.appendChild(label);
                container.appendChild(select);

                return container;
            }

            // Function to remove dropdowns after a certain level
            function removeDropdownsAfter(level) {
                const allLevels = categoryDropdowns.querySelectorAll('.category-level');
                allLevels.forEach(container => {
                    const containerLevel = parseInt(container.getAttribute('data-level'));
                    if (containerLevel > level) {
                        container.remove();
                    }
                });
            }

            // Function to load subcategories
            async function loadSubcategories(parentId, level) {
                try {
                    const response = await fetch(`/admin/api/categories/subcategories/${parentId}`);
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error loading subcategories:', error);
                    return [];
                }
            }

            // Function to populate dropdown options
            function populateDropdown(select, categories, selectedValue = '') {
                select.innerHTML = '<option value="">Select Subcategory</option>';

                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    if (selectedValue && selectedValue == category.id) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });

                return categories.length > 0;
            }

            // Function to handle category selection change
            async function handleCategoryChange(level, selectedValue) {
                // Remove all dropdowns after current level
                removeDropdownsAfter(level);

                if (!selectedValue) {
                    // If no selection, set category_id to the previous level's selection
                    const prevLevel = level - 1;
                    const prevSelect = document.getElementById(`category_${prevLevel}`);
                    categoryId.value = prevSelect ? prevSelect.value : '';
                    return;
                }

                // Set category_id to current selection
                categoryId.value = selectedValue;

                // Load subcategories for next level
                const subcategories = await loadSubcategories(selectedValue, level + 1);

                if (subcategories.length > 0) {
                    // Create next level dropdown
                    const nextLevel = level + 1;
                    const labelText = nextLevel === 1 ? 'Subcategory Level 1' :
                                    nextLevel === 2 ? 'Subcategory Level 2' :
                                    nextLevel === 3 ? 'Subcategory Level 3' :
                                    `Subcategory Level ${nextLevel}`;

                    const nextDropdown = createCategoryDropdown(nextLevel, labelText);
                    categoryDropdowns.appendChild(nextDropdown);

                    const nextSelect = document.getElementById(`category_${nextLevel}`);
                    populateDropdown(nextSelect, subcategories);

                    // Add change event listener to new dropdown
                    nextSelect.addEventListener('change', function() {
                        handleCategoryChange(nextLevel, this.value);
                    });
                }
            }

            // Function to restore original selections
            async function restoreOriginalSelections() {
                if (originalHierarchy.length === 0) return;

                // Set root category
                const rootSelect = document.getElementById('category_0');
                rootSelect.value = originalHierarchy[0].id;

                // Load and set each level of the hierarchy
                for (let i = 0; i < originalHierarchy.length - 1; i++) {
                    const currentCategory = originalHierarchy[i];
                    const nextLevel = i + 1;
                    const nextCategory = originalHierarchy[nextLevel];

                    const subcategories = await loadSubcategories(currentCategory.id, nextLevel);

                    if (subcategories.length > 0) {
                        const labelText = nextLevel === 1 ? 'Subcategory Level 1' :
                                        nextLevel === 2 ? 'Subcategory Level 2' :
                                        nextLevel === 3 ? 'Subcategory Level 3' :
                                        `Subcategory Level ${nextLevel}`;

                        const nextDropdown = createCategoryDropdown(nextLevel, labelText, nextCategory.id);
                        categoryDropdowns.appendChild(nextDropdown);

                        const nextSelect = document.getElementById(`category_${nextLevel}`);
                        populateDropdown(nextSelect, subcategories, nextCategory.id);

                        // Add change event listener
                        nextSelect.addEventListener('change', function() {
                            handleCategoryChange(nextLevel, this.value);
                        });
                    }
                }

                // Set final category_id
                categoryId.value = originalHierarchy[originalHierarchy.length - 1].id;
            }

            // Initialize with original selections
            restoreOriginalSelections();

            // Add change event listener to root category
            const rootSelect = document.getElementById('category_0');
            rootSelect.addEventListener('change', function() {
                handleCategoryChange(0, this.value);
            });
        });
    </script>
</body>
</html>
