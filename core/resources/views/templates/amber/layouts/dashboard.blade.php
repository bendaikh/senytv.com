<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include($activeTemplate . '.partials.meta')

    <!-- Preload Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300..900&family=Poppins:wght@400;500&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'">

    <link rel="stylesheet" href="{{ asset('assets/' . $activeTemplatePath . '/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/' . $activeTemplatePath . '/css/style.min.css') }}" media="print"
        onload="this.media='all'">
    @php
        $primaryColor = setting('primary_color', '#ffbf23');
    @endphp
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
        }
        body {
            background: #f8f9fa;
        }
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }
        .dashboard-sidebar {
            width: 280px;
            background: #fff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        .dashboard-logo {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            background: var(--primary-color);
        }
        .dashboard-logo a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: #fff;
        }
        .dashboard-logo img {
            max-height: 40px;
        }
        .dashboard-nav {
            padding: 1rem 0;
        }
        .dashboard-nav-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .dashboard-nav-item:hover,
        .dashboard-nav-item.active {
            background: #f8f9fa;
            border-left-color: var(--primary-color);
            color: var(--primary-color);
        }
        .dashboard-nav-item svg {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
            vertical-align: middle;
        }
        .dashboard-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
        }
        .dashboard-header {
            background: #fff;
            padding: 1.5rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 999;
            transition: transform 0.3s;
        }
        .whatsapp-float:hover {
            transform: scale(1.1);
        }
        .whatsapp-float svg {
            width: 32px;
            height: 32px;
        }
        @media (max-width: 768px) {
            .dashboard-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .dashboard-sidebar.open {
                transform: translateX(0);
            }
            .dashboard-content {
                margin-left: 0;
            }
            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background: var(--primary-color);
                color: #fff;
                border: none;
                padding: 0.5rem 1rem;
                border-radius: 5px;
            }
        }
        .mobile-menu-btn {
            display: none;
        }
    </style>
</head>

<body>
    <button class="mobile-menu-btn" onclick="document.querySelector('.dashboard-sidebar').classList.toggle('open')">
        ☰ Menu
    </button>

    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="dashboard-logo">
                <a href="{{ route('home') }}">
                    <img src="{{ url(siteLogo()) }}" alt="Logo" />
                    <span class="sec-title">
                        @php($siteParts = splitSiteName())
                        {{ $siteParts[0] }}@if (isset($siteParts[1]))
                            <span>{{ $siteParts[1] }}</span>
                        @endif
                    </span>
                </a>
            </div>
            
            <nav class="dashboard-nav">
                <a href="{{ route('customer.dashboard') }}" class="dashboard-nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('customer.payments') }}" class="dashboard-nav-item {{ request()->routeIs('customer.payments') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('Payments') }}
                </a>
                <a href="{{ route('customer.plans') }}" class="dashboard-nav-item {{ request()->routeIs('customer.plans') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    {{ __('Plans') }}
                </a>
                <a href="{{ route('customer.tickets') }}" class="dashboard-nav-item {{ request()->routeIs('customer.tickets.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('Tickets') }}
                </a>
                <hr style="margin: 1rem 0;">
                <a href="{{ route('home') }}" class="dashboard-nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ __('Back to Home') }}
                </a>
                <form method="POST" action="{{ route('customer.logout') }}" class="dashboard-nav-item" style="border: none; padding: 0;">
                    @csrf
                    <button type="submit" style="background: none; border: none; width: 100%; text-align: left; padding: 0.75rem 1.5rem; color: #dc3545; cursor: pointer;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; width: 20px; height: 20px; margin-right: 0.75rem; vertical-align: middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('Logout') }}
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-content">
            <div class="dashboard-header">
                <h2 class="mb-0">{{ __('Welcome,') }} {{ Auth::guard('web')->user()->full_name }}</h2>
                <p class="text-muted mb-0">{{ __('Manage your account, subscriptions, and support tickets.') }}</p>
            </div>
            
            @yield('content')
        </main>
    </div>

    <!-- WhatsApp Icon -->
    <a href="https://wa.me/{{ whatsapp() }}?text={{ urlencode('Hello, I need assistance') }}" class="whatsapp-float" target="_blank">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="#fff">
            <path d="m.73 39.197 2.674-9.957a19.46 19.46 0 0 1-2.515-9.607C.894 9.041 9.349.421 19.736.421c5.041.002 9.773 2.004 13.332 5.636 3.558 3.632 5.517 8.46 5.515 13.594-.005 10.594-8.46 19.214-18.847 19.214a18.6 18.6 0 0 1-9.014-2.34zm10.455-6.15c2.656 1.607 5.192 2.57 8.545 2.572 8.633 0 15.666-7.164 15.671-15.971.003-8.825-6.997-15.98-15.659-15.982-8.64 0-15.668 7.163-15.67 15.969-.003 3.595 1.03 6.286 2.766 9.103l-1.583 5.894zm18.045-8.828c-.117-.2-.43-.32-.903-.561-.47-.24-2.786-1.402-3.219-1.562-.43-.16-.745-.241-1.06.24-.314.48-1.217 1.563-1.491 1.883s-.55.36-1.02.12c-.471-.242-1.99-.747-3.788-2.384-1.4-1.273-2.346-2.845-2.62-3.327-.274-.48-.028-.74.206-.979.213-.215.471-.56.707-.841.24-.278.317-.479.475-.8.157-.32.08-.601-.04-.842-.118-.24-1.06-2.603-1.45-3.564-.384-.936-.772-.81-1.06-.824l-.904-.016c-.314 0-.824.12-1.255.6-.431.482-1.648 1.642-1.648 4.006s1.687 4.647 1.922 4.966c.236.32 3.32 5.17 8.044 7.25a27 27 0 0 0 2.684 1.011c1.129.366 2.156.314 2.967.191.905-.137 2.786-1.162 3.179-2.283.393-1.123.393-2.084.274-2.285"/>
        </svg>
    </a>

    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>

