<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{asset('assets/')}}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') - {{$general_settings['company_title']??'Taskify'}}</title>


    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset($general_settings['favicon'] ?? 'storage/logos/default_favicon.png') }}" />

    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('assets/css/google-fonts.css')}}" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{asset('assets/vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/apex-charts.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/lightbox/lightbox.min.css')}}" />
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>

    <!-- Date picker -->
    <link rel="stylesheet" href="{{asset('assets/css/daterangepicker.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}" />

    <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/dragula.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/toastr.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/dropzone.min.css')}}" rel="stylesheet" />


    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('assets/js/config.js')}}"></script>

    <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>

</head>

<body>

    <!-- Layout wrapper -->

    @if (Request::is('forgot-password') || Request::is('/') || Request::segment(1)=='reset-password' || Request::is('install'))
    @yield('content')
    @include('labels')
    @else
    <div class="layout-wrapper layout-content-navbar">

        <div class="layout-container">

            <!-- Menu -->
            @authBoth
            <x-menu />

            <!-- Layout container -->
            <div class="layout-page">

                @include('partials._navbar')
                <!-- Content wrapper -->
                <div class="content-wrapper">

                    @include('labels')
                    @yield('content')
                    @include('modals')
                </div>
                <!-- Content wrapper -->

                <!-- footer -->
                <x-footer />
                <!-- / footer -->
                @else
                <div class="container-fluid container-p-y ">
                    <div class="misc-wrapper d-flex flex-column align-items-center justif-content-center">
                        <h2 class="mb-2 mx-2"><?= get_label('session_expired', 'Session expired') ?>!!!</h2>
                        <div class="my-5">
                            <img src="../assets/img/illustrations/page-misc-error-light.png" alt="page-misc-error-light" width="500" class="img-fluid" data-app-dark-img="illustrations/page-misc-error-dark.png" data-app-light-img="illustrations/page-misc-error-light.png" />
                        </div>
                        <a href="{{url('/')}}" class="btn btn-primary"><?= get_label('log_in', 'Log in') ?></a>
                    </div>
                </div>
                @endauth
            </div>

            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script>
        var manage_notifications = '<?= getAuthenticatedUser()->can('manage_system_notifications') ? 'true' : 'false' ?>';
    </script>
    @endif
    <!-- / Layout wrapper -->
    <script src="{{asset('assets/js/time-tracker.js')}}"></script>
    @if (getAuthenticatedUser() && getAuthenticatedUser()->can('create_timesheet'))
    <!-- Timer image -->
    <div onclick="open_timer_section()">
        <span>
            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('time_tracker', 'Time tracker') ?>">
                <img src="{{ asset('storage/94150-clock.png') }}" class="timer-img" id="timer-image" alt="Timer" data-bs-toggle="modal" data-bs-target="#timerModal">
            </a>
        </span>
    </div>
    @endif

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->


    <!-- Main JS -->
    <script src="{{asset('assets/js/main.js')}}"></script>

    <script src="{{asset('assets/js/ui-toasts.js')}}"></script>


    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="{{asset('assets/js/buttons.js')}}"></script>

    <!-- select 2 js !-->
    <script src="{{asset('assets/js/select2.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>

    <!-- Bootstrap-table -->
    <script src="{{asset('assets/js/bootstrap-table/bootstrap-table.min.js')}}"></script>

    <!-- <script src="{{asset('assets/js/bootstrap-table/bootstrap-table-export.min.js')}}"></script> -->
    <!-- <script src="{{asset('assets/js/bootstrap-table/tableExport.min.js')}}"></script> -->

    <!-- Dragula -->
    <script src="{{asset('assets/js/dragula.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.js')}}"></script>

    <!-- Toastr -->
    <script src="{{asset('assets/js/toastr.min.js')}}"></script>

    <script src="{{asset('assets/js/ui-toasts.js')}}"></script>

    <script src="{{asset('assets/js/ui-popover.js')}}"></script>

    <script src="{{asset('assets/js/tinymce.min.js')}}"></script>
    <script src="{{asset('assets/js/tinymce-jquery.min.js')}}"></script>

    <!-- Date picker -->
    <script src="{{asset('assets/js/moment.min.js')}}"></script>

    <script src="{{asset('assets/js/daterangepicker.js')}}"></script>

    <script src="{{asset('assets/lightbox/lightbox.min.js')}}"></script>

    <script src="{{asset('assets/js/dropzone.min.js')}}"></script>

    @authBoth
    <script>
        var authUserId = '<?= getAuthenticatedUser()->id ?>';
    </script>
    @endauth

    <!-- Custom js -->
    <script>
        var csrf_token = '{{ csrf_token() }}';
        var js_date_format = '{{ $js_date_format??"YYYY-MM-DD" }}';
    </script>
    <script src="{{asset('assets/js/custom.js')}}"></script>

    @if(session()->has('message'))
    <script>
        toastr.options = {
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "progressBar": true,
            "extendedTimeOut": "1000",
            "closeButton": true
        };

        toastr.success('{{session("message")}}', 'Success');
    </script>

    @elseif(session()->has('error'))

    <script>
        toastr.options = {
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "progressBar": true,
            "extendedTimeOut": "1000",
            "closeButton": true
        };

        toastr.error('{{session("error")}}', 'Error');
    </script>

    @endif
</body>

</html>