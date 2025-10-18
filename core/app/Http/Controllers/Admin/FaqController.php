<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $languages = getAvailableLanguages();
        $languageNames = getLanguageNames();

        $faqs = Faq::orderBy('order')
            ->when($request->filled('language'), fn($query) => $query->where('language', $request->language))
            ->paginate(15)
            ->through(fn($faq) => tap($faq, fn($f) => $f->language_name = $languageNames[$f->language] ?? strtoupper($f->language)));

        return view('admin.faqs.index', compact('faqs', 'languages', 'languageNames'))
            ->with('selectedLanguage', $request->language);
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateFaqRequest($request);
        $validatedData['order'] = $request->order ?? $this->getNextOrder();

        Faq::create($validatedData);

        return to_route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        $languages = getAvailableLanguages();
        $languageNames = getLanguageNames();

        return view('admin.faqs.edit', compact('faq', 'languages', 'languageNames'));
    }


    public function update(Request $request, Faq $faq)
    {
        $validatedData = $this->validateFaqRequest($request);
        $validatedData['order'] = $request->order ?? $this->getNextOrder();

        $faq->update($validatedData);

        return to_route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return to_route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    private function validateFaqRequest(Request $request): array
    {
        $availableLanguages = getAvailableLanguages();

        return $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required',
            'order' => 'nullable|integer',
            'language' => [
                'required',
                function ($attribute, $value, $fail) use ($availableLanguages) {
                    if (!in_array($value, $availableLanguages)) {
                        $fail('The selected language is invalid.');
                    }
                }
            ],
        ]);
    }

    private function getNextOrder(): int
    {
        return (Faq::max('order') ?? 0) + 1;
    }
}
