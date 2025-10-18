<!-- General Meta Tags -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="language" content="{{ app()->getLocale() }}">
<meta name="rating" content="general">
<meta name="distribution" content="global">

<!-- Page-Specific Meta Tags -->
<title>{{ __('seo.title') }}</title>
<meta name="description" content="{{ $metaDescription ?? __('seo.description') }}">

<!-- Open Graph / Facebook -->
<meta property="og:title" content="{{ $ogTitle ?? ($metaTitle ?? siteName()) }}">
<meta property="og:description" content="{{ $ogDescription ?? __('seo.description') }}">
<meta property="og:image" content="{{ $ogImage ?? url(siteLogo()) }}">
<meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
<meta property="og:type" content="{{ $ogType ?? 'website' }}">

<!-- X Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $twitterTitle ?? __('seo.twitter_title') }}">
<meta name="twitter:description" content="{{ $twitterDescription ?? __('seo.description') }}">
<meta name="twitter:image" content="{{ $twitterImage ?? url(siteLogo()) }}">

<!-- Canonical URL -->
<link rel="canonical" href="{{ $canonical ?? url()->current() }}">

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ url(siteFav()) }}">
<link rel="apple-touch-icon" href="{{ url(siteFav()) }}">

@php
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => isset($blogPost) ? 'Article' : 'WebPage',
        'name' => $ogTitle ?? ($metaTitle ?? siteName()),
        'description' => $ogDescription ?? ($metaDescription ?? __('seo.description')),
        'url' => $ogUrl ?? url()->current(),
        'image' => $ogImage ?? url(siteLogo()),
        'publisher' => [
            '@type' => 'Organization',
            'name' => siteName(),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => url(siteLogo()),
            ],
        ],
    ];

    if (isset($blogPost)) {
        $structuredData['author'] = [
            '@type' => 'Person',
            'name' => 'Admin',
        ];
        $structuredData['datePublished'] =
            $publishedAt ?? $blogPost->translations->first()->created_at->toIso8601String();
        $structuredData['dateModified'] = $updatedAt ?? $blogPost->translations->first()->updated_at->toIso8601String();
    }
@endphp

<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
