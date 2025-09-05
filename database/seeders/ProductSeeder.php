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
        // Don't delete existing products - just add sample products
        $brands = Brand::all();
        $categories = Category::all();

        if ($brands->isEmpty() || $categories->isEmpty()) {
            $this->command->info('No brands or categories found. Please run BrandSeeder and CategorySeeder first.');
            return;
        }

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

        // Create sample products for different categories
        $this->createSampleProducts($brands, $categories, $rootCategories, $level1Categories, $level2Categories, $level3Categories);

        $this->command->info('Sample products created successfully!');
    }

    private function createSampleProducts($brands, $categories, $rootCategories, $level1Categories, $level2Categories, $level3Categories)
    {
        // Get some random categories and brands to use
        $availableCategories = $categories->where('is_active', true)->shuffle();
        $availableBrands = $brands->where('is_active', true)->shuffle();

        // Sample products with different categories and brands
        $sampleProducts = [
            [
                'name' => 'Paracétamol 500mg',
                'description' => 'Antalgique et antipyrétique pour soulager la douleur et la fièvre',
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
            ],
            [
                'name' => 'Ibuprofène 400mg',
                'description' => 'Anti-inflammatoire non stéroïdien pour douleurs et fièvre',
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
            ],
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
            ],
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
            ],
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
            ],
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
            ],
            [
                'name' => 'Vitamine C 1000mg',
                'description' => 'Complément alimentaire pour renforcer les défenses immunitaires',
                'price' => 15.90,
                'sale_price' => 12.90,
                'stock_quantity' => 110,
                'dosage_form' => 'Comprimé',
                'strength' => '1000mg',
                'ingredients' => 'Acide ascorbique, Excipients',
                'usage_instructions' => '1 comprimé par jour avec un verre d\'eau',
                'side_effects' => 'Troubles digestifs à fortes doses',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Nutritionnel',
            ],
            [
                'name' => 'Bandage Adhésif',
                'description' => 'Pansements adhésifs stériles pour plaies superficielles',
                'price' => 4.50,
                'sale_price' => null,
                'stock_quantity' => 200,
                'dosage_form' => 'Pansement',
                'strength' => null,
                'ingredients' => 'Tissu non-tissé, Adhésif hypoallergénique',
                'usage_instructions' => 'Nettoyer la plaie et appliquer le pansement',
                'side_effects' => 'Irritation cutanée rare',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Médical',
            ],
            [
                'name' => 'Savon surgras',
                'description' => 'Savon doux pour peaux sensibles',
                'price' => 7.20,
                'sale_price' => null,
                'stock_quantity' => 90,
                'dosage_form' => 'Savon',
                'strength' => null,
                'ingredients' => 'Sodium Palmate, Aqua, Glycerin',
                'usage_instructions' => 'Utiliser quotidiennement pour la toilette',
                'side_effects' => 'Aucun connu',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Cosmétique',
            ],
            [
                'name' => 'Crème Solaire SPF50',
                'description' => 'Protection solaire haute protection',
                'price' => 19.90,
                'sale_price' => 16.90,
                'stock_quantity' => 65,
                'dosage_form' => 'Crème',
                'strength' => 'SPF50',
                'ingredients' => 'Aqua, Octyldodecanol, Zinc Oxide, Titanium Dioxide',
                'usage_instructions' => 'Appliquer généreusement 20 minutes avant l\'exposition',
                'side_effects' => 'Aucun connu',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Dermatologique',
            ],
            [
                'name' => 'Eau Thermale',
                'description' => 'Eau thermale apaisante en spray',
                'price' => 8.90,
                'sale_price' => null,
                'stock_quantity' => 130,
                'dosage_form' => 'Spray',
                'strength' => null,
                'ingredients' => 'Eau thermale 100%',
                'usage_instructions' => 'Vaporiser sur la peau propre',
                'side_effects' => 'Aucun connu',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Thermal',
            ],
            [
                'name' => 'Gélule Omega 3',
                'description' => 'Acides gras essentiels pour la santé cardiovasculaire',
                'price' => 22.50,
                'sale_price' => 18.90,
                'stock_quantity' => 70,
                'dosage_form' => 'Gélule',
                'strength' => '1000mg',
                'ingredients' => 'Huile de poisson, Gélatine, Glycérine',
                'usage_instructions' => '1 gélule par jour pendant les repas',
                'side_effects' => 'Légers troubles digestifs',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Nutritionnel',
            ],
            [
                'name' => 'Pansement Hydrocolloïde',
                'description' => 'Pansement moderne pour plaies exsudatives',
                'price' => 12.90,
                'sale_price' => null,
                'stock_quantity' => 85,
                'dosage_form' => 'Pansement',
                'strength' => null,
                'ingredients' => 'Hydrocolloïde, Film polyuréthane',
                'usage_instructions' => 'Appliquer sur plaie propre et sèche',
                'side_effects' => 'Macération possible',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Médical',
            ],
            [
                'name' => 'Shampooing Bébé',
                'description' => 'Shampooing doux sans larmes pour bébé',
                'price' => 9.90,
                'sale_price' => 8.50,
                'stock_quantity' => 95,
                'dosage_form' => 'Shampooing',
                'strength' => null,
                'ingredients' => 'Aqua, Coco-Glucoside, Glycérine',
                'usage_instructions' => 'Appliquer sur cheveux mouillés, rincer abondamment',
                'side_effects' => 'Aucun connu',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Pédiatrique',
            ],
            [
                'name' => 'Crème Anti-âge',
                'description' => 'Crème anti-rides pour peau mature',
                'price' => 45.90,
                'sale_price' => 38.90,
                'stock_quantity' => 40,
                'dosage_form' => 'Crème',
                'strength' => null,
                'ingredients' => 'Aqua, Retinol, Hyaluronic Acid, Vitamine E',
                'usage_instructions' => 'Appliquer le soir sur peau nettoyée',
                'side_effects' => 'Irritation légère possible',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Cosmétique',
            ],
            [
                'name' => 'Bain de Bouche',
                'description' => 'Bain de bouche au fluor pour hygiène buccale',
                'price' => 7.50,
                'sale_price' => null,
                'stock_quantity' => 110,
                'dosage_form' => 'Solution',
                'strength' => '250ppm Fluor',
                'ingredients' => 'Aqua, Alcohol, Sodium Fluoride, Aromas',
                'usage_instructions' => 'Se gargariser 30 secondes 2 fois par jour',
                'side_effects' => 'Sensation de brûlure temporaire',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Dentaire',
            ],
            [
                'name' => 'Complément Fer',
                'description' => 'Complément alimentaire riche en fer',
                'price' => 14.90,
                'sale_price' => 12.50,
                'stock_quantity' => 75,
                'dosage_form' => 'Comprimé',
                'strength' => '14mg Fer',
                'ingredients' => 'Fer bisglycinate, Acide ascorbique, Excipients',
                'usage_instructions' => '1 comprimé par jour avec un repas',
                'side_effects' => 'Constipation, nausées',
                'requires_prescription' => false,
                'manufacturer' => 'Laboratoire Nutritionnel',
            ],
        ];

        // Create products with random categories and brands
        foreach ($sampleProducts as $index => $productData) {
            // Skip if product already exists
            if (Product::where('name', $productData['name'])->exists()) {
                continue;
            }

            // Get random category and brand
            $randomCategory = $availableCategories->get($index % $availableCategories->count());
            $randomBrand = $availableBrands->get($index % $availableBrands->count());

            Product::create([
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'],
                'stock_quantity' => $productData['stock_quantity'],
                'dosage_form' => $productData['dosage_form'],
                'strength' => $productData['strength'],
                'ingredients' => $productData['ingredients'],
                'usage_instructions' => $productData['usage_instructions'],
                'side_effects' => $productData['side_effects'],
                'requires_prescription' => $productData['requires_prescription'],
                'manufacturer' => $productData['manufacturer'],
                'category_id' => $randomCategory->id,
                'brand_id' => $randomBrand->id,
                'is_active' => true,
            ]);
        }
    }
}
