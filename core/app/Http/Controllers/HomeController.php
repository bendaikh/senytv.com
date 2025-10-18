<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Plan;
use App\Models\Faq;
use App\Models\Channel;
use App\Models\Slider;
use App\Models\Partner;


class HomeController extends Controller
{
    /**
     * Display the home page with the latest posts, plans, and FAQs.
     */
    public function index(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate');
        $language = app()->getLocale();

        // Fetch plans and FAQs
        $plans = $this->getPlans($language);
        $faqs = $this->getFaqs($language);
        $visitor_country = getCountryCode($request->ip());
        $sliders = Slider::orderBy('order', 'asc')->get();
        $partners = Partner::orderBy('id', 'asc')->get();

        return view("{$activeTemplate}.home", compact('plans', 'faqs', 'visitor_country', 'sliders', 'partners'));
    }


    /**
     * Fetch plans for the given language and format their duration.
     */
    private function getPlans(string $language): \Illuminate\Database\Eloquent\Collection
    {
        // Attempt to retrieve plans for the specified language
        $plans = Plan::where('language', $language)->get();

        // If no plans exist for the specified language, fallback to 'en'
        if ($plans->isEmpty()) {
            $plans = Plan::where('language', 'en')->get();
        }

        // Format the plans and return them
        return $plans->map(fn($plan) => $this->formatPlanDuration($plan));
    }

    /**
     * Fetch FAQs for the given language, ordered by their order attribute.
     */
    private function getFaqs(string $language): \Illuminate\Database\Eloquent\Collection
    {
        return Faq::where('language', $language)
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Format the plan duration based on its type.
     */
    private function formatPlanDuration($plan): object
    {
        $plan->duration_type = $this->getDurationType($plan->duration);
        return $plan;
    }

    /**
     * Determine the type of duration (Days, Months, or Years).
     */
    private function getDurationType(int $duration): string
    {
        if ($duration >= 365) {
            $years = floor($duration / 365);
            $remainingDays = $duration % 365;
            $months = floor($remainingDays / 30);

            if ($months > 0) {
                return "{$years} Year" . ($years > 1 ? "s" : "") . " {$months} Month" . ($months > 1 ? "s" : "");
            }
            return "{$years} Year" . ($years > 1 ? "s" : "");
        }

        if ($duration >= 30) {
            $months = floor($duration / 30);
            $remainingDays = $duration % 30;

            if ($remainingDays > 0) {
                return "{$months} Month" . ($months > 1 ? "s" : "") . " {$remainingDays} Day" . ($remainingDays > 1 ? "s" : "");
            }
            return "{$months} Month" . ($months > 1 ? "s" : "");
        }

        return "{$duration} Day" . ($duration > 1 ? "s" : "");
    }

    /*
     *Channels List
     */
    public function channels(Request $request)
    {

        $activeTemplate = $request->attributes->get('activeTemplate');
        $language = app()->getLocale();

        $channelsByRegion = Channel::select('region', 'country')
            ->distinct()
            ->orderBy('region')
            ->orderBy('country')
            ->get()
            ->groupBy('region')
            ->map(fn($group) => $group->pluck('country')->unique()->values()->all());


        return view("{$activeTemplate}.channels", compact('channelsByRegion', 'language'));
    }


    public function loadChannels($region, $country)
    {
        $channels = Channel::where('region', $region)
            ->where('country', $country)
            ->select('name')
            ->get();

        return response()->json([
            'channels' => $channels
        ]);
    }

    public function searchChannels(Request $request)
    {
        $searchTerm = $request->query('query');
        $countryCode = getCountryCode($request->ip());

        $channels = Channel::where('name', 'like', '%' . $searchTerm . '%')
            ->orderByRaw('country = ? DESC', [$countryCode])
            ->select('region', 'country', 'name')
            ->get();

        return response()->json(['channels' => $channels]);
    }





}