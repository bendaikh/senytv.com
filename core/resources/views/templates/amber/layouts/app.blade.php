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
        
        /* Override SVG icon colors */
        .social-icon svg path {
            fill: var(--primary-color) !important;
        }
        
        .social-icon svg {
            color: var(--primary-color);
        }
        
        /* Social section icons - use JavaScript to change colors */
        
        /* Handle SVG backgrounds with primary color */
        [style*="header-shape"] {
            filter: hue-rotate(0deg) saturate(1);
        }
        
        /* Override any inline SVG fills that might be in the DOM */
        svg path[fill="#FFBF23"],
        svg path[fill="#ffbf23"],
        svg path[fill*="FFBF23"] {
            fill: var(--primary-color) !important;
        }
        
        svg path[stroke="#FFBF23"],
        svg path[stroke="#ffbf23"],
        svg path[stroke*="FFBF23"] {
            stroke: var(--primary-color) !important;
        }
        
        /* Additional yellow color overrides for decorative elements */
        .corner:hover::before {
            background-color: var(--primary-color) !important;
        }
        
        /* Pricing card bar (ribbon badge) */
        section.plans .pricing-card .pricing-bar {
            background-color: var(--primary-color) !important;
        }
        
        /* Scrollbar thumb */
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color) !important;
        }
        
        /* Additional rgba yellow overrides */
        section.best .our-features .parallelogram-wrapper:hover .step-content svg path {
            fill: var(--primary-color) !important;
        }
        
        /* WhatsApp button background */
        .whatsapp {
            background-color: var(--primary-color) !important;
        }
        
        /* Pagination active state */
        .pagination .active {
            background-color: var(--primary-color) !important;
        }
        
        /* Blog card border */
        .articles .blog-card {
            border-bottom-color: var(--primary-color) !important;
        }
        
        /* Channels section accordion border */
        section.channels .channels-list .accordion-button {
            border-top-color: var(--primary-color) !important;
        }
        
        /* Loader border color */
        .loader {
            border-bottom-color: var(--primary-color) !important;
        }
        
        /* Ensure header-shape and pricing-bg SVG backgrounds are handled */
        .HeroSlider::after,
        section.plans::after,
        footer::after,
        .blog::after {
            background-image: url("../images/shape/header-shape.svg") !important;
            filter: none !important;
            -webkit-mask-image: none !important;
            mask-image: none !important;
        }
        
        section.plans .pricing-card .pricing-footer {
            background-image: url("../images/shape/pricing-bg.svg") !important;
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

        // Change SVG icon colors in social section and hero section
        document.addEventListener("DOMContentLoaded", function() {
            const primaryColor = '{{ $primaryColor }}';
            
            // Function to replace yellow colors in SVG content
            function replaceSvgColors(svgText, targetColor) {
                // Replace hardcoded yellow colors with primary color
                const yellowColors = [
                    '#FFBF23', '#ffbf23', '#FFB500', '#ffb500', 
                    '#FEB500', '#feb500', '#FEBC58', '#febc58',
                    'FFBF23', 'ffbf23', 'FFB500', 'ffb500',
                    'FEB500', 'feb500', 'FEBC58', 'febc58'
                ];
                
                let modifiedSvg = svgText;
                yellowColors.forEach(yellow => {
                    // Replace fill colors
                    const fillRegex = new RegExp(`fill=["']?${yellow}["']?`, 'gi');
                    modifiedSvg = modifiedSvg.replace(fillRegex, `fill="${targetColor}"`);
                    
                    // Replace stroke colors
                    const strokeRegex = new RegExp(`stroke=["']?${yellow}["']?`, 'gi');
                    modifiedSvg = modifiedSvg.replace(strokeRegex, `stroke="${targetColor}"`);
                });
                
                return modifiedSvg;
            }
            
            // Function to convert img to inline SVG with primary color
            function convertSvgIcon(img) {
                if (img.src && (img.src.endsWith('.svg') || img.src.includes('.svg'))) {
                    fetch(img.src)
                        .then(response => response.text())
                        .then(svgText => {
                            // Replace yellow colors in SVG text before parsing
                            const modifiedSvg = replaceSvgColors(svgText, primaryColor);
                            
                            // Create a temporary container
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = modifiedSvg;
                            const svgElement = tempDiv.querySelector('svg');
                            
                            if (svgElement) {
                                // Set primary color to all paths and other elements (safety net)
                                const paths = svgElement.querySelectorAll('path, circle, rect, polygon, ellipse, line');
                                paths.forEach(element => {
                                    // Only change fill if it's a yellow color or not white
                                    const currentFill = element.getAttribute('fill');
                                    if (currentFill && 
                                        currentFill.toLowerCase() !== '#ffffff' && 
                                        currentFill.toLowerCase() !== 'white' &&
                                        currentFill.toLowerCase() !== 'none' &&
                                        currentFill.toLowerCase() !== 'transparent') {
                                        // Check if it's a yellow color
                                        const isYellow = currentFill.match(/#(ff|fe)?(bf|b5|bc)?(23|00|58)/i);
                                        if (isYellow || currentFill === 'currentColor') {
                                            element.setAttribute('fill', primaryColor);
                                            element.style.fill = primaryColor;
                                        }
                                    }
                                    // Update stroke if it's yellow
                                    const currentStroke = element.getAttribute('stroke');
                                    if (currentStroke && 
                                        currentStroke.toLowerCase() !== '#ffffff' && 
                                        currentStroke.toLowerCase() !== 'white' &&
                                        currentStroke.toLowerCase() !== 'none') {
                                        const isYellow = currentStroke.match(/#(ff|fe)?(bf|b5|bc)?(23|00|58)/i);
                                        if (isYellow) {
                                            element.setAttribute('stroke', primaryColor);
                                            element.style.stroke = primaryColor;
                                        }
                                    }
                                });
                                
                                // Set width and height from original img
                                svgElement.setAttribute('width', img.width || img.naturalWidth || '40');
                                svgElement.setAttribute('height', img.height || img.naturalHeight || '40');
                                svgElement.style.width = img.style.width || (img.width ? img.width + 'px' : '40px');
                                svgElement.style.height = img.style.height || (img.height ? img.height + 'px' : '40px');
                                svgElement.style.display = 'block';
                                svgElement.style.margin = '0 auto';
                                
                                // Copy classes from img to svg
                                if (img.className) {
                                    svgElement.className = img.className;
                                }
                                
                                // Replace img with inline SVG
                                img.parentNode.replaceChild(svgElement, img);
                            }
                        })
                        .catch(err => console.error('Error loading SVG:', err));
                }
            }
            
            // Function to handle SVG backgrounds in pseudo-elements by injecting inline SVG
            function handleSvgBackgrounds() {
                // Handle HeroSlider::after (header-shape at bottom)
                const heroSlider = document.querySelector('.HeroSlider');
                if (heroSlider) {
                    const headerShapeUrl = '{{ asset("assets/" . $activeTemplatePath . "/images/shape/header-shape.svg") }}';
                    fetch(headerShapeUrl)
                        .then(response => response.text())
                        .then(svgText => {
                            const modifiedSvg = replaceSvgColors(svgText, primaryColor);
                            const wrapper = document.createElement('div');
                            wrapper.innerHTML = modifiedSvg;
                            const svgElement = wrapper.querySelector('svg');
                            if (svgElement) {
                                svgElement.style.cssText = 'position: absolute; left: 0; bottom: -1px; width: 100%; height: clamp(150px, 25vh, 389px); z-index: 2; pointer-events: none;';
                                heroSlider.appendChild(svgElement);
                            }
                        })
                        .catch(() => {}); // Fail silently
                }
                
                // Handle section.plans::after (header-shape inverted at top)
                const plansSection = document.querySelector('section.plans');
                if (plansSection) {
                    const headerShapeUrl = '{{ asset("assets/" . $activeTemplatePath . "/images/shape/header-shape.svg") }}';
                    fetch(headerShapeUrl)
                        .then(response => response.text())
                        .then(svgText => {
                            const modifiedSvg = replaceSvgColors(svgText, primaryColor);
                            const wrapper = document.createElement('div');
                            wrapper.innerHTML = modifiedSvg;
                            const svgElement = wrapper.querySelector('svg');
                            if (svgElement) {
                                svgElement.style.cssText = 'position: absolute; left: 0; top: -1px; width: 100%; height: clamp(150px, 25vh, 389px); z-index: 2; transform: scaleY(-1); pointer-events: none;';
                                plansSection.appendChild(svgElement);
                            }
                        })
                        .catch(() => {});
                }
                
                // Handle footer::after (header-shape inverted at top)
                const footer = document.querySelector('footer');
                if (footer) {
                    const headerShapeUrl = '{{ asset("assets/" . $activeTemplatePath . "/images/shape/header-shape.svg") }}';
                    fetch(headerShapeUrl)
                        .then(response => response.text())
                        .then(svgText => {
                            const modifiedSvg = replaceSvgColors(svgText, primaryColor);
                            const wrapper = document.createElement('div');
                            wrapper.innerHTML = modifiedSvg;
                            const svgElement = wrapper.querySelector('svg');
                            if (svgElement) {
                                svgElement.style.cssText = 'position: absolute; left: 0; top: -1px; width: 100%; height: clamp(150px, 25vh, 389px); z-index: 2; transform: scaleY(-1); pointer-events: none;';
                                footer.appendChild(svgElement);
                            }
                        })
                        .catch(() => {});
                }
                
                // Handle .blog::after (header-shape at bottom)
                const blogSection = document.querySelector('.blog');
                if (blogSection) {
                    const headerShapeUrl = '{{ asset("assets/" . $activeTemplatePath . "/images/shape/header-shape.svg") }}';
                    fetch(headerShapeUrl)
                        .then(response => response.text())
                        .then(svgText => {
                            const modifiedSvg = replaceSvgColors(svgText, primaryColor);
                            const wrapper = document.createElement('div');
                            wrapper.innerHTML = modifiedSvg;
                            const svgElement = wrapper.querySelector('svg');
                            if (svgElement) {
                                svgElement.style.cssText = 'position: absolute; left: 0; bottom: -1%; width: 100%; height: 188px; z-index: 2; pointer-events: none;';
                                blogSection.appendChild(svgElement);
                            }
                        })
                        .catch(() => {});
                }
                
                // Handle pricing-card .pricing-footer (pricing-bg.svg)
                const pricingCards = document.querySelectorAll('section.plans .pricing-card .pricing-footer');
                pricingCards.forEach(card => {
                    const pricingBgUrl = '{{ asset("assets/" . $activeTemplatePath . "/images/shape/pricing-bg.svg") }}';
                    fetch(pricingBgUrl)
                        .then(response => response.text())
                        .then(svgText => {
                            const modifiedSvg = replaceSvgColors(svgText, primaryColor);
                            const wrapper = document.createElement('div');
                            wrapper.innerHTML = modifiedSvg;
                            const svgElement = wrapper.querySelector('svg');
                            if (svgElement) {
                                svgElement.style.cssText = 'position: absolute; left: 0; bottom: 0; width: 100%; height: 28px; pointer-events: none;';
                                card.appendChild(svgElement);
                            }
                        })
                        .catch(() => {});
                });
            }
            
            // Call the function to handle SVG backgrounds
            handleSvgBackgrounds();
            
            // Handle social section icons
            const socialIcons = document.querySelectorAll('section.social .share-icon img');
            socialIcons.forEach(convertSvgIcon);
            
            // Handle hero section caption icons
            const captionIcons = document.querySelectorAll('.caption-icon img');
            captionIcons.forEach(convertSvgIcon);
            
            // Handle header-shape SVG (curved separator)
            const headerShapes = document.querySelectorAll('img[src*="header-shape"], img[src*="shape/header"]');
            headerShapes.forEach(convertSvgIcon);
            
            // Handle all other SVG images
            const allSvgs = document.querySelectorAll('img[src$=".svg"]');
            allSvgs.forEach(function(img) {
                if (img.dataset.processed !== 'true') {
                    img.dataset.processed = 'true';
                    convertSvgIcon(img);
                }
            });
            
            // Handle inline SVG elements that might already be in the DOM
            const inlineSvgs = document.querySelectorAll('svg');
            inlineSvgs.forEach(function(svg) {
                const paths = svg.querySelectorAll('path, circle, rect, polygon, ellipse, line');
                paths.forEach(function(element) {
                    const fill = element.getAttribute('fill');
                    const stroke = element.getAttribute('stroke');
                    
                    if (fill && (fill.match(/#(ff|fe)?(bf|b5|bc)?(23|00|58)/i) || fill === 'currentColor')) {
                        if (fill.toLowerCase() !== '#ffffff' && fill.toLowerCase() !== 'white' && fill.toLowerCase() !== 'none') {
                            element.setAttribute('fill', primaryColor);
                        }
                    }
                    
                    if (stroke && stroke.match(/#(ff|fe)?(bf|b5|bc)?(23|00|58)/i)) {
                        if (stroke.toLowerCase() !== '#ffffff' && stroke.toLowerCase() !== 'white' && stroke.toLowerCase() !== 'none') {
                            element.setAttribute('stroke', primaryColor);
                        }
                    }
                });
            });
        });
    </script>


    @yield('scripts')
    {!! customScripts() !!}
</body>

</html>
