<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('page_title', 'Admin Dashboard')</title>
    <meta name="description" content="@yield('page_description', '')">
    <!-- plugins:css -->
    <link rel="stylesheet" href="/public/assets/node_modules/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/public/assets/node_modules/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="/public/assets/node_modules/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="/public/assets/node_modules/perfect-scrollbar/css/perfect-scrollbar.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="/public/assets/images/favicon.png" />
</head>

<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
        @yield('content')
    </div>

</div>

@push('script-footer')
    <!-- plugins:js -->
    <script src="/public/assets/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="/public/assets/node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="/public/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/public/assets/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="/public/assets/js/off-canvas.js"></script>
    <script src="/public/assets/js/hoverable-collapse.js"></script>
    <script src="/public/assets/js/misc.js"></script>
    <!-- endinject -->
@endpush

@stack('script-footer')

</body>
</html>