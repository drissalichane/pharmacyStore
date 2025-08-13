<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class MapController extends Controller
{
    public function index()
    {
        $ourPharmacies = Location::ourPharmacies()->get();
        $emergencyPharmacies = Location::emergencyPharmacies()->get();
        $allPharmacies = Location::all();
        
        return view('map.index', compact('ourPharmacies', 'emergencyPharmacies', 'allPharmacies'));
    }

    public function ourLocations()
    {
        $pharmacies = Location::ourPharmacies()->get();
        return view('map.our-locations', compact('pharmacies'));
    }

    public function nearbyPharmacies()
    {
        $emergencyPharmacies = Location::emergencyPharmacies()->get();
        $pharmacies24h = Location::pharmacies24Hour()->get();
        
        return view('map.nearby-pharmacies', compact('emergencyPharmacies', 'pharmacies24h'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type', 'all');
        
        $pharmacies = Location::query();
        
        if ($query) {
            $pharmacies->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%");
            });
        }
        
        switch ($type) {
            case 'our':
                $pharmacies = $pharmacies->ourPharmacies();
                break;
            case 'emergency':
                $pharmacies = $pharmacies->emergencyPharmacies();
                break;
            case '24h':
                $pharmacies = $pharmacies->pharmacies24Hour();
                break;
        }
        
        $pharmacies = $pharmacies->get();
        
        return response()->json($pharmacies);
    }
}
