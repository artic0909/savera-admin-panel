<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomePageSettingController extends Controller
{
    public function index()
    {
        $settings = HomePageSetting::all()->pluck('value', 'key');
        return view('admin.home-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = HomePageSetting::where('key', $key)->first();
            if (!$setting) continue;

            if ($setting->type === 'image') {
                if ($request->hasFile($key)) {
                    // Delete old image if exists
                    if ($setting->value && !str_contains($setting->value, 'assets/images/')) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store('home-settings', 'public');
                    $setting->update(['value' => $path]);
                }
            } elseif ($setting->type === 'json') {
                // This will be handled specifically for each section if needed
                // For now, if it's a simple array of images or similar
                if (is_array($value)) {
                    $setting->update(['value' => json_encode($value)]);
                }
            } else {
                $setting->update(['value' => $value]);
            }
        }

        // Special handling for JSON fields that might have file uploads
        // Banner Section (only 4)
        for ($i = 1; $i <= 4; $i++) {
            $key = "banner_$i";
            if ($request->hasFile($key)) {
                $setting = HomePageSetting::where('key', $key)->first();
                if ($setting->value && !str_contains($setting->value, 'assets/images/')) {
                    Storage::disk('public')->delete($setting->value);
                }
                $path = $request->file($key)->store('home-settings', 'public');
                $setting->update(['value' => $path]);
            }
        }

        // Beginning Section Photos
        if ($request->hasFile('beginning_photos')) {
            $setting = HomePageSetting::where('key', 'beginning_photos')->first();
            $currentPhotos = json_decode($setting->value, true) ?: [];

            // If they want to replace all, we should probably have a way to clear.
            // For now, let's say we append or replace based on some logic.
            // But the user said "Individual option update".
            // I'll implement a more structured update in the view.
        }

        return redirect()->back()->with('success', 'Home page settings updated successfully.');
    }

    public function updateSection(Request $request, $section)
    {
        switch ($section) {
            case 'banners':
                for ($i = 1; $i <= 4; $i++) {
                    if ($request->hasFile("banner_$i")) {
                        $setting = HomePageSetting::where('key', "banner_$i")->first();
                        if ($setting->value && !str_contains($setting->value, 'assets/images/')) {
                            Storage::disk('public')->delete($setting->value);
                        }
                        $path = $request->file("banner_$i")->store('home-settings', 'public');
                        $setting->update(['value' => $path]);
                    }
                }
                break;

            case 'beginning':
                $setting = HomePageSetting::where('key', 'beginning_photos')->first();
                $photos = $request->input('beginning_photos_existing', []);

                if ($request->hasFile('beginning_photos_new')) {
                    foreach ($request->file('beginning_photos_new') as $file) {
                        $path = $file->store('home-settings', 'public');
                        $photos[] = $path;
                    }
                }

                // Limit to 20
                $photos = array_slice($photos, 0, 20);
                $setting->update(['value' => json_encode($photos)]);

                if ($request->has('beginning_heading')) {
                    HomePageSetting::where('key', 'beginning_heading')->update(['value' => $request->beginning_heading]);
                }
                break;

            case 'moments':
                $setting = HomePageSetting::where('key', 'moments_videos')->first();
                $moments = [];

                for ($i = 0; $i < 4; $i++) {
                    $videoPath = $request->input("moments_video_existing_$i");
                    $thumbPath = $request->input("moments_thumb_existing_$i");

                    if ($request->hasFile("moments_video_$i")) {
                        $videoPath = $request->file("moments_video_$i")->store('home-settings', 'public');
                    }
                    if ($request->hasFile("moments_thumb_$i")) {
                        $thumbPath = $request->file("moments_thumb_$i")->store('home-settings', 'public');
                    }

                    if ($videoPath && $thumbPath) {
                        $moments[] = ['video' => $videoPath, 'thumbnail' => $thumbPath];
                    }
                }
                $setting->update(['value' => json_encode($moments)]);

                if ($request->has('moments_heading')) {
                    HomePageSetting::where('key', 'moments_heading')->update(['value' => $request->moments_heading]);
                }
                break;

            case 'why_choose':
                $setting = HomePageSetting::where('key', 'why_choose_photos')->first();
                $photos = [];
                for ($i = 0; $i < 4; $i++) {
                    $path = $request->input("why_choose_photo_existing_$i");
                    if ($request->hasFile("why_choose_photo_$i")) {
                        $path = $request->file("why_choose_photo_$i")->store('home-settings', 'public');
                    }
                    if ($path) $photos[] = $path;
                }
                $setting->update(['value' => json_encode($photos)]);

                if ($request->hasFile('why_choose_logo')) {
                    $logoSetting = HomePageSetting::where('key', 'why_choose_logo')->first();
                    $path = $request->file('why_choose_logo')->store('home-settings', 'public');
                    $logoSetting->update(['value' => $path]);
                }

                if ($request->has('why_choose_heading')) {
                    HomePageSetting::where('key', 'why_choose_heading')->update(['value' => $request->why_choose_heading]);
                }
                break;

            case 'store_front':
                if ($request->hasFile('store_front_image')) {
                    $setting = HomePageSetting::where('key', 'store_front_image')->first();
                    $path = $request->file('store_front_image')->store('home-settings', 'public');
                    $setting->update(['value' => $path]);
                }
                break;
        }

        return redirect()->back()->with('success', ucfirst($section) . ' section updated successfully.');
    }
}
