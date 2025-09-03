<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing categories
        Category::query()->delete();

        // Root categories with different subcategory depths
        $rootCategories = [
            [
                'name' => 'Médicaments',
                'description' => 'Médicaments sur ordonnance et en vente libre',
                'slug' => 'medicaments',
                'is_active' => true,
                'sort_order' => 1,
                'root_type' => 'Médicaments'
            ],
            [
                'name' => 'Parapharmacie',
                'description' => 'Produits de parapharmacie et soins personnels',
                'slug' => 'parapharmacie',
                'is_active' => true,
                'sort_order' => 2,
                'root_type' => 'Parapharmacie'
            ],
            [
                'name' => 'Hygiène & Beauté',
                'description' => 'Produits d\'hygiène et de beauté',
                'slug' => 'hygiene-beaute',
                'is_active' => true,
                'sort_order' => 3,
                'root_type' => 'Hygiène & Beauté'
            ],
            [
                'name' => 'Mère & Bébé',
                'description' => 'Produits pour la mère et l\'enfant',
                'slug' => 'mere-bebe',
                'is_active' => true,
                'sort_order' => 4,
                'root_type' => 'Mère & Bébé'
            ]
        ];

        $createdRoots = [];
        foreach ($rootCategories as $category) {
            $createdRoots[] = Category::create($category);
        }

        // Create subcategories for each root with varying depths
        $this->createMedicamentsSubcategories($createdRoots[0]);
        $this->createParapharmacieSubcategories($createdRoots[1]);
        $this->createHygieneBeauteSubcategories($createdRoots[2]);
        $this->createMereBebeSubcategories($createdRoots[3]);
    }

    private function createMedicamentsSubcategories(Category $root)
    {
        // Level 1 subcategories for Médicaments
        $level1 = [
            ['name' => 'Analgésiques', 'description' => 'Médicaments contre la douleur'],
            ['name' => 'Antibiotiques', 'description' => 'Médicaments antibiotiques'],
            ['name' => 'Cardiovasculaire', 'description' => 'Médicaments cardiovasculaires'],
            ['name' => 'Dermatologie', 'description' => 'Médicaments dermatologiques'],
        ];

        $createdLevel1 = [];
        foreach ($level1 as $index => $cat) {
            $createdLevel1[] = Category::create([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => $root->id,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Level 2 subcategories (some have deeper levels, some don't)
        $this->createAnalgesiquesSubcategories($createdLevel1[0]); // 3 levels deep
        $this->createAntibiotiquesSubcategories($createdLevel1[1]); // 2 levels deep
        $this->createCardiovasculaireSubcategories($createdLevel1[2]); // 2 levels deep
        // Dermatologie has no subcategories
    }

    private function createAnalgesiquesSubcategories(Category $parent)
    {
        $subcats = [
            ['name' => 'Anti-inflammatoires', 'description' => 'AINS'],
            ['name' => 'Antalgiques', 'description' => 'Antalgiques simples'],
            ['name' => 'Migraine', 'description' => 'Traitement de la migraine'],
        ];

        $createdSubs = [];
        foreach ($subcats as $index => $cat) {
            $createdSubs[] = Category::create([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => $parent->id,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Level 3 subcategories for Anti-inflammatoires
        $level3 = [
            ['name' => 'Ibuprofène', 'description' => 'Ibuprofène et dérivés'],
            ['name' => 'Diclofénac', 'description' => 'Diclofénac et dérivés'],
            ['name' => 'Corticoïdes', 'description' => 'Corticoïdes locaux'],
        ];

        foreach ($level3 as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => $createdSubs[0]->id, // Anti-inflammatoires
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function createAntibiotiquesSubcategories(Category $parent)
    {
        $subcats = [
            ['name' => 'Pénicillines', 'description' => 'Famille des pénicillines'],
            ['name' => 'Macrolides', 'description' => 'Famille des macrolides'],
            ['name' => 'Quinolones', 'description' => 'Famille des quinolones'],
        ];

        foreach ($subcats as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => $parent->id,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function createCardiovasculaireSubcategories(Category $parent)
    {
        $subcats = [
            ['name' => 'Antihypertenseurs', 'description' => 'Médicaments contre l\'hypertension'],
            ['name' => 'Anticoagulants', 'description' => 'Anticoagulants et antiagrégants'],
            ['name' => 'Diurétiques', 'description' => 'Médicaments diurétiques'],
        ];

        foreach ($subcats as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => $parent->id,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function createParapharmacieSubcategories(Category $root)
    {
        $level1 = [
            ['name' => 'Soins du visage', 'description' => 'Produits de soin du visage'],
            ['name' => 'Soins du corps', 'description' => 'Produits de soin du corps'],
            ['name' => 'Cheveux', 'description' => 'Produits pour les cheveux'],
        ];

        $createdLevel1 = [];
        foreach ($level1 as $index => $cat) {
            $createdLevel1[] = Category::create([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => $root->id,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // Level 2 for Soins du visage
        $visageSubs = [
            ['name' => 'Crèmes hydratantes', 'description' => 'Crèmes hydratantes pour le visage'],
            ['name' => 'Sérums', 'description' => 'Sérums et soins ciblés'],
            ['name' => 'Masques', 'description' => 'Masques de beauté'],
        ];

        foreach ($visageSubs as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => $createdLevel1[0]->id,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function createHygieneBeauteSubcategories(Category $root)
    {
        $level1 = [
            ['name' => 'Hygiène buccale', 'description' => 'Produits d\'hygiène buccale'],
            ['name' => 'Hygiène intime', 'description' => 'Produits d\'hygiène intime'],
            ['name' => 'Déodorants', 'description' => 'Déodorants et anti-transpirants'],
        ];

        foreach ($level1 as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => $root->id,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function createMereBebeSubcategories(Category $root)
    {
        $level1 = [
            ['name' => 'Alimentation', 'description' => 'Produits d\'alimentation pour bébé'],
            ['name' => 'Hygiène bébé', 'description' => 'Produits d\'hygiène pour bébé'],
            ['name' => 'Soins bébé', 'description' => 'Produits de soin pour bébé'],
        ];

        foreach ($level1 as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => $root->id,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
