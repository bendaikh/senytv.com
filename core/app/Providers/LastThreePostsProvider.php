<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\BlogPost;

class LastThreePostsProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $language = app()->getLocale();
   
        $lastThreePosts =  BlogPost::with(['translations' => function ($query) use ($language) {
            $query->where('language', $language)
                ->select('blog_post_id', 'slug', 'title', 'created_at');
        }])
        ->whereHas('translations', function ($query) use ($language) {
            $query->where('language', $language);
        })
        ->where('status', 'published')
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

        View::share('lastThreePosts', $lastThreePosts);
    
    }
}
