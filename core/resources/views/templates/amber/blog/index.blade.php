@extends($activeTemplate . '.layouts.app')

@section('content')
    <header class="blog">
        <div class="blog-overlay"></div>
        <h1 class="blog-title">{{ __('blog.blog_title') }}</h1>
    </header>

    <section class="articles">
        <div class="container">
            <div class="row top-article">
                <div class="col-12">
                    <a href="{{ $lastPost->translations->first()->slug }}" class="text-black">
                        <div class="blog-card">
                            <img src="{{ asset($lastPost->image) }}" class="img-fluid" alt="IPTV Guide" />
                            <div class="card-body">
                                <time class="meta mb-0">{{ $lastPost->created_at->format('F d, Y') }}</time>
                                <h5 class="card-title">
                                    {{ $lastPost->translations->first()->title }}
                                </h5>
                                <p class="card-text">
                                    {!! Str::limit(strip_tags($lastPost->translations->first()->content), 380, '...') !!}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row description">
                <div class="col-12 col-md-6 position-relative">
                    <h1 class="sec-title text-md-start">
                        {!! __('blog.discover_best') !!}
                    </h1>
                </div>
                <div class="col-12 col-md-6">
                    <p class="mb-0">
                        {{ __('blog.blog_description') }}
                    </p>
                </div>
            </div>
            <div class="row">
                @foreach ($articles as $article)
                    <div class="col-12 col-md-6">
                        <a href="{{ route('blog.show', $lastPost->translations->first()->slug) }}" class="text-black">
                            <div class="blog-card">
                                <img src="{{ asset($article->image) }}" class="img-fluid" alt="IPTV Guide" />
                                <div class="card-body">
                                    <time class="meta mb-0">{{ $article->created_at->format('F d, Y') }}</time>
                                    <h5 class="card-title">
                                        {{ $article->translations->first()->title }}
                                    </h5>
                                    <p class="card-text">
                                        {!! Str::limit(strip_tags($article->translations->first()->content), 380, '...') !!}
                                    </p>
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach

                @include($activeTemplate . '.partials.pagination', ['paginator' => $articles])

            </div>
        </div>
    </section>
@endsection
