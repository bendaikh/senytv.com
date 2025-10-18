<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    /**
     * Display the blog page with paginated articles and the latest post.
     */
    public function index(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate');
        $language = app()->getLocale();

        // Fetch paginated articles and the latest post
        $articles = $this->getPaginatedArticles($language);
        $lastPost = $this->getLatestPost($language);


        return view("{$activeTemplate}.blog.index", compact('articles', 'lastPost'));
    }

    /**
     * Fetch paginated articles for the given language.
     */
    private function getPaginatedArticles(string $language): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return BlogPost::with([
            'translations' => function ($query) use ($language) {
                $query->where('language', $language)
                    ->select('blog_post_id', 'slug', 'title', 'content','created_at','updated_at');
            }
        ])
            ->where('status', 'published')
            ->whereHas('translations', function ($query) use ($language) {
                $query->where('language', $language);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['id', 'image', 'created_at']);
    }

    /**
     * Fetch the latest post for the given language.
     */
    private function getLatestPost(string $language): ?BlogPost
    {
        return Cache::remember("latest_post_{$language}", 3600, function () use ($language) {
            return BlogPost::with([
                'translations' => function ($query) use ($language) {
                    $query->where('language', $language)
                        ->select('blog_post_id', 'slug', 'content', 'title', 'created_at');
                }
            ])
                ->where('status', 'published')
                ->whereHas('translations', function ($query) use ($language) {
                    $query->where('language', $language);
                })
                ->orderBy('created_at', 'desc')
                ->first();
        });
    }

    /**
     * Display a specific blog post by its slug.
     */
    public function blogShow(Request $request, $slug): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate');
        $language = app()->getLocale();

        // Fetch the blog post by slug
        $blogPost = $this->getBlogPostBySlug($slug, $language);

        return view("{$activeTemplate}.blog.article", compact('blogPost'));
    }


    /**
     * Fetch a blog post by its slug and language.
     */
    private function getBlogPostBySlug(string $slug, string $language): BlogPost
    {
        return BlogPost::with([
            'translations' => function ($query) use ($language) {
                $query->where('language', $language)
                    ->select('blog_post_id', 'slug', 'title', 'description', 'content', 'created_at', 'updated_at');
            }
        ])
            ->whereHas('translations', function ($query) use ($slug, $language) {
                $query->where('slug', $slug)->where('language', $language);
            })
            ->where('status', 'published')
            ->firstOrFail();
    }

    /**
     * Get Blog posts with translations based on the current language.
     */
    private function getBlogPosts(string $language, int $limit): \Illuminate\Database\Eloquent\Collection
    {
        return BlogPost::with([
            'translations' => function ($query) use ($language) {
                $query->where('language', $language)
                    ->select('blog_post_id', 'slug', 'title', 'created_at');
            }
        ])
            ->whereHas('translations', function ($query) use ($language) {
                $query->where('language', $language);
            })
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get(['id', 'created_at']);
    }
}