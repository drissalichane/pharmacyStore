# Switch Map from OpenStreetMap to Google Maps

## Tasks
- [x] Replace Leaflet scripts with Google Maps JavaScript API
- [x] Update map initialization code
- [x] Update marker creation to use Google Maps markers
- [x] Update popup system to use InfoWindow
- [x] Update showOnMap functions
- [x] Test the updated map functionality

# Pharmacies Archive Feature

## Tasks
- [x] Create pharmacies_archive table migration
- [x] Create PharmaciesArchive model
- [x] Modify ScrapePharmacieDeGarde command to save to archive table
- [x] Run migration to create the table
- [x] Implement duplicate prevention logic (update existing records instead of creating duplicates)
- [ ] Test scraping command to verify data is saved to both tables (can be tested via admin dashboard button)
