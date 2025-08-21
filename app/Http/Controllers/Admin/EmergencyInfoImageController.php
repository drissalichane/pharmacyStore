<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmergencyInfoImage;
use Illuminate\Support\Facades\Storage;

// The private folder (storage/app/private) is for files that should NOT be accessible via the browser, such as sensitive documents, reports, or files for internal use only. You must use Laravel's response/download logic to serve these files securely.

class EmergencyInfoImageController extends Controller
{
    public function uploadForm()
    {
        return view('admin.upload-emergency-info-image');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'emergency_info_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);
        // Always store in storage/app/public/emergency_info_images for web access
        $path = $request->file('emergency_info_image')->store('emergency_info_images', 'public');
        $filename = basename($path);
        $image = EmergencyInfoImage::create([
            'image_path' => $filename,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
        return redirect()->back()->with('success', 'Emergency info image uploaded successfully!');
    }

    public static function getLatestImageUrl()
    {
        $latest = EmergencyInfoImage::latest()->first();
        if ($latest) {
            // Retrieve from emergency_info_images for browser access
            return Storage::url('emergency_info_images/' . $latest->image_path);
        }
        return null;
    }
}
