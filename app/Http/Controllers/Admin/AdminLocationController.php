<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\Location;

class AdminLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'hours' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_our_pharmacy' => 'boolean',
            'is_24h' => 'boolean',
            'is_emergency_pharmacy' => 'boolean',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/locations', $imageName);
            $data['image'] = 'locations/' . $imageName;
        }

        Location::create($data);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return view('admin.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'hours' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_our_pharmacy' => 'boolean',
            'is_24h' => 'boolean',
            'is_emergency_pharmacy' => 'boolean',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($location->image) {
                Storage::disk('public')->delete($location->image);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/locations', $imageName);
            $data['image'] = 'locations/' . $imageName;
        }

        $location->update($data);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location deleted successfully.');
    }

    /**
     * Search for locations by address
     */
    public function searchAddress(Request $request)
    {
        $query = $request->get('query');
        
        // This would typically use a geocoding service
        // For now, return sample coordinates for Marrakech
        $suggestions = [
            [
                'address' => 'Place Jemaa el-Fna, Medina, Marrakech',
                'latitude' => 31.6255,
                'longitude' => -7.9891
            ],
            [
                'address' => 'Avenue Mohammed V, Gueliz, Marrakech',
                'latitude' => 31.6415,
                'longitude' => -7.9991
            ],
            [
                'address' => 'Boulevard Mohammed VI, Hivernage, Marrakech',
                'latitude' => 31.6355,
                'longitude' => -7.9891
            ]
        ];

        return response()->json($suggestions);
    }

    /**
     * Run the pharmacie de garde scraping command
     */
    public function scrapePharmacieDeGarde(Request $request)
    {
        try {
            // Run the scraping command
            Artisan::call('scrape:pharmacie-de-garde');
            
            return response()->json([
                'success' => true,
                'message' => 'Emergency pharmacies updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating pharmacies: ' . $e->getMessage()
            ], 500);
        }
    }
}
