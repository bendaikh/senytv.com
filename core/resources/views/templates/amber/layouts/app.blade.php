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
