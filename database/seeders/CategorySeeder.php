<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Medications',
                'description' => 'Prescription and over-the-counter medications',
                'slug' => 'medications',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Personal Care',
                'description' => 'Hygiene and personal care products',
                'slug' => 'personal-care',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Medical Supplies',
                'description' => 'First aid and medical equipment',
                'slug' => 'medical-supplies',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Vitamins & Supplements',
                'description' => 'Vitamins, minerals, and dietary supplements',
                'slug' => 'vitamins-supplements',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Baby Care',
                'description' => 'Products for babies and infants',
                'slug' => 'baby-care',
                'is_active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
