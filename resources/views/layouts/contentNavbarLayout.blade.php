<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed">
<head>
  <meta charset="UTF-8">
  <title>@yield('title') | E-Book System</title>
  <!-- Load CSS Sneat -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
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
    </div>
  </div>

  <!-- Load JS Sneat -->
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
</body>
</html>