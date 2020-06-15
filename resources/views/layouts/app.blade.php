<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/images/logos/logo.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('styles')

    {{-- plugins for all --}}
    <link href="{{ asset('/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- App css -->
    <link href="{{ asset('/assets/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />

</head>

<body>

     {{-- MODALS SECTION --}}
    @yield('modals')

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <div class="navbar-custom">
            <ul class="list-unstyled topnav-menu float-right mb-0">

    			{{-- notification card --}}
                <x-notification-card></x-notification-card>
				
				{{-- user card --}}
                <x-user-card></x-user-card>

            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="index.html" class="logo text-center">
                    <span class="logo-lg">
                        {{-- <img src="assets/images/logo-dark.png" alt="" height="16"> --}}
                        <span class="h3 text-primary">SIMOS</span>
                        <!-- <span class="logo-lg-text-light">Xeria</span> -->
                    </span>
                    <span class="logo-sm">
                        <!-- <span class="logo-sm-text-dark">X</span> -->
                        <img src="{{ asset('/images/logos/logo.png') }}" alt="" height="24">
                    </span>
                </a>
            </div>
			
			{{-- MENU BAR AND TITLE --}}
            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile disable-btn waves-effect">
                        <i class="fe-menu"></i>
                    </button>
                </li>
                <li>
                    <h4 class="page-title-main text-secondary">@yield('title')</h4>
                </li>
    
            </ul>
        </div>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">

            <div class="slimscroll-menu">

                <!-- User box -->
                <x-user-box></x-user-box>

                <!--- Sidemenu -->
                <x-sidebar></x-sidebar>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container">
					
					{{-- CONTENT --}}
                    @yield('content')
                    
                </div> <!-- container-fluid -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                           2018 - 2020 &copy; SIMOS by <a href="#">Team Vicious</a> 
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="javascript:void(0);">About Us</a>
                                <a href="javascript:void(0);">Help</a>
                                <a href="javascript:void(0);">Contact Us</a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <!-- END wrapper -->

    <!-- Right Sidebar -->
 	
    <!-- /Right-bar -->

    <!-- Right bar overlay-->

    {{-- JS FRAMEWORKS --}}
    <script src="{{ asset('/assets/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('/js/axios/dist/axios.js') }}"></script>

    <script src="{{ asset('/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>

     <!-- Vendor js -->
    <script src="{{ asset('/assets/js/vendor.min.js') }}"></script>
    
    {{-- @yield('dashboard-scripts') --}}

    <!-- App js -->
    <script src="{{ asset('/assets/js/app.min.js') }}"></script>

    {{-- PLUGINS JS --}}
    <script src="{{ asset('/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script src="{{ asset('/js/custom.js') }}"></script>
    <script src="{{ asset('/js/custom-loading.js') }}"></script>

    {{-- SCRIPTS --}}
    @yield('scripts')
</body>
</html>