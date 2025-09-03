<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class AdminProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $rootCategory;
    protected $subCategory;
    protected $brand;

    protected function setUp(): void
    {
        parent::setUp();

        // Create root category
        $this->rootCategory = Category::factory()->create([
            'name' => 'Root Category',
            'slug' => 'root-category',
            'is_root' => true,
            'is_active' => true,
        ]);

        // Create subcategory under root
        $this->subCategory = Category::factory()->create([
            'name' => 'Sub Category',
            'slug' => 'sub-category',
            'parent_id' => $this->rootCategory->id,
            'root_type' => $this->rootCategory->root_type,
            'is_active' => true,
        ]);

        // Create brand
        $this->brand = Brand::factory()->create([
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'is_active' => true,
        ]);
    }

    public function test_create_product_with_root_category_only()
    {
        $response = $this->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'category_id' => $this->rootCategory->id,
            'brand_id' => $this->brand->id,
            'price' => 10.00,
            'stock_quantity' => 5,
            'requires_prescription' => false,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'category_id' => $this->rootCategory->id,
        ]);
    }

    public function test_create_product_with_subcategory()
    {
        $response = $this->post(route('admin.products.store'), [
            'name' => 'Test Product 2',
            'category_id' => $this->subCategory->id,
            'brand_id' => $this->brand->id,
            'price' => 15.00,
            'stock_quantity' => 10,
            'requires_prescription' => true,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product 2',
            'category_id' => $this->subCategory->id,
        ]);
    }

    public function test_create_product_duplicate_detection()
    {
        // Create initial product
        Product::create([
            'name' => 'Duplicate Product',
            'category_id' => $this->rootCategory->id,
            'brand_id' => $this->brand->id,
            'price' => 20.00,
            'stock_quantity' => 3,
            'dosage_form' => 'tablet',
            'strength' => '500mg',
            'requires_prescription' => false,
        ]);

        // Attempt to create duplicate product
        $response = $this->post(route('admin.products.store'), [
            'name' => 'Duplicate Product',
            'category_id' => $this->rootCategory->id,
            'brand_id' => $this->brand->id,
            'price' => 20.00,
            'stock_quantity' => 3,
            'dosage_form' => 'tablet',
            'strength' => '500mg',
            'requires_prescription' => false,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_create_product_invalid_category()
    {
        // Create a category that is not root or immediate child of root
        $invalidCategory = Category::factory()->create([
            'name' => 'Invalid Category',
            'slug' => 'invalid-category',
            'parent_id' => $this->subCategory->id,
            'root_type' => $this->rootCategory->root_type,
            'is_active' => true,
        ]);

        $response = $this->post(route('admin.products.store'), [
            'name' => 'Invalid Category Product',
            'category_id' => $invalidCategory->id,
            'brand_id' => $this->brand->id,
            'price' => 12.00,
            'stock_quantity' => 2,
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_image_upload_on_create()
    {
        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->post(route('admin.products.store'), [
            'name' => 'Product With Image',
            'category_id' => $this->rootCategory->id,
            'brand_id' => $this->brand->id,
            'price' => 30.00,
            'stock_quantity' => 7,
            'image' => $file,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Product With Image',
        ]);
    }
}
