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

    </div>
    <script src="{{ asset('assets/dist/js/tabler.min.js') }}" defer></script>
    @yield('scripts')
</body>

</html>
