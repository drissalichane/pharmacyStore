<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        // Flexible pagination
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 15;

        $categories = $query->latest()->paginate($perPage);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'root_type' => 'nullable|in:Médicaments,Parapharmacie',
            'is_root' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $data = $request->all();

        // Generate unique slug
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $data['slug'] = $slug;

        // Handle root category logic
        if ($request->is_root) {
            $data['parent_id'] = null;
            // Keep the root_type as provided
        } else {
            // For non-root categories, if parent is selected, set root_type to parent's root_type
            if ($request->parent_id) {
                $parent = Category::find($request->parent_id);
                if ($parent) {
                    $data['root_type'] = $parent->root_type;
                }
            }
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        $category->load('parent', 'children');
        $products = $category->productsInTree()->with(['category', 'brand'])->paginate(15);
        return view('admin.categories.show', compact('category', 'products'));
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'root_type' => 'nullable|in:Médicaments,Parapharmacie',
            'is_root' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle root category logic
        if ($request->is_root) {
            $data['parent_id'] = null;
            // Keep the root_type as provided
        } else {
            // For non-root categories, if parent is selected, set root_type to parent's root_type
            if ($request->parent_id) {
                $parent = Category::find($request->parent_id);
                if ($parent) {
                    $data['root_type'] = $parent->root_type;
                }
            }
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && Storage::disk('public')->exists(str_replace('/storage/', '', $category->image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $category->image));
            }

            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Delete image if exists
        if ($category->image && Storage::disk('public')->exists(str_replace('/storage/', '', $category->image))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }

    public function getSubcategories($parentId = null)
    {
        if ($parentId) {
            $categories = Category::where('parent_id', $parentId)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name', 'parent_id']);
        } else {
            // Return root categories
            $categories = Category::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name', 'parent_id', 'root_type']);
        }

        return response()->json($categories);
    }
}
