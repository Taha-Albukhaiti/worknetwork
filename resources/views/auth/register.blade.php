<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="WorkNetwork">
    <meta name="keywords"
          content="worknetwork, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>Admin Registration page</title>

    <style>
        .authLogin-side-wrapper {
            background-image: url("{{ asset('upload/login.png') }}");
            width: 100%;
            height: 100%;
        }
    </style>

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

</head>
<body>
<div class="main-wrapper">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">

            <div class="row w-100 mx-0 auth-page">
                <div class="col-md-8 col-xl-6 mx-auto">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-4 pe-md-0">
                                <div class="authLogin-side-wrapper">
                                    <!-- Hier könnte Ihr Hintergrundbild oder Inhalt stehen -->
                                </div>
                            </div>
                            <div class="col-md-8 ps-md-0">
                                <div class="auth-form-wrapper px-4 py-5">
                                    <a href="#" class="noble-ui-logo logo-light d-block mb-2">Work<span>Network</span></a>
                                    <h5 class="text-muted fw-normal mb-4">Join us! Create your account.</h5>

                                    <form class="forms-sample" method="post" action="{{ route('register') }}">
                                        @csrf
                                        <!-- Name -->
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                                            @if ($errors->has('name'))
                                                <p class="text-danger">{{ $errors->first('name') }}</p>
                                            @endif
                                        </div>

                                        <!-- Email Address -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                            @if ($errors->has('email'))
                                                <p class="text-danger">{{ $errors->first('email') }}</p>
                                            @endif
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" placeholder="Password">
                                            @if ($errors->has('password'))
                                                <p class="text-danger">{{ $errors->first('password') }}</p>
                                            @endif
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Confirm Password">
                                            @if ($errors->has('password_confirmation'))
                                                <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                            @endif
                                        </div>

                                        <!-- Registration Type -->
                                        <div class="mb-3">
                                            <label for="registration_type" class="form-label">Registration Type</label>
                                            <select id="registration_type" name="registration_type" class="form-control">
                                                <option value="user">User</option>
                                                <option value="company">Company</option>
                                            </select>
                                        </div>

                                        <div>
                                            <button type="submit" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                                Register
                                            </button>
                                        </div>

                                        <a href="{{ route('login') }}" class="d-block mt-3 text-muted">Already registered? Log in</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- core:js -->
<script src="{{asset('/backend/vendors/core/core.js')}}"></script>
<!-- endinject -->

<!-- inject:js -->
<script src="{{asset('/backend/vendors/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('/backend/js/template.js')}}"></script>
<!-- endinject -->

</body>
</html>
