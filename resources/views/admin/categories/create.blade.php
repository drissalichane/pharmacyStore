<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Admin</title>
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
            <h1 class="text-3xl font-bold text-gray-900">Add New Category</h1>
            <p class="text-gray-600 mt-2">Create a new product category</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" id="name" name="name" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}" placeholder="Enter category name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="is_root" class="inline-flex items-center">
                            <input type="checkbox" id="is_root" name="is_root" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ old('is_root') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Is Root Category</span>
                        </label>
                    </div>

                    <div id="parent_category_div">
                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Category (Optional)</label>
                        <select id="parent_id" name="parent_id"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('parent_id') border-red-500 @enderror">
                            <option value="">Select Parent Category (Leave empty for root)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-root-type="{{ $category->root_type }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Choose a parent category to create a subcategory</p>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="root_type_div">
                        <label for="root_type" class="block text-sm font-medium text-gray-700">Root Type</label>
                        <select id="root_type" name="root_type"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('root_type') border-red-500 @enderror">
                            <option value="">Select Root Type</option>
                            <option value="Médicaments" {{ old('root_type') == 'Médicaments' ? 'selected' : '' }}>Médicaments</option>
                            <option value="Parapharmacie" {{ old('root_type') == 'Parapharmacie' ? 'selected' : '' }}>Parapharmacie</option>
                        </select>
                        @error('root_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-500 @enderror"
                                  placeholder="Enter category description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Category Image</label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('image') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Upload a category image (JPEG, PNG, GIF, max 2MB)</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('sort_order') border-red-500 @enderror"
                               value="{{ old('sort_order', 0) }}" min="0">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Active
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Create Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
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
            const isRootCheckbox = document.getElementById('is_root');
            const parentSelect = document.getElementById('parent_id');
            const rootTypeSelect = document.getElementById('root_type');
            const parentCategoryDiv = document.getElementById('parent_category_div');
            const rootTypeDiv = document.getElementById('root_type_div');

            function updateRootType() {
                const selectedOption = parentSelect.options[parentSelect.selectedIndex];
                if (parentSelect.value) {
                    // Disable root_type and set to parent's root_type
                    rootTypeSelect.value = selectedOption.getAttribute('data-root-type') || '';
                    rootTypeSelect.disabled = true;
                } else {
                    // Enable root_type for root categories
                    rootTypeSelect.disabled = false;
                }
            }

            function toggleRootCategoryFields() {
                if (isRootCheckbox.checked) {
                    parentCategoryDiv.style.display = 'none';
                    rootTypeDiv.style.display = 'none';
                    rootTypeSelect.disabled = true;
                    parentSelect.value = '';
                } else {
                    parentCategoryDiv.style.display = 'block';
                    rootTypeDiv.style.display = 'block';
                    rootTypeSelect.disabled = false;
                    updateRootType();
                }
            }

            isRootCheckbox.addEventListener('change', toggleRootCategoryFields);
            parentSelect.addEventListener('change', updateRootType);

            // Initialize on page load
            toggleRootCategoryFields();
        });
    </script>
</body>
</html>
