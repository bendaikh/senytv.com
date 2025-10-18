<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogPostTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $languages = getAvailableLanguages();
        $languageNames = getLanguageNames();

        $blogs = BlogPost::with('translations')
            ->when($request->filled('language'), fn($query) => $query->whereHas('translations', fn($query) => $query->where('language', $request->language)))
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), fn($query) => $query->whereHas('translations', fn($query) => $query->where('title', 'like', '%' . $request->search . '%')))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.blogs.index', compact('blogs', 'languages', 'languageNames'))
            ->with('selectedLanguage', $request->language)
            ->with('selectedStatus', $request->status)
            ->with('search', $request->search);
    }

    public function create()
    {
        return view('admin.blogs.create', [
            'languages' => getAvailableLanguages(),
            'languageNames' => getLanguageNames()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:published,draft',
            'language' => 'required|string|size:2',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:155',
            'img_description' => 'required|string|max:125',
            'slug' => 'nullable|string|max:191|unique:blog_post_translations,slug',
            'content' => 'required|string',
        ]);

        $imagePath = uploadImage($request->file('image'), 'blogs');
        if (!$imagePath) {
            return back()->withErrors(['image' => 'Invalid image file.']);
        }

        $blogPost = BlogPost::create(['image' => $imagePath, 'status' => $request->status]);

        $slug = $request->slug ?: Str::slug($request->title);

        BlogPostTranslation::create([
            'blog_post_id' => $blogPost->id,
            'language' => $request->language,
            'title' => $request->title,
            'description' => $request->description,
            'img_alt' => $request->img_description,
            'slug' => $slug,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created!');
    }

    public function edit($id)
    {
        $blogPost = BlogPost::with('translations')->findOrFail($id);
        return view('admin.blogs.edit', [
            'blogPost' => $blogPost,
            'languages' => getAvailableLanguages(),
            'languageNames' => getLanguageNames()
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:published,draft',
            'language' => 'required|string|size:2',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:155',
            'img_description' => 'required|string|max:125',
            'slug' => 'nullable|string|max:191|unique:blog_post_translations,slug,' . $id . ',blog_post_id',
            'content' => 'required|string',
        ]);

        $blogPost = BlogPost::findOrFail($id);

        // Check if the image is being updated and delete the old image
        if ($request->hasFile('image')) {
            // Remove old image if it exists
            if ($blogPost->image) {
                deleteImage($blogPost->image);
            }

            // Upload new image
            $imagePath = uploadImage($request->file('image'), 'blogs');
            if (!$imagePath) {
                return back()->withErrors(['image' => 'Invalid image file.']);
            }

            $blogPost->image = $imagePath;
        }

        $slug = $request->slug ?: Str::slug($request->title);
        $translation = $blogPost->translations()->where('language', $request->language)->first();

        if ($translation) {
            $translation->update([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
            ]);
        } else {
            BlogPostTranslation::create([
                'blog_post_id' => $blogPost->id,
                'language' => $request->language,
                'title' => $request->title,
                'description' => $request->description,
                'img_alt' => $request->img_description,
                'slug' => $slug,
                'content' => $request->content,
            ]);
        }

        $blogPost->status = $request->status;
        $blogPost->save();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated!');
    }

    public function destroy($id)
    {
        $blogPost = BlogPost::findOrFail($id);

        // Remove the associated image from the server
        if ($blogPost->image) {
            deleteImage($blogPost->image);
        }

        // Delete the blog post translations
        $blogPost->translations()->delete();

        // Delete the blog post itself
        $blogPost->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully!');
    }
}
