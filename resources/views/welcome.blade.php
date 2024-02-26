<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="NobleUI">
    <meta name="keywords"
          content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>Network</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="{{asset('/backend/assets/vendors/core/core.css')}}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('/backend/assets/vendors/flatpickr/flatpickr.min.css')}}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('/backend/assets/fonts/feather-font/css/iconfont.css')}}">
    <link rel="stylesheet" href="{{asset('/backend/assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('/backend/assets/css/demo2/style.css')}}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{asset('/backend/assets/images/favicon.png')}}"/>


    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

</head>

<body>
<div class="main-wrapper">
    <div class="page-wrapper">
        @include('body.sidebar')
        <!-- partial:partials/_navbar.html -->
        @include('body.header')
        <!-- partial -->

        <!-- Hauptinhalt -->
        <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-4">
                <div>
                    <h4 class="mb-3 mb-md-0">Welcome to Work<a href="#" class="sidebar-brand"><span>Network</span></a>
                    </h4>
                </div>
                <div class="d-flex align-items-center flex-wrap text-nowrap">
                    <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                            <span class="input-group-text input-group-addon bg-transparent border-primary"
                                  data-toggle=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                      viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                      class="feather feather-calendar text-primary"><rect x="3" y="4"
                                                                                                          width="18"
                                                                                                          height="18"
                                                                                                          rx="2"
                                                                                                          ry="2"></rect><line
                                            x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8"
                                                                                       y2="6"></line><line x1="3"
                                                                                                           y1="10"
                                                                                                           x2="21"
                                                                                                           y2="10"></line></svg></span>
                        <input type="text" class="form-control bg-transparent border-primary flatpickr-input"
                               placeholder="Select date" data-input="" readonly="readonly">
                    </div>
                </div>
            </div>
        </div>

        @include('cards')

        <!-- partial:partials/_footer.html -->
        @include('body.footer')
        <!-- partial -->
    </div>
</div>


<!-- core:js -->
<script src="{{asset('/backend/assets/vendors/core/core.js')}}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{asset('/backend/assets/vendors/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('/backend/assets/vendors/apexcharts/apexcharts.min.js')}}"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{asset('/backend/assets/vendors/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('/backend/assets/js/template.js')}}"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<script src="{{asset('/backend/assets/js/dashboard-dark.js')}}"></script>
<!-- End custom js for this page -->


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type','info') }}"
    switch (type) {
        case 'info':
            toastr.info(" {{ Session::get('message') }} ");
            break;

        case 'success':
            toastr.success(" {{ Session::get('message') }} ");
            break;

        case 'warning':
            toastr.warning(" {{ Session::get('message') }} ");
            break;

        case 'error':
            toastr.error(" {{ Session::get('message') }} ");
            break;
    }
    @endif
</script>
</body>
</html>
