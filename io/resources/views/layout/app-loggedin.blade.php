<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="@yield('page_description', $page_description)">
    <title>@yield('page_title', $page_title)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="/io/public/assets/plugins/morris/morris.css">

    <!-- DataTables -->
    <link href="/io/public/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/io/public/assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="/io/public/assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Multi Item Selection examples -->
    <link href="/io/public/assets/plugins/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="/io/public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/io/public/assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="/io/public/assets/css/style.css" rel="stylesheet" type="text/css"/>

    <script src="/io/public/assets/js/modernizr.min.js"></script>

</head>

<body>

<!-- Navigation Bar-->
<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">

            <div class="container">
                <div class="row">
                    <div class="col-12 text-center m-2">
                        <img src="http://wholesalelingerie.co.uk/pub/media/logo/default/4_trans.png" alt="" height="60"
                             class="logo-small">
                        <img src="http://wholesalelingerie.co.uk/pub/media/logo/default/4_trans.png" alt="" height="40"
                             class="logo-large">
                    </div>
                </div>
            </div>

{{--            <div class="container">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-12 text-center">--}}

{{--            <!-- Logo container-->--}}
{{--            <div class="logo" class="text-center">--}}
{{--                <!-- Image Logo -->--}}

{{--                <a href="index.html" class="logo">--}}
{{--                    <img src="http://wholesalelingerie.co.uk/pub/media/logo/default/4_trans.png" alt="" height="60"--}}
{{--                         class="logo-small">--}}
{{--                    <img src="http://wholesalelingerie.co.uk/pub/media/logo/default/4_trans.png" alt="" height="40"--}}
{{--                         class="logo-large">--}}
{{--                </a>--}}
{{--            </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <!-- End Logo container-->
            <div class="clearfix"></div>

        </div> <!-- end container -->
    </div>
    <!-- end topbar-main -->

    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation" class="text-center">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                    <li class="has-submenu">
                        <a href="/io/public"><i class="mdi mdi-view-dashboard"></i> <span> API Services </span> </a>
                    </li>
                    <li class="has-submenu">
                        <a href="/io/public/sites"><i class="mdi mdi-view-dashboard"></i>
                            <span> Site Configurations </span> </a>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-chart-donut-variant"></i><span> Products </span> </a>
                        <ul class="submenu">
                            <li><a href="/io/public/data/parent_product">Parent Products</a></li>
                            <li><a href="/io/public/data/product">Products</a></li>
                            <li><a href="/io/public/data/image">Images</a></li>
                            <li><a href="/io/public/data/stock_levels">Stock Levels</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-chart-donut-variant"></i><span> Orders </span> </a>
                        <ul class="submenu">
                            <li><a href="/io/public/data/order">Orders</a></li>
                            <li><a href="/io/public/data/customer">Customers</a></li>
                            <li><a href="/io/public/data/order_address">Order Addresses</a></li>
                            <li><a href="/io/public/data/order_grid">Order Grids</a></li>
                            <li><a href="/io/public/data/order_history">Order History</a></li>
                            <li><a href="/io/public/data/order_item">Order Items</a></li>
                            <li><a href="/io/public/data/order_payment">Order Payments</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-chart-donut-variant"></i><span> Companies / Customers </span> </a>
                        <ul class="submenu">
{{--                            <li><a href="/io/public/data/default_billing_address">Default Billing Addresses</a></li>--}}
                            <li><a href="/io/public/data/company">Companies</a></li>
                            <li><a href="/io/public/data/company_address">Company Addresses</a></li>
                            <li><a href="/io/public/data/company_address_contact">Company Address Contacts</a></li>
                            <li><a href="/io/public/data/company_class">Company Classes</a></li>
                            <li><a href="/io/public/data/company_list">Company Lists</a></li>
                            <li><a href="/io/public/data/company_status">Company Statuses</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-chart-donut-variant"></i><span> Misc </span> </a>
                        <ul class="submenu">
                            <li><a href="/io/public/stock_code">Stock Code</a></li>
                            <li><a href="/io/public/data/keycodes">Keycodes</a></li>
                            <li><a href="/io/public/data/keycode_types">Keycode Types</a></li>
                            <li><a href="/io/public/data/mailing_status">Mailing Status</a></li>
                            <li><a href="/io/public/data/manufacturer">Manufacturers</a></li>
                            <li><a href="/io/public/data/sale_source">Sale Sources</a></li>
                            <li><a href="/io/public/data/country_list">Country List</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- End navigation menu -->
            </div> <!-- end #navigation -->
        </div> <!-- end container -->
    </div> <!-- end navbar-custom -->
</header>
<!-- End Navigation Bar-->


<div class="wrapper">
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12">
                <br/><br/>
                <div class="card-box">
                    @yield('content')
                </div>
            </div><!-- end col -->

        </div>
        <!-- end row -->

    </div> <!-- end container -->
</div>
<!-- end wrapper -->


<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                {{date("Y")}} © Devversity Ltd, Garveys IT Ltd
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->

<!-- jQuery  -->
<script src="/io/public/assets/js/jquery.min.js"></script>
<script src="/io/public/assets/js/popper.min.js"></script>
<script src="/io/public/assets/js/bootstrap.min.js"></script>
<script src="/io/public/assets/js/waves.js"></script>
<script src="/io/public/assets/js/jquery.slimscroll.js"></script>

<!-- Required datatable js -->
<script src="/io/public/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/io/public/assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="/io/public/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="/io/public/assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="/io/public/assets/plugins/datatables/jszip.min.js"></script>
<script src="/io/public/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="/io/public/assets/plugins/datatables/vfs_fonts.js"></script>
<script src="/io/public/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="/io/public/assets/plugins/datatables/buttons.print.min.js"></script>

<!-- Key Tables -->
<script src="/io/public/assets/plugins/datatables/dataTables.keyTable.min.js"></script>

<!-- Responsive examples -->
<script src="/io/public/assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/io/public/assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

<!-- Selection table -->
<script src="/io/public/assets/plugins/datatables/dataTables.select.min.js"></script>

<!-- App js -->
<script src="/io/public/assets/js/jquery.core.js"></script>
<script src="/io/public/assets/js/jquery.app.js"></script>

@stack('script-footer')

</body>
</html>
