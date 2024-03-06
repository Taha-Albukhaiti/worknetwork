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
    <link rel="stylesheet" href="{{asset('/backend/vendors/core/core.css')}}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('/backend/vendors/flatpickr/flatpickr.min.css')}}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('/backend/fonts/feather-font/css/iconfont.css')}}">
    <link rel="stylesheet" href="{{asset('/backend/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('/backend/css/demo2/style.css')}}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{asset('/backend/images/favicon.png')}}"/>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <style>
        .card {
            padding: 30px;
            margin-bottom: 30px;
        }

        .card p {
            margin-bottom: 10px;
        }
        .profile-img {
            max-width: 30%;
            height: auto;
            object-fit: cover;
            border-radius: 50%;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

    </style>
</head>

<body>
<div class="main-wrapper">
    <div class="page-wrapper">
        @include('body.sidebar')
        <!-- partial:partials/_navbar.html -->
        @include('body.header')
        <div class="page-content">

            <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                <div>
                    <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
                </div>
                <div class="d-flex align-items-center flex-wrap text-nowrap">
                    <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i
                                data-feather="calendar" class="text-primary"></i></span>
                        <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date"
                               data-input>
                    </div>
                </div>
            </div>

        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Profilbild -->
                            <img class="profile-img" src="{{ !empty($user->photo) ? url('upload/company_images/'.$user->photo) : url('upload/no_image.jpg')}}"
                                 alt="profile">
                            <br>
                            <h2 class="text-center mb-4">  {{ $user->username ?? '' }}</h2>

                            <div class="row">
                                <!-- Company Kontaktdaten -->
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h2 style="font-size: 30px;" class="card-title mb-4"><strong>Company Kontaktdaten</strong></h2>
                                            <p><strong>Username:</strong> {{ $user->username ?? '' }}</p>
                                            <p><strong>Name:</strong> {{ $user->name ?? '' }}</p>
                                            <p><strong>Email:</strong> {{ $user->email ?? '' }}</p>
                                            <p><strong>Phone:</strong> {{ $user->phone ?? '' }}</p>
                                            <hr>
                                            <p>
                                                <strong>Adresse:</strong> {{ $address->street ?? '' }} {{ $address->street_number ?? '' }}
                                            </p>
                                            <p><strong>PLZ:</strong> {{ $address->zip ?? '' }}</p>
                                            <p><strong>Stadt:</strong> {{ $address->city ?? '' }}</p>
                                            <p><strong>Land:</strong> {{ $address->state ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Profile Information -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h2 style="font-size: 30px;" class="card-title mb-4"><strong>Company Profile Information</strong></h2>
                                            <p><strong>Company Website:</strong> {{ $companyProfile->company_website ?? '' }}</p>
                                            <p><strong>Company Description:</strong> {{ $companyProfile->company_description ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- partial:partials/_footer.html -->
        @include('body.footer')
        <!-- partial -->
    </div>
</div>



<!-- core:js -->
<script src="{{asset('/backend/vendors/core/core.js')}}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{asset('/backend/vendors/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('/backend/vendors/apexcharts/apexcharts.min.js')}}"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{asset('/backend/vendors/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('/backend/js/template.js')}}"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<script src="{{asset('/backend/js/dashboard-dark.js')}}"></script>
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
