<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/public/light/assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('page_description', '')">
    <title>@yield('page_title', 'WarmGlow - Admin Dashboard')</title>

    <!-- Bootstrap core CSS     -->
    <link href="/public/light/assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="/public/light/assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="/public/light/assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="/public/light/assets/css/pe-icon-7-stroke.css" rel="stylesheet" />


</head>

<div class="wrapper">
    <div class="sidebar" data-color="red">
        <div class="sidebar-wrapper">
            <div class="logo">
                <a href="/" class="simple-text" style="background:white; padding:10px">
                    <img src="/public/assets/images/logo.png" alt="logo" title="logo" width="200px">
                </a>
            </div>

            <ul class="nav">
                <li class="active">
                    <a href="/">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li>
                    <a href="/client/view">
                        <i class="pe-7s-note2"></i>
                        <p>Clients</p>
                    </a>
                </li>
                <li>
                    <a href="//sales/search/2">
                        <i class="pe-7s-news-paper"></i>
                        <p>Enquiries</p>
                    </a>
                </li>
                <li>
                    <a href="/lead_sources">
                        <i class="pe-7s-map-marker"></i>
                        <p>Lead Sources</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">@yield('page_title', 'Dashboard')</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-globe"></i>
                                <span class="notification">5</span>
                                <b class="caret hidden-lg hidden-md"></b>
                                <p class="hidden-lg hidden-md">
                                    5 Notifications
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Notification 1</a></li>
                                <li><a href="#">Notification 2</a></li>
                                <li><a href="#">Notification 3</a></li>
                                <li><a href="#">Notification 4</a></li>
                                <li><a href="#">Another notification</a></li>
                            </ul>
                        </li>
                        {{--<li>--}}
                            {{--<a href="">--}}
                                {{--<i class="fa fa-search"></i>--}}
                                {{--<p class="hidden-lg hidden-md">Search</p>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <p>
                                    {{$user->first_name}} {{$user->last_name}} ({{$permission_level->permission_name}})
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/admin_tools/users">Manage Users</a></li>
                                <li><a href="/admin_tools/permissions">Manage Permissions</a></li>
                                <li class="divider"></li>
                                <li><a href="/user/details">Your Profile</a></li>
                                <li><a href="/auth/logout">Log Out</a></li>
                            </ul>
                        </li>
                        <li class="separator hidden-lg"></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script> <a href="http://www.devversity.co.uk">Devversity</a>
                </p>
            </div>
        </footer>

    </div>
</div>

<!--   Core JS Files   -->
<script src="/public/light/assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="/public/light/assets/js/bootstrap.min.js" type="text/javascript"></script>

<!--  Notifications Plugin    -->
<script src="/public/light/assets/js/bootstrap-notify.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

        //demo.initChartist();

        $.notify({
            icon: 'nc-bullet-list-67',
            message: "Hi <b>{{$user->first_name}} {{$user->last_name}}</b>, you have new notifications"

        },{
            type: 'info',
            timer: 4000
        });

    });
</script>

@stack('script-footer')
</body>

</html>