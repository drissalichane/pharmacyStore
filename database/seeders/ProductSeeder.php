<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $medications = Category::where('slug', 'medications')->first();
        $personalCare = Category::where('slug', 'personal-care')->first();
        $medicalSupplies = Category::where('slug', 'medical-supplies')->first();
        $vitamins = Category::where('slug', 'vitamins-supplements')->first();

        // Medications
        Product::create([
            'name' => 'Paracetamol 500mg',
            'description' => 'Pain reliever and fever reducer',
            'price' => 5.99,
            'stock_quantity' => 100,
            'category_id' => $medications->id,
            'requires_prescription' => false,
            'manufacturer' => 'Generic Pharma',
            'dosage_form' => 'tablet',
            'strength' => '500mg'
        ]);

        Product::create([
            'name' => 'Ibuprofen 400mg',
            'description' => 'Anti-inflammatory pain reliever',
            'price' => 7.99,
            'stock_quantity' => 75,
            'category_id' => $medications->id,
            'requires_prescription' => false,
            'manufacturer' => 'HealthCare Inc',
            'dosage_form' => 'tablet',
            'strength' => '400mg'
        ]);

        // Personal Care
        Product::create([
            'name' => 'Toothpaste Fresh Mint',
            'description' => 'Fresh mint flavored toothpaste',
            'price' => 3.99,
            'stock_quantity' => 50,
            'category_id' => $personalCare->id,
            'requires_prescription' => false,
            'manufacturer' => 'Dental Care'
        ]);

        Product::create([
            'name' => 'Hand Sanitizer 500ml',
            'description' => 'Alcohol-based hand sanitizer',
            'price' => 4.99,
            'stock_quantity' => 30,
            'category_id' => $personalCare->id,
            'requires_prescription' => false,
            'manufacturer' => 'Clean Hands'
        ]);

        // Medical Supplies
        Product::create([
            'name' => 'Bandages Pack',
            'description' => 'Sterile adhesive bandages',
            'price' => 2.99,
            'stock_quantity' => 200,
            'category_id' => $medicalSupplies->id,
            'requires_prescription' => false,
            'manufacturer' => 'First Aid Pro'
        ]);

        Product::create([
            'name' => 'Digital Thermometer',
            'description' => 'Accurate digital thermometer',
            'price' => 15.99,
            'stock_quantity' => 25,
            'category_id' => $medicalSupplies->id,
            'requires_prescription' => false,
            'manufacturer' => 'HealthTech'
        ]);

        // Vitamins
        Product::create([
            'name' => 'Vitamin C 1000mg',
            'description' => 'Immune system support',
            'price' => 12.99,
            'stock_quantity' => 60,
            'category_id' => $vitamins->id,
            'requires_prescription' => false,
            'manufacturer' => 'VitaHealth',
            'dosage_form' => 'tablet',
            'strength' => '1000mg'
        ]);

        Product::create([
            'name' => 'Vitamin D3 2000IU',
            'description' => 'Bone health and immune support',
            'price' => 9.99,
            'stock_quantity' => 80,
            'category_id' => $vitamins->id,
            'requires_prescription' => false,
            'manufacturer' => 'SunVitamins',
            'dosage_form' => 'tablet',
            'strength' => '2000IU'
        ]);
    }
}
