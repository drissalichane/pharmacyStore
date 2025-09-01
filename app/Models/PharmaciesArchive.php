<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmaciesArchive extends Model
{
    use HasFactory;

    protected $table = 'pharmacies_archive';

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'hours',
        'is_our_pharmacy',
        'is_24h',
        'is_emergency_pharmacy',
        'description',
        'image',
        'scraped_at',
    ];

    protected $casts = [
        'is_our_pharmacy' => 'boolean',
        'is_24h' => 'boolean',
        'is_emergency_pharmacy' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'scraped_at' => 'datetime',
    ];
}
