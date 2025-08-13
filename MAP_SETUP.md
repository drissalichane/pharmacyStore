# Pharmacy Map Setup Guide

## Overview
This pharmacy management system now includes a comprehensive map service that allows clients to:
- Find your pharmacy locations
- Locate nearby emergency pharmacies (Pharmacie de Garde)
- Search for 24-hour pharmacies
- Get directions to any pharmacy

## Features Implemented

### 1. Location Management
- **Location Model**: Stores pharmacy information including coordinates, contact details, and operating hours
- **Database Migration**: Created `locations` table with all necessary fields
- **Sample Data**: Seeded with sample pharmacy locations in Marrakech

### 2. Map Interface
- **Google Maps Integration**: Interactive map showing all pharmacy locations
- **Search & Filter**: Search by name/address and filter by pharmacy type
- **Interactive Markers**: Different colors for your pharmacies vs emergency pharmacies
- **Info Windows**: Click markers to see detailed information and get directions

### 3. Pharmacy Types
- **Our Pharmacies**: Your pharmacy branches (blue markers)
- **Emergency Pharmacies**: Pharmacie de Garde locations (red markers)
- **24-Hour Pharmacies**: Pharmacies open 24/7

## Setup Instructions

### 1. Google Maps API Setup

1. **Get API Key**:
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Create a new project or select existing one
   - Enable the following APIs:
     - Maps JavaScript API
     - Places API
     - Geocoding API
   - Create credentials (API Key)
   - Restrict the API key to your domain for security

2. **Configure Environment**:
   ```bash
   # Add to your .env file
   GOOGLE_MAPS_API_KEY=your_actual_api_key_here
   ```

### 2. Database Setup

Run the migrations and seed the database:
```bash
php artisan migrate
php artisan db:seed --class=LocationSeeder
```

### 3. Routes Available

- `/map` - Main map page with all pharmacies
- `/map/our-locations` - Shows only your pharmacy locations
- `/map/nearby-pharmacies` - Shows emergency and 24-hour pharmacies
- `/map/search` - API endpoint for searching pharmacies

## Sample Data Included

### Our Pharmacies
1. **Pharmacy Al Medina** - Rue des Banques, Medina
2. **Pharmacy Gueliz** - Avenue Mohammed V, Gueliz  
3. **Pharmacy Hivernage** - Boulevard Mohammed VI, Hivernage (24h)

### Emergency Pharmacies (Pharmacie de Garde)
1. **Centre Ville** - Place Jemaa el-Fna, Medina
2. **Palmeraie** - Route de Fès, Palmeraie
3. **Agdal** - Avenue Hassan II, Agdal

### 24-Hour Pharmacies
1. **Menara** - Avenue de la Menara
2. **Sidi Youssef Ben Ali** - Rue Sidi Youssef Ben Ali

## Customization

### Adding Your Pharmacy Locations
1. Access the database directly or create an admin interface
2. Add new records to the `locations` table
3. Set `is_our_pharmacy = true` for your locations
4. Set `is_emergency_pharmacy = true` for emergency pharmacies
5. Set `is_24h = true` for 24-hour pharmacies

### Updating Coordinates
- Use Google Maps to get precise coordinates
- Update the `latitude` and `longitude` fields
- Test the map to ensure markers appear correctly

## Next Steps

### Web Scraping for Pharmacie de Garde
The system is ready for web scraping integration:
- Create a command to scrape current Pharmacie de Garde data
- Update emergency pharmacy locations automatically
- Schedule regular updates

### Additional Features
- User location detection
- Distance calculation
- Route optimization
- Mobile-responsive improvements
- Real-time availability updates

## Security Notes

1. **API Key Security**: 
   - Restrict your Google Maps API key to your domain
   - Monitor API usage to prevent abuse
   - Consider using environment-specific keys

2. **Data Validation**:
   - Validate coordinates before saving
   - Sanitize user inputs in search functionality
   - Implement rate limiting for search API

## Troubleshooting

### Map Not Loading
- Check if Google Maps API key is set correctly
- Verify API key has required permissions
- Check browser console for JavaScript errors

### Markers Not Showing
- Verify coordinates are valid (between -90 and 90 for latitude, -180 and 180 for longitude)
- Check if pharmacy data exists in database
- Ensure JavaScript is enabled in browser

### Search Not Working
- Check network tab for API call errors
- Verify route is accessible
- Check database connection

## API Endpoints

### Search Pharmacies
```
GET /map/search?query={search_term}&type={filter_type}
```

Parameters:
- `query`: Search term (optional)
- `type`: Filter type - 'all', 'our', 'emergency', '24h' (optional)

Response: JSON array of pharmacy locations

## File Structure

```
app/
├── Models/
│   └── Location.php
├── Http/Controllers/
│   └── MapController.php
resources/views/
├── layouts/
│   └── app.blade.php
└── map/
    └── index.blade.php
database/
├── migrations/
│   └── 2025_08_03_043030_create_locations_table.php
└── seeders/
    └── LocationSeeder.php
config/
└── services.php
```

The map service is now fully integrated and ready for use! 🗺️ 