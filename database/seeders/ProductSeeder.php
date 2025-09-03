<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing products
        Product::query()->delete();

        $brands = Brand::all();
        $categories = Category::all();

        // Get categories by level for better organization
        $rootCategories = Category::whereNull('parent_id')->get();
        $level1Categories = Category::whereNotNull('parent_id')
            ->whereHas('parent', function($q) {
                $q->whereNull('parent_id');
            })->get();
        $level2Categories = Category::whereNotNull('parent_id')
            ->whereHas('parent', function($q) {
                $q->whereNotNull('parent_id');
            })->get();
        $level3Categories = Category::whereNotNull('parent_id')
            ->whereHas('parent.parent', function($q) {
                $q->whereNotNull('parent_id');
            })->get();

        // Sample products for different category levels
        $this->createMedicamentProducts($rootCategories->where('name', 'Médicaments')->first(), $brands, $level1Categories, $level2Categories, $level3Categories);
        $this->createParapharmacieProducts($rootCategories->where('name', 'Parapharmacie')->first(), $brands, $level1Categories);
        $this->createHygieneBeauteProducts($rootCategories->where('name', 'Hygiène & Beauté')->first(), $brands, $level1Categories);
        $this->createMereBebeProducts($rootCategories->where('name', 'Mère & Bébé')->first(), $brands, $level1Categories);
    }

    private function createMedicamentProducts($root, $brands, $level1Cats, $level2Cats, $level3Cats)
    {
        // Products for different levels of the Médicaments category
        $medicamentProducts = [
            // Level 1 products (directly under Médicaments)
            [
                'name' => 'Paracétamol 500mg',
                'description' => 'Antalgique et antipyrétique',
                'price' => 5.50,
                'sale_price' => null,
                'stock_quantity' => 150,
                'dosage_form' => 'Comprimé',
                'strength' => '500mg',
                'ingredients' => 'Paracétamol',
                'usage_instructions' => '1 comprimé toutes les 4-6 heures si nécessaire',
                'side_effects' => 'Réactions allergiques rares',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Français',
                'category' => $level1Cats->where('name', 'Analgésiques')->first(),
                'brand' => $brands->where('name', 'Sanofi')->first(),
            ],
            // Level 2 products
            [
                'name' => 'Amoxicilline 500mg',
                'description' => 'Antibiotique de la famille des pénicillines',
                'price' => 12.90,
                'sale_price' => null,
                'stock_quantity' => 75,
                'dosage_form' => 'Gélule',
                'strength' => '500mg',
                'ingredients' => 'Amoxicilline',
                'usage_instructions' => '1 gélule 3 fois par jour pendant 7 jours',
                'side_effects' => 'Nausées, diarrhée, éruption cutanée',
                'requires_prescription' => true,
                'manufacturer' => 'Laboratoire Européen',
                'category' => $level2Cats->where('name', 'Pénicillines')->first(),
                'brand' => $brands->where('name', 'Pfizer')->first(),
            ],
            // Level 3 products (deepest level)
            [
                'name' => 'Ibuprofène 400mg',
                'description' => 'Anti-inflammatoire non stéroïdien',
                'price' => 8.75,
                'sale_price' => 7.50,
                'stock_quantity' => 200,
                'dosage_form' => 'Comprimé',
                'strength' => '400mg',
                'ingredients' => 'Ibuprofène',
                'usage_instructions' => '1 comprimé 3 fois par jour après les repas',
                'side_effects' => 'Irritation gastrique, maux de tête',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire International',
                'category' => $level3Cats->where('name', 'Ibuprofène')->first(),
                'brand' => $brands->where('name', 'Bayer')->first(),
            ],
            [
                'name' => 'Diclofénac 50mg',
                'description' => 'Anti-inflammatoire pour douleurs musculaires',
                'price' => 9.20,
                'sale_price' => null,
                'stock_quantity' => 120,
                'dosage_form' => 'Comprimé gastro-résistant',
                'strength' => '50mg',
                'ingredients' => 'Diclofénac sodique',
                'usage_instructions' => '1 comprimé 2-3 fois par jour',
                'side_effects' => 'Troubles digestifs, étourdissements',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Allemand',
                'category' => $level3Cats->where('name', 'Diclofénac')->first(),
                'brand' => $brands->where('name', 'Novartis')->first(),
            ],
        ];

        foreach ($medicamentProducts as $product) {
            if ($product['category']) {
                Product::create([
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'sale_price' => $product['sale_price'],
                    'stock_quantity' => $product['stock_quantity'],
                    'dosage_form' => $product['dosage_form'],
                    'strength' => $product['strength'],
                    'ingredients' => $product['ingredients'],
                    'usage_instructions' => $product['usage_instructions'],
                    'side_effects' => $product['side_effects'],
                    'requires_prescription' => $product['requires_prescription'],
                    'manufacturer' => $product['manufacturer'],
                    'category_id' => $product['category']->id,
                    'brand_id' => $product['brand'] ? $product['brand']->id : null,
                ]);
            }
        }
    }

    private function createParapharmacieProducts($root, $brands, $level1Cats)
    {
        $parapharmacieProducts = [
            [
                'name' => 'Crème Hydratante Visage',
                'description' => 'Crème hydratante pour tous types de peau',
                'price' => 24.90,
                'sale_price' => 19.90,
                'stock_quantity' => 85,
                'dosage_form' => 'Crème',
                'strength' => null,
                'ingredients' => 'Aqua, Glycerin, Shea Butter, Hyaluronic Acid',
                'usage_instructions' => 'Appliquer matin et soir sur peau nettoyée',
                'side_effects' => 'Aucun connu',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Français',
                'category' => $level1Cats->where('name', 'Soins du visage')->first(),
                'brand' => $brands->where('name', 'La Roche-Posay')->first(),
            ],
            [
                'name' => 'Shampooing Anti-Pelliculaire',
                'description' => 'Shampooing traitant les pellicules',
                'price' => 12.50,
                'sale_price' => null,
                'stock_quantity' => 95,
                'dosage_form' => 'Shampooing',
                'strength' => null,
                'ingredients' => 'Aqua, Sodium Laureth Sulfate, Zinc Pyrithione',
                'usage_instructions' => 'Appliquer sur cheveux mouillés, masser et rincer',
                'side_effects' => 'Irritation oculaire possible',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Cosmétique',
                'category' => $level1Cats->where('name', 'Cheveux')->first(),
                'brand' => $brands->where('name', 'Vichy')->first(),
            ],
        ];

        foreach ($parapharmacieProducts as $product) {
            if ($product['category']) {
                Product::create([
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'sale_price' => $product['sale_price'],
                    'stock_quantity' => $product['stock_quantity'],
                    'dosage_form' => $product['dosage_form'],
                    'strength' => $product['strength'],
                    'ingredients' => $product['ingredients'],
                    'usage_instructions' => $product['usage_instructions'],
                    'side_effects' => $product['side_effects'],
                    'requires_prescription' => $product['requires_prescription'],
                    'manufacturer' => $product['manufacturer'],
                    'category_id' => $product['category']->id,
                    'brand_id' => $product['brand'] ? $product['brand']->id : null,
                ]);
            }
        }
    }

    private function createHygieneBeauteProducts($root, $brands, $level1Cats)
    {
        $hygieneProducts = [
            [
                'name' => 'Dentifrice Blancur',
                'description' => 'Dentifrice au fluor pour dents blanches',
                'price' => 6.90,
                'sale_price' => null,
                'stock_quantity' => 150,
                'dosage_form' => 'Dentifrice',
                'strength' => '1450ppm Fluor',
                'ingredients' => 'Aqua, Sorbitol, Silica, Sodium Fluoride',
                'usage_instructions' => 'Brosser les dents 2 fois par jour',
                'side_effects' => 'Aucun aux doses recommandées',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Dentaire',
                'category' => $level1Cats->where('name', 'Hygiène buccale')->first(),
                'brand' => $brands->where('name', 'Johnson & Johnson')->first(),
            ],
            [
                'name' => 'Déodorant Roll-On',
                'description' => 'Déodorant anti-transpirant longue durée',
                'price' => 8.50,
                'sale_price' => 7.20,
                'stock_quantity' => 120,
                'dosage_form' => 'Roll-on',
                'strength' => '48h protection',
                'ingredients' => 'Aqua, Aluminum Chlorohydrate, Parfum',
                'usage_instructions' => 'Appliquer sur aisselles sèches',
                'side_effects' => 'Irritation cutanée possible',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Cosmétique',
                'category' => $level1Cats->where('name', 'Déodorants')->first(),
                'brand' => $brands->where('name', 'Bioderma')->first(),
            ],
        ];

        foreach ($hygieneProducts as $product) {
            if ($product['category']) {
                Product::create([
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'sale_price' => $product['sale_price'],
                    'stock_quantity' => $product['stock_quantity'],
                    'dosage_form' => $product['dosage_form'],
                    'strength' => $product['strength'],
                    'ingredients' => $product['ingredients'],
                    'usage_instructions' => $product['usage_instructions'],
                    'side_effects' => $product['side_effects'],
                    'requires_prescription' => $product['requires_prescription'],
                    'manufacturer' => $product['manufacturer'],
                    'category_id' => $product['category']->id,
                    'brand_id' => $product['brand'] ? $product['brand']->id : null,
                ]);
            }
        }
    }

    private function createMereBebeProducts($root, $brands, $level1Cats)
    {
        $bebeProducts = [
            [
                'name' => 'Lait Infantile 1er âge',
                'description' => 'Lait infantile pour nourrissons de 0 à 6 mois',
                'price' => 18.90,
                'sale_price' => null,
                'stock_quantity' => 45,
                'dosage_form' => 'Poudre',
                'strength' => '1er âge',
                'ingredients' => 'Lactose, Huiles végétales, Protéines de lait',
                'usage_instructions' => 'Préparer selon les indications du pédiatre',
                'side_effects' => 'Respecter les doses recommandées',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Pédiatrique',
                'category' => $level1Cats->where('name', 'Alimentation')->first(),
                'brand' => $brands->where('name', 'Mustela')->first(),
            ],
            [
                'name' => 'Crème Change Bébé',
                'description' => 'Crème protectrice pour changes',
                'price' => 9.50,
                'sale_price' => 8.50,
                'stock_quantity' => 80,
                'dosage_form' => 'Crème',
                'strength' => null,
                'ingredients' => 'Aqua, Zinc Oxide, Panthenol, Bisabolol',
                'usage_instructions' => 'Appliquer à chaque change',
                'side_effects' => 'Aucun connu',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Pédiatrique',
                'category' => $level1Cats->where('name', 'Hygiène bébé')->first(),
                'brand' => $brands->where('name', 'Avène')->first(),
            ],
        ];

        foreach ($bebeProducts as $product) {
            if ($product['category']) {
                Product::create([
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'sale_price' => $product['sale_price'],
                    'stock_quantity' => $product['stock_quantity'],
                    'dosage_form' => $product['dosage_form'],
                    'strength' => $product['strength'],
                    'ingredients' => $product['ingredients'],
                    'usage_instructions' => $product['usage_instructions'],
                    'side_effects' => $product['side_effects'],
                    'requires_prescription' => $product['requires_prescription'],
                    'manufacturer' => $product['manufacturer'],
                    'category_id' => $product['category']->id,
                    'brand_id' => $product['brand'] ? $product['brand']->id : null,
                ]);
            }
        }
    }
}
