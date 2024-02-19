@extends('user.user_dashboard')

@section('user')
    <!-- partial -->

    <div class="page-content">
        <div class="row profile-body">
            <!-- left wrapper start -->
            <!-- Portfolio Form -->
            @include('user.profile.portfolio_form')
             <!-- left wrapper end -->

            <!-- middle wrapper start -->
            <!-- Profile Information -->
            @include('user.profile.profile_information')
            <!-- middle wrapper end -->
        </div>
    </div>
    <!-- partial:../../partials/_footer.html -->
    <script>
        $(document).ready(function () {
            $('#image').change(function (e) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endsection
