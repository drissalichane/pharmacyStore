<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            'is_our_pharmacy' => 'boolean',
            'is_24h' => 'boolean',
            'is_emergency_pharmacy' => 'boolean',
        ]);

        Location::create($request->all());

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
            'is_our_pharmacy' => 'boolean',
            'is_24h' => 'boolean',
            'is_emergency_pharmacy' => 'boolean',
        ]);

        $location->update($request->all());

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
}
