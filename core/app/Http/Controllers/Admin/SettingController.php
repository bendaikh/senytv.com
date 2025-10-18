<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'key');
        $templates = get_available_templates();
        return view('admin.settings', compact('settings', 'templates'));
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'active_template' => 'required|string',
            'site_logo' => 'nullable|image:allow_svg|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,jpg,ico|max:1024',
            'google_analytics' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:14',
            'whatsapp_dir' => 'required|in:left,center,right',
            'whatsapp_text' => 'nullable|string|max:255',
            'custom_scripts' => 'nullable|string',
        ]);


        DB::transaction(function () use ($validated, $request) {
            foreach ($validated as $key => $value) {
                // Handle image uploads
                if ($request->hasFile($key)) {
                    $subFolder = $key === 'site_logo' ? 'logo' : ($key === 'site_favicon' ? 'favicon' : '');
                    $value = handleImageUpdate($request->file($key), $key, $subFolder);
                }
                // Update the setting
                Setting::setSetting($key, $value);
            }
        });

        Cache::forget('app_settings');

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully!');
    }


    public function socialMediaStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'url' => 'required|url',
            'icon' => 'required|image:allow_svg|mimes:jpg,jpeg,png,svg,webp|max:500',
        ]);

        // Upload icon
        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = uploadImage($request->file('icon'), 'social-media');
        }

        // Load existing links
        $setting = Setting::where('key', 'social_media_links')->first();
        $socialLinks = json_decode($setting->value ?? '[]', true);

        $socialLinks[] = [
            'name' => $request->name,
            'url' => $request->url,
            'icon' => $iconPath,
        ];

        $setting->value = json_encode($socialLinks);
        $setting->save();
        Cache::forget('app_settings');
        return redirect()->back()->with('success', 'Social media link added.');
    }



    public function socialMediaDestroy($id)
    {
        $setting = Setting::where('key', 'social_media_links')->first();

        if (!$setting) {
            return redirect()->back()->with('error', 'Social media setting not found.');
        }

        $socialLinks = json_decode($setting->value, true);

        if (!is_array($socialLinks) || !array_key_exists($id, $socialLinks)) {
            return redirect()->back()->with('error', 'Invalid social media entry.');
        }

        // Delete icon if exists
        if (!empty($socialLinks[$id]['icon'])) {
            if ($socialLinks[$id]['icon']) {
                deleteImage($socialLinks[$id]['icon']);
            }
        }

        // Remove the entry and reindex
        unset($socialLinks[$id]);
        $setting->value = json_encode(array_values($socialLinks));
        $setting->save();
        Cache::forget('app_settings');

        return redirect()->back()->with('success', 'Social media link deleted.');
    }

}
