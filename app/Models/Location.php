<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

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
        'image'
    ];

    protected $casts = [
        'is_our_pharmacy' => 'boolean',
        'is_24h' => 'boolean',
        'is_emergency_pharmacy' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function scopeOurPharmacies($query)
    {
        return $query->where('is_our_pharmacy', true);
    }

    public function scopeEmergencyPharmacies($query)
    {
        return $query->where('is_emergency_pharmacy', true);
    }

    public function scopePharmacies24Hour($query)
    {
        return $query->where('is_24h', true);
    }

    public function getFullAddressAttribute()
    {
        return $this->address . ', Marrakech, Morocco';
    }

    public function getCoordinatesAttribute()
    {
        return [
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude
        ];
    }
}
