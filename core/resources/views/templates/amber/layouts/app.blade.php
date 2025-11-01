<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <style>
        #preloader {
            position: fixed;
            width: 100%;
            height: 100vh;
            background: #ffbf23;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }

        #preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
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
        // Convert hex to RGB for rgba usage
        $hex = str_replace('#', '', $primaryColor);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Generate rgba colors with different opacities
        $primaryRgba03 = "rgba($r, $g, $b, 0.3)";
        $primaryRgba05 = "rgba($r, $g, $b, 0.5)";
        $primaryRgba068 = "rgba($r, $g, $b, 0.68)";
        $primaryRgba01 = "rgba($r, $g, $b, 0.1)";
        $primaryRgba003 = "rgba($r, $g, $b, 0.03)";
        $primaryRgba086 = "rgba($r, $g, $b, 0.86)";
        $primaryRgba031 = "rgba($r, $g, $b, 0.31)";
        $primaryRgba015 = "rgba($r, $g, $b, 0.15)";
        $primaryRgba035 = "rgba($r, $g, $b, 0.35)";
        $primaryRgba073 = "rgba($r, $g, $b, 0.73)";
    @endphp
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
        }
        #preloader {
            background: {{ $primaryColor }};
        }
        
        /* Override hardcoded yellow colors */
        nav.navbar .social-share .get-started {
            border-color: var(--primary-color) !important;
            background-color: {{ $primaryRgba03 }} !important;
        }
        
        nav.navbar .social-share .get-started:hover {
            background-color: rgba(0, 0, 0, 0.3) !important;
        }
        
        @media (max-width: 992px) {
            nav.navbar .social-share .get-started {
                background-color: var(--primary-color) !important;
            }
        }
        
        .corner:hover::before {
            background-color: var(--primary-color) !important;
        }
        
        .corner:hover::after {
            background-color: {{ $primaryRgba01 }} !important;
        }
        
        /* Plans section background gradients */
        section.plans {
            background-image: 
                linear-gradient(64deg, rgba(255,255,255,0) 0, rgb(255 255 255 / 0%) 100%),
                linear-gradient(to bottom, #000 0, rgb(0 0 0 / 0%) 18%, rgb(0 0 0 / 0%)),
                linear-gradient(to bottom, {{ $primaryRgba05 }}, {{ $primaryRgba068 }}),
                linear-gradient(to bottom, {{ $primaryRgba003 }}, {{ $primaryRgba086 }}),
                linear-gradient(to bottom, {{ $primaryRgba031 }}, {{ $primaryRgba015 }}),
                linear-gradient(to bottom, var(--primary-color), var(--primary-color)),
                url(../images/plans/plans-bg.webp) !important;
        }
        
        @media (max-width: 768px) {
            section.plans {
                background-image: 
                    linear-gradient(64deg, rgba(255,255,255,0) 0, rgba(255,255,255,.3) 100%),
                    linear-gradient(to bottom, #0000001c 0, rgba(0,0,0,0) 18%, rgba(0,0,0,0)),
                    linear-gradient(to bottom, {{ $primaryRgba05 }}, {{ $primaryRgba068 }}),
                    linear-gradient(to bottom, {{ $primaryRgba035 }}, {{ $primaryRgba073 }}),
                    linear-gradient(to bottom, {{ $primaryRgba03 }}, {{ $primaryRgba03 }}),
                    linear-gradient(to bottom, var(--primary-color), var(--primary-color)),
                    url(../images/plans/plans-bg.webp) !important;
            }
        }
        
        section.plans .pricing-card .pricing-bar {
            background-color: var(--primary-color) !important;
        }
        
        section.plans .pricing-card .pricing-button:hover {
            background-color: {{ $primaryRgba03 }} !important;
        }
        
        section.our-program .program-info .btn-explore {
            background-color: var(--primary-color) !important;
        }
        
        .articles .blog-card {
            border-bottom-color: var(--primary-color) !important;
        }
        
        .articles .blog-card .card-body .meta::before {
            background-color: var(--primary-color) !important;
        }
    </style>
    @yield('styles')
    @yield('meta')
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    @if (!empty(whatsapp()))
        <a href="https://wa.me/{{ whatsapp() }}?text={{ urlencode(whatsappText()) }}"
            class="whatsapp {{ whatsappDir() }}" target="_blank">
            <img src="{{ asset('assets/' . $activeTemplatePath . '/images/icons/whatsapp.svg') }}" alt="whatsapp"
                loading="lazy">
        </a>
    @endif

    <!-- Navbar -->
    @php($siteParts = splitSiteName())
    @include($activeTemplate . '.partials.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include($activeTemplate . '.partials.footer')

    <!-- JavaScript Files -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                document.getElementById("preloader").classList.add("hidden");
            }, 500);
        });

        // Lazy load external scripts for better performance
        window.addEventListener("load", function() {
            setTimeout(() => {
                let script = document.createElement("script");
                script.src =
                    "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js";
                script.defer = true;
                document.body.appendChild(script);
            }, 300);
        });
    </script>


    @yield('scripts')
    {!! customScripts() !!}
</body>

</html>
