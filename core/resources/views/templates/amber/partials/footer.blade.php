<footer id="about-us">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-5">
                <h3 class="sec-title">About <span>us</span></h3>
                <p class="about pe-5 lh-lg pt-3">
                    {{ __('general.about_us') }}
                </p>
            </div>
            <div class="col-md-6 col-lg-4 mb-5">
                <h3 class="sec-title">Top <span>Articles</span></h3>
                @foreach ($lastThreePosts as $post)
                    <div class="col-12 last-post">
                        <a href="{{ route('blog.show', $post->translations->first()->slug) }}" class="d-flex py-3">
                            <img src="{{ asset($post->image) }}" class="img-fluid" alt="Article Image" />
                            <div class="ms-3">
                                <p class="mb-2">{{ $post->translations->first()->title }}</p>
                                <small class="d-flex align-items-center">
                                    <svg class="me-3" width="13" height="13" viewBox="0 0 13 13" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12.5 6.5C12.5 9.81371 9.81371 12.5 6.5 12.5C3.18629 12.5 0.5 9.81371 0.5 6.5C0.5 3.18629 3.18629 0.5 6.5 0.5C9.81371 0.5 12.5 3.18629 12.5 6.5Z"
                                            stroke="var(--primary-color)" />
                                        <path
                                            d="M8.92138 9.0518C8.83558 9.0518 8.74847 9.02905 8.66982 8.9816L6.71202 7.7986C6.05747 7.3579 5.76562 6.8236 5.76562 6.06765V3.571C5.76562 3.3019 5.98403 3.0835 6.25312 3.0835C6.52222 3.0835 6.74062 3.3019 6.74062 3.571V6.06765C6.74062 6.49405 6.86867 6.7261 7.24762 6.9835L9.17423 8.147C9.40497 8.2861 9.47842 8.58575 9.33932 8.8165C9.24767 8.96795 9.08648 9.0518 8.92138 9.0518Z"
                                            fill="var(--primary-color)" />
                                    </svg>

                                    {{ $post->created_at->format('F d, Y') }}
                                </small>
                            </div>
                        </a>
                    </div>
                @endforeach


            </div>
            <div class="col-md-6 col-lg-4 mb-5">
                <h3 class="sec-title">
                    PAYMENT <span>METHOD</span>
                </h3>
                <div class="row g-3 mt-3">
                    @foreach ($payment_methods as $method)
                        <div class="col-6 d-flex align-items-center p-3 payment">
                            <div class="col-auto">
                                <img src="{{ url($method->img) }}" alt="{{ $method->name }}" />
                            </div>
                            <div class="col ps-3">
                                <span>{{ $method->name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="mini-footer p-3">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-12 col-md-6">
                    <div class="d-flex flex-column flex-lg-row align-items-center">
                        <h3 class="sec-title">{{ $siteParts[0] }}@if (isset($siteParts[1]))
                                <span>{{ $siteParts[1] }}</span>
                            @endif
                        </h3>
                        <span class="copyright ms-3">
                            © {{ date('Y') }} {{ siteName() }} – All rights reserved.
                        </span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="col-md-6 d-none d-md-block">
                    <ul class="list-unstyled d-flex justify-content-end gap-3 mb-0">
                        <li>
                            <a href="{{ route('home') }}" class="text-warning text-decoration-none">Home</a>
                        </li>
                        <li>
                            <a href="/#social" class="text-white text-decoration-none">Contact us</a>
                        </li>
                        <li>
                            <a href="{{ route('terms') }}" class="text-white text-decoration-none">Terms and
                                Conditions</a>
                        </li>
                        <li>
                            <a href="{{ route('privacy') }}" class="text-white text-decoration-none">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="{{ route('refund') }}" class="text-white text-decoration-none">Refund Policy</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</footer>
