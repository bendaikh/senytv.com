<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    // Cache the available languages
    protected $availableLanguages;

    public function __construct()
    {
        $this->availableLanguages = getAvailableLanguages();
    }

    public function index(Request $request)
    {
        $language = $request->get('language');
        $plans = Plan::when($language, function ($query) use ($language) {
            return $query->where('language', $language);
        })->paginate(15);

        $languages = getAvailableLanguages();
        $languageNames = getLanguageNames();

        return view('admin.plans.index', compact('plans', 'languages', 'languageNames'));
    }

    public function create()
    {
        $languageNames = getLanguageNames();
        return view('admin.plans.create', [
            'languages' => $this->availableLanguages,
            'languageNames' => $languageNames,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePlan($request);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan added successfully!');
    }

    public function edit(Plan $plan)
    {
        $languages = getAvailableLanguages();
        $languageNames = getLanguageNames();
        return view('admin.plans.edit', compact('plan','languages','languageNames'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $this->validatePlan($request);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully!');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully!');
    }

    // Reusable validation logic for store and update methods
    private function validatePlan(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'best_plan' => 'nullable|in:0,1',
            'language' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, $this->availableLanguages)) {
                        $fail('The selected language is invalid.');
                    }
                },
            ],
        ]);
    }
}
