<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing brands
        Brand::query()->delete();

        $brands = [
            [
                'name' => 'Pfizer',
                'description' => 'Leader mondial dans l\'industrie pharmaceutique',
                'is_active' => true,
            ],
            [
                'name' => 'Sanofi',
                'description' => 'Entreprise biopharmaceutique internationale',
                'is_active' => true,
            ],
            [
                'name' => 'Novartis',
                'description' => 'Entreprise pharmaceutique suisse',
                'is_active' => true,
            ],
            [
                'name' => 'Johnson & Johnson',
                'description' => 'Entreprise de soins de santé mondiale',
                'is_active' => true,
            ],
            [
                'name' => 'Roche',
                'description' => 'Entreprise biopharmaceutique suisse',
                'is_active' => true,
            ],
            [
                'name' => 'Bayer',
                'description' => 'Entreprise allemande de sciences de la vie',
                'is_active' => true,
            ],
            [
                'name' => 'GSK',
                'description' => 'GlaxoSmithKline - Sciences de la santé',
                'is_active' => true,
            ],
            [
                'name' => 'AstraZeneca',
                'description' => 'Entreprise biopharmaceutique anglo-suédoise',
                'is_active' => true,
            ],
            [
                'name' => 'Merck',
                'description' => 'Entreprise pharmaceutique américaine',
                'is_active' => true,
            ],
            [
                'name' => 'Abbott',
                'description' => 'Entreprise de soins de santé américaine',
                'is_active' => true,
            ],
            [
                'name' => 'La Roche-Posay',
                'description' => 'Marque dermatologique française',
                'is_active' => true,
            ],
            [
                'name' => 'Vichy',
                'description' => 'Marque de soins de la peau',
                'is_active' => true,
            ],
            [
                'name' => 'Avène',
                'description' => 'Marque de soins dermatologiques',
                'is_active' => true,
            ],
            [
                'name' => 'Mustela',
                'description' => 'Spécialiste des soins pour bébés et mamans',
                'is_active' => true,
            ],
            [
                'name' => 'Bioderma',
                'description' => 'Marque de soins dermatologiques',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'description' => $brand['description'],
                'is_active' => $brand['is_active'],
            ]);
        }
    }
}
