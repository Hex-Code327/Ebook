<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>@yield('title') | E-Book System</title>
    
    <!-- Sneat CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <!-- Navbar -->
        @include('layouts.partials.navbar')
        
        <!-- Content wrapper -->
        <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
                @yield('content')
            </div>
            <!-- / Content -->
            
            <!-- Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Sneat JS -->
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    @stack('scripts')
</body>
</html>