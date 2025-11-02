<nav class="navbar navbar-expand-lg py-lg-4 z-3" id="navbar">
    <div class="container d-flex justify-content-between flex-wrap position-relative">
        <a class="navbar-brand d-flex align-items-center gap-2 text-white" href="{{ route('home') }}">
            <img src="{{ url(siteLogo()) }}" alt="Logo" />
            
            <span class="sec-title pt-3">
                {{ $siteParts[0] }}@if (isset($siteParts[1]))
                    <span>{{ $siteParts[1] }}</span>
                @endif
            </span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="social-share d-lg-flex d-none align-items-center position-absolute top-0 end-0">
            <a href="#about-us" class="social-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" preserveAspectRatio="xMidYMid meet"
                    fill="none">
                    <path
                        d="M20.008.421C9.302.421.621 9.102.621 19.81c0 10.706 8.681 19.388 19.387 19.388s19.39-8.682 19.39-19.388S30.713.42 20.007.42m4.036 30.048q-1.496.59-2.388.9a6.3 6.3 0 0 1-2.071.31q-1.813 0-2.818-.885-1.004-.883-1.003-2.244 0-.53.074-1.081.09-.63.241-1.246l1.249-4.412q.166-.638.28-1.2a5.3 5.3 0 0 0 .112-1.039q0-.843-.348-1.177c-.234-.221-.676-.33-1.334-.33q-.483-.001-.993.148c-.336.104-.629.197-.868.29l.33-1.36q1.226-.5 2.347-.855a7 7 0 0 1 2.117-.358q1.8.002 2.777.87.974.87.975 2.258 0 .288-.067 1.013-.058.678-.25 1.331l-1.242 4.399q-.168.597-.274 1.208a6.4 6.4 0 0 0-.12 1.027q0 .878.392 1.195.391.319 1.358.319.453 0 1.027-.16.57-.158.83-.279zm-.22-17.854a2.97 2.97 0 0 1-2.092.807c-.814 0-1.517-.269-2.101-.807a2.58 2.58 0 0 1-.875-1.958c0-.764.295-1.42.875-1.963a2.97 2.97 0 0 1 2.1-.816q1.226-.002 2.093.816.87.816.87 1.963 0 1.15-.87 1.958" />
                </svg>
            </a>
            <a href="{{ route('blog.index') }}" class="social-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" preserveAspectRatio="xMidYMid meet">
                    <path
                        d="M38.932 24.616c-.013-2.883-.022-4.967-1.457-6.8-1.626-2.075-4.133-2.427-6.165-2.486.342-1.45.441-3.058.283-4.795-.044-5.787-4.4-9.946-10.446-10.111L21.06.42 11.812.43h-.1c-3.597 0-6.44 1.06-8.45 3.153C2.017 4.877.364 7.138.189 10.795L.182 25.54c-.018.278.01.56.006.848 0 4.195.68 7.215 2.8 9.447s5.255 3.362 9.315 3.362l.265-.001H26.73c3.622-.03 6.683-1.212 8.852-3.418 2.165-2.201 3.33-5.338 3.37-9.07 0 0-.018-1.502-.02-2.092m-26.37-16.33 7.901-.01c1.863.03 3.349 1.678 3.318 3.519-.029 1.822-1.533 3.152-3.37 3.152l-7.956.003a3.4 3.4 0 0 1-2.369-1.013 3.33 3.33 0 0 1-.949-2.372c.03-1.84 1.56-3.297 3.425-3.28m14.852 21.937H11.61a3.4 3.4 0 0 1-2.385-.977 3.31 3.31 0 0 1-.987-2.356c0-.884.355-1.732.987-2.357a3.4 3.4 0 0 1 2.385-.976h15.803c.894 0 1.752.35 2.385.976a3.3 3.3 0 0 1 0 4.714 3.405 3.405 0 0 1-2.385.976" />
                </svg>
            </a>

            <a href="https://wa.me/{{ whatsapp() }}?text={{ urlencode('Hello, I need assistance') }}" class="social-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" preserveAspectRatio="xMidYMid meet">
                    <path
                        d="m.73 39.197 2.674-9.957a19.46 19.46 0 0 1-2.515-9.607C.894 9.041 9.349.421 19.736.421c5.041.002 9.773 2.004 13.332 5.636 3.558 3.632 5.517 8.46 5.515 13.594-.005 10.594-8.46 19.214-18.847 19.214a18.6 18.6 0 0 1-9.014-2.34zm10.455-6.15c2.656 1.607 5.192 2.57 8.545 2.572 8.633 0 15.666-7.164 15.671-15.971.003-8.825-6.997-15.98-15.659-15.982-8.64 0-15.668 7.163-15.67 15.969-.003 3.595 1.03 6.286 2.766 9.103l-1.583 5.894zm18.045-8.828c-.117-.2-.43-.32-.903-.561-.47-.24-2.786-1.402-3.219-1.562-.43-.16-.745-.241-1.06.24-.314.48-1.217 1.563-1.491 1.883s-.55.36-1.02.12c-.471-.242-1.99-.747-3.788-2.384-1.4-1.273-2.346-2.845-2.62-3.327-.274-.48-.028-.74.206-.979.213-.215.471-.56.707-.841.24-.278.317-.479.475-.8.157-.32.08-.601-.04-.842-.118-.24-1.06-2.603-1.45-3.564-.384-.936-.772-.81-1.06-.824l-.904-.016c-.314 0-.824.12-1.255.6-.431.482-1.648 1.642-1.648 4.006s1.687 4.647 1.922 4.966c.236.32 3.32 5.17 8.044 7.25a27 27 0 0 0 2.684 1.011c1.129.366 2.156.314 2.967.191.905-.137 2.786-1.162 3.179-2.283.393-1.123.393-2.084.274-2.285" />
                </svg>
            </a>
            @auth('web')
                <div class="dropdown ms-4">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::guard('web')->user()->full_name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('customer.payments') }}">{{ __('Payments') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('customer.plans') }}">{{ __('Plans') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('customer.tickets') }}">{{ __('Tickets') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ __('Logout') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <div class="get-started ms-4">
                    <a href="#pricing">{{ __('buttons.get_started') }}</a>
                </div>
                <div class="ms-2">
                    <a href="{{ route('customer.login') }}" class="btn btn-sm btn-outline-light">{{ __('Login') }}</a>
                </div>
            @endauth
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center position-absolute">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">{{ __('general.home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#social">{{ __('general.contact') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#pricing">{{ __('general.pricing') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about-us">{{ __('general.about') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#faqs">{{ __('general.faq') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog.index') }}">{{ __('general.blog') }}</a>
                </li>

                <div class="social-share d-flex justify-content-center align-items-center flex-column d-lg-none">
                    @auth('web')
                        <div class="mb-3 w-100 text-center">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary mb-2 w-100">{{ __('Dashboard') }}</a>
                            <form method="POST" action="{{ route('customer.logout') }}" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100">{{ __('Logout') }}</button>
                            </form>
                        </div>
                    @else
                        <a href="#pricing" class="get-started my-4">{{ __('buttons.get_started') }}</a>
                        <a href="{{ route('customer.login') }}" class="btn btn-outline-primary mb-3">{{ __('Login') }}</a>
                    @endauth
                    <div class="d-flex align-items-center gap-4">
                        <a href="#" class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                                preserveAspectRatio="xMidYMid meet">
                                <path
                                    d="m20.311 29.57 8.826-2.256 3.688 11.462zm20.312-14.815H25.086L20.311 0l-4.775 14.755H0L12.574 23.9 7.8 38.655l12.574-9.145 7.737-5.61z" />
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                                preserveAspectRatio="xMidYMid meet" fill="none">
                                <path
                                    d="M20.008.421C9.302.421.621 9.102.621 19.81c0 10.706 8.681 19.388 19.387 19.388s19.39-8.682 19.39-19.388S30.713.42 20.007.42m4.036 30.048q-1.496.59-2.388.9a6.3 6.3 0 0 1-2.071.31q-1.813 0-2.818-.885-1.004-.883-1.003-2.244 0-.53.074-1.081.09-.63.241-1.246l1.249-4.412q.166-.638.28-1.2a5.3 5.3 0 0 0 .112-1.039q0-.843-.348-1.177c-.234-.221-.676-.33-1.334-.33q-.483-.001-.993.148c-.336.104-.629.197-.868.29l.33-1.36q1.226-.5 2.347-.855a7 7 0 0 1 2.117-.358q1.8.002 2.777.87.974.87.975 2.258 0 .288-.067 1.013-.058.678-.25 1.331l-1.242 4.399q-.168.597-.274 1.208a6.4 6.4 0 0 0-.12 1.027q0 .878.392 1.195.391.319 1.358.319.453 0 1.027-.16.57-.158.83-.279zm-.22-17.854a2.97 2.97 0 0 1-2.092.807c-.814 0-1.517-.269-2.101-.807a2.58 2.58 0 0 1-.875-1.958c0-.764.295-1.42.875-1.963a2.97 2.97 0 0 1 2.1-.816q1.226-.002 2.093.816.87.816.87 1.963 0 1.15-.87 1.958" />
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                                preserveAspectRatio="xMidYMid meet">
                                <path
                                    d="M38.932 24.616c-.013-2.883-.022-4.967-1.457-6.8-1.626-2.075-4.133-2.427-6.165-2.486.342-1.45.441-3.058.283-4.795-.044-5.787-4.4-9.946-10.446-10.111L21.06.42 11.812.43h-.1c-3.597 0-6.44 1.06-8.45 3.153C2.017 4.877.364 7.138.189 10.795L.182 25.54c-.018.278.01.56.006.848 0 4.195.68 7.215 2.8 9.447s5.255 3.362 9.315 3.362l.265-.001H26.73c3.622-.03 6.683-1.212 8.852-3.418 2.165-2.201 3.33-5.338 3.37-9.07 0 0-.018-1.502-.02-2.092m-26.37-16.33 7.901-.01c1.863.03 3.349 1.678 3.318 3.519-.029 1.822-1.533 3.152-3.37 3.152l-7.956.003a3.4 3.4 0 0 1-2.369-1.013 3.33 3.33 0 0 1-.949-2.372c.03-1.84 1.56-3.297 3.425-3.28m14.852 21.937H11.61a3.4 3.4 0 0 1-2.385-.977 3.31 3.31 0 0 1-.987-2.356c0-.884.355-1.732.987-2.357a3.4 3.4 0 0 1 2.385-.976h15.803c.894 0 1.752.35 2.385.976a3.3 3.3 0 0 1 0 4.714 3.405 3.405 0 0 1-2.385.976" />
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="none">
                                <path fill-rule="evenodd"
                                    d="M39.73 19.81c0 10.707-8.681 19.387-19.389 19.387S.953 30.517.953 19.81C.953 9.102 9.633.421 20.341.421S39.73 9.101 39.73 19.81m-18.694-5.075q-2.829 1.176-11.307 4.87-1.376.548-1.442 1.07c-.074.59.665.823 1.671 1.14q.206.064.424.134c.99.322 2.322.699 3.014.714q.942.02 2.102-.777 7.921-5.347 8.178-5.405c.121-.028.288-.062.402.039.113.1.102.291.09.342-.073.313-2.974 3.009-4.475 4.404-.468.435-.8.744-.867.814-.152.158-.307.308-.456.451-.92.887-1.61 1.552.038 2.637.792.522 1.425.953 2.057 1.384.69.47 1.38.939 2.27 1.523.227.148.444.303.655.453.803.573 1.525 1.088 2.416 1.006.518-.048 1.053-.535 1.325-1.988.642-3.434 1.905-10.873 2.197-13.94a3.4 3.4 0 0 0-.033-.762c-.025-.151-.08-.366-.276-.525-.232-.189-.59-.229-.75-.226-.73.013-1.849.402-7.233 2.642"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                                preserveAspectRatio="xMidYMid meet">
                                <path
                                    d="m.73 39.197 2.674-9.957a19.46 19.46 0 0 1-2.515-9.607C.894 9.041 9.349.421 19.736.421c5.041.002 9.773 2.004 13.332 5.636 3.558 3.632 5.517 8.46 5.515 13.594-.005 10.594-8.46 19.214-18.847 19.214a18.6 18.6 0 0 1-9.014-2.34zm10.455-6.15c2.656 1.607 5.192 2.57 8.545 2.572 8.633 0 15.666-7.164 15.671-15.971.003-8.825-6.997-15.98-15.659-15.982-8.64 0-15.668 7.163-15.67 15.969-.003 3.595 1.03 6.286 2.766 9.103l-1.583 5.894zm18.045-8.828c-.117-.2-.43-.32-.903-.561-.47-.24-2.786-1.402-3.219-1.562-.43-.16-.745-.241-1.06.24-.314.48-1.217 1.563-1.491 1.883s-.55.36-1.02.12c-.471-.242-1.99-.747-3.788-2.384-1.4-1.273-2.346-2.845-2.62-3.327-.274-.48-.028-.74.206-.979.213-.215.471-.56.707-.841.24-.278.317-.479.475-.8.157-.32.08-.601-.04-.842-.118-.24-1.06-2.603-1.45-3.564-.384-.936-.772-.81-1.06-.824l-.904-.016c-.314 0-.824.12-1.255.6-.431.482-1.648 1.642-1.648 4.006s1.687 4.647 1.922 4.966c.236.32 3.32 5.17 8.044 7.25a27 27 0 0 0 2.684 1.011c1.129.366 2.156.314 2.967.191.905-.137 2.786-1.162 3.179-2.283.393-1.123.393-2.084.274-2.285" />
                            </svg>
                        </a>
                    </div>
                </div>
            </ul>
        </div>
    </div>
</nav>
