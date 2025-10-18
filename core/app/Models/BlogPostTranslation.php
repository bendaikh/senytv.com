<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostTranslation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['blog_post_id', 'language', 'title', 'description', 'img_alt', 'slug', 'content'];

    /**
     * Get the blog post that owns the translation.
     *
     * @return BelongsTo<BlogPost, BlogPostTranslation>
     */
    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }
}