@extends($activeTemplate . '.layouts.app')

@section('content')
    <header class="blog">
        <div class="blog-overlay"></div>
        <h1 class="blog-title">{{ __('general.privacy_title') }}</h1>
    </header>

    <section class="articles">
        <div class="container">
            <div class="row top-article">
                <div class="col-12">
                    <div class="blog-card">

                        <div class="card-body">
                            <time class="meta mb-0">{{ $privacy->privacy_policy_updated_at->format('F d, Y') }}</time>


                            {!! $privacy->privacy_policy !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
