@extends($activeTemplate . '.layouts.app')

@section('meta')
@php
    $metaTitle = $blogPost->translations->first()->title . ' | ' . siteName();
    $metaDescription = $blogPost->translations->first()->description;
    $ogTitle = $metaTitle;
    $ogDescription = $metaDescription;
    $ogImage = asset($blogPost->image);
    $ogUrl = url()->current();
    $ogType = "article";
    $twitterTitle = $metaTitle;
    $twitterDescription = $metaDescription;
    $twitterImage = $ogImage;
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    <header class="blog">
        <div class="blog-overlay"></div>
        <h1 class="blog-title">{{ __('blog.blog_title') }}</h1>
    </header>

    <article class="articles">
        <div class="container">
            <div class="row top-article">
                <div class="col-12">
                    <div class="blog-card">
                        <img src="{{ asset($blogPost->image) }}" class="img-fluid" alt="IPTV Guide" />
                        <div class="card-body">
                            <time class="meta mb-0"
                                datetime="{{ $blogPost->created_at->toIso8601String() }}">{{ $blogPost->created_at->format('F d, Y') }}</time>
                            <h5 class="card-title">
                                {{ $blogPost->translations->first()->title }}
                            </h5>
                            <p class="card-text">
                                {!! $blogPost->translations->first()->content !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </article>
@endsection
