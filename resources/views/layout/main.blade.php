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
    <!-- Global UI Styles -->
    <link href="{{ asset('assets/css/global-ui-styles.css')}}" rel="stylesheet" type="text/css" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" >

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .error {
            color: #dc3545;
            font-size: 0.875em;
            }
            input.error, select.error {
            border-color: #dc3545;
        }
        
        /* Select2 Modal Fixes */
        .select2-container--open {
            z-index: 9999;
        }
        
        .modal .select2-container {
            z-index: 9999;
        }
        
        .select2-dropdown {
            z-index: 9999;
        }
        
        /* Ensure Select2 width matches parent */
        .select2-container {
            width: 100% !important;
        }

        /* Enhanced Responsive Table Styles */
        .table-responsive {
            overflow-x: auto;
            overflow-y: visible;
            width: 100%;
        }
        
        .table-nowrap {
            white-space: nowrap;
        }
        
        .table-responsive .table {
            margin-bottom: 0;
        }
        
        /* DataTables responsive styling */
        .dataTables_wrapper .dataTables_scroll {
            overflow: auto;
        }
        
        .dataTables_wrapper .dataTables_scrollBody {
            overflow-x: auto;
            overflow-y: visible;
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .table th, .table td {
                padding: 0.5rem 0.25rem;
            }
        }
        
        /* Ensure proper column min-widths are respected */
        .table th[style*="min-width"], .table td[style*="min-width"] {
            white-space: nowrap;
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


    @yield('scripts')
</body>
</html>
