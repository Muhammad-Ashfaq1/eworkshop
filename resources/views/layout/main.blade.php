<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
    <meta charset="utf-8" />
    <title>LWMC Workshop - @yield('title', 'LWMC Workshop')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="LWMC Workshop Management System" name="description" />
    <meta content="LWMC IT Department" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <!-- jsvectormap css -->
    <link href="{{ asset('assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--Swiper slider css-->
    <link href="{{  asset('assets/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" />
    <!--Swiper slider css-->
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/toaster.css') }}" /> --}}
    <!--datatable responsive css-->
    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.min.css') }}" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{asset('assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Sidebar Custom CSS -->
    <link href="{{ asset('assets/css/sidebar-custom.css')}}" rel="stylesheet" type="text/css" />
    <!-- Admin Panel Inspired Clean Styles (PRIORITY LOADING) -->
    <link href="{{ asset('assets/css/admin-panel-style.css')}}" rel="stylesheet" type="text/css" />
    <!-- Admin Panel DataTable Styles -->
    <link href="{{ asset('assets/css/admin-datatable-style.css')}}" rel="stylesheet" type="text/css" />
    <!-- DataTable Fixed Controls CSS -->
    <link href="{{ asset('assets/css/datatable-fixed-controls.css')}}" rel="stylesheet" type="text/css" />
    <!-- Badge Improvements CSS -->
    <link href="{{ asset('assets/css/badge-improvements.css')}}" rel="stylesheet" type="text/css" />
    <!-- DataTable Sorting Icons CSS V2 -->
    <link href="{{ asset('assets/css/datatable-sorting-icons-v2.css')}}" rel="stylesheet" type="text/css" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" >
    <!-- Google Fonts for Inter font family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Essential styles that need to be inline */
        .error {
            color: #dc3545 !important;
            font-size: 0.875em !important;
        }
        
        input.error, select.error {
            border-color: #dc3545 !important;
        }

        /* Select2 Modal Fixes - Essential for functionality */
        .select2-container--open {
            z-index: 9999 !important;
        }

        .modal .select2-container {
            z-index: 9999 !important;
        }

        .select2-dropdown {
            z-index: 9999 !important;
        }

        .select2-container {
            width: 100% !important;
        }

        /* Essential table responsive behavior - simplified */
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
            width: 100% !important;
            -webkit-overflow-scrolling: touch !important;
        }

        .table-nowrap {
            white-space: nowrap !important;
        }

        /* Essential DataTables functionality */
        .dataTables_wrapper {
            overflow: visible !important;
            width: 100% !important;
        }

        .dataTables_scrollBody {
            overflow: visible !important;
        }

        /* Keep column min-widths functional */
        .table th[style*="min-width"], 
        .table td[style*="min-width"] {
            white-space: nowrap !important;
        }
    </style>
    @yield('styles')
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('layout.header')

        <!-- ========== App Menu ========== -->
        @include('layout.sidebar')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

           @include('layout.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->


    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!--Swiper slider js-->
    <script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Dashboard init -->
    <script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <!-- Toastr JavaScript (requires jQuery to be loaded first) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

    <!-- Sweet Alerts js -->
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <!-- Sweet alert init js-->
        <script src="{{ asset('assets/js/pages/sweetalerts.init.js') }}"></script>
        <!-- Custom Sweet Alerts functions -->
        <script src="{{ asset('assets/js/custom/sweet-alerts.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

    <!-- Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('assets/admin/js/custom.js') }}"></script>
    
    <!-- DataTable Utilities -->
    <script src="{{ asset('assets/js/datatable-utils.js') }}"></script>
    
    <!-- Badge System -->
    <script src="{{ asset('assets/js/badge-system.js') }}"></script>
    
    <!-- DataTable Sorting Fix -->
    <script src="{{ asset('assets/js/datatable-sorting-fix.js') }}"></script>

    <!-- Common DataTable Responsive Configuration -->

    @yield('scripts')
</body>
</html>
