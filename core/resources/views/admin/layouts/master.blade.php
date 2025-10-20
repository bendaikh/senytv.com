<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.header')
    @yield('styles')
</head>

<body>
    <div class="page">
        @include('admin.partials.navbar')
        <div class="page-wrapper">

            @yield('content')

            @include('admin.partials.footer')

        </div>

        <!-- Mobile Menu Toggle Button -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16" />
                <path d="M4 12h16" />
                <path d="M4 18h16" />
            </svg>
        </button>
    </div>
    
    <script src="{{ asset('assets/dist/js/tabler.min.js') }}" defer></script>
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const navbar = document.querySelector('.navbar');
            
            if (mobileMenuToggle && navbar) {
                mobileMenuToggle.addEventListener('click', function() {
                    navbar.classList.toggle('show');
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!navbar.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                        navbar.classList.remove('show');
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
