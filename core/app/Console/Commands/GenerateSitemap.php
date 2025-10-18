<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\BlogPost;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sitemap generation...');

        $sitemap = Sitemap::create();

        // Add your static URLs
        $this->addUrl($sitemap, route('home'), 'daily', 1.0);
        $this->addUrl($sitemap, route('blog.index'), 'daily', 0.8);
        $this->addUrl($sitemap, route('channels.index'), 'weekly', 0.7);
        $this->addUrl($sitemap, route('privacy'), 'monthly', 0.3);
        $this->addUrl($sitemap, route('terms'), 'monthly', 0.3);
        $this->addUrl($sitemap, route('refund'), 'monthly', 0.3);

        // Load blog posts with translations eager loaded
        $blogPosts = BlogPost::with('translations')->where('status', 'published')->get();

        foreach ($blogPosts as $post) {
            // Use the first translation as the main <loc>
            $mainTranslation = $post->translations->first();

            $mainUrl = route('blog.show', ['slug' => $mainTranslation->slug]);

            $urlTag = Url::create($mainUrl)
                ->setLastModificationDate($post->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8);

            // Add hreflang alternate links for all translations
            foreach ($post->translations as $translation) {
                $altUrl = route('blog.show', ['slug' => $translation->slug]);
                $urlTag->addAlternate($altUrl, $translation->language);
            }

            $sitemap->add($urlTag);
        }


        $path = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'sitemap.xml';

        $sitemap->writeToFile($path);

        $this->info('Sitemap successfully generated at ' . public_path('sitemap.xml'));
    }

    protected function addUrl(Sitemap $sitemap, string $url, string $changefreq, float $priority, $lastMod = null)
    {
        $urlTag = Url::create($url)
            ->setChangeFrequency($changefreq)
            ->setPriority($priority);

        if ($lastMod) {
            $urlTag->setLastModificationDate($lastMod);
        }

        $sitemap->add($urlTag);
    }
}
