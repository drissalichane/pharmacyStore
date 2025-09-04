<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminSettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = Setting::getGroup('appearance');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update settings
        if ($request->has('site_name')) {
            Setting::set('site_name', $request->site_name, 'string', 'appearance');
        }

        if ($request->has('site_description')) {
            Setting::set('site_description', $request->site_description, 'string', 'appearance');
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Upload and update site logo
     */
    public function uploadLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            // Generate unique filename
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();

            // Store in public/images directory
            $path = $file->storeAs('images', $filename, 'public');

            // Delete old logo if exists
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Update setting
            Setting::set('site_logo', $path, 'string', 'appearance');

            return redirect()->back()->with('success', 'Logo uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No logo file provided.');
    }

    /**
     * Remove current logo and revert to default
     */
    public function removeLogo()
    {
        $oldLogo = Setting::get('site_logo');

        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        // Remove setting to use default
        Setting::where('key', 'site_logo')->delete();

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('setting_site_logo');

        return redirect()->back()->with('success', 'Logo removed successfully! Using default logo.');
    }
}
