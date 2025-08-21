<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPharmacyImageController extends Controller
{
    public function uploadForm()
    {
        return view('admin.upload-pharmacy-image');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pharmacy_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);
        $path = $request->file('pharmacy_image')->store('public/pharmacy_images');
        $filename = basename($path);
        // Save filename in a config or DB if needed
        return redirect()->back()->with('success', 'Image uploaded successfully!');
    }
    public static function getLatestImageUrl()
    {
        $files = Storage::files('public/pharmacy_images');
        if (count($files) > 0) {
            $latest = collect($files)->sort()->last();
            return Storage::url($latest);
        }
        return null;
    }
}
