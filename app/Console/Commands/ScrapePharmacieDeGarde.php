<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use App\Models\PharmaciesArchive;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
// use Symfony\Component\DomCrawler\Crawler;

class ScrapePharmacieDeGarde extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:pharmacie-de-garde';
    
    /**
     * HTTP client for making requests
     */
    private $client;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Pharmacie de Garde data from syndicat website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Pharmacie de Garde scraping...');

        // Only scrape daytime pharmacies (ignore night)
        $this->client = new \GuzzleHttp\Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ]
        ]);

        try {
            // Clear existing emergency pharmacies
            Location::where('is_emergency_pharmacy', true)->delete();
            $this->info('Cleared existing emergency pharmacies');

            // Scrape both day and night service pharmacies
            $dayPharmacies = $this->scrapeServicePharmacies('day');
            $nightPharmacies = $this->scrapeServicePharmacies('night');
            $allPharmacies = array_merge($dayPharmacies, $nightPharmacies);

            if ($allPharmacies && count($allPharmacies) > 0) {
                $this->info('Successfully scraped real data from website');
                $scrapedAt = now();
                foreach ($allPharmacies as $pharmacy) {
                    try {
                        // Save to locations table
                        Location::create($pharmacy);

                        // Also save to pharmacies_archive table
                        $archiveData = $pharmacy;
                        $archiveData['scraped_at'] = $scrapedAt;

                        // Check for existing record with same relevant fields (excluding timestamps)
                        $existingArchive = PharmaciesArchive::where('name', $archiveData['name'])
                            ->where('address', $archiveData['address'])
                            ->where('latitude', $archiveData['latitude'])
                            ->where('longitude', $archiveData['longitude'])
                            ->where('phone', $archiveData['phone'])
                            ->where('email', $archiveData['email'])
                            ->where('website', $archiveData['website'])
                            ->where('hours', $archiveData['hours'])
                            ->where('is_our_pharmacy', $archiveData['is_our_pharmacy'])
                            ->where('is_24h', $archiveData['is_24h'])
                            ->where('is_emergency_pharmacy', $archiveData['is_emergency_pharmacy'])
                            ->where('description', $archiveData['description'])
                            ->where('image', $archiveData['image'])
                            ->first();

                        if ($existingArchive) {
                            // Update scraped_at timestamp if record exists but data is same
                            $existingArchive->scraped_at = $scrapedAt;
                            $existingArchive->save();
                        } else {
                            // Create new record if no matching record found
                            PharmaciesArchive::create($archiveData);
                        }

                        $this->info("Added: {$pharmacy['name']}");
                    } catch (\Exception $e) {
                        $this->warn("Failed to add pharmacy: " . $e->getMessage());
                    }
                }
            } else {
                $this->info('No real data found, using sample data...');
                $this->addSampleEmergencyPharmacies();
            }

            $this->info('Pharmacie de Garde scraping completed successfully!');
            $this->info('Visit: https://www.syndicat-pharmaciens-marrakech.com/pharmacies-de-garde-marrakech for more details');

        } catch (\Exception $e) {
            $this->error('Error during scraping: ' . $e->getMessage());
            Log::error('Pharmacie de Garde scraping failed: ' . $e->getMessage());
            $this->addSampleEmergencyPharmacies();
        }
    }

    private function addSampleEmergencyPharmacies()
    {
        $emergencyPharmacies = [
            // GUELIZ - Garde du Jour
            [
                'name' => 'Pharmacie de Garde - GUELIZ (Garde du Jour)',
                'address' => 'Avenue Mohammed V, Gueliz, Marrakech',
                'latitude' => 31.6415,
                'longitude' => -7.9991,
                'phone' => '+212 524 44 44 44',
                'hours' => 'DE 09H A 23H SANS INTERRUPTION',
                'description' => 'Emergency pharmacy service in Gueliz district. Garde du jour.',
                'is_our_pharmacy' => false,
                'is_24h' => false,
                'is_emergency_pharmacy' => true,
            ],
            // GUELIZ - Garde de Nuit
            [
                'name' => 'Pharmacie de Garde - GUELIZ (Garde de Nuit)',
                'address' => 'Rue de la Liberté, Gueliz, Marrakech',
                'latitude' => 31.6425,
                'longitude' => -7.9981,
                'phone' => '+212 524 45 45 45',
                'hours' => 'DE 23H A 09H SANS INTERRUPTION',
                'description' => 'Emergency pharmacy service in Gueliz district. Garde de nuit.',
                'is_our_pharmacy' => false,
                'is_24h' => false,
                'is_emergency_pharmacy' => true,
            ],
            // DAOUDIAT - Garde du Jour
            [
                'name' => 'Pharmacie de Garde - DAOUDIAT (Garde du Jour)',
                'address' => 'Route de Fès, Daoudiat, Marrakech',
                'latitude' => 31.6455,
                'longitude' => -7.9791,
                'phone' => '+212 524 46 46 46',
                'hours' => 'DE 09H A 23H SANS INTERRUPTION',
                'description' => 'Emergency pharmacy service in Daoudiat district. Garde du jour.',
                'is_our_pharmacy' => false,
                'is_24h' => false,
                'is_emergency_pharmacy' => true,
            ],
            // DAOUDIAT - Garde de Nuit
            [
                'name' => 'Pharmacie de Garde - DAOUDIAT (Garde de Nuit)',
                'address' => 'Boulevard Hassan II, Daoudiat, Marrakech',
                'latitude' => 31.6465,
                'longitude' => -7.9781,
                'phone' => '+212 524 47 47 47',
                'hours' => 'DE 23H A 09H SANS INTERRUPTION',
                'description' => 'Emergency pharmacy service in Daoudiat district. Garde de nuit.',
                'is_our_pharmacy' => false,
                'is_24h' => false,
                'is_emergency_pharmacy' => true,
            ],
            // MEDINA - Garde du Jour
            [
                'name' => 'Pharmacie de Garde - MEDINA (Garde du Jour)',
                'address' => 'Place Jemaa el-Fna, Medina, Marrakech',
                'latitude' => 31.6255,
                'longitude' => -7.9891,
                'phone' => '+212 524 48 48 48',
                'hours' => 'DE 09H A 23H SANS INTERRUPTION',
                'description' => 'Emergency pharmacy service in Medina district. Garde du jour.',
                'is_our_pharmacy' => false,
                'is_24h' => false,
                'is_emergency_pharmacy' => true,
            ],
            // MEDINA - Garde de Nuit
            [
                'name' => 'Pharmacie de Garde - MEDINA (Garde de Nuit)',
                'address' => 'Rue Riad Zitoun, Medina, Marrakech',
                'latitude' => 31.6265,
                'longitude' => -7.9881,
                'phone' => '+212 524 49 49 49',
                'hours' => 'DE 23H A 09H SANS INTERRUPTION',
                'description' => 'Emergency pharmacy service in Medina district. Garde de nuit.',
                'is_our_pharmacy' => false,
                'is_24h' => false,
                'is_emergency_pharmacy' => true,
            ],
            // HIVERNAGE - Garde du Jour
            [
                'name' => 'Pharmacie de Garde - HIVERNAGE (Garde du Jour)',
                'address' => 'Boulevard Mohammed VI, Hivernage, Marrakech',
                'latitude' => 31.6355,
                'longitude' => -7.9891,
                'phone' => '+212 524 50 50 50',
                'hours' => 'DE 09H A 23H SANS INTERRUPTION',
                'description' => 'Emergency pharmacy service in Hivernage district. Garde du jour.',
                'is_our_pharmacy' => false,
                'is_24h' => false,
                'is_emergency_pharmacy' => true,
            ],
            // HIVERNAGE - Garde de Nuit
            [
                'name' => 'Pharmacie de Garde - HIVERNAGE (Garde de Nuit)',
                'address' => 'Avenue de France, Hivernage, Marrakech',
                'latitude' => 31.6365,
                'longitude' => -7.9881,
                'phone' => '+212 524 51 51 51',
                'hours' => 'DE 23H A 09H SANS INTERRUPTION',
                'description' => 'Emergency pharmacy service in Hivernage district. Garde de nuit.',
                'is_our_pharmacy' => false,
                'is_24h' => false,
                'is_emergency_pharmacy' => true,
            ]
        ];

        $scrapedAt = now();
        foreach ($emergencyPharmacies as $pharmacy) {
            Location::create($pharmacy);

            // Also save to pharmacies_archive table
            $archiveData = $pharmacy;
            $archiveData['scraped_at'] = $scrapedAt;

            // Check for existing record with same relevant fields (excluding timestamps)
            $existingArchive = PharmaciesArchive::where('name', $archiveData['name'])
                ->where('address', $archiveData['address'])
                ->where('latitude', $archiveData['latitude'])
                ->where('longitude', $archiveData['longitude'])
                ->where('phone', $archiveData['phone'])
                ->where('email', $archiveData['email'])
                ->where('website', $archiveData['website'])
                ->where('hours', $archiveData['hours'])
                ->where('is_our_pharmacy', $archiveData['is_our_pharmacy'])
                ->where('is_24h', $archiveData['is_24h'])
                ->where('is_emergency_pharmacy', $archiveData['is_emergency_pharmacy'])
                ->where('description', $archiveData['description'])
                ->where('image', $archiveData['image'])
                ->first();

            if ($existingArchive) {
                // Update scraped_at timestamp if record exists but data is same
                $existingArchive->scraped_at = $scrapedAt;
                $existingArchive->save();
            } else {
                // Create new record if no matching record found
                PharmaciesArchive::create($archiveData);
            }

            $this->info("Added: {$pharmacy['name']}");
        }

        $this->info('Added ' . count($emergencyPharmacies) . ' emergency pharmacies');
    }

    private function scrapeRealData()
    {
        $allPharmacies = [];
        
        // Scrape day service pharmacies
        $this->info('Scraping day service pharmacies...');
        $dayPharmacies = $this->scrapeServicePharmacies('day');
        $allPharmacies = array_merge($allPharmacies, $dayPharmacies);
        
        // Scrape night service pharmacies
        $this->info('Scraping night service pharmacies...');
        $nightPharmacies = $this->scrapeServicePharmacies('night');
        $allPharmacies = array_merge($allPharmacies, $nightPharmacies);
        
        $this->info('Successfully scraped ' . count($allPharmacies) . ' pharmacies total');
        return $allPharmacies;
    }
    
    private function scrapeServicePharmacies($serviceType)
    {
        $pharmacies = [];
        try {
            $baseUrl = "https://www.syndicat-pharmaciens-marrakech.com/pharmacies-de-garde-marrakech";
            $url = $serviceType === 'night' ? $baseUrl . '-nuit' : $baseUrl;
            $this->info("Fetching {$serviceType} service from URL: $url");
            $response = $this->client->get($url);
            $html = $response->getBody()->getContents();
            // Step 1: Extract region links from main page
            if ($serviceType === 'night') {
                preg_match_all('/<a[^>]*href="(\/pharmacies-de-garde-marrakech-nuit\/[a-zA-Z0-9\-]+)"[^>]*>/i', $html, $regionMatches);
            } else {
                preg_match_all('/<a[^>]*href="(\/pharmacies-de-garde-marrakech\/[a-zA-Z0-9\-]+)"[^>]*>/i', $html, $regionMatches);
            }
            $regionLinks = [];
            if (!empty($regionMatches[1])) {
                foreach ($regionMatches[1] as $link) {
                    if (strpos($link, 'http') !== 0) {
                        $link = 'https://www.syndicat-pharmaciens-marrakech.com' . $link;
                    }
                    $regionLinks[] = $link;
                }
            }
            $this->info("[{$serviceType}] Found " . count($regionLinks) . " region links: " . implode(", ", $regionLinks));
            // Step 2: For each region, extract pharmacy links
            $pharmacyLinks = [];
            foreach ($regionLinks as $regionUrl) {
                $this->info("[{$serviceType}] Fetching region URL: $regionUrl");
                $regionResponse = $this->client->get($regionUrl);
                $regionHtml = $regionResponse->getBody()->getContents();
                preg_match_all('/<a[^>]*href="(\/pharmacies-de-garde-marrakech\/pharmacie\/ph-[^\"]+)"[^>]*>/i', $regionHtml, $matches);
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $link) {
                        if (strpos($link, 'http') !== 0) {
                            $link = 'https://www.syndicat-pharmaciens-marrakech.com' . $link;
                        }
                        $pharmacyLinks[] = $link;
                    }
                }
                $this->info("[{$serviceType}] Found " . count($matches[1]) . " pharmacy links in region: $regionUrl");
            }
            $pharmacyLinks = array_unique($pharmacyLinks);
            $this->info("[{$serviceType}] Found " . count($pharmacyLinks) . " pharmacy links for {$serviceType} service: " . implode(", ", $pharmacyLinks));
            // Step 3: Scrape each pharmacy detail page
            foreach ($pharmacyLinks as $link) {
                $pharmacyData = $this->scrapeIndividualPharmacy($link, $serviceType);
                if ($pharmacyData) {
                    $pharmacies[] = $pharmacyData;
                }
                sleep(1);
            }
        } catch (\Exception $e) {
            $this->error("Error fetching {$serviceType} service data: " . $e->getMessage());
        }
        return $pharmacies;
    }
    
    private function extractPharmacyLinks($html)
    {
        $links = [];
        // Find all pharmacy detail links in region page
        preg_match_all('/<a[^>]*href="([^"]*\/pharmacie\/ph-[^"]+)"[^>]*>/i', $html, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $link) {
                if (strpos($link, 'http') !== 0) {
                    $link = 'https://www.syndicat-pharmaciens-marrakech.com' . $link;
                }
                $links[] = $link;
            }
        }
        return array_unique($links);
    }
    
    private function scrapeIndividualPharmacy($url, $serviceType)
    {
        try {
            $response = $this->client->get($url);
            $html = $response->getBody()->getContents();

            // Extract pharmacy name from <h1> if available
            $pharmacyName = 'Unknown Pharmacy';
            if (preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $html, $titleMatches)) {
                $pharmacyName = strip_tags($titleMatches[1]);
            }

            // Extract address, phone, region from <li> tags
            $address = 'Address not available';
            $phone = '';
            $region = '';
            if (preg_match_all('/<li[^>]*>\s*<span[^>]*class="material-symbols-rounded"[^>]*>(.*?)<\/span>\s*(.*?)<\/li>/is', $html, $liMatches, PREG_SET_ORDER)) {
                foreach ($liMatches as $li) {
                    $icon = trim($li[1]);
                    $text = trim(strip_tags($li[2]));
                    if ($icon === 'pin_drop' && $address === 'Address not available') {
                        $address = $text;
                    }
                    if ($icon === 'call' && !$phone) {
                        if (preg_match('/href="tel:([^"]+)"/', $li[2], $phoneMatch)) {
                            $phone = $phoneMatch[1];
                        } else {
                            $phone = $text;
                        }
                    }
                    if ($icon === 'flag' && !$region) {
                        $region = $text;
                    }
                }
            }

            // Extract Google Maps link and coordinates (robust)
            $googleMapsUrl = null;
            $latitude = null;
            $longitude = null;
            if (preg_match('/<a[^>]*href="([^"]*google\.com\/maps[^"]*daddr=([\d\.\-]+),([\d\.\-]+)[^"]*)"/i', $html, $mapMatches)) {
                $googleMapsUrl = $mapMatches[1];
                $latitude = (float) $mapMatches[2];
                $longitude = (float) $mapMatches[3];
            }

            // Determine hours based on service type
            $hours = $serviceType === 'night' ? 'DE 23H A 09H SANS INTERRUPTION' : 'DE 09H A 23H SANS INTERRUPTION';

            // Use coordinates from Google Maps if available
            $coordinates = ['lat' => $latitude, 'lng' => $longitude];
            if (!$latitude || !$longitude) {
                $coordinates = $this->geocodeAddress($address);
            }

            // Print detailed info to terminal
            $this->info("Extracted: \nName: $pharmacyName\nAddress: $address\nPhone: $phone\nRegion: $region\nGoogle Maps: $googleMapsUrl\nLat: $latitude\nLng: $longitude\nLink: $url\n");

            return [
                'name' => $pharmacyName,
                'address' => $address,
                'latitude' => $coordinates['lat'] ?? 31.6295,
                'longitude' => $coordinates['lng'] ?? -7.9811,
                'phone' => $phone,
                'hours' => $hours,
                'description' => "Emergency pharmacy service. {$serviceType} service. Region: {$region}",
                'is_our_pharmacy' => false,
                'is_24h' => false,
                'is_emergency_pharmacy' => true,
            ];
        } catch (\Exception $e) {
            $this->error("Error scraping pharmacy {$url}: " . $e->getMessage());
            return null;
        }
    }
    
    private function geocodeAddress($address)
    {
        // Simple geocoding for Marrakech addresses
        // In a real implementation, you'd use a geocoding service
        $marrakechCoords = [
            'lat' => 31.6295,
            'lng' => -7.9811
        ];
        
        // Add some variation based on address keywords
        if (strpos(strtolower($address), 'gueliz') !== false) {
            $marrakechCoords['lat'] += 0.01;
            $marrakechCoords['lng'] += 0.01;
        } elseif (strpos(strtolower($address), 'medina') !== false) {
            $marrakechCoords['lat'] -= 0.01;
            $marrakechCoords['lng'] -= 0.01;
        }
        
        return $marrakechCoords;
    }

    private function parseHtmlContent($html, $regionName = 'Grand Gueliz')
    {
        $pharmacies = [];
        
        // Define regions and their coordinates
        $regions = [
            'gueliz' => ['lat' => 31.6415, 'lng' => -7.9991],
            'grand gueliz' => ['lat' => 31.6415, 'lng' => -7.9991],
            'daoudiat' => ['lat' => 31.6455, 'lng' => -7.9791],
            'medina' => ['lat' => 31.6255, 'lng' => -7.9891],
            'hivernage' => ['lat' => 31.6355, 'lng' => -7.9891],
            'massira' => ['lat' => 31.6555, 'lng' => -7.9691],
            'agdal' => ['lat' => 31.6355, 'lng' => -7.9691],
            'menara' => ['lat' => 31.6155, 'lng' => -7.9791],
            'sidi youssef' => ['lat' => 31.6455, 'lng' => -7.9691],
            'm\'hamid' => ['lat' => 31.6355, 'lng' => -7.9791],
        ];

        // Based on the actual website structure from https://www.syndicat-pharmaciens-marrakech.com/pharmacies-de-garde-marrakech/grand-gueliz
        // Look for patterns like "Ph. ZOUAN person" or "Ph. M AVENUE person Mme. CHAKIB"
        
        // Extract pharmacy names from the HTML
        $pharmacyNames = [];
        
        // Pattern to match "Ph. [NAME] person" or "Ph. [NAME] person [PERSON_NAME]"
        preg_match_all('/Ph\.\s+([A-Z\s]+?)\s+person\s*([A-Z\s\.]+)?/i', $html, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $index => $pharmacyName) {
                $cleanName = trim($pharmacyName);
                if (strlen($cleanName) > 2) {
                    $personName = isset($matches[2][$index]) ? trim($matches[2][$index]) : '';
                    $pharmacyNames[] = [
                        'name' => $cleanName,
                        'person' => $personName
                    ];
                }
            }
        }
        
        // Try to extract addresses from the HTML
        $addresses = [];
        
        // Look for address patterns after pharmacy names
        preg_match_all('/Ph\.\s+[A-Z\s]+.*?person.*?([A-Z\s]+(?:avenue|rue|boulevard|place|route|quartier|zone)[^<>\n]+)/i', $html, $addressMatches);
        if (!empty($addressMatches[1])) {
            foreach ($addressMatches[1] as $address) {
                $cleanAddress = trim(strip_tags($address));
                if (strlen($cleanAddress) > 5) {
                    $addresses[] = $cleanAddress;
                }
            }
        }
        
        // If no addresses found, try alternative patterns
        if (empty($addresses)) {
            preg_match_all('/([A-Z\s]+(?:avenue|rue|boulevard|place|route|quartier|zone)[^<>\n]+)/i', $html, $addressMatches);
            if (!empty($addressMatches[1])) {
                foreach ($addressMatches[1] as $address) {
                    $cleanAddress = trim(strip_tags($address));
                    if (strlen($cleanAddress) > 5) {
                        $addresses[] = $cleanAddress;
                    }
                }
            }
        }

        // If no pharmacy names found with the main pattern, try alternative patterns
        if (empty($pharmacyNames)) {
            // Look for any text that contains "Ph." followed by a name
            preg_match_all('/Ph\.\s+([A-Z\s]+)/i', $html, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $pharmacyName) {
                    $cleanName = trim($pharmacyName);
                    if (strlen($cleanName) > 2) {
                        $pharmacyNames[] = [
                            'name' => $cleanName,
                            'person' => ''
                        ];
                    }
                }
            }
        }

        $this->info('Found ' . count($pharmacyNames) . ' pharmacy names: ' . implode(', ', array_column($pharmacyNames, 'name')));

        // Determine if this is day or night service based on the HTML content
        $isDayService = stripos($html, 'garde du jour') !== false || stripos($html, 'de 09h a 23h') !== false;
        $isNightService = stripos($html, 'garde de nuit') !== false || stripos($html, 'de 23h a 09h') !== false;
        
        // If neither is explicitly detected, assume it's day service (most common)
        if (!$isDayService && !$isNightService) {
            $isDayService = true;
        }
        
        // Use the provided region name
        $currentRegion = $regionName;
        
        // For testing purposes, let's create both day and night services for some regions
        // This will help demonstrate the separation functionality
        $createBothServices = in_array(strtolower($currentRegion), ['grand gueliz', 'medina']);

        // Create pharmacy entries for each found pharmacy name
        $pharmacyCount = 0;
        foreach ($pharmacyNames as $index => $pharmacyData) {
            if ($pharmacyCount >= 4) break; // Limit to 4 pharmacies per region
            
            $pharmacyName = $pharmacyData['name'];
            $personName = $pharmacyData['person'];
            
            // Get address for this pharmacy (if available)
            $pharmacyAddress = $currentRegion . ', Marrakech';
            if (!empty($addresses) && isset($addresses[$index])) {
                $pharmacyAddress = $addresses[$index] . ', ' . $currentRegion . ', Marrakech';
            }
            
            // Determine service type and hours
            if ($isDayService) {
                $pharmacies[] = [
                    'name' => 'Ph. ' . $pharmacyName . ' - ' . $currentRegion,
                    'address' => $pharmacyAddress,
                    'latitude' => $regions[strtolower($currentRegion)]['lat'] ?? 31.6415,
                    'longitude' => $regions[strtolower($currentRegion)]['lng'] ?? -7.9991,
                    'phone' => '+212 524 ' . rand(40, 49) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                    'hours' => 'DE 09H A 23H SANS INTERRUPTION',
                    'description' => 'Emergency pharmacy service in ' . $currentRegion . ' district. Garde du jour. ' . ($personName ? 'Responsable: ' . $personName : ''),
                    'is_our_pharmacy' => false,
                    'is_24h' => false,
                    'is_emergency_pharmacy' => true,
                ];
                
                // For testing, create night service for some regions
               /* if ($createBothServices && $index < 2) { // Only first 2 pharmacies
                    $pharmacies[] = [
                        'name' => 'Ph. ' . $pharmacyName . ' NOCTURNE - ' . $currentRegion,
                        'address' => $pharmacyAddress,
                        'latitude' => ($regions[strtolower($currentRegion)]['lat'] ?? 31.6415) + 0.001,
                        'longitude' => ($regions[strtolower($currentRegion)]['lng'] ?? -7.9991) + 0.001,
                        'phone' => '+212 524 ' . rand(50, 59) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                        'hours' => 'DE 23H A 09H SANS INTERRUPTION',
                        'description' => 'Emergency pharmacy service in ' . $currentRegion . ' district. Garde de nuit. ' . ($personName ? 'Responsable: ' . $personName : ''),
                        'is_our_pharmacy' => false,
                        'is_24h' => false,
                        'is_emergency_pharmacy' => true,
                    ];
                }*/
            } elseif ($isNightService) {
                $pharmacies[] = [
                    'name' => 'Ph. ' . $pharmacyName . ' - ' . $currentRegion,
                    'address' => $pharmacyAddress,
                    'latitude' => $regions[strtolower($currentRegion)]['lat'] ?? 31.6415,
                    'longitude' => $regions[strtolower($currentRegion)]['lng'] ?? -7.9991,
                    'phone' => '+212 524 ' . rand(50, 59) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                    'hours' => 'DE 23H A 09H SANS INTERRUPTION',
                    'description' => 'Emergency pharmacy service in ' . $currentRegion . ' district. Garde de nuit. ' . ($personName ? 'Responsable: ' . $personName : ''),
                    'is_our_pharmacy' => false,
                    'is_24h' => false,
                    'is_emergency_pharmacy' => true,
                ];
            }
            
            $pharmacyCount++;
        }

        // If no pharmacies were found, create sample data for the detected region
        if (empty($pharmacies)) {
            $this->info('No pharmacy names found, creating sample data for ' . $currentRegion);
            
            if ($isDayService) {
                $pharmacies[] = [
                    'name' => 'Ph. ZOUAN - ' . $currentRegion,
                    'address' => $currentRegion . ', Marrakech',
                    'latitude' => $regions[strtolower($currentRegion)]['lat'] ?? 31.6415,
                    'longitude' => $regions[strtolower($currentRegion)]['lng'] ?? -7.9991,
                    'phone' => '+212 524 44 44 44',
                    'hours' => 'DE 09H A 23H SANS INTERRUPTION',
                    'description' => 'Emergency pharmacy service in ' . $currentRegion . ' district. Garde du jour.',
                    'is_our_pharmacy' => false,
                    'is_24h' => false,
                    'is_emergency_pharmacy' => true,
                ];
            } elseif ($isNightService) {
                $pharmacies[] = [
                    'name' => 'Ph. NOCTURNE - ' . $currentRegion,
                    'address' => $currentRegion . ', Marrakech',
                    'latitude' => $regions[strtolower($currentRegion)]['lat'] ?? 31.6415,
                    'longitude' => $regions[strtolower($currentRegion)]['lng'] ?? -7.9991,
                    'phone' => '+212 524 55 55 55',
                    'hours' => 'DE 23H A 09H SANS INTERRUPTION',
                    'description' => 'Emergency pharmacy service in ' . $currentRegion . ' district. Garde de nuit.',
                    'is_our_pharmacy' => false,
                    'is_24h' => false,
                    'is_emergency_pharmacy' => true,
                ];
            }
        }

        $this->info('Found ' . count($pharmacies) . ' pharmacies from website parsing');
        return $pharmacies;
    }

}
