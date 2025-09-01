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

    public function processCroppedImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        try {
            // Store the cropped image temporarily
            $path = $request->file('image')->store('temp', 'public');
            $fullPath = storage_path('app/public/' . $path);

            // Process OCR using Tesseract
            $tesseractPath = '"C:\\Program Files\\Tesseract-OCR\\tesseract.exe"';
            $command = "$tesseractPath \"$fullPath\" stdout -l fra 2>&1";
            $output = shell_exec($command);

            // Log the command and output for debugging
            \Log::info("Tesseract command: $command");
            \Log::info("Tesseract output: $output");

            // Clean up temporary file
            Storage::disk('public')->delete($path);

            if ($output === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'OCR processing failed'
                ]);
            }

            // Clean up the extracted text
            $cleanedText = trim($output);
            $cleanedText = preg_replace('/\s+/', ' ', $cleanedText);

            return response()->json([
                'success' => true,
                'text' => $cleanedText ?: 'No text detected in the cropped area.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error processing cropped image OCR: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing image: ' . $e->getMessage()
            ]);
        }
    }
}
