<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Our Pharmacy Locations
        Location::create([
            'name' => 'Pharmacy Al Medina',
            'address' => 'Rue des Banques, Medina, Marrakech',
            'latitude' => 31.6295,
            'longitude' => -7.9811,
            'phone' => '+212 524 44 44 44',
            'email' => 'contact@pharmacyalmedina.ma',
            'website' => 'https://pharmacyalmedina.ma',
            'hours' => 'Monday-Saturday: 8:00 AM - 10:00 PM, Sunday: 9:00 AM - 8:00 PM',
            'is_our_pharmacy' => true,
            'is_24h' => false,
            'is_emergency_pharmacy' => false,
            'description' => 'Our main pharmacy located in the heart of Medina, offering a wide range of medications and health products.',
        ]);

        Location::create([
            'name' => 'Pharmacy Gueliz',
            'address' => 'Avenue Mohammed V, Gueliz, Marrakech',
            'latitude' => 31.6415,
            'longitude' => -7.9991,
            'phone' => '+212 524 45 45 45',
            'email' => 'contact@pharmacygueliz.ma',
            'website' => 'https://pharmacygueliz.ma',
            'hours' => 'Monday-Saturday: 8:00 AM - 11:00 PM, Sunday: 9:00 AM - 9:00 PM',
            'is_our_pharmacy' => true,
            'is_24h' => false,
            'is_emergency_pharmacy' => false,
            'description' => 'Modern pharmacy in the Gueliz district, providing comprehensive pharmaceutical services.',
        ]);

        Location::create([
            'name' => 'Pharmacy Hivernage',
            'address' => 'Boulevard Mohammed VI, Hivernage, Marrakech',
            'latitude' => 31.6355,
            'longitude' => -7.9891,
            'phone' => '+212 524 46 46 46',
            'email' => 'contact@pharmacyhivernage.ma',
            'website' => 'https://pharmacyhivernage.ma',
            'hours' => '24/7',
            'is_our_pharmacy' => true,
            'is_24h' => true,
            'is_emergency_pharmacy' => false,
            'description' => '24-hour pharmacy serving the Hivernage area with emergency services available.',
        ]);

        // Emergency Pharmacies (Pharmacie de Garde)
        Location::create([
            'name' => 'Pharmacie de Garde - Centre Ville',
            'address' => 'Place Jemaa el-Fna, Medina, Marrakech',
            'latitude' => 31.6255,
            'longitude' => -7.9891,
            'phone' => '+212 524 47 47 47',
            'email' => 'urgence@pharmaciedegarde.ma',
            'hours' => '24/7 Emergency Service',
            'is_our_pharmacy' => false,
            'is_24h' => true,
            'is_emergency_pharmacy' => true,
            'description' => 'Emergency pharmacy service in the city center, available 24/7 for urgent medical needs.',
        ]);

        Location::create([
            'name' => 'Pharmacie de Garde - Palmeraie',
            'address' => 'Route de Fès, Palmeraie, Marrakech',
            'latitude' => 31.6455,
            'longitude' => -7.9791,
            'phone' => '+212 524 48 48 48',
            'email' => 'urgence@pharmaciedegarde.ma',
            'hours' => '24/7 Emergency Service',
            'is_our_pharmacy' => false,
            'is_24h' => true,
            'is_emergency_pharmacy' => true,
            'description' => 'Emergency pharmacy serving the Palmeraie area and surrounding neighborhoods.',
        ]);

        Location::create([
            'name' => 'Pharmacie de Garde - Agdal',
            'address' => 'Avenue Hassan II, Agdal, Marrakech',
            'latitude' => 31.6355,
            'longitude' => -7.9991,
            'phone' => '+212 524 49 49 49',
            'email' => 'urgence@pharmaciedegarde.ma',
            'hours' => '24/7 Emergency Service',
            'is_our_pharmacy' => false,
            'is_24h' => true,
            'is_emergency_pharmacy' => true,
            'description' => 'Emergency pharmacy in the Agdal district, providing urgent medical assistance.',
        ]);

        // Additional 24-Hour Pharmacies
        Location::create([
            'name' => 'Pharmacie 24h - Menara',
            'address' => 'Avenue de la Menara, Menara, Marrakech',
            'latitude' => 31.6255,
            'longitude' => -7.9691,
            'phone' => '+212 524 50 50 50',
            'email' => 'contact@pharmacie24h.ma',
            'hours' => '24/7',
            'is_our_pharmacy' => false,
            'is_24h' => true,
            'is_emergency_pharmacy' => false,
            'description' => '24-hour pharmacy near the Menara gardens, serving the local community.',
        ]);

        Location::create([
            'name' => 'Pharmacie 24h - Sidi Youssef Ben Ali',
            'address' => 'Rue Sidi Youssef Ben Ali, Marrakech',
            'latitude' => 31.6155,
            'longitude' => -7.9791,
            'phone' => '+212 524 51 51 51',
            'email' => 'contact@pharmacie24h.ma',
            'hours' => '24/7',
            'is_our_pharmacy' => false,
            'is_24h' => true,
            'is_emergency_pharmacy' => false,
            'description' => '24-hour pharmacy in the Sidi Youssef Ben Ali neighborhood.',
        ]);
    }
}
