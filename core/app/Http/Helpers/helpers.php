<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use GeoIp2\Database\Reader;

function loadSettings()
{
    static $settingsCache = null;

    if ($settingsCache === null) {
        // Cache settings for performance optimization
        $settingsCache = Cache::remember('app_settings', now()->addMinutes(60), function () {
            return Setting::whereIn('key', [
                'google_analytics',
                'custom_scripts',
                'site_name',
                'site_logo',
                'site_favicon',
                'seo_keywords',
                'seo_description',
                'seo_title',
                'whatsapp',
                'whatsapp_text',
                'whatsapp_dir',
                'img_slider_1',
                'img_slider_2',
                'img_slider_3',
                'best_section_img',
                'our_partners_img',
                'social_media_links',
            ])->pluck('value', 'key')->toArray();
        });
    }

    return $settingsCache;
}

function getSetting($key, $default = null)
{
    return loadSettings()[$key] ?? $default;
}

function setSetting($key, $value)
{
    Setting::setSetting($key, $value);

    // Clear cache to reflect updated settings
    Cache::forget('app_settings');
}

// Generalized helper to retrieve setting values dynamically
function setting($key, $default = null)
{
    return getSetting($key, $default);
}

function siteName()
{
    return setting('site_name');
}

function splitSiteName()
{
    return explode(' ', siteName(), 2);
}


function siteLogo()
{
    return setting('site_logo');
}
function siteFav()
{
    return setting('site_favicon');
}
function seoTitle()
{
    return setting('seo_title');
}
function seoDescription()
{
    return setting('seo_description');
}


function getSocialMediaLinks(): array
{
    $data = setting('social_media_links');

    if (is_string($data)) {
        $decoded = json_decode($data, true);
        return is_array($decoded) ? $decoded : [];
    }

    return is_array($data) ? $data : [];
}


function seoKeywords()
{
    return setting('seo_keywords');
}
function googleAnalyticsKey()
{
    return setting('google_analytics');
}
function whatsapp()
{
    return setting('whatsapp');
}


function whatsappText()
{
    return setting('whatsapp_text');
}


function whatsappDir(): string
{
    return match (setting('whatsapp_dir')) {
        'left' => 'start-0',
        'right' => 'end-0',
        'center' => 'top-50',
        default => 'end-0',
    };
}

function customScripts(): ?string
{
    return setting('custom_scripts');
}

function getImages(): array
{
    return [
        setting('img_slider_1'),
        setting('img_slider_2'),
        setting('img_slider_3'),
        setting('best_section_img'),
        setting('our_partners_img'),
    ];
}


function get_available_templates()
{
    $templatePath = resource_path('views/templates');
    return array_map(function ($dir) use ($templatePath) {
        return basename($dir);
    }, array_filter(glob($templatePath . '/*'), 'is_dir'));
}



function uploadImage(UploadedFile $file, string $subFolder = '', ?string $filename = null): ?string
{
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];

    // Validate file extension
    $extension = strtolower($file->getClientOriginalExtension());
    if (!in_array($extension, $allowedExtensions)) {
        return null;
    }

    // Validate MIME type to prevent malicious file uploads
    if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
        return null;
    }

    // Generate a unique filename if not provided
    $filename = $filename ?: Str::random(20) . '.' . $extension;

    // Define the folder path inside assets/images
    $folderPath = dirname(__DIR__, 4) . '/assets/images' . ($subFolder ? '/' . $subFolder : '');


    $filePath = 'assets/images/' . ($subFolder ? $subFolder . '/' : '') . $filename;


    // Create the directory if it doesn't exist
    if (!File::exists($folderPath)) {
        File::makeDirectory($folderPath, 0777, true, true);
    }

    // Move the uploaded file to the designated folder
    if ($file->move($folderPath, $filename)) {
        return $filePath;
    }

    return null;
}



function deleteImage(string $filePath): bool
{
    // Get the full path of the image
    $fullPath = dirname(base_path()) . '/' . $filePath;

    // Delete the image if it exists
    return File::exists($fullPath) ? File::delete($fullPath) : false;
}


function handleImageUpdate($file, $key, $subFolder)
{
    // Upload the new image and get its path
    $newPath = uploadImage($file, $subFolder);

    if ($newPath) {
        // Retrieve the old image path from the database
        $oldPath = Setting::getSetting($key);

        // Delete the old image if it exists
        if ($oldPath) {
            deleteImage($oldPath);
        }

        return $newPath;
    }

    return null;
}


function getCountryCode(string $ip): string
{
    try {
        $databasePath = storage_path('app/private/GeoLite2-Country.mmdb');

        if (!file_exists($databasePath)) {
            return config('app.fallback_locale', 'en');
        }

        $reader = new Reader(filename: $databasePath);
        $record = $reader->country($ip);
        return strtolower($record->country->isoCode);
    } catch (\Exception $e) {
        return config('app.fallback_locale', 'en');
    }
}



function getAvailableLanguages()
{
    $langPath = base_path('lang');
    $languages = [];

    if (File::exists($langPath)) {
        foreach (File::directories($langPath) as $folder) {
            $langCode = basename($folder);
            $languages[$langCode] = $langCode;
        }
    }

    return $languages;
}

function getLanguageNames()
{
    $path = storage_path('app/private/languages.json');

    if (!File::exists($path)) {
        return [];
    }

    $languages = json_decode(file_get_contents($path), true);
    $languageNames = [];

    foreach ($languages as $language) {
        $languageNames[$language['code']] = $language['name'];
    }

    return $languageNames;
}

function getLanguageName($iso)
{
    $languageNames = getLanguageNames();
    return $languageNames[$iso] ?? strtoupper($iso);
}

/**
 * Helper function to load and cache countries data.
 */
function loadCountriesData()
{
    static $countries = null;

    if ($countries === null) {
        // Load the JSON file into an array
        $countriesData = json_decode(file_get_contents(storage_path('app/private/countries.json')), true);

        // Normalize and index the data for faster lookups
        $countries = [];
        foreach ($countriesData as $country) {
            $code = strtoupper($country['code']);
            $countries[$code] = [
                'name' => $country['name'] ?? 'Unknown Country',
                'currency_symbol' => $country['currency_symbol'] ?? '',
                'currency_name' => $country['currency_name'] ?? 'Unknown Currency',
            ];
        }
    }

    return $countries;
}

/**
 * Get the full country name by ISO code.
 */
function getFullCountryName($iso)
{
    $countries = loadCountriesData();
    $iso = strtoupper($iso);

    return $countries[$iso]['name'] ?? 'Unknown Country';
}

/**
 * Get the currency symbol or name by ISO code.
 */
function getCurrency($iso)
{
    $countries = loadCountriesData();
    $iso = strtoupper($iso);

    if (isset($countries[$iso]) && in_array($countries[$iso], ['it', 'ca'])) {
        $currencyName = $countries[$iso]['currency_name'];

        return $currencyName;
    }

    // Default to '$' if the ISO code is not found
    return 'USD';
}

