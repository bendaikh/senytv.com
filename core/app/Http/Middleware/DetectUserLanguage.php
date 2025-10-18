<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class DetectUserLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $countryCode = strtolower(getCountryCode($request->ip()));

        $languageMap = [
            'ca' => 'fr',     // Canada
            'mx' => 'es',     // Mexico
            'br' => 'pt',     // Brazil
            'in' => 'hi',     // India
            'cn' => 'zh',     // China
            'tw' => 'zh',     // Taiwan
            'my' => 'ms',     // Malaysia
            'hk' => 'zh',     // Hong Kong
            'be' => 'nl',     // Belgium
            'ch' => 'de',     // Switzerland
            'at' => 'de',     // Austria
            'de' => 'de',     // Germany
            'es' => 'es',     // Spain
            'ar' => 'es',     // Argentina
            'co' => 'es',     // Colombia
            'cl' => 'es',     // Chile
            'pe' => 'es',     // Peru
            've' => 'es',     // Venezuela
            'pt' => 'pt',     // Portugal
            'ru' => 'ru',     // Russia
            'jp' => 'ja',     // Japan
            'kr' => 'ko',     // South Korea

            'it' => 'it',     // Italy
            'fr' => 'fr',     // France
            'nl' => 'nl',     // Netherlands
            'pl' => 'pl',     // Poland
            'se' => 'sv',     // Sweden
            'no' => 'no',     // Norway
            'fi' => 'fi',     // Finland
            'dk' => 'da',     // Denmark
            'cz' => 'cs',     // Czech Republic
            'hu' => 'hu',     // Hungary
            'ro' => 'ro',     // Romania
            'tr' => 'tr',     // Turkey
            'th' => 'th',     // Thailand
            'vn' => 'vi',     // Vietnam
            'id' => 'id',     // Indonesia
            'ua' => 'uk',     // Ukraine
        ];

        // Set the language based on the country code or use fallback
        $language = $languageMap[$countryCode] ?? config('app.fallback_locale', 'en');

        // Optionally, you can store it in the session if needed later
        Session::put('locale', $language);

        App::setLocale($language);

        return $next($request);
    }
}
