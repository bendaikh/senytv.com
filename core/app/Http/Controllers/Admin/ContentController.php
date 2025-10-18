<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->get('language', 'en');
        $langPath = base_path("lang/{$lang}");

        // Check if the language directory exists and contains files
        if (!File::isDirectory($langPath) || !$files = $this->getLanguageFiles($langPath)) {
            return redirect()->route('admin.languages.index')->with('error', 'Language directory or files not found!');
        }

        $file = $request->get('file', $files[0]);
        $filePath = "{$langPath}/{$file}.php";

        if (!File::exists($filePath)) {
            return redirect()->route('admin.languages.index')->with('error', 'Language file not found!');
        }

        // Load and filter the language file content
        $content = include $filePath;
        if (!is_array($content)) {
            return redirect()->route('admin.languages.index')->with('error', 'Invalid language file format!');
        }

        // Search filtering
        $content = collect($content)->filter(fn($value) => !$request->get('search') || str_contains(strtolower($value), strtolower($request->get('search'))));

        // Sanitize content
        $content = $content->map(fn($value) => e($value));

        // Pagination
        $perPage = min(50, max(5, (int) $request->get('per_page', 10)));
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = $content->slice(($currentPage - 1) * $perPage, $perPage);

        $paginatedContent = new LengthAwarePaginator($items, $content->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('admin.languages', [
            'lang' => $lang,
            'file' => $file,
            'paginatedContent' => $paginatedContent,
            'languages' => getAvailableLanguages(),
            'files' => $files,
            'languageNames' => getLanguageNames(),
        ]);
    }

    public function update(Request $request, $language, $file)
    {
        // Get available languages and files
        $availableLanguages = getAvailableLanguages();
        $availableFiles = $this->getAvailableFiles($language);

        // Validate content input from the form
        $validated = $request->validate([
            'content' => 'required|array',
        ]);

        // Ensure the language is valid
        if (!in_array($language, $availableLanguages)) {
            return redirect()->route('admin.languages.index')->with('error', 'Invalid language!');
        }

        // Ensure the file is valid for the selected language
        if (!in_array($file, $availableFiles)) {
            return redirect()->route('admin.languages.index')->with('error', 'Invalid language file!');
        }

        $filePath = base_path("lang/{$language}/{$file}.php");

        if (!File::exists($filePath)) {
            return redirect()->route('admin.languages.index')->with('error', 'Language file not found!');
        }

        // Backup the original language file before modifying
        $backupPath = "{$filePath}.bak";
        File::copy($filePath, $backupPath);

        // Process the content securely
        $safeContent = collect($validated['content'])->mapWithKeys(fn($value, $key) => [
            e($key) => e($value)
        ]);

        // Update the language file content
        $content = "<?php\n\nreturn " . var_export($safeContent->toArray(), true) . ";\n";

        try {
            File::put($filePath, $content);
        } catch (\Exception $e) {
            File::copy($backupPath, $filePath);  // Restore on failure
            return redirect()->route('admin.languages.index')->with('error', 'Failed to update language file!');
        }

        File::delete($backupPath);

        return redirect()->route('admin.languages.index', ['language' => $language, 'file' => $file])
            ->with('success', 'Language file updated successfully!');
    }


    private function getLanguageFiles($langPath)
    {
        return collect(File::files($langPath))
            ->filter(fn($file) => $file->getExtension() === 'php')
            ->map(fn($file) => pathinfo($file->getFilename(), PATHINFO_FILENAME))
            ->toArray();
    }

    private function getAvailableFiles($lang)
    {
        return $this->getLanguageFiles(base_path("lang/{$lang}"));
    }
}
