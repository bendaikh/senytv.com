<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['image', 'status'];

    /**
     * Get all translations for the blog post.
     *
     * @return HasMany<BlogPostTranslation>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(BlogPostTranslation::class);
    }

    /**
     * Get the translation for a specific language or the application's default locale.
     *
     * @param string|null $lang
     * @return BlogPostTranslation|null
     */
    public function translation(?string $lang = null): ?BlogPostTranslation
    {
        return $this->translations
            ->where('language', $lang ?? app()->getLocale())
            ->first();
    }

    /**
     * Eager load translations for a specific language.
     *
     * @param string|null $lang
     * @return void
     */
    public function loadTranslation(?string $lang = null): void
    {
        $this->load(['translations' => function ($query) use ($lang) {
            $query->where('language', $lang ?? app()->getLocale());
        }]);
    }
}