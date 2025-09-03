<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'slug',
        'is_active',
        'sort_order',
        'parent_id',
        'root_type',
        'is_root'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_root' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function getAllSubcategoryIds()
    {
        $ids = collect([$this->id]);
        foreach ($this->children as $child) {
            $ids = $ids->merge($child->getAllSubcategoryIds());
        }
        return $ids;
    }

    public function productsInTree()
    {
        $subcategoryIds = $this->getAllSubcategoryIds();
        return Product::whereIn('category_id', $subcategoryIds)->active();
    }

    public function brandsInTree()
    {
        $subcategoryIds = $this->getAllSubcategoryIds();
        return Brand::whereHas('products', function ($query) use ($subcategoryIds) {
            $query->whereIn('category_id', $subcategoryIds)->active();
        })->withCount(['products' => function ($query) use ($subcategoryIds) {
            $query->whereIn('category_id', $subcategoryIds)->active();
        }])->active();
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeByRootType($query, $rootType)
    {
        return $query->where('root_type', $rootType);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getBreadcrumbAttribute()
    {
        $breadcrumb = [];
        $category = $this;

        while ($category) {
            array_unshift($breadcrumb, $category->name);
            $category = $category->parent;
        }

        return implode(' / ', $breadcrumb);
    }
}
