# TODO: Add Brand Model and Update Categories for Nested Structure

## Steps to Complete

### 1. Create Brands Migration
- Create migration file: `database/migrations/[timestamp]_create_brands_table.php`
- Fields: id, name, logo, description, timestamps

### 2. Create Brand Model
- Create `app/Models/Brand.php`
- Add relationships: products()

### 3. Update Categories Migration
- Modify `database/migrations/2025_07_30_034116_create_categories_table.php`
- Add: parent_id (nullable foreign to categories.id), root_type (enum), image

### 4. Update Products Migration
- Modify `database/migrations/2025_07_30_034117_create_products_table.php`
- Add: brand_id (foreign to brands), attributes (json), images (json)

### 5. Update Category Model
- Modify `app/Models/Category.php`
- Add: parent/child relationships, root_type cast, methods for subcategories, products/brands in tree

### 6. Update Product Model
- Modify `app/Models/Product.php`
- Add: brand relationship, attributes cast, breadcrumb method

### 7. Run Migrations
- Execute `php artisan migrate`

### 8. Create Root Categories Seeder
- Create seeder for Médicaments and Parapharmacie root categories

### 9. Update Controllers
- Update ProductController for new functionalities (filter by category+brand, get products in category tree)
- Add methods for brands in category tree

### 10. Update Views (if needed)
- Update product/category views to display new fields and relationships

## Progress Tracking
- [x] Step 1: Create Brands Migration
- [x] Step 2: Create Brand Model
- [x] Step 3: Update Categories Migration
- [x] Step 4: Update Products Migration
- [x] Step 5: Update Category Model
- [x] Step 6: Update Product Model
- [x] Step 7: Run Migrations
- [x] Step 8: Create Root Categories Seeder
- [x] Step 9: Update Controllers
- [x] Step 10: Update Views
- [x] Step 11: Add is_root field to categories
- [x] Step 12: Update Category forms with is_root checkbox
- [x] Step 13: Update AdminCategoryController for is_root logic
- [x] Step 14: Update Category model with is_root field
- [x] Step 15: Fix Product Create/Edit Forms JavaScript Logic
- [x] Step 16: Update AdminProductController to use is_root field
